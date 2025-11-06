<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop kolom lama
            $table->dropColumn(['order_id', 'total']);
            
            // Tambah kolom baru
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('schedule_id');
            $table->json('seats'); // Array kursi yang dibeli
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('schedule_id')->references('id')->on('schedules');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['schedule_id']);
            $table->dropColumn([
                'user_id', 'schedule_id', 'seats', 'total_amount', 
                'payment_status', 'payment_method', 'midtrans_order_id', 'midtrans_transaction_id'
            ]);
            
            $table->unsignedBigInteger('order_id');
            $table->decimal('total', 10, 2);
        });
    }
};