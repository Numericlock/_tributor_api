<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_follow extends Model
{
    protected $table = 'users_follows';
	protected $fillable = ['subject_user_id', 'followed_user_id', 'is_canceled'];
	
	public function scopeOfSubject($query,$user_id){
		return $query->where('subject_user_id',$user_id);
	}
	public function scopeOfFollowed($query,$user_id){
		return $query->where('followed_user_id',$user_id);
	}
	public function scopeOfCanceled($query,$num){
		return $query->where('is_canceled',$num);
	}
	public function scopeIsFollow($query,$subject,$followed){
		return $query->where('subject_user_id' , $subject)
						->where('followed_user_id' , $followed);
	}
}
