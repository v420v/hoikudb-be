<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_provider_file_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_provider_id')->constrained('data_providers');
            $table->string('display_name')->comment('表示名');
            $table->enum('file_type', ['csv', 'pdf'])->comment('ファイルタイプ');
            $table->enum('new_line', ['lf', 'crlf'])->comment('改行コード');
            $table->enum('encoding', ['sjis', 'utf-8'])->comment('エンコーディング');
            $table->string('delimiter')->comment('区切り文字');
            $table->string('enclosure')->comment('囲み文字');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_provider_file_configs');
    }
};
