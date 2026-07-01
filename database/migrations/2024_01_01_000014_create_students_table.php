<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male','female']);
            $table->string('religion')->default('Islam');
            $table->string('blood_group')->nullable();
            $table->string('photo')->nullable();
            $table->text('address');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('guardian_phone');
            $table->string('guardian_email')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('father_nid')->nullable();
            $table->foreignId('class_id')->constrained('classes');
            $table->foreignId('section_id')->constrained('sections');
            $table->unsignedBigInteger('session_id');
            $table->unsignedSmallInteger('roll_number')->nullable();
            $table->date('admission_date');
            $table->string('previous_school')->nullable();
            $table->string('previous_class')->nullable();
            $table->string('tc_number')->nullable();
            $table->enum('status', ['active','inactive','transferred'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('students'); }
};
