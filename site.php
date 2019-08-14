<?php 

use \Hcode\Page;

//Rotas do site principal

$app->get('/', function() {   
    
	$page = new Page();
	
	$page->setTpl("index");

});

?>
