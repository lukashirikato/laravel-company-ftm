<?php

namespace App\Helpers;

class WhatsAppHelper
{
    /**
     * Kirim pesan WhatsApp dengan membuka wa.me
     *
     * @param string $phone  Nomor HP tujuan (format lokal, misalnya 0812xxxx)
     * @param string $message Isi pesan yang ingin dikirim
     * @return void
     */
    public static function send($phone, $message)
    {
        // Ubah 0 di depan jadi 62 (kode negara Indonesia)
        $phone = preg_replace('/^0/', '62', $phone);
        $text = urlencode($message);

        $url = "https://wa.me/{$phone}?text={$text}";

        header("Location: $url");
        exit;
    }
}
