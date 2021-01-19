<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('currencies')) {
            return;
        }

        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('名称');
            $table->string('remark')->default('')->comment('备注');
            $table->timestamps();
        });

        $now = now();

        \App\Models\Currency::query()->insert([
            [
                'name'       => 'CNY',
                'remark'     => '人民币',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'USD',
                'remark'     => '美元',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'HKD',
                'remark'     => '港元',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
