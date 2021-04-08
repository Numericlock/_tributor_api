<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disclosure_list extends Model
{
	protected $table = 'disclosure_lists';
	protected $fillable = ['name', 'owner_user_id', 'is_published', 'is_hidden'];
}
