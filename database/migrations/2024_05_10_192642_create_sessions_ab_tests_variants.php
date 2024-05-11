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
        Schema::create('sessions_ab_test_variants', function (Blueprint $table) {
            $table->foreignId('session_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('ab_test_variant_id');

            $table->primary(
                [
                    'session_id',
                    'ab_test_variant_id'
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions_ab_test_variants');
    }
};
