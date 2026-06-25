<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->enum('level', ['basico', 'intermedio', 'avanzado'])->default('basico');
            $table->enum('status', ['borrador', 'publicado', 'archivado'])->default('borrador');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->dateTime('sale_start')->nullable();
            $table->dateTime('sale_end')->nullable();
            $table->integer('duration_weeks')->default(1);
            $table->string('meta_description', 300)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('instructor_id');
            $table->index('status');
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
