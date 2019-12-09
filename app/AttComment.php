<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttComment extends Model
{
  
    /*
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        '_date', '_owner', 'st_pabsence', 'st_incomplete', 'st_delayapproval', 'st_lateclass', 'st_tabsence'
    ];
}