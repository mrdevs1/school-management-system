<?php
// ============================================================
// FILE: database/migrations/2024_01_01_000001_create_sessions_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // 2024-2025
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sessions'); }
};

// ============================================================
// FILE: database/migrations/2024_01_01_000002_create_classes_table.php
// ============================================================
return new class extends Migration {
    public function up(): void {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // প্রথম শ্রেণী, দাখিল ৬ষ্ঠ বর্ষ
            $table->string('name_en')->nullable();
            $table->unsignedTinyInteger('numeric_name')->nullable();
            $table->enum('type', ['school','madrasa','both'])->default('school');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('classes'); }
};

// ============================================================
// FILE: database/migrations/2024_01_01_000003_create_teachers_table.php
// ============================================================
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

// ============================================================
// FILE: database/migrations/2024_01_01_000004_create_sections_table.php
// ============================================================
return new class extends Migration {
    public function up(): void {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained()->cascadeOnDelete();
            $table->string('name');             // A, B, আলিম
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sections'); }
};

// ============================================================
// FILE: database/migrations/2024_01_01_000005_create_students_table.php
// ============================================================
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
            $table->foreignId('session_id')->constrained('sessions');
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

// ============================================================
// FILE: database/migrations/2024_01_01_000006_create_attendances_table.php
// ============================================================
return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes');
            $table->foreignId('section_id')->nullable()->constrained('sections');
            $table->date('date');
            $table->enum('status', ['present','absent','late','leave']);
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('taken_by')->nullable();
            $table->timestamps();
            $table->unique(['student_id','date']);
        });
    }
    public function down(): void { Schema::dropIfExists('attendances'); }
};

// ============================================================
// FILE: database/migrations/2024_01_01_000007_create_fee_categories_table.php
// ============================================================
return new class extends Migration {
    public function up(): void {
        Schema::create('fee_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->enum('frequency', ['monthly','yearly','once']);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('fee_categories'); }
};

// ============================================================
// FILE: database/migrations/2024_01_01_000008_create_fee_collections_table.php
// ============================================================
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
            $table->string('month_year')->nullable();        // 2024-01
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

// ============================================================
// FILE: database/migrations/2024_01_01_000009_create_exams_table.php
// ============================================================
return new class extends Migration {
    public function up(): void {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('session_id')->constrained('sessions');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('exams'); }
};

// ============================================================
// FILE: database/migrations/2024_01_01_000010_create_subjects_table.php
// ============================================================
return new class extends Migration {
    public function up(): void {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('code')->unique();
            $table->foreignId('class_id')->constrained('classes');
            $table->unsignedSmallInteger('full_marks')->default(100);
            $table->unsignedSmallInteger('pass_marks')->default(33);
            $table->enum('type', ['theory','practical','viva'])->default('theory');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('subjects'); }
};

// ============================================================
// FILE: database/migrations/2024_01_01_000011_create_results_table.php
// ============================================================
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

// ============================================================
// FILE: database/migrations/2024_01_01_000012_create_salary_payments_table.php
// ============================================================
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

// ============================================================
// FILE: database/migrations/2024_01_01_000013_create_notices_table.php
// ============================================================
return new class extends Migration {
    public function up(): void {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('audience', ['all','students','teachers','parents'])->default('all');
            $table->date('publish_date');
            $table->date('expire_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notices'); }
};
