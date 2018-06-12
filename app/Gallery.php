<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public function GalleryHasManyImages()
    {
        return $this->hasMany('App\Image');
    }

    public function GalleryBelongsToUser()
    {
    	return $this->belongsTo(User::class);
    } 
}
