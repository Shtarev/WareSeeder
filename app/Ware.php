<?php
/*у таблицы три связи:
на ее id ссылается внешний ключ ware_id из waresfotos (многие фото к одному товару)
у нее есть первичный ключ firma_id который ссылается на id таблицы firmas (одна фирма ко многим товарам)
связь с категориями как многие со многими через таблицу untermenu_ware
В массиве $searchable прописан поиск по полям таблицы 
*/
namespace App;

use Illuminate\Database\Eloquent\Model;

class Ware extends Model
{
    protected $fillable = [
        'artikel',
        'firma_id',
        'country',
        'title',
        'descriptionM',
        'keywordsM',
        'sugnatur',
        'beschreibung',
        'kaufpreis',
        'verkaufpreis',
        'anzahl',
        'checkin',
        'auswahlliste'
    ];
    
    /*связь с fotos*/
    public function foto(){
        return $this->hasMany('App\Foto');
    }
    /*связь с меню как многие со многими через таблицу menu_ware*/
    public function menu(){
        return $this->belongsToMany('App\Menu');
    }
}
