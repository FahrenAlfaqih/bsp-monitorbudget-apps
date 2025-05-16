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
        Schema::table('deklarasi_perjalanan_dinas', function (Blueprint $table) {
            $table->string('pr')->after('total_biaya');
            $table->string('po')->after('pr');
            $table->string('ses')->after('po');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deklarasi_perjalanan_dinas', function (Blueprint $table) {
            $table->dropColumn('pr');
            $table->dropColumn('po');
            $table->dropColumn('ses');
        });
    }
};
