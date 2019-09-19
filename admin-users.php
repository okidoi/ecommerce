<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;


//Rotas dos usuários da administração do site


$app->get("/admin/users", function(){

	User::verifyLogin();

	$search = (isset($_GET['search'])) ? $_GET['search'] : "" ;
	$page = (isset($_GET['page']))? (int)$_GET['page'] : 1;  // Se veio como parametro a pagina então a utiliza. Caso contrario é a primeira página.

	$qtdRegistrosPorPagina = 2;

	if($search != ''){

		$pagination = User::getPageSearch($search, $page, $qtdRegistrosPorPagina);
	
	}else{
		
		$pagination = User::getPage($page, $qtdRegistrosPorPagina);

	}


	$pages = [];

	for($x = 0; $x < $pagination['pages']; $x++)
	{
		array_push($pages, [
			'href'=>'/admin/users?'. http_build_query([

				'page'=>$x+1,
				'search'=>$search
			]),
			'text'=>$x+1

		]);
	}

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$pagination['data'], 
		"search"=>$search,
		"pages"=>$pages
	));

});

$app->get("/admin/users/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

//Devido ao caminho /delete se ele entrasse antes da rota seguinte ele não entraria aqui
$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;


});

$app->get("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

$app->post("/admin/users/create", function(){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;

});

//UPDATE
$app->post("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;
});


?>