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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->string('curruncy_pair')->nullable();
            $table->decimal('buy_value', 15, 2)->nullable();
            $table->decimal('buy_price', 15, 2)->nullable();
            $table->decimal('sell_value', 15, 2)->nullable();
            $table->decimal('sell_price', 15, 2)->nullable();
            $table->decimal('bought_amount', 15, 2)->nullable();
            $table->string('position')->nullable();
            $table->decimal('pnl', 15, 2)->nullable();
            $table->bigInteger('timestamp');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
