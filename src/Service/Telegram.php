<?php

namespace App\Service;


Class Telegram{

    function enviar($id){
        $token = "6206676355:AAGfvcMtx2UMrqbKNy_KqxfjzltaCPAqboM";

        $data = [
        'text' => 'Has realizado una reserva',
        'chat_id' => $id,
        ];
        return $a=file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?".http_build_query($data));
    }
}
