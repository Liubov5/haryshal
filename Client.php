<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	protected $fillable = ["parent_name","child_name","diagnosis","physical_limit","user_id","parent_contact","status"];
    
}
