<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    return $this->belongsTo('App\Gallery');
}
