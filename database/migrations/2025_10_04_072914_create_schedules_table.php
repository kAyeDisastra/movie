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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Film::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Studio::class)->constrained()->cascadeOnDelete();
            $table->date('show_date');
            $table->time('show_time');
            $table->foreignIdFor(App\Models\Price::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\User::class, 'created_by')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
