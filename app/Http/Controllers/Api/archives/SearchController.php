<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\searchRequest;
use App\Models\User;
use App\Models\UserSession;
use App\Models\UsersPosts;
use App\Models\Post_valid_disclosure_list;
use App\Models\Disclosure_list_user;
use Log;

class SearchController extends Controller
{
	public function __construct(){
        // 作成したMiddlewareを呼び出し
        $this->middleware('auth.before');
    }
	public function search (Request $request){
		$posts = "";
        $str ="";
        $user = $request->base_user;
		$lists = $request->base_user_lists;
		return view('search',compact('posts','str','user','lists'));
	}
	
	public function post_search (searchRequest $request){
		$str = $request->str;
		$posts = UsersPosts::select('users_posts.*','users.id as users_id', 'users.name as users_name')
		->leftjoin('posts_valid_disclosure_lists', 'users_posts.id', '=', 'posts_valid_disclosure_lists.post_id')
		->leftjoin('users_follows', 'users_follows.followed_user_id', '=', 'users_posts.post_user_id')
        ->leftjoin('users', 'users_posts.post_user_id', '=', 'users.id')
		->leftjoin('disclosure_lists_users', 'disclosure_lists_users.list_id', '=', 'posts_valid_disclosure_lists.list_id')
		->where('disclosure_lists_users.user_id',$request->base_user->user_id)
		->where('users_posts.post_user_id', 'LIKE', '%'.$str.'%')
		->orWhere('users_posts.content_text','LIKE', '%'.$str.'%')
		->orWhere('users.name','LIKE', '%'.$str.'%')
		->orWhereNull('disclosure_lists_users.user_id')
		->where('users_posts.post_user_id', 'LIKE', '%'.$str.'%')
		->orWhere('users_posts.content_text','LIKE', '%'.$str.'%')
		->orWhere('users.name','LIKE', '%'.$str.'%')
		->orWhere('users_posts.post_user_id',$request->base_user->user_id)
		->where('users_posts.post_user_id', 'LIKE', '%'.$str.'%')
		->orWhere('users_posts.content_text','LIKE', '%'.$str.'%')
		->orWhere('users.name','LIKE', '%'.$str.'%')
		->distinct()
		->latest()
		->offset(0)
		->limit(25)
		->get();
		//要修正
		Log::debug("さーちりくえすと");
        $user = $request->base_user;
		$lists = $request->base_user_lists;
		return view('search',compact('posts','user','lists','str'));
	}	
	
	public function get_search_posts (searchRequest $request){
		$str = $request->str;
		$posts = UsersPosts::select('users_posts.*','users.id as users_id', 'users.name as users_name')
		->leftjoin('posts_valid_disclosure_lists', 'users_posts.id', '=', 'posts_valid_disclosure_lists.post_id')
		->leftjoin('users_follows', 'users_follows.followed_user_id', '=', 'users_posts.post_user_id')
        ->leftjoin('users', 'users_posts.post_user_id', '=', 'users.id')
		->leftjoin('disclosure_lists_users', 'disclosure_lists_users.list_id', '=', 'posts_valid_disclosure_lists.list_id')
		->where('disclosure_lists_users.user_id',$request->base_user->user_id)
		->where('users_posts.post_user_id', 'LIKE', '%'.$str.'%')
		->orWhere('users_posts.content_text','LIKE', '%'.$str.'%')
		->orWhere('users.name','LIKE', '%'.$str.'%')
		->orWhereNull('disclosure_lists_users.user_id')
		->where('users_posts.post_user_id', 'LIKE', '%'.$str.'%')
		->orWhere('users_posts.content_text','LIKE', '%'.$str.'%')
		->orWhere('users.name','LIKE', '%'.$str.'%')
		->orWhere('users_posts.post_user_id',$request->base_user->user_id)
		->where('users_posts.post_user_id', 'LIKE', '%'.$str.'%')
		->orWhere('users_posts.content_text','LIKE', '%'.$str.'%')
		->orWhere('users.name','LIKE', '%'.$str.'%')
		->distinct()
		->latest()
		->offset($request->num)
		->limit(25)
		->get();
		//要修正
		Log::debug("さーちりくえすと");
		return $posts;
	}
	
	public function users_search (searchRequest $request){
		$users = User::select('id as users_id','name as users_name')
		->where('id', 'LIKE', $request->str."%")
		->orWhere('name', 'LIKE', $request->str."%")	
		->having('id','!=',$request->base_user->user_id)
		->get();
		Log::debug($users."さーちりくえすと");
		return $users;
	}
	
	public function list_users_search (searchRequest $request){
		$list_users = Disclosure_list_user::select('user_id')
		->where('list_id', $request->list_id)
		->where('is_deleted', 0)
		->get();
		foreach($list_users as $value){
			$value = $value['user_id'];
			Log::debug($value."さーちりくえすと");
		}
		$users = User::select('id as users_id','name as users_name')
		->whereNotIn('id', $list_users)
		->where('id', 'LIKE', "%".$request->str."%")
		->where('id','!=',$request->base_user->user_id)
		->orWhere('name', 'LIKE', "%".$request->str."%")
		->where('id','!=',$request->base_user->user_id)
		->get();
		Log::debug($list_users."さーちりくえすと");
		Log::debug($users."さーちりくえすと");
		return $users;
	}
}
