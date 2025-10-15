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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meja_id')->constrained('mejas');
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans');
            $table->foreignId('waiter_id')->constrained('users');
            $table->integer('total')->default(0);
            $table->integer('dibayar')->default(0);
            $table->enum('status', ['draft', 'bayar', 'batal'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
