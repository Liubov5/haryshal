<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ["from","to","body","user_id","status"];
    public function user(){
    	$this->belongsTo("App\User");
    }
}
