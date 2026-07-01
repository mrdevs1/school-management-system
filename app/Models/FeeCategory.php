<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    protected $fillable = ['name','amount','frequency','description'];
    protected $casts    = ['amount' => 'decimal:2'];
}


