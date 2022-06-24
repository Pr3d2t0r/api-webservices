<?php
define("APPLICATIONPATH", dirname(__FILE__));
define("DEFAULT_TYPE", "json");
define('DB_HOSTNAME', "localhost");
define('DB_NAME', "desafio");
define('DB_USERNAME', "root");
define('DB_PASSWORD', "");
define('DB_CHARSET', "utf8");
define('XML_ROOT_ELEMENT', "root");

// if it is being used put the path to autoload
define('COMPOSER_AUTOLOAD', "./vendor/autoload.php");

/**
 * Options:
 *  development (Default)
 *  production
 */
define('ENVIRONMENT', "production");
/**
 * Options:
 *  Request Method (Best to use one or the other):
 *      not_allowed_methods -> array of the request methods blocked
 *          Example:
 *           "not_allowed_methods" => [
*                 "get"
 *           ]
 *      allowed_methods -> array of the request methods allowed (Has priority over the not allowed config)
 *  Ip access control:
 *      allowed_clients_ip -> array of allowed ips, if array exists access is only allowed to the specified ip's,
 *                              by default all ip's are allowed
 */
const SECURITY_CONFIG = [
    "allowed_methods" => [
        "get",
        "post",
        "put",
        "delete"
    ],
    "allowed_clients_ip" => [
        "::1",
        "192.168.20.144"
    ]
];

require_once "loader.php";