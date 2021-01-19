<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutomaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('automatics')){
            return;
        }

        Schema::create('automatics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('名称');
            $table->unsignedInteger('project_id')->comment('项目');
            $table->decimal('price')->comment('金额');
            $table->decimal('手续费率')->default('0')->comment('手续费率');
            $table->unsignedTinyInteger('type')->default(1);
            $table->unsignedInteger('day')->default(1);
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
        Schema::dropIfExists('automatics');
    }
}
