<?php

use Hcode\Page;

$app->get('/', function() {//rota principal
    
	$page = new Page();

	$page->setTpl("index");

});


?>