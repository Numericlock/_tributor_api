<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSession;
use App\Models\Disclosure_list;
use App\Models\Disclosure_list_user;
use App\Http\Requests\listFormRequest;
use App\Http\Requests\listMemberRequest;
use Log;
use File;

class ListsController extends Controller
{

    public function __construct()
    {
        // 作成したMiddlewareを呼び出し
       // $this->middleware('auth.before');
    }

	public function index (Request $request){
		//$user = $request->base_user;
		//$lists = $request->base_user_lists;
		//return view('lists',compact('user','lists'));
        $user_id = "hishida1";
        $lists = Disclosure_list::index($user_id);
        //$image = File::get(public_path().'/img/list_icon/1.png');
        //$base64_image = base64_encode($image);
        //Log::debug("画像：".public_path());
        //Log::debug("画像：".$base64_image);
//        /foreach($lists as $list){
//        /    //Log::debug("画像：".$list['id']);
//        /    $image = File::get(public_path().'/img/list_icon/'. $list['id'] .'.png');
//        /    $base64_image = base64_encode($image);
//        /    //Log::debug("画像：".public_path());
//        /    //Log::debug("画像：".$base64_image);
//        /    $list['icon'] = $base64_image;
//        /}
        return $lists;
	}

	public function lists_insert(listFormRequest $request){

		$list = new Disclosure_list;
		$list -> name = $request->name;
		$list -> owner_user_id = $request->base_user->user_id;
		$list -> is_published = $request->publish;
		$list -> is_hidden = 0;
		$list -> save();
		$id = $list->id;
        
        //ヘッダに「data:image/png;base64,」が付いているので、それは外す
        $canvas = $request->icon;
        $canvas = preg_replace("/data:[^,]+,/i","",$canvas);
 
//残りのデータはbase64エンコードされているので、デコードする
        $canvas = base64_decode($canvas);
 
//まだ文字列の状態なので、画像リソース化
        $image = imagecreatefromstring($canvas);
 
//画像として保存（ディレクトリは任意）
        $savepath=$id;
        $path2 ='img/list_icon/';
        $path2 .=$savepath;
        $img_path =  self::unique_filename($path2);
        imagesavealpha($image, TRUE); // 透明色の有効
        imagepng($image ,$img_path);
        
        foreach($request->users as $user){
            Disclosure_list_user::create([
                'list_id'=> $id,
                'user_id'=> $user,
                'is_deleted'=> 0
            ]);
        }
		return $list;
	}
	public function lists_update(listFormRequest $request){
		$list = $request->list_id;
		$count = Disclosure_list::where('id', $list)
		->where('owner_user_id', $request->base_user->user_id)
		->count();

		if($count != 0){
			Disclosure_list::where('owner_user_id', $request->base_user->user_id)
			->where('id', $list)
			->update(['name'=> $request->name, 'is_published'=> $request->publish]);
			
			foreach($request->users as $user){
				$user_count = Disclosure_list_user::isMember($list, $user)->count();
				if($user_count == 0){
					Disclosure_list_user::create([
						'list_id'=> $list,
						'user_id'=> $user,
						'is_deleted'=> 0
					]);
				}else{
					Disclosure_list_user::isMember($list, $user)->update(['is_deleted'=> 0]);
				}
			}
			$list_users = Disclosure_list_user::select('disclosure_lists.name as list_name', 'disclosure_lists.is_published as list_is_published', 'disclosure_lists_users.user_id as users_id', 'users.name as users_name')
			->join('users', 'users.id', '=', 'disclosure_lists_users.user_id')
			->join('disclosure_lists', 'disclosure_lists.id', '=', 'disclosure_lists_users.list_id')
			->where('disclosure_lists_users.list_id', $request->list_id)
			->where('disclosure_lists_users.is_deleted', 0)
			->get();
		}
		return $list_users;
	}

	public function user_add_lists(Request $request){
		$lists = $request->base_user_lists;
		$user_id = $request->user_id;
		$lists_ids=[];
        foreach($lists as $list){
			array_push($lists_ids, $list->id);
        } 
        foreach($request->checked as $list){
			if(in_array($list, $lists_ids) == true){
				$count = Disclosure_list_user::isMember($list, $user_id)->count();
				if($count == 0){
					Disclosure_list_user::create([
						'list_id'=> $list,
						'user_id'=> $user_id,
						'is_deleted'=> 0
					]);
				}else{
					Disclosure_list_user::isMember($list, $user_id)->update(['is_deleted'=> 0]);
				}
			}
			//Log::debug(in_array($list, $lists)."あぢでええｗ");
        }        
		foreach($request->notchecked as $list){
			if(in_array($list, $lists_ids) == true){
				Disclosure_list_user::isMember($list, $user_id)->update(['is_deleted'=> 1]);
			}
        }

		return $lists;
	}
	
	public function users_lists(Request $request){
		$lists = Disclosure_list_user::select('list_id')
		->join('disclosure_lists', 'disclosure_lists.id', '=', 'disclosure_lists_users.list_id' )
		->where('disclosure_lists_users.user_id', $request->input('user_id'))
		->where('disclosure_lists.owner_user_id', $request->base_user->user_id)
		->where('disclosure_lists_users.is_deleted', 0)
		->get();
		Log::debug($lists."LISTMEMBERあいでwwwwwwwwwwwー");
		return $lists;
	}
	
	
	public function lists_member_post($id, Request $request){
		$lists = $request->base_user_lists;
		$user = $request->base_user;
		//$id=$request->id;
		$current_list = Disclosure_list::select('id as list_id','name','owner_user_id')
		->where('id', $id)
		->first();
		if($current_list->owner_user_id == $request->base_user->user_id){
			$list_users = Disclosure_list_user::select('disclosure_lists_users.user_id as users_id', 'users.name as users_name')
			->join('users', 'users.id', '=', 'disclosure_lists_users.user_id')
			->where('disclosure_lists_users.list_id', $id)
			->where('disclosure_lists_users.is_deleted', 0)
			->get();
			$count = $list_users->count();
			return view('lists_members',compact('lists','user', 'current_list', 'list_users', 'count'));
		}else{
			return redirect('/lists');
		}
	}
	
	public function user_remove(Request $request){
		$user_id=$request->user_id;
		$list_id=$request->list_id;
		$count = Disclosure_list::where('id',$list_id)->where('owner_user_id', $request->base_user->user_id)->count();
		Log::debug($count."LISTMEMBERあいでー");
		if($count != 0){
			Disclosure_list_user::isMember($list_id, $user_id)->update(['is_deleted'=> 1]);
		}
		return $request->base_user; 
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
