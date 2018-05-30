<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ["login","email","password"];	
    public function advertisements(){
    	return $this->hasMany("App\Advertisement");
    }

}
