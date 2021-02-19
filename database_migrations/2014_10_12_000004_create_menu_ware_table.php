<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuWareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_ware', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('ware_id')->unsigned()->default(1); // связь с id-товара
            $table->foreign('ware_id')->references('id')->on('wares'); // внешний ключ на id товара
            
            $table->integer('menu_id')->unsigned()->default(1); // связь с id-меню
            $table->foreign('menu_id')->references('id')->on('menus'); // внешний ключ на id меню
            
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
        Schema::dropIfExists('menu_ware');
    }
}
