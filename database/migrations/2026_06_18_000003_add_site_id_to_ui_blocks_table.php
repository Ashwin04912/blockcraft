<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ui_blocks', function (Blueprint $table) {
            $table->foreignId('site_id')
                  ->after('id')
                  ->constrained('sites')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ui_blocks', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Site::class);
            $table->dropColumn('site_id');
        });
    }
};
