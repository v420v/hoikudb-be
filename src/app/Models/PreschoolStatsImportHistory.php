<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreschoolStatsImportHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_provider_id',
        'user_id',
        'target_date',
        'kind',
        'file_name',
    ];

    const KIND_WAITING = 'waiting';
    const KIND_CHILDREN = 'children';
    const KIND_ACCEPTANCE = 'acceptance';

    const KIND_JA = [
        self::KIND_WAITING => '受け入れ可能数',
        self::KIND_CHILDREN => '入所児童数',
        self::KIND_ACCEPTANCE => '入所待ち児童数',
    ];

    public function dataProvider()
    {
        return $this->belongsTo(DataProvider::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preschoolStats()
    {
        return $this->hasMany(PreschoolStat::class);
    }

    public function getKindJaAttribute()
    {
        return self::KIND_JA[$this->kind];
    }
}
