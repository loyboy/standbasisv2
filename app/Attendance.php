<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function subclass()
    {
        return $this->belongsTo('App\SubjectClass', 'sub_class_id', 'id');
    }
    
    public function term()
    {
        return $this->belongsTo('App\Term', 'term', 'id');
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
    'sub_class_id', 'term',  'time_id', '_date', 'period', 'image', '_done',  '_desc'
   ];

    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [];
}
