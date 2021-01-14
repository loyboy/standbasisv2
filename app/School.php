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
      'name', 'id', '_type', 'town', 'lga', 'state', 'owner', 'polregion', 'faith', 'operator', 'gender', 'residence', 'population', 'logo', 'location', 'address', 'email', 'phone', 'sri', 'mission', 'rating', 'tour', 'calendar'
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [];

   

   public static function boot()
   {
      $keysToID = array( 
         "nw" => "94", "jigawa" => "941", "kaduna" => "942","kano" => "943","katsina" => "944", "kebbi" => "945", "sokoto" => "946", "zamfara" => "947", 
         "ne" => "97", "adamawa" => "971", "bauchi" => "972","borno" => "973","gombe" => "974", "taraba" => "975", "yobe" => "976",
         "sw" => "95", "nc" => "98", "se" => "99", "ss" => "96"        
      );

       parent::boot();

       self::creating(function($school){
           
         if ( strtolower($keysToID[ $school->polregion ]) !== null ){
            
            if ( strtolower($keysToID[ $school->state ]) !== null  ){
               
              $schid =  $keysToID[ $school->polregion ]."".$keysToID[ $school->state ] ;
              
              $school->id = $schid;

            }
         }         

       });
   }

}
