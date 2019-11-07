<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    public function assessment()
    {
        return $this->belongsTo('App\Assessment', 'ass_id', 'id');
    }
    public function enrollment()
    {
        return $this->belongsTo('App\Enrollment', 'enrol_id', 'id');
    }

     /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
        'ass_id', 'enrol_id', '_date', 'actual', 'max', 'perf'
   ];
}
