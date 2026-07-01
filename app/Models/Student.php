<?php
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
            $year = now()->year;
            $last = self::where("student_id", "like", "S-".$year."-%")->orderByDesc("student_id")->value("student_id");
            $next = $last ? ((int) substr($last, -4)) + 1 : 1;
            do {
                $id = "S-".$year."-".str_pad($next++, 4, "0", STR_PAD_LEFT);
            } while (self::where("student_id", $id)->exists());
            $student->student_id = $id;
        });
    }

    public function studentClass()  { return $this->belongsTo(Classes::class, 'class_id'); }
    public function section()       { return $this->belongsTo(Section::class); }
    public function session()       { return $this->belongsTo(Session::class); }
    public function attendances()   { return $this->hasMany(Attendance::class); }
    public function feeCollections(){ return $this->hasMany(FeeCollection::class); }
    public function results()       { return $this->hasMany(Result::class); }
}


