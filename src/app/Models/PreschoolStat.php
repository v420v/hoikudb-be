<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreschoolStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'preschool_stats_import_history_id',
        'preschool_id',
        'target_date',
        'kind',
        'zero_year_old',
        'one_year_old',
        'two_year_old',
        'three_year_old',
        'four_year_old',
        'five_year_old',
    ];

    const KIND_WAITING = 'waiting';
    const KIND_CHILDREN = 'children';
    const KIND_ACCEPTANCE = 'acceptance';

    const KIND_JA = [
        self::KIND_WAITING => '受け入れ可能数',
        self::KIND_CHILDREN => '入所児童数',
        self::KIND_ACCEPTANCE => '入所待ち児童数',
    ];

    public function preschoolStatsImportHistory()
    {
        return $this->belongsTo(PreschoolStatsImportHistory::class);
    }

    public function preschool()
    {
        return $this->belongsTo(Preschool::class);
    }

    public function getKindJaAttribute()
    {
        return self::KIND_JA[$this->kind];
    }
}
