<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PostsInterface
{
    public function listPosts(Request $request);
    public function savePosts(Request $request);
    public function showPosts($id);
    public function updatePosts(Request $request, $id);
    public function deletePosts($id);
}
