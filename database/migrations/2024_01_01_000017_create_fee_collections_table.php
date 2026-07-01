<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('fee_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_category_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->string('month_year')->nullable();
            $table->enum('payment_method', ['cash','bkash','nagad','bank'])->default('cash');
            $table->string('transaction_id')->nullable();
            $table->unsignedBigInteger('collected_by')->nullable();
            $table->date('payment_date');
            $table->string('receipt_no')->unique();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('fee_collections'); }
};
