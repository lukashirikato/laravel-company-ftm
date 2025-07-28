<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsappController extends Controller
{
    public function sendWhatsapp()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.whatsapp_from');
        $to = 'whatsapp:+6288219775687'; // Ganti dengan nomor yang sudah join sandbox

        $twilio = new Client($sid, $token);

        $twilio->messages->create($to, [
            'from' => $from,
            'body' => 'Halo! Ini pesan WhatsApp dari Laravel via Twilio ✅'
        ]);

        return "✅ Pesan berhasil dikirim ke $to!";
    }
}
