<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
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
        //$this->middleware('auth.before');
    }
	
	public function profile ($user_id, Request $request){
        $user = Auth::user(); 
		$base_user_id = $user->id;
		$profile = User::select('users.user_id as user_id','users.name as name','users.summary as summary','users_follows.is_canceled as is_canceled', 'users_follows.subject_user_id as subject_user_id',
		\DB::raw(//フォロー数
			"(SELECT COUNT(subject_user_id = users.id  OR NULL) AS subject_count FROM users_follows) AS subject_count "
		),
		\DB::raw(//フォロワー数
			"(SELECT COUNT(*) FROM users_follows WHERE followed_user_id = users.id) AS followed_count "
		),
		\DB::raw(//フォローされているかどうか
			"(SELECT COUNT(followed_user_id = '$base_user_id' OR NULL) FROM `users_follows` WHERE subject_user_id = users.id AND is_canceled = 0) AS users_followed_count "
		),
		\DB::raw(//フォローしているかどうか
			"(SELECT COUNT(subject_user_id = '$base_user_id' OR NULL) FROM `users_follows` WHERE followed_user_id = users.id AND is_canceled = 0) AS users_subject_count "
		)
		)
		->leftjoin('users_follows', 'users_follows.followed_user_id', '=', 'users.id')
		//->leftjoin('users_posts', 'users_posts.post_user_id', '=', 'users.id')
		->where('users.user_id',$user_id)
		->firstOrFail();
        //$user_id = User::select('id')->where('user_id',$user_id)->firstOrFail();
		//$reposts = UsersSharePost::ofReposts($user_id->id)->latest()->get();
		//$myPosts = UsersPosts::MyPosts($base_user_id,$user_id->id)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
        //$imgPosts = UsersPosts::MyPosts($base_user_id,$user_id->id)->having('attached_count','>',0)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
		//$myPosts = $myPosts->unique('posts_id');
		//$start_post = $myPosts->first();
		//$last_post = $myPosts->last();
        //$userIds = $myPosts->unique('users_id');
        //$imgPosts = $imgPosts->unique('posts_id');
       // $userIdsimg = $imgPosts->unique('users_id');
		return $profile;

		
	}
    public function user_posts($user_id, Request $request){
        $user = Auth::user(); 
		$base_user_id = $user->id;
        $user_id = User::select('id')->where('user_id',$user_id)->firstOrFail();
       // $myPosts = UsersPosts::MyPosts($base_user_id,$user_id->id)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
        $myPosts = UsersPosts::Posts($base_user_id)->ofUser($base_user_id,$user_id->id)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
		$myPosts = $myPosts->unique('posts_id');
		$start_post = $myPosts->first();
		$last_post = $myPosts->last();
        return $myPosts;   
    }    
    
    public function user_media_posts($user_id, Request $request){
        $user = Auth::user(); 
		$base_user_id = $user->id;
        $user_id = User::select('id')->where('user_id',$user_id)->firstOrFail();
        $imgPosts = UsersPosts::Posts($base_user_id)->ofUser($base_user_id,$user_id->id)->having('attached_count','>',0)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
        $imgPosts = $imgPosts->unique('posts_id');
        return $imgPosts;   
    }    
    
    public function user_reply_posts($user_id, Request $request){
        $user = Auth::user(); 
		$base_user_id = $user->id;
        $user_id = User::select('id')->where('user_id',$user_id)->firstOrFail();
        $replyPosts = UsersPosts::Posts($base_user_id)->ofUserReply($base_user_id,$user_id->id)->having('attached_count','>',0)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
        $replyPosts = $replyPosts->unique('posts_id');
        return $replyPosts;   
    }
}
