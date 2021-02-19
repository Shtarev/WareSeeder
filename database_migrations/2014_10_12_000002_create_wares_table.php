<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wares', function (Blueprint $table) {
            $table->increments('id');
            $table->string('artikel', 255); // артикул 
            $table->string('country', 255); // страна
            $table->string('title', 255); // наименование оно же в head
            $table->string('descriptionM', 255); // meta_description
            $table->string('keywordsM', 255); // meta_keywords
            $table->string('sugnatur', 1020); // краткое описание
            $table->text('beschreibung'); // полное описание
            $table->integer('kaufpreis')->unsigned()->default(0); // за сколько куплено
            $table->integer('verkaufpreis')->unsigned()->default(0); // за сколько продаётся
            $table->integer('anzahl')->unsigned()->default(0); // количество
            $table->integer('einschalten')->unsigned()->default(1); // показывать или нет товар (1 - показывать)
            $table->integer('auswahlliste')->unsigned()->default(0); // показывать ли на главной в избранных-auswahlliste (1-показывать)
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
        Schema::dropIfExists('wares');
    }
}
