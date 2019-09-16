<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subjectclass extends Model
{
    public function classstream()
    {
        return $this->belongsTo('App\ClassStream', 'class_id', 'id');
    }
    public function teacher()
    {
        return $this->belongsTo('App\Teacher', 'tea_id', 'id');
    }
    public function subject()
    {
        return $this->belongsTo('App\Subject', 'sub_id', 'id');
    }
   /* public function timetable()
    {
        return $this->belongsTo('App\Timetable', 'foreign_key', 'time_id');
    } */

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'class_id', 'tea_id', 'sub_id', 'title', 'delegated','totalcount','totalslot'
   ];
}
