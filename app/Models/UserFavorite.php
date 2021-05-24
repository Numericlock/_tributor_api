<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
   	protected $table = 'users_favorites';
    protected $fillable = ['user_id', 'post_id', 'is_canceled'];
    
	public function scopePosts($query,$user_id){
		return $query->select('users_posts.*','users_posts.id as posts_id', 'users.id as users2_id', 'users2.name as users2_name', 'users.user_id as user_id', 'users.name as users_name', 'users_follows.subject_user_id as subject_user_id','users_follows.is_canceled as is_canceled','users_share_posts.updated_at as share_at',
		\DB::raw(//リツイートかどうか
			"CASE WHEN users_follows2.subject_user_id != '$user_id' OR users_follows2.is_canceled = 1 OR users_share_posts.updated_at IS NULL OR users_share_posts.is_deleted = 1 OR users_share_posts.repost_user_id = '$user_id' OR users_posts.post_user_id = '$user_id' THEN users_posts.created_at ELSE users_share_posts.updated_at END AS post_at"
			//"COALESCE(users_share_posts.updated_at, users_posts.created_at) as post_at"
		),
		\DB::raw(//フォロー数
			"(SELECT COUNT(subject_user_id = users.id  OR NULL) AS subject_count FROM users_follows) AS subject_count "
		),
		\DB::raw(//フォロワー数
			"(SELECT COUNT(*) FROM users_follows WHERE followed_user_id = users.id) AS followed_count "
		),
		\DB::raw(//フォローされているかどうか
			"(SELECT COUNT(followed_user_id = '$user_id' OR NULL) FROM `users_follows` WHERE subject_user_id = users.id AND is_canceled = 0) AS users_followed_count "
		),
		\DB::raw(//コメントの数
			"(SELECT COUNT(*) FROM users_posts WHERE parent_post_id = posts_id AND is_deleted = 0) AS comment_count "
		),
		\DB::raw(//いいねの数
			"(SELECT COUNT(*) FROM users_favorites WHERE post_id = posts_id AND is_canceled = 0) AS favorite_count "
		),
		\DB::raw(//いいねしているかどうか
			"(SELECT COUNT(*) FROM users_favorites WHERE post_id = posts_id AND is_canceled = 0 AND user_id = '$user_id') AS is_favorite "
		),
		\DB::raw(//リトリビュートしているかどうか
			"(SELECT COUNT(*) FROM users_share_posts WHERE origin_post_id = posts_id AND is_deleted = 0 AND repost_user_id = '$user_id') AS is_retribute "
		),
		\DB::raw(//リトリビュートの数
			"(SELECT COUNT(*) FROM users_share_posts WHERE origin_post_id = posts_id AND is_deleted = 0) AS retribute_count "
		),
		\DB::raw(//添付ファイルの数
			"(SELECT COUNT(*) FROM attached_contents WHERE post_id = posts_id) AS attached_count "
		)
							  
		)
        ->leftjoin('users_posts', 'users_favorites.post_id', '=', 'users_posts.id')
		->leftjoin('posts_valid_disclosure_lists', 'users_posts.id', '=', 'posts_valid_disclosure_lists.post_id')
		->leftjoin('users_share_posts', 'users_posts.id', '=', 'users_share_posts.origin_post_id')
		->leftjoin('users_follows', 'users_follows.followed_user_id', '=', 'users_posts.post_user_id')
		->leftjoin('users_follows as users_follows2', 'users_follows2.followed_user_id', '=', 'users_share_posts.repost_user_id')
        ->leftjoin('users', 'users_posts.post_user_id', '=', 'users.id')
        ->leftjoin('users as users2', 'users_share_posts.repost_user_id', '=', 'users2.id')
		->leftjoin('disclosure_lists_users', 'disclosure_lists_users.list_id', '=', 'posts_valid_disclosure_lists.list_id');
	}
    
	public function scopeOfUser($query,$user_id){
		return $query->where('users_favorites.user_id',$user_id);
	}
}
