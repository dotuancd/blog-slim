<?php

use App\Models\Post;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishedAtToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->tinyInteger('status')->default(Post::STATUS_DRAFT)->after('content')->index();
            $table->timestamp('published_at')->nullable()->after('user_id')->index();
            $table->timestamp('approved_at')->nullable()->after('published_at');
            $table->unsignedInteger('approved_by')->nullable()->after('approved_at');
            $table->foreign('approved_by')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');

            $table->dropIndex(['published_at']);
            $table->dropColumn('published_at');

            $table->dropColumn('approved_at');
            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');
        });
    }
}
