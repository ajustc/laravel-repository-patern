<?php

namespace App\Repositories;

use App\Interfaces\PostsInterface;
use App\Models\PostsModel;
use App\Providers\NewsHistoryCreated;
use App\Providers\NewsHistoryDeleted;
use App\Providers\NewsHistoryUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostsRepository implements PostsInterface
{
    public function listPosts(Request $request)
    {
        $limit  = $request->limit ? $request->limit : 20;
        $page   = $request->page && $request->page > 0 ? $request->page : 1;
        $offset = ($page - 1) * $limit;

        $posts = PostsModel::with('comment')->with('user_comment')
            ->where('is_active', 1)
            ->get()->map(function ($c) {
                $c->setRelation('comment', $c->comment->take(5));
                return $c;
            })->slice($offset, $limit);

        $response['code']    = 200;
        $response['status']  = true;
        $response['message'] = 'Success';
        $response['data']    = $posts->toArray();

        return $response;
    }

    public function savePosts(Request $request)
    {
        $auth = Auth::user();

        $validate = Validator::make($request->all(), [
            'title'       => ['required'],
            'description' => ['required'],
            'image'       => ['required'],
        ]);

        if ($validate->fails()) {
            $response['code']    = 422;
            $response['status']  = false;
            $response['data']    = [];
            $response['message'] = 'Failed';
            $response['error']   = $validate->errors();

            return response()->json($response, 422);
        }

        $imageName = 'default.png';
        if (!empty($request->file('image'))) {
            $imageFile = $request->file('image');

            $imageName = date('YmdHis') . '-' . $imageFile->getClientOriginalName();
            if (!Storage::exists('public/news/default.png') || !Storage::exists('public/user/' . $imageName)) {
                Storage::putFileAs('public/news', $imageFile, $imageName);
            }
        }

        DB::beginTransaction();
        try {
            $posts = PostsModel::create([
                'user_id'     => $auth->id,
                'title'       => $request->title,
                'slug'        => \Illuminate\Support\Str::slug($request->title),
                'description' => $request->description,
                'image'       => $imageName,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ]);

            // created data history
            Event::dispatch(new NewsHistoryCreated($auth, $posts));

            $response['code']    = 200;
            $response['status']  = true;
            $response['message'] = 'Success';
            $response['error']   = [];
            $response['data']    = [];
            DB::commit();
        } catch (\Throwable $th) {
            $response['code']    = 500;
            $response['status']  = true;
            $response['message'] = 'Failed';
            $response['error']   = $th->getMessage();
            $response['data']    = [];
            DB::rollBack();
        }

        return $response;
    }

    public function showPosts($id)
    {
        $posts = PostsModel::with('comment')
            ->where('id', $id)->where('is_active', 1)->first();

        if (empty($posts)) {
            $response['code']    = 200;
            $response['status']  = false;
            $response['message'] = 'Failed';
            $response['error']   = 'Post not found';
            $response['data']    = [];

            return $response;
        }
        
        $response['code']    = 200;
        $response['status']  = true;
        $response['message'] = 'Success';
        $response['error']   = [];
        $response['data']    = $posts;

        return $response;
    }

    public function updatePosts(Request $request, $id)
    {
        $auth = Auth::user();

        $validate = Validator::make($request->all(), [
            'title'       => ['required'],
            'description' => ['required'],
            'image'       => ['required'],
        ]);

        if ($validate->fails()) {
            $response['code']    = 422;
            $response['status']  = false;
            $response['data']    = [];
            $response['message'] = 'Failed';
            $response['error']   = $validate->errors();


            return response()->json($response, 422);
        }

        $posts = PostsModel::find($id);

        $imageName = $posts->image ?? '';
        if (!empty($request->file('image'))) {
            $imageFile = $request->file('image');

            $imageName = date('YmdHis') . '-' . $imageFile->getClientOriginalName();
            if (!Storage::exists('public/news/default.png') || !Storage::exists('public/user/' . $imageName)) {
                Storage::putFileAs('public/news', $imageFile, $imageName);
            }
        }

        DB::beginTransaction();
        try {
            $posts->update([
                'user_id'     => $auth->id,
                'title'       => $request->title,
                'slug'        => \Illuminate\Support\Str::slug($request->title),
                'description' => $request->description,
                'image'       => $imageName,
                'updated_at'  => date('Y-m-d H:i:s')
            ]);

            // updated data history
            Event::dispatch(new NewsHistoryUpdated($auth, $posts));

            $response['code']    = 200;
            $response['status']  = true;
            $response['message'] = 'Success';
            $response['error']   = [];
            $response['data']    = [];
            DB::commit();
        } catch (\Throwable $th) {
            $response['code']    = 500;
            $response['status']  = true;
            $response['message'] = 'Failed';
            $response['error']   = $th->getMessage();
            $response['data']    = [];
            DB::rollBack();
        }

        return $response;
    }

    public function deletePosts($id)
    {
        $auth = Auth::user();

        $posts = PostsModel::find($id);

        DB::beginTransaction();
        try {
            $posts->update([
                'is_active' => '0'
            ]);

            // deleted data history
            Event::dispatch(new NewsHistoryDeleted($auth, $posts));

            $response['code']    = 200;
            $response['status']  = true;
            $response['message'] = 'Success';
            $response['error']   = [];
            $response['data']    = [];
            DB::commit();
        } catch (\Throwable $th) {
            $response['code']    = 500;
            $response['status']  = true;
            $response['message'] = 'Failed';
            $response['error']   = $th->getMessage();
            $response['data']    = [];
            DB::rollBack();
        }

        return $response;
    }
}
