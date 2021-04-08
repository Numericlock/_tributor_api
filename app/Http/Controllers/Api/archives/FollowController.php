<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_follow;
use Log;

class FollowController extends Controller
{
	public function __construct(){
        // 作成したMiddlewareを呼び出し
        $this->middleware('auth.before');
    }
	
    public function follow (Request $request){
		$user = $request->base_user;
		$count = User_follow::isFollow($user->user_id,$request->user_id)->count();
		Log::debug($count."followカウント");
		if($count == 0){
			User_follow::create([
				'subject_user_id'=>$user->user_id,
				'followed_user_id'=>$request->user_id,
				'is_canceled'=>0
			]);
			Log::debug($count."followカウント");
		}elseif($count == 1){
			User_follow::isFollow($user->user_id,$request->user_id)->update(['is_canceled'=> 0]);
		}
		return $count;
	}
	
    public function remove (Request $request){
		$user = $request->base_user;
		User_follow::isFollow($user->user_id,$request->user_id)->update(['is_canceled'=> 1]);
		return $user;
	}
}
