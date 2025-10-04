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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Schedule::class)->constrained()->cascadeOnDelete();
            $table->dateTime('order_time');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->foreignIdFor(App\Models\User::class, 'cashier_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
