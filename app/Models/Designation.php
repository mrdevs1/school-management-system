<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = ['name', 'order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'designation', 'name');
    }

    public static function active()
    {
        return self::where('is_active', true)->orderBy('order')->get();
    }
}
