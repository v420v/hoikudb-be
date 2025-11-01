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
        Schema::create('preschool_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preschool_stats_import_history_id')->constrained('preschool_stats_import_histories');
            $table->foreignId('preschool_id')->constrained('preschools');
            $table->date('target_date')->comment('対象日');
            $table->enum('kind', ['waiting','children','acceptance'])->comment('種別');
            $table->integer('zero_year_old')->comment('0歳児');
            $table->integer('one_year_old')->comment('1歳児');
            $table->integer('two_year_old')->comment('2歳児');
            $table->integer('three_year_old')->comment('3歳児');
            $table->integer('four_year_old')->comment('4歳児');
            $table->integer('five_year_old')->comment('5歳児');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preschool_stats');
    }
};
