<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galleryalbum extends Model
{
    protected $table = 'galleryalbums';

    public function gallerycategory()
    {
        return $this->belongsTo('App\Models\Gallerycategory');
    }
}