<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User_favorite;
use Log;

class FavoriteController extends Controller
{
	public function __construct(){
        // 作成したMiddlewareを呼び出し
        //$this->middleware('auth.before');
    }
	
	
	public function users_favorite (Request $request){
        $user = Auth::user(); 
		$user_id = $user->id;
        $post_id = $request->post_id;
        //$request->base_user->user_id
		$count = User_favorite::where('user_id', $user_id)->where('post_id', $post_id)->count();
		if($count == 0){
			User_favorite::create([
				'user_id'=>$user_id,
				'post_id'=>$post_id,
				'is_canceled'=>0
			]);	
		}elseif($count == 1){
			User_favorite::where('user_id', $user_id)->where('post_id', $post_id)->update(['is_canceled'=> 0]);
		}
		return $count;
	}
	
    public function remove (Request $request){
        $user = Auth::user(); 
		$user_id = $user->id;
        $post_id = $request->post_id;
		User_favorite::where('user_id', $user_id)->where('post_id', $post_id)->update(['is_canceled'=> 1]);
		return;
	}
}
