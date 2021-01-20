<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColNextAppliedAt2Automatics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumns('automatics', [
            'is_applied',
            'next_applied_at',
        ])) {
            return;
        }

        Schema::table('automatics', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_applied')->default(0);
            $table->dateTime('next_applied_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumns('automatics', [
            'is_applied',
            'next_applied_at',
        ])) {
            return;
        }

        Schema::table('automatics', function (Blueprint $table) {
            $table->dropColumn([
                'is_applied',
                'next_applied_at',
            ]);
        });
    }
}
