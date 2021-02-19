<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Tabellemacher\Tabellemacher; // Подключаем класс
use App\Ware; // Подключаем Модель
class WareSeeder extends Seeder
{

	public $table_0 = 'menus'; // Название таблицы с меню
    public $table_1 = 'wares'; // Название таблицы с товаром
	public $table_2 = 'fotos'; // Название таблицы где фото товара 
    public $table_3 = 'menu_ware'; // Связь фото с товаром
    public $wievielMenu = 4; // Сколько пунктов меню
    public $menu_sub = 7; // До скольки пунктов подменю в меню
    public $menu_id = array(); // Массив с айди подменю исключая меню
	public $wieviel = 40; // Сколько полей с товаром делать
	public $wievielFoto = 3; // Сколько фото будет у одного товара
	public $upLoadDir = 'images/'; // Директория с фотографиями товара
	
	// транзитная директория для фото, ее путь оставить как есть в директории с классом 
	public $imgBankDir = 'vendor/laravel/framework/src/Illuminate/Database/Tabellemacher/fotoBank/';
	 
    public function run()
    {
        $stackText = array(); // Для таблицы с товарами
        $stackFoto = array(); // Для таблицы с фотографиями
        $stackMenu = array(); // Для таблицы с меню
		// Создаем объект и передаем в него пути для папок с фото, там срабатывает конструктор и вызывает метод создающий 10 временных цветных фото которые грузятся в транзитную директорию
        $Tabellemacher = new Tabellemacher($this->upLoadDir, $this->imgBankDir);
        $result = Ware::get('id')->last(); // Проверяем есть ли в таблице с товаром записи
        if($result == null) { // Если записей нет, то на всякий случай обнуляем автоинкремент, чтоб записи начинались с id=1
            DB::statement('ALTER TABLE '.$this->table_0.' AUTO_INCREMENT=0');
            DB::statement('ALTER TABLE '.$this->table_1.' AUTO_INCREMENT=0');
            DB::statement('ALTER TABLE '.$this->table_2.' AUTO_INCREMENT=0');
            $ware_id = 1; // Для поля с внешним ключом в таблище фотографий
        }
        else{ // Если в таблице с товаром есть записи
            $ware_id = ++$result->id; // Следующий id товара для поля с внешним ключом в таблище фотографий
        }
        // Создаем Меню
        for($j = 0; $j < $this->wievielMenu; $j++) {
            array_push($stackMenu, array(
                'title' => ucfirst($Tabellemacher -> slovo()), // Создаем рыбу текста,
                'menu_id' => 0,
                'checken' => 1
            ));
        }
        // Подменю
        for($i=0, $vsego_podmenu = $this->wievielMenu*$this->menu_sub; $i<$vsego_podmenu; $i++) {
            array_push($stackMenu, array(
                'title' => ucfirst($Tabellemacher -> slovo()), // Создаем рыбу текста,
                'menu_id' => rand(1, $this -> wievielMenu),
                'checken' => 1
            ));
        }
        // Готовим переменные для связи товара с меню? чтоб потом не вызывать в цикле
        $erste_sub = $this->wievielMenu+1; // первое id подменю
        $letzte_sub = count($stackMenu); // последнее id подменю
        
        DB::table($this->table_0)->insert($stackMenu); // вставляем созданные данные в таблицу Меню
        
		// Создаем данные для таблицы товаров
        for($i = 0; $i < $this->wieviel; $i++) {
            // Наполняем массив с полямми и их значениями таблиы товаров
			array_push($stackText, array(
                'artikel' => rand(100, 1000),
                'country' => '',
                'title' => ucfirst($Tabellemacher -> slovo()),
                'descriptionM' => $Tabellemacher -> kurzeText($kurzeLang = 10),
                'keywordsM' => $Tabellemacher -> kurzeText($kurzeLang = 4),
                'sugnatur' => $Tabellemacher -> paragraph(),
                'beschreibung' => $Tabellemacher -> lorem(1000),
                'kaufpreis' => rand(10, 100),
                'verkaufpreis' => rand(101, 200),
                'anzahl' => rand(1, 10),
                'einschalten' => 1,
                'auswahlliste' => 0
            ));
            // Создаем данные для таблицы с фото
			for($j = 0; $j < $this->wievielFoto; $j++) {
				$Tabellemacher -> bildFinden(); // Вытаскиваем случайное фото из транзитной директории
				// прописываем полям таблицы с фото названия фото одновременно делая их методом bild('ширина', 'высота')
				array_push($stackFoto, array(
					'image' => $Tabellemacher -> bild(600, 600),
					'imageG' => $Tabellemacher -> bild(400, 400),
					'imageM' => $Tabellemacher -> bild(250, 250),
					'imageK' => $Tabellemacher -> bild(100, 100),
					'ware_id' => $ware_id
				));
			}
            DB::table($this->table_1)->insert($stackText); // вставляем созданные данные в таблицу товаров
            DB::table($this->table_2)->insert($stackFoto); // вставляем созданные данные в таблицу с фото
            // очищаем массивы, чтоб небыло повторов
            array_splice($stackText, 0);
            array_splice($stackFoto, 0);
            // вставляем связь меню - товар
            
            DB::table($this->table_3)->insert([
                'ware_id' => $ware_id,
                'menu_id' => rand($erste_sub, $letzte_sub)
            ]); 
            
            $ware_id++; // внешний ключ для следующей записи
        }

		$Tabellemacher->fotoKiller(); // удаляем временные фото из транзитной директории
    }
}
