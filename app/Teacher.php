<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'id');
    }
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'fname', 'lname', 'agerange', 'bias', 'gender', 'coursetype', 'qualification', 'experience', 'photo', '_type', '_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
