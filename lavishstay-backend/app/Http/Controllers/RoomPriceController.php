<?php
    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\RoomPricing;
    use App\Models\Room;
    use App\Models\Event;
    use App\Models\Holiday;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;

    class RoomPriceController extends Controller
    {

        public function event_festival ()
        {
            return view('admin.room_prices.event_festival');
        }
        public function weekend_price ()
        {
            return view('admin.room_prices.weekend_price');
        }
        public function dynamic_price ()
        {
            return view('admin.room_prices.dynamic_price');
        }

        
    }

