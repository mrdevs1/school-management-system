<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_id')->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male','female']);
            $table->string('qualification');
            $table->string('designation');
            $table->string('department')->nullable();
            $table->string('subject_specialty')->nullable();
            $table->decimal('salary', 10, 2)->default(0);
            $table->date('joining_date');
            $table->string('photo')->nullable();
            $table->string('nid')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('teachers'); }
};
