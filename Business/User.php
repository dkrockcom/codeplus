<?php

class User extends BusinessBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public $Username;
    public $Password;
    public $Name;
    public $DOB;
    public $CreatedOn;
    public $CreatedBy;
    public $ModifiedOn;
    public $ModifiedBy;

}
