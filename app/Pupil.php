<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pupil extends Model
{
    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'id');
    }

    public function guardian()
    {
        return $this->belongsTo('App\Teacher', 'guardian', 'id');
    }

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
    'school_id', 'fname', 'lname', 'gender', 'entry', 'status' , 'guardian'
   ];
}
