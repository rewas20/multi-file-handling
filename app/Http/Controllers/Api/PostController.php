<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\user;
use Illuminate\Http\Request;

use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = auth()->user()->posts;
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'=>'required|integer',
            'title'=>'required|string|max:255',
            'description'=>'required|string|max:1000',
            'files' => 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:1000',
        ]);

        $user = User::find($request->user_id);
        if(!$user){
            return response()->json([
                'status'=>false,
                'message'=>'User Not Found '
            ],404);
        }

        Post::create($request->all());

        if ($request->has('files')) {
            $postObj->addMultipleMediaFromRequest(['files']) ->each(function ($file) {
                $file->toMediaCollection('post_attachments');
                $file->storingConversionsOnDisk('media')->toMediaCollection('post_attachments','media');
            });
        }

        return response()->json([
            'status'=>true,
            'message'=>'Post is created successfully'
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $post)
    {
        $posts = auth()->user()->posts;
        $postObj = $posts->find($post);

        if(!$postObj){
            return response()->json([
                'status'=>false,
                'message'=>'Post Not Found '
            ],404);
        }

        return (new PostResource($postObj))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $post)
    {
        $posts = auth()->user()->posts;
        $postObj = $posts->find($post);

        if(!$postObj){
            return response()->json([
                'status'=>false,
                'message'=>'Post Not Found '
            ],404);
        }

        $request->validate([
            'user_id'=>'integer',
            'title'=>'string|max:255',
            'description'=>'string|max:1000',
            'files' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:1000',
        ]);

        $user = User::find($request->user_id);
        if(!$user&&$request->user_id){
            return response()->json([
                'status'=>false,
                'message'=>'User Not Found '
            ],404);
        }

        /* dd($request->files); */
        $postObj->update($request->all());

        if ($request->has('files')) {
            $postObj->clearMediaCollection('post_attachments');
            $postObj->addMultipleMediaFromRequest(['files']) ->each(function ($file) {
                $file->toMediaCollection('post_attachments');
                $file->storingConversionsOnDisk('media')->toMediaCollection('post_attachments','media');
            });
        }

        return response()->json([
            'status'=>true,
            'message'=>'Post is updated successfully'
        ],202);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $post)
    {
        $posts = auth()->user()->posts;
        $postObj = $posts->find($post);

        if(!$postObj){
            return response()->json([
                'status'=>false,
                'message'=>'Post Not Found '
            ],404);
        }

        $postObj->delete();

        return response()->json([
            'status'=>true,
            'message'=>'Post is deleted successfully'
        ],202);

    }
}
