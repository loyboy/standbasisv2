<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rowcall extends Model
{
    public function attendance()
    {
        return $this->belongsTo('App\Attendance', 'att_id', 'id');
    }
    public function pupil()
    {
        return $this->belongsTo('App\Pupil', 'pup_id', 'id');
    }
     /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'att_id', 'pup_id', 'pupil_name', '_status', 'remark'
    ];

    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [];
}
