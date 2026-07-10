<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->string('provider')->default('mercadopago');
            $table->string('preference_id')->nullable();
            $table->string('provider_payment_id')->nullable()->index();
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_process', 'refunded'])
                  ->default('pending');
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3)->default('MXN');
            $table->json('raw_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};