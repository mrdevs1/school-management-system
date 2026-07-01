<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->decimal('deduction', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->string('month_year');
            $table->enum('payment_method', ['cash','bank','bkash'])->default('cash');
            $table->date('payment_date');
            $table->unsignedBigInteger('paid_by')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['teacher_id','month_year']);
        });
    }
    public function down(): void { Schema::dropIfExists('salary_payments'); }
};
