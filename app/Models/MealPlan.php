<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    protected $fillable = [
        'student_id','month_year','total_days','present_days',
        'rate_per_day','total_amount','student_paid',
        'institution_paid','due_amount','note','created_by',
    ];
    protected $casts = [
        'total_amount'=>'decimal:2','student_paid'=>'decimal:2',
        'institution_paid'=>'decimal:2','due_amount'=>'decimal:2',
        'rate_per_day'=>'decimal:2',
    ];
    public function student() { return $this->belongsTo(Student::class); }
    public function creator() { return $this->belongsTo(User::class,'created_by'); }
}
