<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController, StudentController, TeacherController,
    AttendanceController, FeeController, FeeCategoryController,
    ExamController, SubjectController, ResultController,
    SalaryController, NoticeController, SettingsController,
    ApiController, ClassController, SectionController
};

Route::get('/', fn() => redirect()->route('dashboard'));

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    // Student import/export
    Route::get('/students/import',  [App\Http\Controllers\StudentImportController::class, 'index'])->name('students.import');
    Route::post('/students/import', [App\Http\Controllers\StudentImportController::class, 'import'])->name('students.import.store');
    Route::get('/students/sample',  [App\Http\Controllers\StudentImportController::class, 'downloadSample'])->name('students.sample');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Students
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    Route::resource('students', StudentController::class);
    Route::get('students/{student}/id-card', [StudentController::class, 'idCard'])->name('students.id-card');

    // Teachers
    Route::resource('teachers', TeacherController::class);

    // Classes & Sections
    Route::resource('classes', ClassController::class);
    Route::resource('sections', SectionController::class);

    // Subjects
    Route::resource('subjects', SubjectController::class);

    // Attendance
    Route::get('/attendance',         [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance',        [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/monthly', [AttendanceController::class, 'monthly'])->name('attendance.monthly');
    Route::get('/attendance/teacher', [AttendanceController::class, 'teacherAttendance'])->name('attendance.teacher');

    // Fees
    Route::get('/fees',                   [FeeController::class, 'index'])->name('fees.index');
    Route::post('/fees/collect',          [FeeController::class, 'collect'])->name('fees.collect');
    Route::get('/fees/receipt/{receipt}', [FeeController::class, 'receipt'])->name('fees.receipt');
    Route::get('/fees/student/{student}', [FeeController::class, 'studentLedger'])->name('fees.ledger');
    Route::get('/fees/due',               [FeeController::class, 'due'])->name('fees.due');
    Route::get('/fees/report',            [FeeController::class, 'report'])->name('fees.report');
    Route::resource('fee-categories', FeeCategoryController::class);

    // Exams
    Route::resource('exams', ExamController::class);

    // Results
    Route::get('/results',                              [ResultController::class, 'index'])->name('results.index');
    Route::post('/results',                             [ResultController::class, 'store'])->name('results.store');
    Route::get('/results/marksheet/{student}/{exam}',   [ResultController::class, 'marksheet'])->name('results.marksheet');
    Route::get('/results/merit-pdf/{exam}/{class}',     [ResultController::class, 'meritPdf'])->name('results.merit-pdf');

    // Salaries
    Route::get('/salaries',              [SalaryController::class, 'index'])->name('salaries.index');
    Route::post('/salaries/pay',         [SalaryController::class, 'pay'])->name('salaries.pay');
    Route::post('/salaries/pay-all',     [SalaryController::class, 'payAll'])->name('salaries.pay-all');
    Route::get('/salaries/slip/{payment}',[SalaryController::class, 'slip'])->name('salaries.slip');

    // Notices
    Route::resource('notices', NoticeController::class);

    // Settings
    Route::get('/settings',                          [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings',                         [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/session',                 [SettingsController::class, 'storeSession'])->name('settings.store-session');
    Route::post('/settings/session/{session}/current',[SettingsController::class, 'setCurrentSession'])->name('settings.set-current-session');
});

// API
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/sections',         [ApiController::class, 'sections']);
    Route::get('/students/search',  [ApiController::class, 'studentSearch']);
    Route::get('/teachers/search',  [ApiController::class, 'teacherSearch']);
    Route::get('/fee/student/{id}', [ApiController::class, 'studentFeeHistory']);
});

// Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function() {
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('profile.edit', compact('currentSession'));
    })->name('profile.edit');
});

// Profile Update
Route::middleware(['auth'])->group(function () {
    Route::patch('/profile', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.auth()->id(),
        ]);
        auth()->user()->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);
        return back()->with('success','প্রোফাইল আপডেট হয়েছে!');
    })->name('profile.update');

    Route::patch('/profile/password', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);
        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ড সঠিক নয়!']);
        }
        auth()->user()->update(['password' => \Hash::make($request->password)]);
        return back()->with('success','পাসওয়ার্ড পরিবর্তন হয়েছে!');
    })->name('profile.password');
});

// Meals (খোরাকি)
Route::middleware(['auth'])->group(function () {
    Route::get('/meals',              [App\Http\Controllers\MealController::class, 'index'])->name('meals.index');
    Route::get('/meals/create',       [App\Http\Controllers\MealController::class, 'create'])->name('meals.create');
    Route::post('/meals',             [App\Http\Controllers\MealController::class, 'store'])->name('meals.store');
    Route::post('/meals/bulk',        [App\Http\Controllers\MealController::class, 'bulkStore'])->name('meals.bulk');
    Route::delete('/meals/{meal}',    [App\Http\Controllers\MealController::class, 'destroy'])->name('meals.destroy');

    // Welfare (গোরাবা ফান্ড)
    Route::get('/welfare',            [App\Http\Controllers\WelfareController::class, 'index'])->name('welfare.index');
    Route::get('/welfare/create',     [App\Http\Controllers\WelfareController::class, 'create'])->name('welfare.create');
    Route::post('/welfare',           [App\Http\Controllers\WelfareController::class, 'store'])->name('welfare.store');
    Route::get('/welfare/{welfare}/edit',  [App\Http\Controllers\WelfareController::class, 'edit'])->name('welfare.edit');
    Route::put('/welfare/{welfare}',       [App\Http\Controllers\WelfareController::class, 'update'])->name('welfare.update');
    Route::delete('/welfare/{welfare}',    [App\Http\Controllers\WelfareController::class, 'destroy'])->name('welfare.destroy');
    Route::get('/welfare/student/{student}',[App\Http\Controllers\WelfareController::class, 'studentReport'])->name('welfare.student');

    // Reports
    Route::get('/reports',            [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/financial',  [App\Http\Controllers\ReportController::class, 'financial'])->name('reports.financial');
    Route::get('/reports/students',   [App\Http\Controllers\ReportController::class, 'students'])->name('reports.students');
    Route::get('/reports/attendance', [App\Http\Controllers\ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/welfare',    [App\Http\Controllers\ReportController::class, 'welfare'])->name('reports.welfare');
    Route::get('/reports/meal',       [App\Http\Controllers\ReportController::class, 'meal'])->name('reports.meal');

    // Student Import

});

// Designations
Route::middleware(['auth'])->group(function () {
    Route::resource('designations', App\Http\Controllers\DesignationController::class)->except(['create','edit','show']);
    Route::post('designations/{designation}/toggle', [App\Http\Controllers\DesignationController::class, 'toggle'])->name('designations.toggle');
    Route::post('designations/reorder', [App\Http\Controllers\DesignationController::class, 'reorder'])->name('designations.reorder');
});
