<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price')->comment('金额');
            $table->unsignedInteger('type')->default('1')->comment('类型');
            $table->unsignedInteger('project_id')->comment('项目');
            $table->decimal('确认金额')->comment('确认金额');
            $table->decimal('确认份额')->comment('确认份额');
            $table->decimal('确认净值')->comment('确认净值');
            $table->decimal('手续费')->comment('手续费');
            $table->dateTime('confirmed_at')->comment('确认时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
