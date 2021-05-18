<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;
    protected $fillable = ['id', 'name', 'password', 'email', 'email_verified_at', 'remember_token','user_id', 'birth_on', 'summary', 'is_deleted'];
	//protected $guarded = ['id', 'password', 'created_at']
}
