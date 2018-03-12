<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exampleimage extends Model
{
    protected $table = 'exampleimages';

    public function example()
    {
        return $this->belongsTo('App\Models\Example');
    }
}