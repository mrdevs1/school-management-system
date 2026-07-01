<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->decimal('marks_obtained', 5, 2);
            $table->string('grade', 4)->nullable();
            $table->decimal('grade_point', 3, 2)->nullable();
            $table->timestamps();
            $table->unique(['student_id','exam_id','subject_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('results'); }
};
