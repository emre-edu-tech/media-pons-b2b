<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // many to many relationship with product model
    public function products() {
        return $this->belongsToMany('App\Product');
    }
}
