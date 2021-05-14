<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\UsersSharePost;
use Log;

class RetributeController extends Controller
{
	public function __construct(){
        // 作成したMiddlewareを呼び出し
        //$this->middleware('auth.before');
    }
	
	
	public function retribute (Request $request){
		Log::debug($request->post_id);
        $user = Auth::user(); 
		$user_id = $user->id;
        $post_id = $request->post_id;
		$count = UsersSharePost::where('repost_user_id', $user_id)->where('origin_post_id', $post_id)->count();
		if($count == 0){
			UsersSharePost::create([
				'repost_user_id'=>$user_id,
				'origin_post_id'=>$post_id,
				'is_deleted'=>0
			]);	
		}elseif($count == 1){
			UsersSharePost::where('repost_user_id', $user_id)->where('origin_post_id', $post_id)->update(['is_deleted'=> 0]);
		}
		return $count;
	}
	
    public function remove (Request $request){
        $user = Auth::user(); 
		$user_id = $user->id;
        $post_id = $request->post_id;
		$user = $request->base_user;
		Log::debug($post_id."wadwa");
		UsersSharePost::where('repost_user_id', $user_id)->where('origin_post_id', $post_id)->update(['is_deleted'=> 1]);
		return $user;
	}
}
