<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('enrollments')) {
            $this->createLmsEnrollmentsTable('enrollments');
            return;
        }

        if (Schema::hasColumn('enrollments', 'course_id')) {
            return;
        }

        $legacyRows = DB::table('enrollments')->orderBy('id')->get();

        $this->createLmsEnrollmentsTable('enrollments_lms');

        foreach ($legacyRows as $row) {
            $courseId = DB::table('courses')
                ->where('name', $row->course_name)
                ->value('id');

            if ($courseId && DB::table('enrollments_lms')
                ->where('user_id', $row->user_id)
                ->where('course_id', $courseId)
                ->exists()) {
                $courseId = null;
            }

            DB::table('enrollments_lms')->insert([
                'id' => $row->id,
                'user_id' => $row->user_id,
                'course_id' => $courseId,
                'status' => $row->status === 'pagado' ? 'activo' : $row->status,
                'progress' => $row->status === 'completado' ? 100 : 0,
                'last_accessed_at' => null,
                'total_time_minutes' => 0,
                'completed_at' => $row->status === 'completado' ? $row->updated_at : null,
                'enrolled_at' => $row->created_at,
                'legacy_course_name' => $row->course_name,
                'legacy_level' => $row->level,
                'legacy_price' => $row->price,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }

        Schema::drop('enrollments');
        Schema::rename('enrollments_lms', 'enrollments');
    }

    public function down(): void
    {
        if (! Schema::hasTable('enrollments')) {
            return;
        }

        Schema::create('enrollments_legacy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('course_name');
            $table->string('level');
            $table->decimal('price', 8, 2);
            $table->enum('status', ['pendiente', 'pagado', 'completado'])->default('pendiente');
            $table->timestamps();
        });

        $rows = DB::table('enrollments')->orderBy('id')->get();

        foreach ($rows as $row) {
            $course = $row->course_id
                ? DB::table('courses')->where('id', $row->course_id)->first()
                : null;

            DB::table('enrollments_legacy')->insert([
                'id' => $row->id,
                'user_id' => $row->user_id,
                'course_name' => $row->legacy_course_name ?? $course?->name ?? 'Curso no identificado',
                'level' => $row->legacy_level ?? $course?->level ?? 'basico',
                'price' => $row->legacy_price ?? $course?->price ?? 0,
                'status' => $row->status === 'activo' ? 'pagado' : $row->status,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }

        Schema::drop('enrollments');
        Schema::rename('enrollments_legacy', 'enrollments');
    }

    private function createLmsEnrollmentsTable(string $tableName): void
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pendiente', 'activo', 'completado', 'suspendido'])->default('pendiente');
            $table->decimal('progress', 5, 2)->default(0);
            $table->dateTime('last_accessed_at')->nullable();
            $table->integer('total_time_minutes')->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('enrolled_at')->nullable();
            $table->string('legacy_course_name')->nullable();
            $table->string('legacy_level')->nullable();
            $table->decimal('legacy_price', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
            $table->index('status');
        });
    }
};
