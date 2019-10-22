<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonnoteManagement extends Model
{
    public function lessonnote()
    {
        return $this->belongsTo('App\Lessonnote', 'lsn_id', 'id');
    }
     /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'lsn_id', '_submission', '_resubmission', '_revert', '_approval', '_cycle', '_launch', '_closure'
    ];

    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [];
}
