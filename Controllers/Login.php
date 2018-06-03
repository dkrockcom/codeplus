<?php

require_once '../AppLoad.php';

class LoginController extends ControllerBase
{
    public function __construct()
    {
        parent::__construct(null, AUTHORIZATION_DISABLED);
    }

    public function execute()
    {
        try {
            $username = $this->params("Username", true);
            $password = $this->params("Password", true);
            $password = Common::passwordHash($password);
            $tokenValue = null;
            $db = new Database();
            $db->where("Username", $username);
            $db->where("Password", $password);
            $row = $db->getOne('User');

            if ($row) {
                $tokenValue = Identity::generateToken($row["UserId"], 60 * 24);
                Common::serializeObject(array(SUCCESS => true, TOKEN => $tokenValue, USER => array($row)));
            } else {
                Common::serializeObject(array(SUCCESS => false, ERROR => INVALID_USERNAME_PASSWORD));
            }
        } catch (Exception $ex) {
            Common::serializeObject($ex->getMessage());
        }
    }
}
Common::executeController("LoginController");
