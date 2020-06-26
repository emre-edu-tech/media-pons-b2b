<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    public function fromContact() {
        return $this->hasOne('App\User', 'id', 'from_user');
    }
}
