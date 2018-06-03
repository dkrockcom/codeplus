<?php

class AppConfig
{
    //Database Configuration.
    const HOST = "localhost";
    const DBUSER = "root";
    const DBPASSWORD = "123456";
    const DATABASE = "test";
    const PORT = null;
    const SOCKET = null;
    const CHAR_SET = 'utf8';

    //Default File Upload Size
    const DEFAULT_UPLOAD_SIZE = 5; //Size 5 MB

    //Default Date Format.
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    //JWT Configuration.
    const JWT_SEQURITY_KEY = "YOUR_JWT_SEQURIT_KEY";
    const JWT_ENCRYPTION = 'HS512';
    const JWT_LEEWAY = 60;
}
