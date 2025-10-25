<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvImportHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_name',
        'kind',
    ];

    const KIND_WAITING = 'waiting';
    const KIND_CHILDREN = 'children';
    const KIND_ACCEPTANCE = 'acceptance';

    const KIND_JA = [
        self::KIND_WAITING => '受け入れ可能数',
        self::KIND_CHILDREN => '入所児童数',
        self::KIND_ACCEPTANCE => '入所待ち児童数',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preschoolMonthlyStats()
    {
        return $this->hasMany(PreschoolMonthlyStat::class);
    }

    // kind_ja
    public function getKindJaAttribute()
    {
        return self::KIND_JA[$this->kind];
    }
}
