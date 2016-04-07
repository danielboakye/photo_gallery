<?php 	

require($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/Session.php");
require_once( ROOT_PATH . "incs/_functions.php");

$session->logout();
redirect_to("login.php");