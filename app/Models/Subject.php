<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name','name_en','code','class_id','full_marks','pass_marks','type'];

    public function studentClass() { return $this->belongsTo(Classes::class, 'class_id'); }
}


