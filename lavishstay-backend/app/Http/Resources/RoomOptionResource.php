<?php
       namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomOptionResource extends JsonResource
{
    public function toArray($request)
    {
        $currencyCode = $request->query('currency', 'VND');
        $currency = Currency::find($currencyCode);
        $exchangeRate = $currency ? $currency->exchange_rate : 1.0;

        return [
            'id' => $this->option_id,
            'pricePerNight' => [
                'value' => $this->price_per_night_vnd * $exchangeRate,
                'currency' => $currencyCode,
                'formatted' => $this->formatPrice($this->price_per_night_vnd * $exchangeRate, $currency),
            ],
            // ...
        ];
    }

    private function formatPrice($price, $currency)
    {
        $amount = $currency->currency_code === 'USD' ? number_format($price, 2, '.', ',') : number_format($price, 0, ',', '.');
        return str_replace('{amount}', $amount, $currency->format);
    }
}
?>