<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LsnPerformance extends Model
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
    'lsn_id', 'policy', 'fully', 'qua', 'flag', 'perf'
   ];

    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [];
}
