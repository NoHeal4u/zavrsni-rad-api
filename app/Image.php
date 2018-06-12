<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{	
	protected $fillable = [
	
        'images','gallery_id'
    ];

    public function imageBelongsToGallery()
    {
    	return $this->belongsTo(Gallery::class);
    } 
}
