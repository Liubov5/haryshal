<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $fillable = ["volunteer_name","volunteer_passport","volunteer_contact","status","user_id"];
    public function user(){
    	return $this->belongsTo("App\User");
    }
}
