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
        Schema::create('preschool_monthly_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('csv_import_history_id')->constrained('csv_import_histories');
            $table->foreignId('preschool_id')->constrained('preschools');
            $table->date('target_date');
            $table->enum('kind', ['waiting','children','acceptance']);
            $table->integer('zero_year_old');
            $table->integer('one_year_old');
            $table->integer('two_year_old');
            $table->integer('three_year_old');
            $table->integer('four_year_old');
            $table->integer('five_year_old');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preschool_monthly_stats');
    }
};
