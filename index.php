<?php 

session_start();
require_once("vendor/autoload.php"); //composer

use \Slim\Slim;    //namespaces dentro do vendor
use \Hcode\Page;  //namespaces dentro do vendor
use \Hcode\PageAdmin;  
use \Hcode\Model\User;

$app = new Slim();   // Slim Framework

$app->config('debug', true);

$app->get('/', function() {   //Rota
    
	$page = new Page();
	
	$page->setTpl("index");

});

$app->get('/admin', function() {   //Rota

	User::verifyLogin();
    
	$page = new PageAdmin();
	
	$page->setTpl("index");

});

$app->get('/admin/login', function() {   //Rota para o login. Desabilitar o Header e o Footer
    
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	
	$page->setTpl("login");

});

$app->post('/admin/login', function() {   //Rota para o login. Desabilitar o Header e o Footer
    
    User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;

});

$app->get('/admin/logout', function() {   //Rota para o login. Desabilitar o Header e o Footer
    
    User::logout();

	header("Location: /admin/login");
	exit;

});



$app->run(); //executa de fato

 ?>