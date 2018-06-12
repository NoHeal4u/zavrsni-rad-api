<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function imageBelongsToGallery()
    {
    	return $this->belongsTo(Gallery::class);
    } 
}
