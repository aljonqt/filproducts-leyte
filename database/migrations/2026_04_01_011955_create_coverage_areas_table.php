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
    Schema::create('coverage_areas', function (Blueprint $table) {
        $table->id();
        $table->string('barangay');
        $table->string('area_name');
        $table->string('city');
        $table->string('province');
        $table->boolean('isavailable')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coverage_areas');
    }
};
