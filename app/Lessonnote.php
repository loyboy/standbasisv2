<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Enrollment;
use App\Score;

class Lessonnote extends Model
{
    public function teacher()
    {
        return $this->belongsTo('App\Teacher', 'tea_id', 'id');
    }
    public function subject()
    {
        return $this->belongsTo('App\Subject', 'sub_id', 'id');
    }
    public function term()
    {
        return $this->belongsTo('App\Term', 'term_id', 'id');
    }

   
     /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'tea_id',  'sub_id', 'term_id', 'class_category', 'title', '_date', 'comment_principal', 'comment_admin', 'period', '_file'
    ];

    /**
     * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [];
}
