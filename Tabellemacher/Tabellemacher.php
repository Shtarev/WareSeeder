<?php

namespace Illuminate\Database\Tabellemacher;

class Tabellemacher
{
    public $slovo; // для строки из которой выберется одно слово
    public $content; // весь текст очень длинный
    public $imgArray = array(); // массив куда занесутся сделанные цветные шаблоны для фото, чтоб потом их удалить
    
    public $upLoadDir; // куда загружать фото (определять только через конструктор!!!)
    public $imgBankDir; // откуда брать фото (определять только через конструктор!!!)
    public $fotoName; // случайное фото

    function __construct($upLoadDir, $imgBankDir) 
    {
        $this->upLoadDir = public_path($upLoadDir); // куда загружать фото
        $this->imgBankDir = base_path($imgBankDir); // откуда брать фото
        $this->content = file_get_contents(base_path('vendor/laravel/framework/src/Illuminate/Database/Tabellemacher/file/loremipsum.txt'));
        $this->slovo = file_get_contents(base_path('vendor/laravel/framework/src/Illuminate/Database/Tabellemacher/file/lorem.txt'));
        $this->fotomacher($imgBankDir); // запускаем изготовление фото
    }
    // возвращает одно слово
    public function slovo() {
        $lorem = explode(' ', $this->slovo);
        $key = rand(0, count($lorem)-1);
        return $lorem[$key];
    }
    
    // возвращает короткий текст в аргументе кол-во слов
    public function kurzeText($kurzeLang = 10) {
        $lorem = '';
        for($i=0; $i<$kurzeLang; $i++) {
         if($i<$kurzeLang) {
            $lorem = $lorem.$this->slovo().' ';
         }
         else {
            $lorem = $lorem.$this->slovo();
         }
        }
        return $lorem;
    }
    
    // возвращает один абзатц
    public function paragraph() {
        $loremipsum = $this->content;
        $paragraph = '';
        for($i=0, $j=rand(0,55); $i<$j; $i++) {
            $punktPoz = strpos($loremipsum, '</p>'); // позиция конца первого абзатца
            $paragraph = trim(substr($loremipsum, 0, $punktPoz+4)); // первый абзатц
        }
        return $paragraph;
    }
    
    
    // возвращает длинный текст в аргументе кол-во символов
    public function lorem($lang = 2000)
    {
        if($lang < 1000){$lang = 1000;}
        $loremipsum = $this->content;
        for($i=0, $j=rand(0,55); $i<$j; $i++) {
            $punktPoz = strpos($loremipsum, '</p>'); // позиция конца первого абзатца
            $paragraph = trim(substr($loremipsum, 0, $punktPoz+4)); // первый абзатц
            $loremipsum = substr($loremipsum, $punktPoz+4).$paragraph; // строка начинается со второго абзатца первый абзатц переносится в конец
        }
        // делаем текст
        $text = trim(substr($loremipsum, 0, $lang)); // длинна текста - 3 аргумент
        $pos = strrpos($text, '</p>'); // ищем закрытие последнего абзатца
        return substr($text, 0, $pos+4); // текст сделан
    }
    
    // делает временных 10 фото разных цветов и загружает их в транзитную директорию
	public function fotomacher($imgBankDir){
		$imgDir = $imgBankDir; // сюда загрузятся эти временные фотографии
		for($i=0; $i<10; $i++){
			$img = imagecreatetruecolor(500, 500); // создаем матрицу с размерами 500Х500
			$color = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255)); // создаем рандомный цвет
			imagefill($img, 0, 0, $color); // заливаем фотоматрицу этим цветом
			$fotoName = $imgDir.rand(1, 1000).'.jpg'; // создаем содержащее путь имя для фото 
			imagejpeg($img, $fotoName); // создаем из матрицы фото
			imagedestroy($img); // удаляем матрицу
			$this->imgArray[$i] = $fotoName; // заносим путь с именем фото в массив, чтоб потом это фото удалить
		}
	}
    // удаляет эти сделаные фото из транзитной директории
	public function fotoKiller(){
		foreach($this->imgArray as $value){
			unlink($value);
		}
	}
    
    /*находим случайное фото в транзитной директории*/
    public function bildFinden()
    {
        $scandir = scandir($this->imgBankDir);
        $arrFoto = array();
        foreach($scandir as $key=>$value){
            $tip = mime_content_type($this->imgBankDir.$value);
            if($tip == 'image/jpeg' || $tip == 'image/png' || $tip == 'image/gif' || $tip == 'image/bmp'){
                array_push($arrFoto, $value);
            }
        }
        $foto = array_rand($arrFoto); // случайный ключ
        $this->fotoName =  $arrFoto[$foto]; // случайное фото (имя)
    }
    
    /*Изменение фото*/
    public function bild($shir,$vis)
    {
        /*новое имя для случайного фото*/
        $pos = strrpos($this->fotoName,'.');//позиция точки перед расшиsрением
        $extension = substr($this->fotoName,$pos);//расширение вместе с точкой
        $extension = strtolower($extension);//переводим расширение в нижний регистр
        $RestFilename = substr($this->fotoName, 0, $pos);//вся строка до точки
        $newFotoName = uniqid().$extension;//переименовка файла и пристыковка расширения
    
        /*копируем случайное фото под новым именем в заданную директорию*/
        $newFotoAdresse = $this->upLoadDir.$newFotoName;
        copy($this->imgBankDir.$this->fotoName, $newFotoAdresse);
        
        /*видоизменяем фото*/
        //начало высчитываем процентное соотношение высоты к ширине
        $MusterProzent = 100/$shir*$vis;//далее относительно $MusterProzent будем считать размер
        //конец-высчитываем процентное соотношение высоты к ширине

        list($width, $height,$type) = getimagesize($newFotoAdresse);//получаем ширину и высоту и тип загруженного фото

        /*подгон загруженного фото под нужные размеры*/
        $VergleichProzent = 100/$width*$height;//высчитываем процентное соотношение высоты к ширине загруженного фото

        //если соотношение высоты к ширине у загруженного фото больше или равно относительно нужного, то подгоняем фото по ширине
        if ($MusterProzent <= $VergleichProzent)
        {
        //выщитываем процент разницы между нужным и загруженным изображениями относительно ширины
            $prozent1 = 100/$width*$shir;//нужное фото по ширине составляет $prozent1 процентов от загруженного
            $prozent = 100-$prozent1;//$prozent это разница в процентах между размерами нужного и загружаемого фото, то есть на сколько больше или меньше ширина нужного фото относительно загруженнного (так как в процентах, то подходит для последующего вычисления как ширины так и для высоты, то есть на сколько изменим ширину, на столько изменим и высоту)

            $x = $width/100*$prozent;//задаем числовое значение разнице для ширины

            $widthNEW=$width-$x;//задаем новую ширину (если $x отрицательная, то $width увелмчится на $x. если $x положительная, то $width уменьшится на $x )
        //выщитываем новую высоту
            $y = $height/100*$prozent;//задаем числовое значение разнице для высоты
            $heightNEW = $height-$y;//задаем новую высоту (если $y отрицательная, то $height увелмчится на $y. если $y положительная, то $width уменьшится на $y )

        //в зависимости от типа файла используем нужную функцию и создаём образ изображения из файла для вставки в подложку
        if($type==1){$src=imagecreatefromgif($newFotoAdresse);}
        if($type==2){$src=imagecreatefromjpeg($newFotoAdresse);}
        if($type==3){$src=imagecreatefrompng($newFotoAdresse);}
        if($type==6){$src=imagecreatefromwbmp($newFotoAdresse);}

        //меняем размер загруженого фото
        $dst = imagecreatetruecolor($widthNEW,$heightNEW);//создаем образ подложку пустого изображения с новыми размерами для вставки в него загруженного фото, которое растянется(увеличится) до новых размеров
        imagecopyresized ($dst, $src, 0, 0, 0, 0, $widthNEW, $heightNEW, $width, $height);
        //готово, фото увеличено

        $src = $dst;//передаем $src из только что отработавшей функции imagecopyresized новый образ

        $dst = imagecreatetruecolor($shir,$vis);//создаем образ подложку пустого изображения нужных размеров для вставки в него вырезки из загруженного фото

        //так как в данном случае скорее всего высота у $src будет больше чем у $dst, то вставляем так чтобы по горизонтали центр $src совпал с центром $dst 
        $unterschied = $heightNEW-$vis;//излишек по высоте
        $unterschied = $unterschied/2;//разбиваем излишек на 2 чтобы убрать их поровну сверху и снизу (воспользуемся только для отступа сверху по оси y)
        imagecopy($dst, $src, 0, 0, 0, $unterschied, $widthNEW,$heightNEW);

        //в зависимости от типа файла используем нужную функцию и кидаем сформированное фото в папку.
        if($type==1){imagegif($dst, $newFotoAdresse);}
        if($type==2){imagejpeg($dst, $newFotoAdresse);}
        if($type==3){imagepng($dst, $newFotoAdresse);}
        if($type==6){imagewbmp($dst, $newFotoAdresse);}	

        imagedestroy($dst);//удаляем образ фото
        imagedestroy($src);//удаляем образ фото
        }

        //если соотношение высоты к ширине у загруженного фото меньше чем у нужного, то подгоняем фото по высоте
        if ($MusterProzent > $VergleichProzent)
        {
        //выщитываем процент разницы между нужным и загруженным изображениями относительно высоты
            $prozent1 = 100/$height*$vis;//нужное фото по высоте составляет $prozent1 процентов от загруженного
            $prozent = 100-$prozent1;//$prozent это разница в процентах между размерами нужного и загружаемого фото, то есть на сколько больше или меньше высота нужного фото относительно загруженнного (так как в процентах, то подходит для последующего вычисления как высоты так и для ширины, то есть на сколько изменим высоту, на столько изменим и ширину)

            $x = $height/100*$prozent;//задаем числовое значение разнице для высоты

            $heightNEW = $height-$x;//задаем новую высоту (если $x отрицательная, то $height увелмчится на $x. если $x положительная, то $height уменьшится на $x )

        //выщитываем новую ширину
            $y = $width/100*$prozent;//задаем числовое значение разнице для ширины
            $widthNEW = $width-$y;//задаем новую ширину (если $y отрицательная, то $width увелмчится на $y. если $y положительная, то $width уменьшится на $y )

        //в зависимости от типа файла используем нужную функцию и создаём образ изображения из файла для вставки в подложку
        if($type==1){$src=imagecreatefromgif($newFotoAdresse);}
        if($type==2){$src=imagecreatefromjpeg($newFotoAdresse);}
        if($type==3){$src=imagecreatefrompng($newFotoAdresse);}
        if($type==6){$src=imagecreatefromwbmp($newFotoAdresse);}

        //меняем размер загруженого фото
        $dst = imagecreatetruecolor($widthNEW,$heightNEW);//создаем образ подложку пустого изображения с новыми оазмерами для вставки в него загруженного фото, которое растянется(увеличится) до новых размеров
        imagecopyresized ($dst, $src, 0, 0, 0, 0, $widthNEW, $heightNEW, $width, $height);
        //готово, фото увеличено

        $src = $dst;//передаем $src из только что отваботавшей функции imagecopyresized новый образ

        $dst = imagecreatetruecolor($shir,$vis);//создаем образ подложку пустого изображения нужных размеров для вставки в него вырезки из загруженного фото

        //так как в данном случае скорее всего ширина у $src будет больше чем у $dst, то вставляем так чтобы по вертикали центр $src совпал с центром $dst 
        $unterschied = $widthNEW-$shir;//излишек по ширине
        $unterschied = $unterschied/2;//разбиваем излишек на 2 чтобы убрать их поровну слева и справа (воспользуемся только для отступа слева по оси х)
        imagecopy($dst, $src, 0, 0, $unterschied, 0, $widthNEW,$heightNEW);

        //в зависимости от типа файла используем нужную функцию и кидаем сформированное фото в папку. на всякий случай видоизменяем фото пристыковывая tanin_sad, если на хостинге вместо замены одноименного файла будет создаваться дубликат
        if($type==1){imagegif($dst, $newFotoAdresse);}
        if($type==2){imagejpeg($dst, $newFotoAdresse);}
        if($type==3){imagepng($dst, $newFotoAdresse);}
        if($type==6){imagewbmp($dst, $newFotoAdresse);}	

        imagedestroy($dst);//удаляем образ фото
        imagedestroy($src);//удаляем образ фото
        }
        return $newFotoName;
    }

}
/********************************************************************************
Генерация текста 
1) создать объект(если он не создан) 2) вызвать lorem(дл. большого текста, дл. короткого текста) 3) использовать свойства

Генерация фото 
1) создать объект(если он не создан) 2) вызвать bildFinden() 3) вызваьть bild(ширина, высота)
**********************************************************************************/