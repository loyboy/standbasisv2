<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttActivity extends Model
{
    public function attendance()
    {
        return $this->belongsTo('App\Attendance', 'att_id', 'id');
    }

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'att_id', 'owner', 'ownertype', 'expected', 'actual', 'slip', '_comment', '_action'
    ];
}
