<?php
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});
Route::get('/onLoad',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$user = App\User::where("id",$req->user_id)->get();
	$res1=DB::table("clients")->where("user_id",$req->user_id)->get();
	$res2 = DB::table("volunteers")->where("user_id",$req->user_id)->get();
	$res = new Collection;
	$result = $res->merge($res1)->merge($res2)->merge($user);
	return $result;
});
Route::get('/checkLogin',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$user = App\User::where("login", "=", $req->login)->get();
	return $user;
});
Route::get('/checkEmail',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$user = App\User::where("email","=",$req->email)->get();
	return $user;
});
Route::get('/addUser',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$user_id = App\User::insertGetId(
		[
		"login"=>$req->login,
		"email"=>$req->email,
		"password"=>$req->password,
		"created_at"=>date('Y-m-d H:i:s'),
		"updated_at"=>date('Y-m-d H:i:s')
		]
	);
	return $user_id;
});
Route::get('/signIn',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$user = App\User::where("login",$req->login)->where("password",$req->password)->get();
	return $user;
});
Route::get("/getPerson",function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$user = App\User::where("id", $req->user_id)->get();
	$client = App\Client::where("user_id",$req->user_id)->get();
	$volunteer = App\Volunteer::where("user_id",$req->user_id)->get();
	$res = new Collection;
	$result = $res->merge($client)->merge($volunteer)->merge($user);
	return $result;
});
Route::get('/addAdvertisement',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$id = App\Advertisement::insertGetId([
		"from"=>$req->from,
		"to"=>$req->to,
		"body"=>$req->body,
		"user_id"=>$req->user_id,
		"status"=>0,
		"created_at"=>date('Y-m-d H:i:s'),
		"updated_at"=>date('Y-m-d H:i:s'),
		"time"=>date('Y-m-d H:i:s')
	]);
	return $id;
});
Route::get('/checkRole',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$client = App\Client::where("user_id",$req->user_id)->get();
	$volunteer = App\Volunteer::where("user_id",$req->user_id)->get();
	$res = new Collection;
	$result = $res->merge($client)->merge($volunteer);
	return $result;
});
Route::get('/addVolunteer',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$volunteer_id = App\Volunteer::insertGetId([
		"volunteer_name"=>$req->name,
		"volunteer_passport"=>$req->passport,
		"volunteer_contact"=>$req->phone_number,
		"user_id"=>$req->user_id,
		"status"=>1,
		"created_at"=>date('Y-m-d H:i:s'),
		"updated_at"=>date('Y-m-d H:i:s')
	]);
	return $volunteer_id;
});
Route::get('/getVolunteerInfo',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$user = App\Volunteer::where("user_id",$req->user_id)->get();
	return $user;
});
Route::get('/getClientInfo',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$user = App\Client::where("user_id",$req->user_id)->get();
	return $user;
});
Route::get('/getAdvertisement',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$user = App\Advertisement::where("user_id",$req->user_id)->where("status",0)->get();
	return $user;
});
Route::get('/allAdverts',function(){
	header("Access-Control-Allow-Origin:*");
	$result = App\Advertisement::orderBy('created_at','desc')->where('status',0)->get();
	return $result;
});
Route::get('/allAdverts',function(){
	header("Access-Control-Allow-Origin:*");
	$result = App\Advertisement::orderBy('created_at','desc')->where('status',0)->get();
	return $result;
});
Route::get('/respond',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	 App\Advertisement::where("id",$req->advert_id)->update(["status"=>1,"volunteer_id"=>$req->volunteer_id]);
	 return "Ваша заявка принята";
	
});
Route::get('/addClient',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$client_id = App\Client::insertGetId([
		"parent_name"=>$req->name_parent,
		"child_name"=>$req->name_child,
		"diagnosis"=>$req->diagnosis,
		"physical_limit"=>$req->physical_limit,
		"user_id"=>$req->user_id,
		"parent_contact"=>$req->phone_parent,
		"status"=>0,
		"created_at"=>date('Y-m-d H:i:s'),
		"updated_at"=>date('Y-m-d H:i:s')
	]);
	return $client_id;
});
Route::get('/getOldAdvertisement',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$client = App\User::find($req->user_id);
	$adverts = $client->advertisements()->where("status",2)->orderBy('created_at','desc')->get();
	return $adverts;
});
Route::get('/getRespondedAdverts',function(Request $req){
	header("Access-Control-Allow-Origin:*");

	$client = App\User::find($req->user_id);
	$adverts = $client->advertisements()->where("status",1)->get();
	
	return $adverts;
});
Route::get('/getVolunteersInfo',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$arr = [];
	for($i=0;$i<count($req->vol_id);$i++){
		$user = App\Volunteer::where("user_id",$req->vol_id[$i])->get();
		array_push($arr,$user);
	}

	return $arr;
});
Route::get('/deleteAdvert',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	App\Advertisement::find($req->adv_id)->update(["status"=>2]);
	return "Удалено";
});

Route::get('/getOldVolunteers',function(Request $req){
	header("Access-Control-Allow-Origin:*");
		$arr = [];
	for($i=0;$i<count($req->vol_id);$i++){
		$user = App\Volunteer::where("user_id",$req->vol_id[$i])->get();
		array_push($arr,$user);
	}

	return $arr;
});
Route::get('/getNewAdverts',function(){
	header("Access-Control-Allow-Origin:*");
	$result = App\Advertisement::where('status',0)->get();
	return $result;
});
Route::get('/getOldVoluntAdverts',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$adverts = App\Advertisement::where("volunteer_id",$req->volunt_id)->where("status",2)->get();
	return $adverts;
});
Route::get('/getActiveVoluntAdverts',function(Request $req){
header("Access-Control-Allow-Origin:*");
	$adverts = App\Advertisement::where("volunteer_id",$req->volunt_id)->where("status",1)->orderBy("created_at","desc")->get();
	return $adverts;
});
Route::get('/getActiveAdvertInfo',function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$arr=[];
	for($i=0;$i<count($req->user_id);$i++){
		$user =  App\Client::where("user_id",$req->user_id[$i])->orderBy("created_at","desc")->get();
		array_push($arr,$user);
	}
		return $arr;
});

Route::get('/getParentAdvertsInfo', function(Request $req){
	header("Access-Control-Allow-Origin:*");
	$arr=[];
	for($i=0;$i<count($req->array);$i++){
		$user =  App\Client::where("user_id",$req->array[$i])->orderBy("created_at","desc")->get();
		array_push($arr,$user);
	}
		return $arr;
});
Route::get('/refuseRequest',function(Request $req){
		header("Access-Control-Allow-Origin:*");
		App\Advertisement::find($req->advert_id)->update(["status"=>0]);
		return "ok";
});