<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassStream extends Model
{
    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'id');
    }

    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'school_id', 'title', 'ext', 'category'
    ];

    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [];
}
