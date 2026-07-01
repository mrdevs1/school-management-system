<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['name','session_id','start_date','end_date','description'];
    protected $casts    = ['start_date'=>'date','end_date'=>'date'];

    public function session() { return $this->belongsTo(Session::class); }
    public function results() { return $this->hasMany(Result::class); }
}


