<?php

define("ROOT", __DIR__);
//define("DEFAULT_UPLOAD", $_SERVER['DOCUMENT_ROOT'] . '/Uploads');
define("DEFAULT_UPLOAD", getcwd() . '/../Uploads/');
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);

//DB Constants
define('CREATE', 'CREATE');
define('UPDATE', 'UPDATE');
define('DELETE', 'DELETE');
define('SELECT', 'SELECT');

//Server
define('POST', 'POST');
define('GET', 'GET');
define('REQUEST_METHOD', 'REQUEST_METHOD');
define('HTTP_AUTHORIZATION', 'HTTP_AUTHORIZATION');
define('HTTP_HOST', 'HTTP_HOST');
define('HTTP', 'HTTP');
define('HTTPS', 'HTTPS');
define('HTTPS_OFF', 'off');

//Login Controller name
define('LOGIN', 'LOGIN');
define('TOKEN', 'Token');
define('USER', 'User');

//JWT
define('ISSUER', 'iss');
define('ISSUED_AT', 'iat');
define('NOT_BEFORE', 'nbf');
define('EXPIRE', 'exp');
define('JWT_DATA', 'data');
define('JWT_USER_ID', 'UserId');
define('AUTHORIZATION_ERROR', 'Authorization Error: ');

//Response keys
define('SUCCESS', 'Success');
define('ERROR', 'Error');
define('MESSAGE', 'Message');
define('DATA', 'Data');
define('RECORD_COUNT', 'RecordCount');

//Controller base.
define('SAVE', 'SAVE');
define('LOAD', 'LOAD');
define('GETLIST', 'LIST');

//Filter Params
define('EQUAL', 'EQ');
define('NOT_EQUAL', 'NEQ');
define('GREATER_THEN', 'GT');
define('GREATER_THEN_OR_EQUAL', 'GTE');
define('LESS_THEN', 'LT');
define('LESS_THEN_OR_EQUAL', 'LTE');
define('LIKE', 'LIKE');
define('IN', 'IN');
define('NOTIN', 'NOTIN');

//Filter keys
define('FIELD_NAME', 'fieldname');
define('FILTER_TYPE', 'type');
define('VALUE', 'value');
define('CONDITION', 'condition');

//Filter DB Types
define('INT', 'INT');
define('STRING', 'STRING');
define('DATE', 'DATE');
define('DOUBLE', 'DOUBLE');
define('DECIMAL', 'DECIMAL');

//Database default columns
define('CREATED_ON', 'CreatedOn');
define('CREATED_BY', 'CreatedBy');
define('MODIFIED_ON', 'ModifiedOn');
define('MODIFIED_BY', 'ModifiedBy');

//define('DELETE', 'DELETE'); // Already defined.
define('OTHERACTION', 'OTHERACTION');
define('REQUEST_URI', 'REQUEST_URI');
define('AUTHORIZATION_ENABLED', true);
define('AUTHORIZATION_DISABLED', false);

//Messages
define('INVALID_POST_REQUEST', 'Invalid Post Request');
define('ID_NOT_BLANK_ZERO', 'Id should not be blank/zero');
define('RECORD_SUCCESSFULLY_CREATED', 'Record has been successfully created');
define('RECORD_SUCCESSFULLY_UPDATED', 'Record has been successfully updated');
define('RECORDS_BEEN_SUCCESSFULLY_DELETED', 'Records has been successfully Deleted');
define('SHOULD_NOT_BE_BLANK', ' should not be blank');
define('FILE_UPLOADED', 'File Uploaded');
define('INVALID_USERNAME_PASSWORD', 'Invalid username and password.');
define('RECORDS_NOT_EXISTS', "Opp's Record not exists.");

//BusinessBase
define('ID', 'Id');
