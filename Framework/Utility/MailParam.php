<?php

class MailParam
{
    public $To;
    public $From;
    public $Subject;
    public $Body;
    public $IsHtml;

    public function __construct()
    {
        $this->From = AppConfig::SERVER_EMAIL;
        $this->IsHtml = false;
    }
}
