<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//php artisan make:model TimetableSch -m //Create a model with PHP artisan
//php artisan make:middleware AdminApi
//php artisan make:controller TeacherController --api

class School extends Model
{
    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
      'name', '_type', 'town', 'lga', 'state', 'owner', 'polregion', 'faith', 'operator', 'gender', 'residence', 'population', 'logo', 'location', 'address', 'email', 'phone', 'sri', 'mission', 'rating', 'tour', 'calendar'
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];
}
