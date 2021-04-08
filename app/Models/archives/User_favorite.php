<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_favorite extends Model
{
   	protected $table = 'users_favorites';
    protected $fillable = ['user_id', 'post_id', 'is_canceled'];
}
