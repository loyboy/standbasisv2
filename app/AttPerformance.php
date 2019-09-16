<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttPerformance extends Model
{
    public function attendance()
    {
        return $this->belongsTo('App\Attendance', 'foreign_key', 'att_id');
    }

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
    'att_id', 'policy', 'fully', 'qua', 'flag', 'perf'
   ];

    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [];
}
