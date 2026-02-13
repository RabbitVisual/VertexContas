<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint ) {
            ->id();
            ->string('name');
            ->string('slug')->unique();
            ->string('icon')->nullable();
            ->timestamps();
        });

        Schema::create('posts', function (Blueprint ) {
            ->id();
            ->foreignId('author_id')->constrained('users')->onDelete('cascade');
            ->foreignId('category_id')->constrained('blog_categories')->onDelete('cascade');
            ->string('title');
            ->string('slug')->unique();
            ->longText('content');
            ->string('featured_image')->nullable();
            ->enum('status', ['draft', 'pending_review', 'published'])->default('draft');
            ->boolean('is_premium')->default(false);
            ->unsignedBigInteger('views')->default(0);
            ->timestamps();
        });

        Schema::create('comments', function (Blueprint ) {
            ->id();
            ->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            ->foreignId('user_id')->constrained('users')->onDelete('cascade');
            ->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            ->text('content');
            ->boolean('is_approved')->default(false);
            ->timestamps();
        });

        Schema::create('post_likes', function (Blueprint ) {
            ->id();
            ->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            ->foreignId('user_id')->constrained('users')->onDelete('cascade');
            ->timestamps();
        });

        Schema::create('saved_posts', function (Blueprint ) {
            ->id();
            ->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            ->foreignId('user_id')->constrained('users')->onDelete('cascade');
            ->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_posts');
        Schema::dropIfExists('post_likes');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('blog_categories');
    }
};
