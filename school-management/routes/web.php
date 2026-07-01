<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    StudentController,
    TeacherController,
    AttendanceController,
    FeeController,
    FeeCategoryController,
    ExamController,
    SubjectController,
    ResultController,
    SalaryController,
    NoticeController,
    SettingsController,
    ApiController,
};

// ── Auth Routes (Laravel Breeze) ─────────────────────────
require __DIR__.'/auth.php';

// ── Authenticated Routes ──────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // ── Students ─────────────────────────────────────────
    Route::resource('students', StudentController::class);
    Route::get('students/{student}/id-card',      [StudentController::class, 'idCard'])->name('students.id-card');
    Route::get('students/{student}/promote',      [StudentController::class, 'promoteForm'])->name('students.promote');
    Route::post('students/{student}/promote',     [StudentController::class, 'promote'])->name('students.promote.store');
    Route::get('students/export',                 [StudentController::class, 'export'])->name('students.export');

    // ── Teachers ─────────────────────────────────────────
    Route::resource('teachers', TeacherController::class);
    Route::get('teachers/{teacher}/salary-history',[TeacherController::class, 'salaryHistory'])->name('teachers.salary');

    // ── Classes & Sections ────────────────────────────────
    Route::resource('classes',  \App\Http\Controllers\ClassController::class);
    Route::resource('sections', \App\Http\Controllers\SectionController::class);

    // ── Subjects ──────────────────────────────────────────
    Route::resource('subjects', SubjectController::class);

    // ── Attendance ────────────────────────────────────────
    Route::get( '/attendance',          [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance',          [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get( '/attendance/monthly',  [AttendanceController::class, 'monthly'])->name('attendance.monthly');
    Route::get( '/attendance/teacher',  [AttendanceController::class, 'teacherAttendance'])->name('attendance.teacher');

    // ── Fees ──────────────────────────────────────────────
    Route::get( '/fees',                         [FeeController::class, 'index'])->name('fees.index');
    Route::post('/fees/collect',                 [FeeController::class, 'collect'])->name('fees.collect');
    Route::get( '/fees/receipt/{receipt}',       [FeeController::class, 'receipt'])->name('fees.receipt');
    Route::get( '/fees/student/{student}',       [FeeController::class, 'studentLedger'])->name('fees.ledger');
    Route::get( '/fees/due',                     [FeeController::class, 'due'])->name('fees.due');
    Route::get( '/fees/report',                  [FeeController::class, 'report'])->name('fees.report');
    Route::resource('fee-categories', FeeCategoryController::class);

    // ── Exams ─────────────────────────────────────────────
    Route::resource('exams', ExamController::class);

    // ── Results ───────────────────────────────────────────
    Route::get( '/results',                                  [ResultController::class, 'index'])->name('results.index');
    Route::post('/results',                                  [ResultController::class, 'store'])->name('results.store');
    Route::get( '/results/marksheet/{student}/{exam}',       [ResultController::class, 'marksheet'])->name('results.marksheet');
    Route::get( '/results/merit-pdf/{exam}/{class}',         [ResultController::class, 'meritPdf'])->name('results.merit-pdf');

    // ── Salaries ──────────────────────────────────────────
    Route::get( '/salaries',             [SalaryController::class, 'index'])->name('salaries.index');
    Route::post('/salaries/pay',         [SalaryController::class, 'pay'])->name('salaries.pay');
    Route::post('/salaries/pay-all',     [SalaryController::class, 'payAll'])->name('salaries.pay-all');
    Route::get( '/salaries/slip/{payment}',[SalaryController::class, 'slip'])->name('salaries.slip');

    // ── Notices ───────────────────────────────────────────
    Route::resource('notices', NoticeController::class);

    // ── Settings ──────────────────────────────────────────
    Route::get( '/settings',   [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings',   [SettingsController::class, 'update'])->name('settings.update');

});

// ── Internal API (AJAX) ───────────────────────────────────
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/sections',          [ApiController::class, 'sections']);
    Route::get('/students/search',   [ApiController::class, 'studentSearch']);
    Route::get('/teachers/search',   [ApiController::class, 'teacherSearch']);
    Route::get('/fee/student/{id}',  [ApiController::class, 'studentFeeHistory']);
});
