<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    public function galleryalbum()
    {
        return $this->belongsTo('App\Models\Galleryalbum');
    }
}