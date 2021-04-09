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
		$reposts = UsersSharePost::ofReposts($user_id)->latest()->get();
		$posts = UsersPosts::ofPosts($user_id)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
		/////$posts = $posts->merge($reposts);
		////$posts = $posts->sortByDesc('share_at')
        foreach($posts as $post){
            $images = [];
            for($i=0;$i<$post['attached_count'];$i++){
                $image = File::get(public_path().'/img/post_img/'. $post['id'] . '_' . $i . '.png');
                $base64_image = base64_encode($image);
                $images[$i] = $base64_image;
                //Log::debug("画像：".public_path());
                //Log::debug("画像：".$base64_image);
            }
            $post['images'] = $images;
        }
		$posts = $posts->unique('posts_id');
		$start_post = $posts->first();
		$last_post = $posts->last();
		///$posts = $posts->sortByDesc('created_at');
        $userIds = $posts->unique('users_id'); 
		//$lists = $request->base_user_lists;
        
		//return compact('posts', 'start_post', 'last_post', 'userIds', 'user','lists');
		return compact('posts', 'start_post', 'last_post', 'userIds');
        //return $posts;
	}
}
