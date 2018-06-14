<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{		
	protected $fillable = [
	
        'gallery_name','description', 'user_id'
    ];

    public function galleryHasManyImages()
    {
        return $this->hasMany('App\Image');
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    } 

    public function galleryHasOneImage()
    {
        return $this->hasOne('App\Image')->orderBy('id')->latest();
    }
}
