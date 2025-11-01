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
        Schema::create('preschool_stats_import_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_provider_id')->constrained('data_providers');
            $table->foreignId('user_id')->constrained('users');
            $table->date('target_date')->comment('対象日');
            $table->enum('kind', ['waiting','children','acceptance'])->comment('種別');
            $table->string('file_name')->comment('ファイル名');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preschool_stats_import_histories');
    }
};
