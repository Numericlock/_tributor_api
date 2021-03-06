<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Log;

class UserController extends Controller
{
    public $successStatus = 200;
    //
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        //$user = User::create(['name' => $input['name']],['email' => $input['email']],['password' => $input['password']]); 
        Log::debug($user);
        $success['token'] =  $user->createToken('_tributorApp')-> accessToken; 
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }    
    
    public function email_exists(Request $request) 
    { 
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        return User::where('email', $request->email)->count();
        
    }
    public function user_id_exists(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        $count = User::where('user_id', $request->user_id)->count();
        return $count ? true : false;
    }
    
    
    
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
    
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
}