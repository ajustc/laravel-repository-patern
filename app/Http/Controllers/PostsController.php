<?php

namespace App\Http\Controllers;

use App\Models\PostsCreatedHistoryModel;
use App\Models\PostsDeletedHistoryModel;
use App\Models\PostsModel;
use App\Models\PostsUpdatedHistoryModel;
use App\Providers\NewsHistory;
use App\Repositories\PostsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    protected $postRepository;

    public function __construct(PostsRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getLists = $this->postRepository->listPosts($request);
        $response = $getLists;

        return response()->json($response, $response['code']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $savePosts = $this->postRepository->savePosts($request);
        $response = $savePosts['code'] == 200 ? $savePosts : (array) $savePosts['data'];

        return response()->json($response, $response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $showPosts = $this->postRepository->showPosts($id);
        $response = $showPosts['code'] == 200 ? $showPosts : (array) $showPosts['data'];

        return response()->json($response, $response['code']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $updatePosts = $this->postRepository->updatePosts($request, $id);
        $response    = $updatePosts['code'] == 200 ? $updatePosts : (array) $updatePosts['data'];

        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletePosts = $this->postRepository->deletePosts($id);
        $response    = $deletePosts;

        return response()->json($response, $response['code']);
    }
}
