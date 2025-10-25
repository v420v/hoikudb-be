<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreschoolLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'preschool_id',
        'location',
    ];

    public function preschool()
    {
        return $this->belongsTo(Preschool::class);
    }
}
