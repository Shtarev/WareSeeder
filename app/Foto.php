<?php
/*в таблице есть Внешний ключ связанный с таблицей wares*/
namespace App;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $fillable = [
        'image',
        'imageK',
        'imageM',
        'imageG',
        'ware_id'
    ];
    
    /*связь с Ware*/
    public function ware(){
        return $this->belongsTo('App\Ware');
    }
}
