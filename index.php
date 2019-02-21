<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;

$app = new Slim();

$app->config("debug", true);//mostra detalhes do erro

require_once("functions.php");
require_once("site.php");
require_once("adm.php");
require_once("users.php");
require_once("categories.php");
require_once("products.php");










$app->run();//executa a classe

 ?>