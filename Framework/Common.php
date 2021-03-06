<?php

class Common
{
    public static $SAVE = "SAVE";
    public static $LOAD = "LOAD";
    public static $DELETE = "DELETE";
    public static $LIST = "LIST";
    public static $OTHERACTION = "OTHERACTION";

    /**
     * executeController method - Get initialized class with name whcih is uses for start a rest controller
     * @param string $controllerName - controller class name
     *
     * @return ControllerClassInstance
     */
    public static function executeController($controllerName)
    {
        $reflection = new ReflectionClass($controllerName);
        return $reflection->newInstance();
    }

    /**
     * serializeObject method - Generate rest conroller json output for client side uses
     * @param object $data - data can be a array/object
     */
    public static function serializeObject($data)
    {
        header('Content-type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * error method - Generate rest conroller json error output for client side uses
     * @param string $message - messgage can be string.
     */
    public static function error($message)
    {
        $response = array(SUCCESS => false, ERROR => $message);
        self::serializeObject($response);
    }

    /**
     * getControllerName method - Use for get current controller name
     * @return string controller name
     */
    public static function getControllerName()
    {
        $uri = explode("/", $_SERVER[REQUEST_URI]);
        $length = sizeof($uri);
        $uri = $uri[$length - 1];
        $name = explode(".", $uri);
        return strtoupper($name[0]);
    }

    /**
     * toValidJson method - validate the json for php which get from user input by the http post/get request
     * @param string $string - Json string
     * @param boolean $assoc - Json association
     * @param boolean $fixNames - FixNames
     *
     * @return string json string
     */
    public static function toValidJson($string, $assoc = true, $fixNames = true)
    {
        if (strpos($string, '(') === 0) {
            $string = substr($string, 1, strlen($string) - 2); // remove outer ( and )
        }
        if ($fixNames) {
            $string = preg_replace("/(?<!\"|'|\w)([a-zA-Z0-9_]+?)(?!\"|'|\w)\s?:/", "\"$1\":", $string);
        }
        return json_decode($string, $assoc);
    }

    /**
     * sendMail method - Send email from server with specific params
     * @param string $to - Receiver Email Address
     * @param string $from - Send Email Address
     * @param string $subject - Subject of the email
     * @param string $body - Content for the mail.
     * @param string $isHTML - Check for mail sent in html and simple.
     *
     * @return bool status of mail sent.
     */
    public static function sendMail($mailParam)
    {
        try {
            //TODO: More Condition Pending to implement.
            mail($mailParam->To, $mailParam->Subject, $mailParam->Body);
            return array(SUCCESS => true, MESSAGE => 'Mail Sent Successfully.');
        } catch (Exception $ex) {
            return array(SUCCESS => false, ERROR => $ex->getMessage());
        }
    }

    /**
     * passwordHash method - Generate password hash
     * @param string $password - Password string
     *
     * @return string hash password
     */
    public static function passwordHash($password)
    {
        return sha1($password);
    }

    /**
     * fileUpload method - Use for upload a file
     * @param File $files - User file which are need to upload
     * @param boolean $isDeleteOld - Check for if same file exist the delete the old file
     * @param int $size - Maxmimum file size upload limit
     * @param string $uploaddir - Upload directory
     */
    public static function fileUpload($files, $isDeleteOld, $size = AppConfig::DEFAULT_UPLOAD_SIZE, $uploaddir = DEFAULT_UPLOAD)
    {
        try {
            // Check if the form was submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $files = $files["file"];
                // Check if file was uploaded without errors
                if (isset($files) && $files["error"] == 0) {
                    $filename = $files["name"];
                    $filetype = $files["type"];
                    $filesize = $files["size"];

                    // Verify file extension
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    if (!array_key_exists($extension, AppConfig::FILE_SUPPORT)) {
                        Common::serializeObject(array(SUCCESS => false, ERROR => "Error: Invalid file format."));
                        return;
                    }

                    // Verify file size - 5MB maximum
                    $maxsize = $size * 1024 * 1024;
                    if ($filesize > $maxsize) {
                        Common::serializeObject(array(SUCCESS => false, ERROR => "Error: File size is larger than the allowed limit."));
                        return;
                    }

                    // Verify MYME type of the file
                    if (in_array($filetype, AppConfig::FILE_SUPPORT)) {
                        // Check whether file exists before uploading it
                        if (file_exists($uploaddir . $files["name"])) {
                            Common::serializeObject(array(SUCCESS => false, ERROR => $files["name"] . " is already exists."));
                        } else {
                            move_uploaded_file($files["tmp_name"], $uploaddir . $files["name"]);
                            Common::serializeObject(array(SUCCESS => true, MESSAGE => "Your file has been successfully uploaded."));
                        }
                    } else {
                        Common::serializeObject(array(SUCCESS => false, ERROR => "Error: There was a problem uploading your file. Please try again."));
                    }
                } else if ($files["error"] == 1) {
                    Common::serializeObject(array(SUCCESS => false, ERROR => "Error: File size is larger than the allowed limit."));
                }
            } else {
                Common::serializeObject(array(SUCCESS => false, ERROR => "Error: " . $files["error"]));
            }
        } catch (Exception $ex) {
            Common::serializeObject(array(SUCCESS => false, MESSAGE => $ex->getMessage()));
        }
    }
}
