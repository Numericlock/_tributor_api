<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\User_follow;
use App\Models\UsersPosts;
use App\Models\UsersSharePost;
use App\Models\Post_valid_disclosure_list;
use App\Models\Disclosure_list;
use App\Models\Disclosure_list_user;
use App\Http\Requests\PostFormRequest;
use Log;

class ProfileController extends Controller
{
    public function __construct()
    {
        // 作成したMiddlewareを呼び出し
        $this->middleware('auth.before');
    }
	
	public function profile ($user_id, Request $request){
		$user = $request->base_user;
		$base_user_id =$user->user_id;
		$posts = User::select('users.id as user_id','users.name as name','users.introduction as introduction','users_follows.is_canceled as is_canceled', 'users_follows.subject_user_id as subject_user_id',
		\DB::raw(//フォロー数
			"(SELECT COUNT(subject_user_id = users.id  OR NULL) AS subject_count FROM users_follows) AS subject_count "
		),
		\DB::raw(//フォロワー数
			"(SELECT COUNT(*) FROM users_follows WHERE followed_user_id = users.id) AS followed_count "
		),
		\DB::raw(//フォローされているかどうか
			"(SELECT COUNT(followed_user_id = '$base_user_id' OR NULL) FROM `users_follows` WHERE subject_user_id = '$user_id' AND is_canceled = 0) AS users_followed_count "
		),
		\DB::raw(//フォローしているかどうか
			"(SELECT COUNT(subject_user_id = '$base_user_id' OR NULL) FROM `users_follows` WHERE followed_user_id = '$user_id' AND is_canceled = 0) AS users_subject_count "
		)
		)
		->leftjoin('users_follows', 'users_follows.followed_user_id', '=', 'users.id')
		->leftjoin('users_posts', 'users_posts.post_user_id', '=', 'users.id')
		->where('users.id',$user_id)
		->get();
		$current_user = $posts->first();
        
		$reposts = UsersSharePost::ofReposts($user_id)->latest()->get();
		$myPosts = UsersPosts::MyPosts($base_user_id,$user_id)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
        $imgPosts = UsersPosts::MyPosts($base_user_id,$user_id)->having('attached_count','>',0)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
		$myPosts = $myPosts->unique('posts_id');
		$start_post = $myPosts->first();
		$last_post = $myPosts->last();
        $userIds = $myPosts->unique('users_id');
        $imgPosts = $imgPosts->unique('posts_id');
        $userIdsimg = $imgPosts->unique('users_id');
		$lists = $request->base_user_lists;
		return view('profile',compact('current_user','myPosts','userIdsimg','imgPosts', 'start_post', 'last_post', 'userIds', 'user','lists'));

		
	}
}
