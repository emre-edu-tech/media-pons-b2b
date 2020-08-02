<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // many to many relationship with product model
    public function categories() {
        return $this->belongsToMany('App\Category');
    }

    // overrided regular price with a mutator
    public function getRegularPriceAttribute($priceVal) {
        return number_format($priceVal/100, 2, ',', '.');
    }

    // overrided sale price with a mutator
    public function getSalePriceAttribute($priceVal) {
        return number_format($priceVal/100, 2, ',', '.');
    }
}
