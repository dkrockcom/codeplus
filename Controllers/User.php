<?php

require_once '../AppLoad.php';

class UserController extends ControllerBase
{
    public function __construct()
    {
        parent::__construct(new User(), AUTHORIZATION_DISABLED);
    }

    public function execute()
    {
        echo "executed";
    }
}
Common::executeController("UserController");
