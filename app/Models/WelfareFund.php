<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WelfareFund extends Model
{
    protected $fillable = [
        'student_id','title','type','month_year','total_amount',
        'student_contribution','institution_contribution',
        'donor_contribution','donor_name','date','note','created_by',
    ];
    protected $casts = [
        'date'=>'date',
        'total_amount'=>'decimal:2',
        'student_contribution'=>'decimal:2',
        'institution_contribution'=>'decimal:2',
        'donor_contribution'=>'decimal:2',
    ];
    public function student() { return $this->belongsTo(Student::class); }
    public function creator() { return $this->belongsTo(User::class,'created_by'); }
}
