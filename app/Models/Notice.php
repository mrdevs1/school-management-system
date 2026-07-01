<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['title','content','audience','publish_date','expire_date','created_by'];
    protected $casts    = ['publish_date'=>'date','expire_date'=>'date'];

    public function creator() { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}