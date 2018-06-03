<?php

class WebSocketUser
{
    public $socket;
    public $id;
    public $headers = array();
    public $handshake = false;

    public $handlingPartialPacket = false;
    public $partialBuffer = "";

    public $sendingContinuous = false;
    public $partialMessage = "";

    public $hasSentClose = false;

    /**
     * __construct constructor
     * @param int $id - user id
     * @param object $socket - socket
     */
    public function __construct($id, $socket)
    {
        $this->id = $id;
        $this->socket = $socket;
    }
}
