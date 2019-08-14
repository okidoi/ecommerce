<?php 

session_start();
require_once("vendor/autoload.php"); //composer

use \Slim\Slim;    //namespaces dentro do vendor


$app = new Slim();   // Slim Framework

$app->config('debug', true);


require_once("site.php");
require_once("admin.php");
require_once("admin-users.php");
require_once("admin-categories.php");
require_once("admin-products.php");


$app->run(); //executa de fato

 ?>