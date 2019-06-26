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
            $table->tinyInteger('status')->default(Post::STATUS_DRAFT);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->foreign('approved_by')->on('users')->reference('id');

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
            $table->dropColumn('status');

            $table->dropIndex(['published_at']);
            $table->dropColumn('published_at');

            $table->dropColumn('approved_at');
            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');
        });
    }
}
