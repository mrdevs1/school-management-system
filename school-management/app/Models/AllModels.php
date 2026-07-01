<?php
// ============================================================
// app/Models/Student.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id','name','name_en','date_of_birth','gender','religion','blood_group',
        'photo','address','father_name','mother_name','guardian_phone','guardian_email',
        'guardian_occupation','father_nid','class_id','section_id','session_id',
        'roll_number','admission_date','previous_school','previous_class','tc_number',
        'status',
    ];

    protected $casts = [
        'date_of_birth'  => 'date',
        'admission_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($student) {
            $year  = now()->year;
            $count = self::whereYear('created_at', $year)->count() + 1;
            $student->student_id = 'S-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        });
    }

    public function studentClass()  { return $this->belongsTo(Classes::class, 'class_id'); }
    public function section()       { return $this->belongsTo(Section::class); }
    public function session()       { return $this->belongsTo(Session::class); }
    public function attendances()   { return $this->hasMany(Attendance::class); }
    public function feeCollections(){ return $this->hasMany(FeeCollection::class); }
    public function results()       { return $this->hasMany(Result::class); }
}


// ============================================================
// app/Models/Teacher.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id','name','name_en','email','phone','date_of_birth','gender',
        'qualification','designation','department','subject_specialty','salary',
        'joining_date','photo','nid','address','status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date'  => 'date',
        'salary'        => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($t) {
            $count = self::count() + 1;
            $t->teacher_id = 'T-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        });
    }

    public function salaryPayments() { return $this->hasMany(SalaryPayment::class); }
    public function sections()       { return $this->hasMany(Section::class); }
}


// ============================================================
// app/Models/Classes.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    protected $fillable = ['name','name_en','numeric_name','type'];

    public function sections()  { return $this->hasMany(Section::class, 'class_id'); }
    public function students()  { return $this->hasMany(Student::class, 'class_id'); }
    public function subjects()  { return $this->hasMany(Subject::class, 'class_id'); }
}


// ============================================================
// app/Models/Section.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['class_id','name','teacher_id'];

    public function studentClass() { return $this->belongsTo(Classes::class, 'class_id'); }
    public function teacher()      { return $this->belongsTo(Teacher::class); }
    public function students()     { return $this->hasMany(Student::class); }
}


// ============================================================
// app/Models/Session.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['name','start_date','end_date','is_current'];
    protected $casts    = ['start_date'=>'date','end_date'=>'date','is_current'=>'boolean'];
}


// ============================================================
// app/Models/Attendance.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_id','class_id','section_id','date','status','remarks','taken_by'];
    protected $casts    = ['date' => 'date'];

    public function student() { return $this->belongsTo(Student::class); }
}


// ============================================================
// app/Models/FeeCategory.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    protected $fillable = ['name','amount','frequency','description'];
    protected $casts    = ['amount' => 'decimal:2'];
}


// ============================================================
// app/Models/FeeCollection.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCollection extends Model
{
    protected $fillable = [
        'student_id','fee_category_id','amount','discount','paid_amount',
        'due_amount','month_year','payment_method','transaction_id',
        'collected_by','payment_date','receipt_no',
    ];

    protected $casts = ['payment_date'=>'date','amount'=>'decimal:2','paid_amount'=>'decimal:2','due_amount'=>'decimal:2'];

    public function student()      { return $this->belongsTo(Student::class); }
    public function feeCategory()  { return $this->belongsTo(FeeCategory::class); }
    public function collectedBy()  { return $this->belongsTo(\App\Models\User::class, 'collected_by'); }
}


// ============================================================
// app/Models/Exam.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['name','session_id','start_date','end_date','description'];
    protected $casts    = ['start_date'=>'date','end_date'=>'date'];

    public function session() { return $this->belongsTo(Session::class); }
    public function results() { return $this->hasMany(Result::class); }
}


// ============================================================
// app/Models/Subject.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name','name_en','code','class_id','full_marks','pass_marks','type'];

    public function studentClass() { return $this->belongsTo(Classes::class, 'class_id'); }
}


// ============================================================
// app/Models/Result.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = ['student_id','exam_id','subject_id','marks_obtained','grade','grade_point'];
    protected $casts    = ['marks_obtained'=>'decimal:2','grade_point'=>'decimal:2'];

    public function student() { return $this->belongsTo(Student::class); }
    public function exam()    { return $this->belongsTo(Exam::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
}


// ============================================================
// app/Models/SalaryPayment.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    protected $fillable = [
        'teacher_id','basic_salary','bonus','deduction','net_salary',
        'month_year','payment_method','payment_date','paid_by','note',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'net_salary'   => 'decimal:2',
    ];

    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function paidBy()  { return $this->belongsTo(\App\Models\User::class, 'paid_by'); }
}


// ============================================================
// app/Models/Notice.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['title','content','audience','publish_date','expire_date','created_by'];
    protected $casts    = ['publish_date'=>'date','expire_date'=>'date'];

    public function creator() { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}
