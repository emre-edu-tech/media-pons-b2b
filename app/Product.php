<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // overrided regular price with a mutator
    public function getRegularPriceAttribute($priceVal) {
        return number_format($priceVal/100, 2, ',', '.');
    }

    // overrided sale price with a mutator
    public function getSalePriceAttribute($priceVal) {
        return number_format($priceVal/100, 2, ',', '.');
    }
}
