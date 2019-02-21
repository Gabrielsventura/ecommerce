<?php

use Hcode\Page;
use Hcode\Model\Products;


$app->get('/', function() {//rota principal

	$products = Products::listAll();
    
	$page = new Page();

	$page->setTpl("index", [
      'products'=>Products::checkList($products)

	]);

});


?>