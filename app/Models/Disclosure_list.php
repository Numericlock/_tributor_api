<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disclosure_list extends Model
{
	protected $table = 'disclosure_lists';
	protected $fillable = ['name', 'owner_user_id', 'is_published', 'is_hidden'];
    
    public function scopeIndex($query,$user_id){
        return $query->select('disclosure_lists.*',\DB::raw("GROUP_CONCAT(disclosure_lists_users.user_id)"))
        ->leftjoin('disclosure_lists_users','disclosure_lists_users.list_id','=','disclosure_lists.id')
        ->where('owner_user_id', $user_id)
        ->where('is_hidden', 0)
        ->groupBy('disclosure_lists.id')
        ->latest('updated_at')
        ->get();
    }
}
