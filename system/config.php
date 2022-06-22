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

const SECURITY_CONFIG = [
    /*"not_allowed_methods" => [
        "get"
    ]*/
];

require_once "loader.php";