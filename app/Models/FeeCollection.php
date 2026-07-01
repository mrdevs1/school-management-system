<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCollection extends Model
{
    protected $fillable = [
        'student_id','fee_category_id','amount','discount','paid_amount',
        'due_amount','month_year','payment_method','transaction_id',
        'collected_by','payment_date','receipt_no',
    ];

    protected $casts = ['payment_date'=>'date','amount'=>'decimal:2','paid_amount'=>'decimal:2','due_amount'=>'decimal:2'];

    public function student()      { return $this->belongsTo(Student::class); }
    public function feeCategory()  { return $this->belongsTo(FeeCategory::class); }
    public function collectedBy()  { return $this->belongsTo(\App\Models\User::class, 'collected_by'); }
}


