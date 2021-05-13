<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdBirthOnSummaryIsDeletedLastLoginAtToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_id',32)->nullable(false)->unique();
            $table->date('birth_on')->nullable()->default(null);
            $table->string('summary',512)->nullable()->default(null);
            $table->tinyInteger('is_deleted')->nullable(false);
            $table->timestamp('last_login_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_id');  
            $table->dropColumn('birth_on');  
            $table->dropColumn('summary');  
            $table->dropColumn('is_deleted');  
            $table->dropColumn('last_login_at');  
        });
    }
}
