<?php
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


