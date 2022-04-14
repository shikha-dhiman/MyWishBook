<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use Validator;
use File;
use App\Models\admin;

class UserauthController extends Controller
{
    public function register(Request $req)
    {
    	
    	if($req->isMethod('post')){

    	   $rule = ['password' => ['min:8'],
	                'mobile' => ['min:10'],
	                'image' => "mimes:jpeg,jpg,png|max:2048"
            	];
	       $validator = Validator::make($req->all(), $rule);
	       if($validator->fails()){
	            $errors = $validator->errors()->first();
	            return $errors;
	        }else{
	        	$photo = "";
	            if($req->file()) {
	                $data = $req->input('image');
	                $photo = $req->file('image')->getClientOriginalName();
	                $destination = base_path() . '/public/user_asset/images';
	                $req->file('image')->move($destination, $photo);
	            }
	            $select = DB::table('tbl_users')->where(['mobile' => $req->mobile])->get(); 
	            $count = $select->count();
	            if($count > 0){
	                $status = 0;
	                $message = "The phone number has already been taken.";
	                return $message;
	            }else{
		    		date_default_timezone_set('Asia/Kolkata');
		    		$date = date('Y-m-d H:i:s');
		    		$params = [
		    			"name" => "$req->user_name",
		    			"email" => "$req->email",
		    			"mobile" => "$req->mobile",
		    			"dob" => "$req->dob",
		    			"profile_image" => "$photo",
		    			"password" => "$req->password",
		    			"created_at" => $date,
		    			"updated_at" => "$date",
		    			"is_active" => 1
		    		];
    			$query = DB::table('tbl_users')->insert($params);
    			if($query == true){
    				$message = "Register successfully.";
    				return $message;
    				}
    		    }
    		}
    	}

    	return view('User.Auth.register');
    }
     public function login(Request $req)
    {
        if($req->isMethod('post')){
	        $rule = ['username' => ['required']];
	        $validator = Validator::make($req->all(), $rule);
	        if($validator->fails()){
	            $errors =  $validator->errors()->first();
	            return $errors;
	        }else{
	            $username = $req->username;
	            $password = $req->password; 
	            $query = Admin::where(['username' => $username, 'password' => $password])->get();
	            $Count = $query->count();
	            if($Count == 1){
	                foreach ($query as $key => $value) {
	                   $username= $value->username;
	                   $user_details = ['username' => $username];
	                   session($user_details);
	                    $message =  "Logged in sucessfully";
	                    return $message;
	                }
	            }else{
	                $message =  "Wrong username or password.";
	                return $message;
	            }
	        }
    	}
    	return view('User.Auth.login');
    }
}
