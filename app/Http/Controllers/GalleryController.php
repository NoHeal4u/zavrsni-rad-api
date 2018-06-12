<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gallery;
use App\Image;

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
        return Gallery::with('galleryHasManyImages')->get();
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

        // $gallery->galleryHasManyImages()->saveMany([
        //     for ($i=0; $i <count($imagesArray) ; $i++) { 
        //        $imagesArray[$i];
        //     }
        //      ]);

        foreach ($imagesArray as $imageObject) {
            $gallery->galleryHasManyImages()->save($imageObject);
        }

        
        
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
        $gallery = Gallery::with('galleryHasManyImages')->find($id);

        if(!isset($gallery)) {
            abort(404, "Gallery not found");
        }

        return $gallery;
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
       $gallery = Gallery::find($id);

       $gallery->gallery_name = $request->input('gallery_name');
       $gallery->description = $request->input('description');
       $gallery->images = $request->input('images');
       

       $rulesForGallery = [
            'gallery_name' => 'required|min:2|max:255',
            'description' => 'max:1000',
            'images' => 'required',
            'images.*' => ['required', 'url', 'regex: /(?:(?:(?:\.jpg))|(?:(?:\.jpeg))|(?:(?:\.png)))/ '  ]
            
            
        ]; 

        $galleryArray = [ 'gallery_name' => $gallery->gallery_name, 'description' => $gallery->description, 'images' => $gallery->images
        ];

        var_dump($galleryArray);

        $validator = Validator::make($galleryArray, $rulesForGallery);

        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }
       
        $gallery->save();

        $gallery->galleryHasManyImages()->delete();

        $imagesArray = [];

        // print_r($request->images);

        foreach ($request->images as $imageLink) {
            array_push($imagesArray, new Image(['images' => "$imageLink"]));
        }

        // print_r($imagesArray);

        // $gallery->galleryHasManyImages()->saveMany([
        //     for ($i=0; $i <count($imagesArray) ; $i++) { 
        //        $imagesArray[$i];
        //     }
        //      ]);

        foreach ($imagesArray as $imageObject) {
            $gallery->galleryHasManyImages()->save($imageObject);
        }

        
        
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
        //
    }
}
