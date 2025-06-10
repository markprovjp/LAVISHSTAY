<?php
       namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->query('language', 'vi');
        $currencyCode = $lang === 'vi' ? 'VND' : 'USD'; // Map ngôn ngữ với tiền tệ
        $currency = Currency::find($currencyCode);
        $exchangeRate = $currency ? $currency->exchange_rate : 1.0;

        // Quy đổi giá
        $price = $this->base_price_vnd * $exchangeRate;
        $formattedPrice = $this->formatPrice($price, $currency);

        return [
            'id' => $this->room_id,
            'name' => $this->getTranslatedAttribute('name', $lang),
            'image' => $this->image,
            'price' => [
                'value' => $price,
                'currency' => $currencyCode,
                'formatted' => $formattedPrice,
            ],
            'size' => $this->size,
            'view' => $this->getTranslatedAttribute('view', $lang),
            'bedType' => [
                'default' => $this->bed_type_default,
                'options' => $this->bedOptions->pluck('bed_option_name')->toArray(),
            ],
            'amenities' => $this->services->where('is_main', false)->pluck('service.name')->toArray(),
            'mainAmenities' => $this->services->where('is_main', true)->pluck('service.name')->toArray(),
            'rating' => $this->rating,
            'urgencyRoomMessage' => $this->getTranslatedAttribute('urgency_message', $lang),
            'lavishPlusDiscount' => $this->lavish_plus_discount,
            'maxGuests' => $this->max_guests,
            'totalRooms' => $this->total_rooms,
            'description' => $this->getTranslatedAttribute('description', $lang),
            'images' => $this->images->pluck('image_url')->toArray(),
            'options' => RoomOptionResource::collection($this->options),
        ];
    }

    private function formatPrice($price, $currency)
    {
        if (!$currency) {
            return number_format($price, 0, ',', '.') . ' ₫';
        }
        $amount = $currency->currency_code === 'USD' ? number_format($price, 2, '.', ',') : number_format($price, 0, ',', '.');
        return str_replace('{amount}', $amount, $currency->format);
    }
}
?>