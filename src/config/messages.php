<?php

return [
    // データプロバイダー関連
    'data_provider' => [
        'created' => 'データプロバイダーを作成しました。',
        'updated' => 'データプロバイダーを更新しました。',
        'deleted' => 'データプロバイダーを削除しました。',
    ],

    // ファイル設定関連
    'file_config' => [
        'created' => 'ファイル設定を作成しました。',
        'updated' => 'ファイル設定を更新しました。',
        'deleted' => 'ファイル設定を削除しました。',
    ],

    // インポート関連
    'import' => [
        'success' => 'ファイルのインポートが完了しました。',
        'error' => 'ファイルのインポート中にエラーが発生しました: :message',
        'validation' => [
            'area_info_required' => 'エリア情報が存在しません',
            'name_required' => '園名が存在しません',
            'building_code_required' => '建物コードが存在しません',
        ],
        'file_type' => [
            'pdf_not_supported' => 'PDF file type is not supported',
            'invalid_file_type' => 'Invalid file type',
        ],
    ],

    // 認証関連
    'auth' => [
        'login_failed' => 'ログイン情報が正しくありません。',
    ],

    // 確認ダイアログ
    'confirm' => [
        'logout' => 'ログアウトしますか？',
        'delete' => '本当に削除しますか？',
    ],
];

