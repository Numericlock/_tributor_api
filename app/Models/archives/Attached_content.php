<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attached_content extends Model
{
	protected $table = 'attached_contents';
	protected $fillable = ['post_id', 'content_type', 'content_file_path'];
}
