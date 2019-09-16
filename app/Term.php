<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'id');
    }

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
    'school_id', 'term', 'session', 'resumedate', '_status', 'holiday'
   ];
}
