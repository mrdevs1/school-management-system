<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['class_id','name','teacher_id'];

    public function studentClass() { return $this->belongsTo(Classes::class, 'class_id'); }
    public function teacher()      { return $this->belongsTo(Teacher::class); }
    public function students()     { return $this->hasMany(Student::class); }
}


