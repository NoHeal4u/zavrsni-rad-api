<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public function galleryHasManyImages()
    {
        return $this->hasMany('App\Image');
    }

    public function galleryBelongsToUser()
    {
    	return $this->belongsTo(User::class);
    } 
}
