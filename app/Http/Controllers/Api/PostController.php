<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsersPosts;
use App\Models\Post_valid_disclosure_list;
use App\Models\Disclosure_list_user;
use App\Models\Attached_content;
use App\Models\User_favorite;
use App\Http\Requests\PostFormRequest;
use Log;

class PostController extends Controller
{
    public function __construct()
    {
        // 作成したMiddlewareを呼び出し
       // $this->middleware('auth.before');
    }
	
	public function post (PostFormRequest $request){
		$post = new UsersPosts;
		//$post -> post_user_id = $request->base_user->user_id;
        $user_id = "hishida1";
		$post -> post_user_id = $user_id;
		$post -> content_text = $request->content_text;
		if($request->attached_files && !$request->content_text) $post -> content_text = "";
        if($request->parent_post_id) $post -> parent_post_id = $request->parent_post_id;
		$post -> is_share_available = 0;
		$post -> is_deleted = 0;
        $png ="png";
		$post -> save();
		$id = $post->id;
        if($request->lists){
            foreach($request->lists as $list){
                Post_valid_disclosure_list::create([
                    'list_id'=> $list,
                    'post_id'=> $id,
                    'is_hidden'=> 0
                ]);
            }
        }
        if($request->attached_files){
            foreach($request->attached_files as $index => $file){
                $canvas = $file;
                $extension = mb_substr($canvas , 11, 3);
                $canvas = preg_replace("/data:[^,]+,/i","",$canvas);
                $canvas = base64_decode($canvas);
                $image = imagecreatefromstring($canvas);
                $savepath=$id;
                imagesavealpha($image, TRUE); // 透明色の有効

                switch($extension){
                    case "gif":
                    $img_path = 'img/post_img/'.$savepath."_".$index.'.'.$extension;
                    imagegif($image ,$img_path);
                    break;

                    default:
                    $img_path = 'img/post_img/'.$savepath."_".$index.'.png';
                    imagepng($image ,$img_path);
                break;
                }

                Attached_content::create([
                    'post_id'=> $id,
                    'content_type'=>$extension,
                    'content_file_path'=>$img_path
                ]);

            }
        }
        $posts2 = UsersPosts::ofUserPosts($user_id, $id)->first();
		return $posts2;
	}

	public function get_posts (Request $request){
		$user = $request->base_user;
		$posts = UsersPosts::ofPosts($user->user_id)->having('post_at', '<', $request->num)->orderBy('post_at', 'desc')->distinct()->offset(0)->limit(25)->get();
		
		$posts = $posts->unique('posts_id')->values();
		return $posts;
	}
	public function get_latest_posts (Request $request){
		$user = $request->base_user;
		//DB::enableQueryLog();
		$posts = UsersPosts::ofPosts($user->user_id)->having('post_at', '>', $request->num)->orderBy('post_at', 'asc')->get();
		//Log::debug(DB::getQueryLog());
		
		$posts = $posts->unique('posts_id')->values();
		return $posts;
	}
	public function get_new_posts (Request $request){
		$user = $request->base_user;
		//DB::enableQueryLog();
		$posts = UsersPosts::ofPosts($user->user_id,$request->num)->having('post_at', '>', $post_at)->orderBy('post_at', 'asc')->offset(0)->limit(25)->get();
		//Log::debug(DB::getQueryLog());
		
		//$posts = $posts->unique('posts_id');
		return $posts;
	}
    
    public function get_parent (Request $request){
		$user = $request->base_user;
		$posts = UsersPosts::parentPosts($user->user_id, $request->pearent)->offset($request->num)->limit(25)
        ->get();
		$posts = $posts->unique('posts_id');
		return $posts;
	}
    
    private static function unique_filename($org_path, $num=0){

		if( $num > 0){
			$info = pathinfo($org_path);
			$path = $info['dirname'] . "/" . $info['filename'] . "_" . $num;
			if(isset($info['extension'])) $path .= "." . $info['extension'];
		} else {
			$path = $org_path;
		}

		if(file_exists($path)){
			$num++;
			return unique_filename($org_path, $num);
		} else {
			$path.=".png";
			return $path ;
		}
	}
}