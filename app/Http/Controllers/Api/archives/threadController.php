<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersPosts;
use App\Models\Post_valid_disclosure_list;
use App\Models\Attached_content;
use App\Models\UserFavorite;
use App\Http\Requests\PostFormRequest;
use Log;
class threadController extends Controller
{
    public function __construct(){
        // 作成したMiddlewareを呼び出し
        $this->middleware('auth.before');
    }


    public function thread (Request $request,$users_id,$posts_id){
		$user = $request->base_user;
		$parent_post = UsersPosts::parentPosts($user->user_id,$posts_id)->first();
        
        $child_posts = UsersPosts::childPosts($user->user_id,$posts_id)->latest()->offset(0)->limit(25)->get();
        $child_posts = $child_posts->unique('posts_id');
		$userIds = $child_posts->unique('users_id'); 
		$lists = $request->base_user_lists;
        return view('thread',compact('parent_post','child_posts', 'userIds', 'user','lists'));

	}
}
