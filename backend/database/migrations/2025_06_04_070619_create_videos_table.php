<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('youtube_video_id')->unique(); // Crucial for fetching thumbnail and embedding
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable(); // Optional: you might fetch this too
            $table->string('status')->default('published'); // draft, published
            $table->boolean('is_featured')->default(false);
            // Optional: Link to your Post model when a video is tied to a blog post.
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};