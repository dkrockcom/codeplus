<?php

use \Firebase\JWT\JWT;

class Identity
{
    /**
     * userIdentity method returns a logged in user information.
     * @return array
     */
    public static function userIdentity()
    {
        $token = htmlentities((string) $_SERVER[HTTP_AUTHORIZATION]);
        try {
            JWT::$leeway = AppConfig::JWT_LEEWAY;
            $decoded = JWT::decode($token, AppConfig::JWT_SEQURITY_KEY, array(AppConfig::JWT_ENCRYPTION));
            return $decoded->data;
        } catch (Exception $ex) {
            Common::serializeObject($ex->getMessage());
            exit();
        }
    }

    /**
     * generateToken method returns JWT Token
     * @param int $userId interval in the formats:
     * @param int $time - Token expire time
     * @param string $time - Jwt security token
     *
     * @return JWT Token
     */
    public static function generateToken($userId, $time = null, $key = null)
    {
        $issuedAt = time();
        $notBefore = $issuedAt + 10;
        $expire = ($notBefore + 10) + (is_null($time) ? 1 : $time * 60);
        $serverName = (isset($_SERVER[HTTPS]) && $_SERVER[HTTPS] != HTTPS_OFF ? 'https://' : 'http://') . $_SERVER[HTTP_HOST];
        $token = array(
            ISSUER => $serverName,
            ISSUED_AT => $issuedAt,
            NOT_BEFORE => $notBefore,
            EXPIRE => $expire,
            JWT_DATA => array(JWT_USER_ID => $userId),
        );
        $jwt = JWT::encode($token, AppConfig::JWT_SEQURITY_KEY, AppConfig::JWT_ENCRYPTION);
        return $jwt;
    }

    /**
     * Method returns true/false for authorization
     * @return boolean
     */
    public static function authorization()
    {
        try {
            $token = htmlentities((string) $_SERVER[HTTP_AUTHORIZATION]);
            JWT::$leeway = AppConfig::JWT_LEEWAY;
            $decoded = JWT::decode($token, AppConfig::JWT_SEQURITY_KEY, array(AppConfig::JWT_ENCRYPTION));
            return true;
        } catch (Exception $ex) {
            Common::serializeObject(array(SUCCESS => false, ERROR => AUTHORIZATION_ERROR . $ex->getMessage()));
            return false;
        }
    }
}
