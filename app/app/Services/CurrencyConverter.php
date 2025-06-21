<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyConverter
{
    public static function convertUsdToTsh($amount)
    {
        try {
            $response = Http::get('https://open.er-api.com/v6/latest/USD');

            if ($response->ok()) {
                $rate = $response->json()['rates']['TZS'] ?? 2500;
                return $amount * $rate;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $amount * 2500;
    }
}