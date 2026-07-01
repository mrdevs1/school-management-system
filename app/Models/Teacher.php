<?php
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


