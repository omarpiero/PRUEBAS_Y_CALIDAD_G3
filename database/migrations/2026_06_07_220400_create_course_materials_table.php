<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->cascadeOnDelete();
            $table->enum('type', ['video', 'documento', 'presentacion', 'texto', 'recurso']);
            $table->string('title', 300);
            $table->text('description')->nullable();
            $table->longText('content')->nullable(); // For rich-text type
            $table->string('file_path', 500)->nullable(); // For uploaded files
            $table->string('file_type', 20)->nullable(); // pdf, docx, pptx, zip, xlsx, mp4, webm
            $table->string('video_url', 500)->nullable(); // For YouTube/Vimeo URLs
            $table->enum('video_source', ['youtube', 'vimeo', 'upload'])->nullable();
            $table->integer('duration_minutes')->nullable(); // Video duration
            $table->integer('order')->default(0);
            $table->boolean('is_downloadable')->default(false);
            $table->timestamps();

            $table->index(['module_id', 'order']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_materials');
    }
};
