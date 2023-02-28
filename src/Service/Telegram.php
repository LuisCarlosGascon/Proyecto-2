<?php

namespace App\Service;


Class Telegram{

    function enviar(){
        $token = "6206676355:AAGfvcMtx2UMrqbKNy_KqxfjzltaCPAqboM";

        $data = [
        'text' => 'Hola que tal',
        'chat_id' => '965284773',
        ];
        return $a=file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?".http_build_query($data));
    }
    


}
