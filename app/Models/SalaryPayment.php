<?php
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


