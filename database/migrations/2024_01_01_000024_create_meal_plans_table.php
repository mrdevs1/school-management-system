<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('month_year'); // 2024-01
            $table->integer('total_days')->default(30);
            $table->integer('present_days')->default(0);
            $table->decimal('rate_per_day', 8, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('student_paid', 10, 2)->default(0);
            $table->decimal('institution_paid', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->unique(['student_id','month_year']);
        });
    }
    public function down(): void { Schema::dropIfExists('meal_plans'); }
};
