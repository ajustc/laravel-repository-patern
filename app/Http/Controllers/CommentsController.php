<?php

namespace App\Http\Controllers;

use App\Jobs\CreateComment;
use App\Models\CommentsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth = Auth::user();

        $validate = Validator::make($request->all(), [
            'post_id' => ['required'],
            'text'    => ['required'],
        ]);

        if ($validate->fails()) {
            $response['status']  = false;
            $response['message'] = 'Failed';
            $response['error']   = $validate->errors();

            return response()->json($response, 422);
        }

        DB::beginTransaction();
        try {
            $payload = (object) [
                'post_id' => $request->post_id,
                'text'    => $request->text,
            ];
            dispatch(new CreateComment($auth, $payload));

            $response['code']    = 200;
            $response['status']  = true;
            $response['message'] = 'Success';
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
