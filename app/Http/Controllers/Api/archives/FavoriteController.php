<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_favorite;
use Log;

class FavoriteController extends Controller
{
	public function __construct(){
        // 作成したMiddlewareを呼び出し
        $this->middleware('auth.before');
    }
	
	
	public function users_favorite (Request $request){
		Log::debug($request->post_id);
		$count = User_favorite::where('user_id', $request->base_user->user_id)->where('post_id', $request->post_id)->count();
		if($count == 0){
			User_favorite::create([
				'user_id'=>$request->base_user->user_id,
				'post_id'=>$request->post_id,
				'is_canceled'=>0
			]);	
		}elseif($count == 1){
			User_favorite::where('user_id', $request->base_user->user_id)->where('post_id', $request->post_id)->update(['is_canceled'=> 0]);
		}
		return $count;
	}
	
    public function remove (Request $request){
		$user = $request->base_user;
		Log::debug($request->post_id."wadwa");
		User_favorite::where('user_id', $request->base_user->user_id)->where('post_id', $request->post_id)->update(['is_canceled'=> 1]);
		return $user;
	}
}
