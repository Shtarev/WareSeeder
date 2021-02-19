<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'checken'
    ];

    // связь с товарами
    public function ware(){
        return $this->belongsToMany('App\Ware');
    }
}
