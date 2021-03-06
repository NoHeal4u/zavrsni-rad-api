<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gallery;
use App\Image;
use App\User;
use App\Comment;

use Validator, DB, Hash;
use JWTAuth;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Gallery::with(['galleryHasOneImage','user'])->orderBy('created_at','desc')->get();
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
       $fetchedGalleryObject = $request->only('gallery_name', 'description', 'images');

       $rulesForGallery = [
            'gallery_name' => 'required|min:2|max:255',
            'description' => 'max:1000',
            'images' => 'required',
            'images.*' => ['required', 'url', 'regex: /(?:(?:(?:\.jpg))|(?:(?:\.jpeg))|(?:(?:\.png)))/ '  ]
            
            
        ]; 

        $validator = Validator::make($fetchedGalleryObject, $rulesForGallery);

        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }

        $gallery = new Gallery();

        $gallery->gallery_name = $request->input('gallery_name');
        $gallery->description = $request->input('description');
        $gallery->user_id = auth()->user()->id;

        // $gallery_name = $request->gallery_name;
        // $description = $request->description;
        // $user_id = auth()->user()->id;


        
        // Gallery::create(['gallery_name' => $gallery_name, 'description' => $description, 'user_id' => $user_id]);
       
        $gallery->save();

        $imagesArray = [];

        // print_r($request->images);

        foreach ($request->images as $imageLink) {
            array_push($imagesArray, new Image(['images' => "$imageLink"]));
        }

        // print_r($imagesArray);

        $gallery->galleryHasManyImages()->saveMany($imagesArray);

        // foreach ($imagesArray as $imageObject) {
        //     $gallery->galleryHasManyImages()->save($imageObject);
        // }

        
        
        return response()->json(['success'=> true, 'message'=> 'Gallery Saved!!']);

        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gallery = Gallery::with(['galleryHasManyImages', 'user', 'comments'])->find($id);
        $comments = Comment::with(['user'])->where('gallery_id',$id)->get();

        if(!isset($gallery)) {
            abort(404, "Gallery not found");
        }
        // print_r($comments);
        if(!isset($comments)) {
            abort(404, "Comments not found");
        }

        return compact('gallery', 'comments');
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
       $gallery = Gallery::with('galleryHasManyImages')->find($id);
        // var_dump($gallery);

       $gallery->gallery_name = $request->input('gallery_name');
       $gallery->description = $request->input('description');
       
       

       $rulesForGallery = [
            'gallery_name' => 'required|min:2|max:255',
            'description' => 'max:1000',
            'images' => 'required',
            'images.*' => ['required', 'url', 'regex: /(?:(?:(?:\.jpg))|(?:(?:\.jpeg))|(?:(?:\.png)))/ '  ]
            
            
        ]; 

        $galleryArray = [ 'gallery_name' => $gallery->gallery_name, 'description' => $gallery->description, 'images' => $request->input('images')
        ];

        // var_dump($galleryArray);

        $validator = Validator::make($galleryArray, $rulesForGallery);

        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }

        // $gallery->images = $request->input('images');
        $gallery->save();


        // $gallery->galleryHasManyImages()->delete();

        $imagesArray = [];

        // print_r($request->images);

        foreach ($request->images as $imageLink) {
            array_push($imagesArray, new Image(['images' => "$imageLink"]));
        }

        $gallery->galleryHasManyImages()->delete();
        $gallery->galleryHasManyImages()->saveMany($imagesArray);


        // print_r($imagesArray);

        // $gallery->galleryHasManyImages()->saveMany([
        //     for ($i=0; $i <count($imagesArray) ; $i++) { 
        //        $imagesArray[$i];
        //     }
        //      ]);

        // foreach ($imagesArray as $imageObject) {
        //     $gallery->galleryHasManyImages()->save($imageObject);
        // }

        
        
        return response()->json(['success'=> true, 'message'=> 'Gallery Saved!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $gallery = Gallery::with('galleryHasManyImages')->find($id);

       if(!isset($gallery)) {
            abort(404, "Gallery not found");
        }

       $gallery->galleryHasManyImages()->delete();
       $gallery->delete();
    }
}
