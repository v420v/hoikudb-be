<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preschool extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'name',
        'building_code',
        'area_info',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public function preschoolMonthlyStats()
    {
        return $this->hasMany(PreschoolMonthlyStat::class);
    }

    public function preschoolLocation()
    {
        return $this->hasOne(PreschoolLocation::class);
    }
}
