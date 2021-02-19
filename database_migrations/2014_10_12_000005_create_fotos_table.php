<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fotos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 255)->nullable(); // фото оригинального размера
            $table->string('imageK', 255)->nullable(); // фото маленького размера
            $table->string('imageM', 255)->nullable(); // фото среднего размера
            $table->string('imageG', 255)->nullable(); // фото большого
            $table->integer('ware_id')->unsigned()->default(0); // связь с id-товара
            $table->foreign('ware_id')->references('id')->on('wares'); // внешний ключ на id товара которому принадлежить фото
            
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
        Schema::dropIfExists('fotos');
    }
}
