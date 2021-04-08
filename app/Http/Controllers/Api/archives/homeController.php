<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_follow;
use App\Models\UsersPosts;
use App\Models\UsersSharePost;
use App\Models\Post_valid_disclosure_list;
use App\Models\Disclosure_list;
use App\Models\Disclosure_list_user;
use App\Http\Requests\PostFormRequest;
use Log;


class HomeController extends Controller
{

	public function __construct(){
        // 作成したMiddlewareを呼び出し
        $this->middleware('auth.before');
    }


    public function home (Request $request){
		$user = $request->base_user;
		$reposts = UsersSharePost::ofReposts($user->user_id)->latest()->get();
		$posts = UsersPosts::ofPosts($user->user_id)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
		///$posts = $posts->merge($reposts);
		//$posts = $posts->sortByDesc('share_at')

		$posts = $posts->unique('posts_id');
		$start_post = $posts->first();
		$last_post = $posts->last();
		///$posts = $posts->sortByDesc('created_at');
        $userIds = $posts->unique('users_id'); 
		$lists = $request->base_user_lists;
		return view('home',compact('posts', 'start_post', 'last_post', 'userIds', 'user','lists'));

	}
}
