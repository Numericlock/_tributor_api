<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \LaravelTreats\Model\Traits\HasCompositePrimaryKey;

class Disclosure_list_user extends Model
{
	protected $table = 'disclosure_lists_users';
	protected $fillable = ['list_id', 'user_id', 'is_deleted'];
	
	public function scopeIsMember($query, $list_id, $user_id){
		return $query->where('list_id', $list_id)
				->where('user_id', $user_id);
	}
	
}
