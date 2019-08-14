<?php 


use \Hcode\PageAdmin;
use \Hcode\Model\User;


//Rotas da administração do site

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



$app->get("/admin/forgot", function(){

    $page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	
	$page->setTpl("forgot");
});


$app->post("/admin/forgot", function(){

	
	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");
	exit;
});

$app->get("/admin/forgot/sent", function(){

    $page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	
	$page->setTpl("forgot-sent");

});

$app->get("/admin/forgot/reset", function(){

	$user = User::validForgotDecrypt($_GET["code"]);

    $page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	
	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));
});



$app->post("/admin/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password  = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	
	$page->setTpl("forgot-reset-success");

});



?>
