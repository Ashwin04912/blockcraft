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
        Schema::table('ui_blocks', function (Blueprint $table) {
            // Non-unique: the reorder loop transiently revisits values mid-swap,
            // a unique constraint would break it without rewriting that logic.
            $table->index(['site_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ui_blocks', function (Blueprint $table) {
            $table->dropIndex(['site_id', 'display_order']);
        });
    }
};
