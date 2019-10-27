<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    public function subject()
    {
        return $this->belongsTo('App\Subject', 'sub_id', 'id');
    }
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
        'sub_id', 'lsn_id', 'source', 'title', '_date', '_type', 'ext'
   ];
}
