<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('projects')) {
            return;
        }

        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('名称');
            $table->string('code')->default('')->comment('代码');
            $table->string('url')->nullable()->comment('接口地址');
            $table->unsignedInteger('currency_id')->comment('货币');
            $table->unsignedInteger('catalog_id')->comment('投资分类');
            $table->unsignedInteger('account_id')->comment('账号');
            $table->decimal('持有金额')->default('0')->comment('持有金额');
            $table->decimal('持有份额')->default('0')->comment('持有份额');
            $table->decimal('持仓成本价')->comment('持仓成本价');
            $table->decimal('最新净值')->default('1')->comment('最新净值');
            $table->decimal('持有收益')->default('0')->comment('持有收益');
            $table->decimal('持有收益率')->default('0')->comment('持有收益率');
            $table->decimal('累计收益')->default('0')->comment('累计收益');
            $table->decimal('累计收益率')->default('0')->comment('累计收益率');
            $table->decimal('买入费率')->default('0')->comment('买入费率');
            $table->decimal('卖出费率')->default('0')->comment('卖出费率');
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
        Schema::dropIfExists('projects');
    }
}
