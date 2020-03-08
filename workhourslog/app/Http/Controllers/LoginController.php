<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Validator;
use App\User;
use \DB;
use Auth;
use Response;

class LoginController extends Controller
{
	// public function login2()
	// {
	// 	echo "good morning";
	// 	return;
	// }

	public function login(Request $request)
	{
		try {
		    $validator = Validator::make(
		        $request->all(),[
		            'email' => 'email',
		            'password' => 'min:6'
		        ]
		    );
			
		    if($validator->fails()) return $this->apiResponse(['error'=>'validation is not fullfill'],true);
		    $model = User::where('email', $request->email)->first();

		    if( !$model ) return $this->apiResponse(['error'=>'Invalid login credentials'],true);
		    //try logging in the user
		    if(Auth::attempt($request->only(['email', 'password']), $request->input('remember'))) {
		      	$user = Auth::user();
		      	return $this->apiResponse(['message' => 'Successfully login', 'data' => $user]);
		    }
		    return $this->apiResponse(['error'=>'Invalid login credentials'],true);
		} catch(\Exception $e) {
			return $this->apiResponse(['error'=>'Invalid login credentials'],true);
		}
	}

	public function register(Request $request) 
	{
		try
		{
			$validator = Validator::make(
		        $request->all(),[
		            'email' => 'required',
		            'password' => 'required',
		            'name' => 'required'
		        ]
		    );
			if($validator->fails()) $this->apiResponse(['error'=>'validation is not fullfill'],true);
            
            $user1 = User::create(['email'=>$request->email, 'password' => bcrypt($request->password),'name' => $request->name,]);
            
            $id = \DB::getPdo()->lastInsertId();
            $user = User::findOrFail($id);
            if($user)
        		return $this->apiResponse(['message' => 'Successfully user is Register', 'data' => $user]);
        	else
        	{
        		return $this->apiResponse(['error'=>'data not Register'],true);
        	}
        } catch(\Exception $e) {
            return $this->apiResponse(['error'=>'Invalid login credentials'],true);
        }
	}


}
