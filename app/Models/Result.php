<?php
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


