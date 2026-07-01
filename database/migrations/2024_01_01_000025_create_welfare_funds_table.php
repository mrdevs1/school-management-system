<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('welfare_funds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // বৃত্তি, সহায়তা, বই কিনে দেওয়া
            $table->enum('type', ['scholarship','book','food','clothing','medical','other']);
            $table->string('month_year')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('student_contribution', 10, 2)->default(0);
            $table->decimal('institution_contribution', 10, 2)->default(0);
            $table->decimal('donor_contribution', 10, 2)->default(0);
            $table->string('donor_name')->nullable();
            $table->date('date');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('welfare_funds'); }
};
