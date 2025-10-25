<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreschoolMonthlyStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'csv_import_history_id',
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

    public function csvImportHistory()
    {
        return $this->belongsTo(CsvImportHistory::class);
    }

    public function preschool()
    {
        return $this->belongsTo(Preschool::class);
    }
}
