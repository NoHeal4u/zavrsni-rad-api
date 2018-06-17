<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Gallery;
use App\Image;
use App\User;
use App\Comment;

use Validator, DB, Hash;
use JWTAuth;

class CommentController extends Controller
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
        // $fetchCommentObject = $request->only('comment', 'gallery_id');

        $comment = new Comment();

        $comment->comment = $request->input('comment');
        $comment->gallery_id = $request->input('gallery_id');
        $comment->user_id = auth()->user()->id;

        $comment->save();

        return response()->json(['success'=> true, 'message'=> 'Comment Saved!!']);
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
        $comment = Comment::find($id);

       if(!isset($comment)) {
            abort(404, "Comment not found");
        }

       $comment->delete();
    }
}
