<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Fonnte
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function sendWa($noWa, $message)
    {
        $token = "EUKaf54rJmQ2BHsRkMcd";
        Http::withHeader("Authorization", $token)->post("https://api.fonnte.com/send", [
            'target' => $noWa,
            'message' => $message,
            'countryCode' => '62', //optional
        ]);
    }
}
