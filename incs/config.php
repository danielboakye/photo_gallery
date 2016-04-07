<?php 

defined('DB_HOST') ? null : define("DB_HOST", "www.arkinc.com");
defined('DB_NAME') ? null : define("DB_NAME", "photo_gallery");
defined('DB_USER') ? null : define("DB_USER", "daniel");
defined('DB_PASS') ? null : define("DB_PASS", "joe");

define('DS', DIRECTORY_SEPARATOR); 

//@constant BASE_URL for links
// defined('BASE_URL') ? null : define("BASE_URL", DS . "photo_gallery" . DS . "public" . DS);
defined('BASE_URL') ? null : define("BASE_URL", "/photo_gallery/public/");

//@constant ROOT_PATH for includes
// defined('ROOT_PATH') ? null : define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT'] . DS . "photo_gallery" . DS);
defined('ROOT_PATH') ? null : define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/");

$log_file = ROOT_PATH . "logs" . DS . "log.txt";
