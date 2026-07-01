<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_id','class_id','section_id','date','status','remarks','taken_by'];
    protected $casts    = ['date' => 'date'];

    public function student() { return $this->belongsTo(Student::class); }
}


