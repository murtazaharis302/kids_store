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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('transaction_id');
            $table->string('sender_name')->nullable()->after('reference_number');
            $table->text('payment_notes')->nullable()->after('sender_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['reference_number', 'sender_name', 'payment_notes']);
        });
    }
};
