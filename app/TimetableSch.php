<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimetableSch extends Model
{
    //
    public function subclass()
    {
        return $this->belongsTo('App\Subjectclass', 'sub_class', 'id');
    }

    public function timetable()
    {
        return $this->belongsTo('App\Timetable', 'time_id', 'id');
    }

     /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'sub_class', 'time_id', 'comment'
   ];
}
