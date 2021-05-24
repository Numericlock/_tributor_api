<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersPosts;
use App\Models\Post_valid_disclosure_list;
use App\Models\Attached_content;
use App\Models\UserFavorite;
use App\Http\Requests\PostFormRequest;
use Log;

class PostController extends Controller
{
    public function __construct()
    {
        // 作成したMiddlewareを呼び出し
        $this->middleware('auth.before');
    }
	
	public function post (PostFormRequest $request){
		$post = new UsersPosts;
		$post -> post_user_id = $request->base_user->user_id;
		$post -> content_text = $request->content_text;
		if($request->filetati && !$request->content_text){
			$post -> content_text = "";
		}
		$post -> is_share_available = 0;
		$post -> is_deleted = 0;
		if($request->parent_post_id){
			$post -> parent_post_id = $request->parent_post_id;
		}
        $png ="png";
		$post -> save();
		$id = $post->id;
		$user = $request->base_user;
        foreach($request->lists as $list){
            Log::debug($list);
            Log::debug($id);
            Post_valid_disclosure_list::create([
                'list_id'=> $list,
                'post_id'=> $id,
                'is_hidden'=> 0
            ]);
        }
        
        Log::debug("あひる");
            $count=0;
        foreach($request->filetati as $file){
            $canvas = $file;
            $extension = mb_substr($canvas , 11, 3);
            $canvas = preg_replace("/data:[^,]+,/i","",$canvas);
            $canvas = base64_decode($canvas);
            $image = imagecreatefromstring($canvas);
            $savepath=$id;
            imagesavealpha($image, TRUE); // 透明色の有効
            
            switch($extension){
                case "gif":
                $img_path = 'img/post_img/'.$savepath."_".$count.'.'.$extension;
                imagegif($image ,$img_path);
                break;
                               
                default:
                $img_path = 'img/post_img/'.$savepath."_".$count.'.png';
                imagepng($image ,$img_path);
            break;
            }
            $count++;
            
            Attached_content::create([
                'post_id'=> $id,
                'content_type'=>$extension,
                'content_file_path'=>$img_path
            ]);
			
        }
        Log::debug("あひる3");
        $posts2 = UsersPosts::ofUserPosts($user->user_id, $id)->first();
		Log::debug($posts2->post_user_id."あひる3");
		return $posts2;
	}
	
	public function get_posts (Request $request){
		$user = $request->base_user;
		$posts = UsersPosts::ofPosts($user->user_id)->having('post_at', '<', $request->num)->orderBy('post_at', 'desc')->offset(0)->limit(25)->get();
		
		$posts = $posts->unique('posts_id')->values();
		Log::debug($posts."ごみんわどぁｗｗｗｗ");
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
        Log::debug($posts."あひる3");
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
