<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
	protected $table = 'users_sessions';
    protected $fillable = ['id', 'user_id', 'challenge', 'login_at', 'logout_at'];
}
