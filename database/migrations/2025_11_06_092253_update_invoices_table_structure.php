<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\Order::class);
            $table->dropColumn('total');

            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Schedule::class)->constrained()->cascadeOnDelete();
            $table->json('seats');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\User::class);
            $table->dropConstrainedForeignIdFor(Schedule::class);
            $table->dropColumn([
                'user_id', 'schedule_id', 'seats', 'total_amount',
                'payment_status', 'payment_method', 'midtrans_order_id', 'midtrans_transaction_id'
            ]);

            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->decimal('total', 10, 2);
        });
    }
};
