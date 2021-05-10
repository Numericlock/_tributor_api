<?php


namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User_follow;
use App\Models\UsersPosts;
use App\Models\UsersSharePost;
use App\Models\Post_valid_disclosure_list;
use App\Models\Disclosure_list;
use App\Models\Disclosure_list_user;
//use App\Http\Requests\PostFormRequest;
use Log;
use File;

class HomeController extends Controller
{

	public function __construct(){
        // 作成したMiddlewareを呼び出し
        //$this->middleware('auth.before');
    }


  //  public function home (Request $request){
    public function home (Request $request){
		//$user = $request->base_user;
      //  
        
		$user_id = "hishida1";
		//$reposts = UsersSharePost::ofReposts($user_id)->latest()->get();
		$posts = UsersPosts::posts($user_id)->ofTimeline($user_id)->orderBy('post_at', 'desc')->distinct()->offset(0)->limit(50)->get();
		$posts = $posts->unique('posts_id');
		$start_post = $posts->first();
		$last_post = $posts->last();
		///$posts = $posts->sortByDesc('created_at');
        $userIds = $posts->unique('users_id'); 
        return response()->json(['posts' => $posts,'start_post' => $start_post, 'last_post' => $last_post, 'userIds' => $userIds]);
	}
	public function get_before_posts (Request $request){
		$user_id = "hishida1";
		$posts = UsersPosts::ofPosts($user_id)->having('post_at', '<', $request->num)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
		
		$posts = $posts->unique('posts_id');
		$start_post = $posts->first();
		$last_post = $posts->last();
        $userIds = $posts->unique('users_id'); 
        //return compact('posts', 'start_post', 'last_post', 'userIds');
        return response()->json(['posts' => $posts,'start_post' => $start_post, 'last_post' => $last_post, 'userIds' => $userIds]);
	}
}
