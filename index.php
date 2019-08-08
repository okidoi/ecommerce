<?php 

require_once("vendor/autoload.php"); //composer

use \Slim\Slim;    //namespaces dentro do vendor
use \Hcode\Page;  //namespaces dentro do vendor

$app = new Slim();   // Slim Framework

$app->config('debug', true);

$app->get('/', function() {   //Rota
    
	$page = new Page();
	
	$page->setTpl("index");

});

$app->run(); //executa de fato

 ?>