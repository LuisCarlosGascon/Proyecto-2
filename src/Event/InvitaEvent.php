<?php


namespace App\Event;


use Symfony\Contracts\EventDispatcher\Event;
use App\Service\Telegram;

class InvitaEvent extends Event
{

    private $telegram;
    public const NAME = 'invita.user';

    public function __construct()
    {
        $this->telegram=new Telegram();
    }


}