<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_posts', function (Blueprint $table) {
            $table->bigIncrements('id')->nullable(false);
            $table->tinyInteger('is_share_available')->nullable(false);
            $table->string('post_user_id',15)->nullable(false);
            $table->string('content_text',255)->nullable(false);
            $table->bigInteger('parent_post_id')->nullable()->default(null);
            $table->tinyInteger('is_deleted')->nullable(false);
            $table->double('longitude', 16,12)->nullable()->default(null);
            $table->double('latitude', 16,12)->nullable()->default(null);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_posts');
    }
}
