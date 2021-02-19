<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu =  Menu::all();
        $menu = $menu->toArray(); // обязательно переводим в массив
        $nav = array(); // здесь будет меню
        $cat = array();

        foreach($menu as $key=>$value) {
            if(!$value['menu_id']){
                $nav[$value['id']]['title'] = $value['title'];
                $nav[$value['id']]['sub'] = array();
                unset($menu[$key]);
            }
            elseif($key != key($cat)) {
                $cat[$key] = $menu[$key];
                $cat[$key]['sub'] = array();
            }
        }

        foreach($nav as $keyN=>$valueN) {
            foreach($cat as $keyC=>$valueC) {
                if($keyN == $valueC['menu_id']) {
                    foreach($menu as $keyM=>$valueM) {
                        if($valueC['id'] == $valueM['menu_id']) {
                            $cat[$keyC]['sub'][$keyM] = $menu[$keyM];
                            $nav[$keyN]['sub'][$keyC] = $cat[$keyC];
                        }
                        else {
                            $nav[$keyN]['sub'][$keyC] = $cat[$keyC];
                        }
                    }
                }
            }
        }
        return $nav;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
