<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomPriceController extends Controller
{


    //Lễ hội, sự kiện////////////////////
    public function event_festival()
    {
        return view('admin.room_prices.event_festival.index');
    }








    ///Giá động////////////////////
    public function dynamic_price()
    {
        return view('admin.room_prices.dynamic_price.index');
    }











    //Giá cuối tuần////////////////////
    public function weekend_price()
    {
        return view('admin.room_prices.weekend_price.index');
    }
}
