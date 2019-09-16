<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    public function pupil()
    {
        return $this->belongsTo('App\Pupil', 'pupil_id', 'id');
    }
    public function term()
    {
        return $this->belongsTo('App\Term', 'term_id', 'id');
    }
    public function classtream()
    {
        return $this->belongsTo('App\ClassStream', 'class_id', 'id');
    }
    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'pupil_id', 'term_id', 'class_id', 'enrol_date'
   ];

    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [];
}
