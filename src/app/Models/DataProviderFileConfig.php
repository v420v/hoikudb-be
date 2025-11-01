<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProviderFileConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_provider_id',
        'display_name',
        'file_type',
        'new_line',
        'encoding',
        'delimiter',
        'enclosure',
    ];

    /**
     * ファイルタイプ
     */
    const FILE_TYPE_CSV = 'csv';
    const FILE_TYPE_PDF = 'pdf';

    /**
     * 改行コード
     */
    const NEW_LINE_LF = 'lf';
    const NEW_LINE_CRLF = 'crlf';

    /**
     * エンコーディング
     */
    const ENCODING_SJIS = 'sjis';
    const ENCODING_UTF_8 = 'utf-8';

    /**
     * 区切り文字
     */
    const DELIMITER_COMMA = ',';
    const DELIMITER_TAB = '\t';
    const DELIMITER_SEMICOLON = ';';
    const DELIMITER_COLON = ':';

    /**
     * 囲み文字
     */
    const ENCLOSURE_DOUBLE_QUOTE = '"';
    const ENCLOSURE_SINGLE_QUOTE = "'";
    const ENCLOSURE_BACKTICK = '`';
    const ENCLOSURE_PIPE = '|';

    public function dataProvider()
    {
        return $this->belongsTo(DataProvider::class);
    }
}
