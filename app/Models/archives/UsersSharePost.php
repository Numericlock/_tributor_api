<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersSharePost extends Model
{
    protected $fillable = ['repost_user_id', 'origin_post_id', 'is_deleted'];
	
	public function scopeOfReposts($query,$user_id){
		return $query->select('users_posts.*','users_posts.id as posts_id', 'users.id as users_id', 'users.name as users_name', 'users_follows.subject_user_id as subject_user_id','users_follows.is_canceled as is_canceled',
		\DB::raw(//フォロー数
			"(SELECT COUNT(subject_user_id = users.id  OR NULL) AS subject_count FROM users_follows) AS subject_count "
		),
		\DB::raw(//フォロワー数
			"(SELECT COUNT(*) FROM users_follows WHERE followed_user_id = users.id) AS followed_count "
		),
		\DB::raw(//フォローされているかどうか
			"(SELECT COUNT(followed_user_id = '$user_id' OR NULL) FROM `users_follows` WHERE subject_user_id = users.id AND is_canceled = 0) AS users_followed_count "
		),
		\DB::raw(//フォローされているかどうか
			"(SELECT COUNT(*) FROM users_posts WHERE parent_post_id = posts_id AND is_deleted = 0) AS comment_count "
		),
		\DB::raw(//いいねの数
			"(SELECT COUNT(*) FROM users_favorites WHERE post_id = posts_id AND is_canceled = 0) AS favorite_count "
		),
		\DB::raw(//いいねしているかどうか
			"(SELECT COUNT(*) FROM users_favorites WHERE post_id = posts_id AND is_canceled = 0 AND user_id = '$user_id') AS is_favorite "
		),
		\DB::raw(//いいねしているかどうか
			"(SELECT COUNT(*) FROM users_share_posts WHERE origin_post_id = posts_id AND is_deleted = 0 AND repost_user_id = '$user_id') AS is_retribute "
		),
		\DB::raw(//添付ファイルの数
			"(SELECT COUNT(*) FROM attached_contents WHERE post_id = posts_id) AS attached_count "
		)
							  
		)
		->leftjoin('users_follows', 'users_follows.followed_user_id', '=', 'users_share_posts.repost_user_id')
        ->leftjoin('users_posts', 'users_posts.id', '=', 'users_share_posts.origin_post_id')
        ->leftjoin('users', 'users_posts.post_user_id', '=', 'users.id')
        ->leftjoin('users as users2', 'users_share_posts.repost_user_id', '=', 'users.id')
		->where('users_follows.subject_user_id',$user_id)
		->distinct();
	}
}
