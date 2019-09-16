<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LsnActivity extends Model
{
    public function lessonnote()
    {
        return $this->belongsTo('App\Lessonnote', 'foreign_key', 'lsn_id');
    }

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'lsn_id', 'owner', 'ownertype', 'expected', 'actual', 'slip', 'action'
    ];
}
