<?php 

use \Hcode\Page;
use \Hcode\Model\Product;

//Rotas do site principal

$app->get('/', function() {   

	$products = Product::listAll();
    
	$page = new Page();


	$page->setTpl("index", [
		'products'=>Product::checkList($products)
	]);

});

?>
