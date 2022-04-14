<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\user;
use App\Models\otp;
use Session;
use DB,Validator,File,DateTime;

class UserApiAuthController extends Controller
{
	public function register(Request $req)
    {
    	if($req->isMethod('post')){

    	   $rule = ['name' => "required|regex:/^[a-zA-Z-' ]*$/|",
    	   			'password' => 'required|min:8',
    	   			'email' => "required",
    	   			'dob' => 'required',
	                'mobile' => 'required|min:10|unique:users',
	                'image' => "mimes:jpeg,jpg,png|max:2048"
            	];
	       $validator = Validator::make($req->all(), $rule);
	       if($validator->fails()){
	            return response()->json([
				'status' => false,
				'status_code' => true,
				'message' => $validator->messages()->first(),
				'results' => (object)[]
			]);
	        }else{
	        	$photo = "";
	            if($req->file()) {
	                $data = $req->input('image');
	                $photo = $req->file('image')->getClientOriginalName();
	                $destination = base_path() . '/public/user_asset/images';
	                $req->file('image')->move($destination, $photo);
	            }
	    		date_default_timezone_set('Asia/Kolkata');
	    		$date = date('Y-m-d H:i:s');
	    		$user_id = rand();
	    		$otp_num = mt_rand(100000, 999999);
	    		$SendOTP = $this->SendOTP($req->mobile, $otp_num);
				$user = new User;
	    		$user->user_id = $user_id;
	    			$user->name = $req->name;
    			$user->email = $req->email;
    			$user->mobile =  $req->mobile;
    			$user->dob = $req->dob;
    			$user->profile_image = $photo;
    			$user->password = base64_encode($req->password);
    			$user->is_number_verified = 0;
    			$user->is_active = 1;
				$user->save();
				$otp = new otp;
				$otp->user_id = $user_id;
				$otp->otp = $otp_num;
				$otp->is_active = 1;
				$otp->save();
				return response()->json([
		            'status' => true,
		            'status_code' => true,
		            'message' => "Register successfully.",
		            'results' => [
		                'data' => [
		                	'user_id' => $user->user_id,
		                	'is_number_verified' => $user->is_number_verified
		                ] 
		            ]
	        	]);
    		}
    	}
    }
     public function login(Request $req)
    {
        if($req->isMethod('post')){
	        $rule = ['mobile' => 'required|min:10',
	    			'password' => 'required'];
	        $validator = Validator::make($req->all(), $rule);
	        if($validator->fails()){
	            return response()->json([
					'status' => false,
					'status_code' => true,
					'message' => $validator->messages()->first(),
					'results' => (object)[]
				]);
	        }else{
	            $mobile = $req->mobile;
	            $password = $req->password; 
	            $query = User::where(['mobile' => $mobile, 'password' => base64_encode($password)])->count();
	            if($query == 1){
	            	$user = User::where('mobile', $mobile)->get();
	                return response()->json([
			            'status' => true,
			            'status_code' => true,
			            'message' => "Login successfully.",
			            'results' => [
			                'data' => $user
			            ]
			        ]);
	            }else{
	               	return response()->json([
						'status' => false,
						'status_code' => true,
						'message' => "Wrong username or password.",
						'results' => (object)[]
					]);
	            }
	        }
    	}
    }

    public function SendOTP($mobile,$otp)
	{
		$fields = array(
		"variables_values" => $otp,
		"route" => "otp",
		"numbers" => $mobile,
		);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => json_encode($fields),
		CURLOPT_HTTPHEADER => array(
			"authorization: nOq2Kc7FTI69Rj5BVhgkCiMsvSrWbdyQpNt1JLmEA8zZ4H3fXDfLzm4PFpliBx9CEWXrAkQacje7gho",
			"accept: */*",
			"cache-control: no-cache",
			"content-type: application/json"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		return $response;
		if ($err) {
		echo "cURL Error #:" . $err;
		}
	}	
	
	public function otpVerification(Request $req){
    	$rule = ['otp'=> "required|min:6|regex:/[0-9]/|max:6"];
    	$validator = Validator::make($req->all(), $rule);
		if($validator->fails()){
	    	return response()->json([
				'status' => false,
				'status_code' => true,
				'message' => $validator->errors()->first(),
				'results' => (object)[]
			]);
    	}else{
    		date_default_timezone_set('Asia/Kolkata');
	        $date = strtotime(date('Y-m-d H:i:s'));
    		$If_user_exists = User::where('user_id', $req->user_id)->count();
    		if($If_user_exists == 1){
    			$user = User::where('user_id', $req->user_id)->first();
    			$dbotp = OTP::where('user_id', $req->user_id)->first();
    			if($dbotp->otp == $req->otp){
	                $db_date = strtotime($dbotp->updated_at);
    				$seconds = $date - $db_date;
    				if($seconds <= 60){
    					User::where('user_id', $req->user_id)->update(['is_number_verified' => 1]);
	    				return response()->json([
			            'status' => true,
			            'status_code' => true,
			            'message' => "Otp verified.",
			            'results' => (object)[]
		        	]);
    				}else{
	    				return response()->json([
							'status' => false,
							'status_code' => true,
							'message' => "Otp expired! Please click on resend button.",
							'results' => (object)[]
						]);
    				}
    			}else{
	                return response()->json([
						'status' => false,
						'status_code' => true,
						'message' => "Please enter your valid one time password!",
						'results' => (object)[]
					]);
                }
    		}else{
    			return response()->json([
					'status' => false,
					'status_code' => true,
					'message' => "User not found.",
					'results' => (object)[]
				]);
            }
	    }
    }

    public function resendOTP(Request $req){
    	date_default_timezone_set('Asia/Kolkata');
        $date = date('Y-m-d H:i:s');
    	$user = User::where('mobile', $req->mobile)->count();
    	if($user == 1){
	    	$user = User::where('mobile', $req->mobile)->first();
			$otp = otp::where(['user_id' => $user->user_id])->count();
			if($otp == 1){
			$query = otp::where(['user_id' => $user->user_id])->first();
			$otp_num = mt_rand(100000, 999999);
	        $SendOTP = $this->SendOTP($req->mobile, $otp_num);
			$update = Otp::where('user_id', $user->user_id)->update(['otp' => $otp_num]);
			if($update == 1){
				return response()->json([
		            'status' => true,
		            'status_code' => true,
		            'message' => "OTP resent to ".$req->mobile." successfully.",
		            'results' => (object)[]
	        	]);
				}
			}
		}else{
            return response()->json([
				'status' => false,
				'status_code' => true,
				'message' => "We are unable to send otp.User not exists!",
				'results' => (object)[]
			]);
       }
    }

    public function forgotPassword(Request $req){
    	$rule = ['mobile'=> "required|numeric|regex:/[0-9]{9}/|digits:10"];
		$validator = Validator::make($req->all(), $rule);
		if($validator->fails()){
	    		return response()->json([
				'status' => false,
				'status_code' => true,
				'message' => $validator->errors()->first(),
				'results' => (object)[]
			]);
    	}else{
    		$user = User::where('mobile', $req->mobile)->count();
			if($user == 1){
    			$otp_num = mt_rand(100000, 999999);
    			$SendOTP = $this->SendOTP($req->mobile, $otp_num);
    			date_default_timezone_set('Asia/Kolkata');
                $date = date('Y-m-d H:i:s');
                $user = User::where('mobile', $req->mobile)->first();
    			$update = Otp::where('user_id', $user->user_id)->update(['otp' => $otp_num]);
				if($update == 1){
    				return response()->json([
			            'status' => true,
			            'status_code' => true,
			            'message' => "We have sent an OTP to your ".$req->mobile.". Kindly use OTP to proceed.",
			            'results' => (object)[]
		        	]);
    			}
    		}else{
    			return response()->json([
					'status' => false,
					'status_code' => true,
					'message' => "Ooh! Looks like this phone number is not registered with us. Kindly register or use other registered id.",
					'results' => (object)[]
				]);
    		}
    	}
    }
    public function resetPassword(Request $req){
		$rule = ['password' => 'required|min:8',
    			'confirm_password' => "required|",
				];
		$validator = Validator::make($req->all(), $rule);
		if($validator->fails()){
			return response()->json([
				'status' => false,
				'status_code' => true,
				'message' => $validator->errors()->first(),
				'results' => (object)[]
			]);
    	}else{
    		if($req->password == $req->confirm_password){
    			date_default_timezone_set('Asia/Kolkata');
                $date_modified = date('Y-m-d H:i:s');
    			$new_password = base64_encode($req->password);
    			$update = User::where('mobile', $req->mobile)->update(['password' => $req->password]);
    			if($update == 1){
    				return response()->json([
			            'status' => true,
			            'status_code' => true,
			            'message' => "Your password has been updated successfully.",
			            'results' => (object)[]
		        	]);
    			}else{
    				return response()->json([
						'status' => false,
						'status_code' => true,
						'message' => "Ooh! Looks like this phone number is not registered with us. Kindly register or use other registered id.",
						'results' => (object)[]
					]);
    			}
    		}else{
    			return response()->json([
					'status' => false,
					'status_code' => true,
					'message' => "Password do not match, both password should be same.",
					'results' => (object)[]
				]);
    		}
    	}
	}
}
