# WareSeeder
Три связанных ключами таблицы - Меню, Товары, Фото товаров

Создает таблицу с товарами, таблицу с фото и сами фото, таблицу с меню,

Здесь обычный набор полей, который использую. 

Если к таблице с товарами надо добавить какое-нибудь доп. поле, то промиши его в

миграции CreateWaresTable, затем внеси в модель Ware в $fillable и в WareSeeder в

код заполнения таблицы

**************************************

__________________________________

В папке public создать папку images

__________________________________

Папку Tabellemacher со всем содержимым сохранить по пути:

yousite.loc\vendor\laravel\framework\src\Illuminate\Database

__________________________________

Создать посев именно через cmd: 

php artisan make:seeder WareSeeder

он появится в database\seeds\WareSeeder.php

и полностью копируем в него код из папки с исходнтками: database_seeds\WareSeeder.php

если надо, то редактруем какие-нибудь данные, например размер фото или количество записей

_________________________

В фремворке: database\seeds\DatabaseSeeder.php заполняем run():

public function run()
    {
         $this->call('WareSeeder');
    }
    
__________________________________

Переносим из папки с исходнтками в фремворк остальные файлы

__________________________________
Запускаем миграции через cmd: 

php artisan migrate

__________________________________

Запускаем посев через cmd: 

php artisan db:seed

Готово____________________________

Дополнительно:

в папке дополнительно MenuController пример как вытаскивать созданное меню
