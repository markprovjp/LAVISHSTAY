<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        $currencyCode = $request->query('currency', 'VND');
        $currency = \App\Models\Currency::find($currencyCode);
        $exchangeRate = $currency ? $currency->exchange_rate : 1.0;

        return [
            'id' => $this->booking_id,
            'option' => new RoomOptionResource($this->option),
            'checkInDate' => $this->check_in_date,
            'checkOutDate' => $this->check_out_date,
            'totalPrice' => [
                'value' => $this->total_price_vnd * $exchangeRate,
                'currency' => $currencyCode,
                'formatted' => $this->formatPrice($this->total_price_vnd * $exchangeRate, $currency),
            ],
            'guestCount' => $this->guest_count,
            'guestName' => $this->guest_name,
            'guestEmail' => $this->guest_email,
            'guestPhone' => $this->guest_phone,
            'status' => $this->status,
            'payments' => $this->payments->map(fn($payment) => [
                'id' => $payment->payment_id,
                'amount' => [
                    'value' => $payment->amount_vnd * $exchangeRate,
                    'currency' => $currencyCode,
                    'formatted' => $this->formatPrice($payment->amount_vnd * $exchangeRate, $currency),
                ],
                'type' => $payment->payment_type,
                'status' => $payment->status
            ])
        ];
    }

    private function formatPrice($price, $currency)
    {
        $amount = $currency->currency_code === 'USD' ? number_format($price, 2, '.', ',') : number_format($price, 0, ',', '.');
        return str_replace('{amount}', $amount, $currency->format);
    }
}
?>