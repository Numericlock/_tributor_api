<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post_valid_disclosure_list extends Model
{
    protected $table = 'posts_valid_disclosure_lists';
	protected $fillable = ['list_id', 'post_id', 'is_hidden'];
}
