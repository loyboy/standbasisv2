<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolPolicy extends Model
{
    public function school()
    {
        return $this->belongsTo('App\School', 'foreign_key', 'sch_id');
    }
     /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'sch_id', 'fair', 'late', 'signoff', 'accept_tea', 'accept_head', 'lsn_submit', 'lsn_resubmit', 'lsn_action', 'lsn_cycle', 'lsn_closure'
    ];

    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [];
}
