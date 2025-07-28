<?php

namespace App\Helpers;

use Twilio\Rest\Client;

class TwilioWhatsAppHelper
{
    public static function send($to, $message)
    {
        $sid    = config('services.twilio.sid');
        $token  = config('services.twilio.token');
        $from   = config('services.twilio.whatsapp_from');

        $client = new Client($sid, $token);

        return $client->messages->create(
            "whatsapp:$to",
            [
                'from' => "whatsapp:$from",
                'body' => $message
            ]
        );
    }
}
