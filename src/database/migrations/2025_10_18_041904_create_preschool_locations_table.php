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
        Schema::create('preschool_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preschool_id')->constrained('preschools');
            $table->string('address', 255)->nullable();
            $table->geometry('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preschool_locations');
    }
};
