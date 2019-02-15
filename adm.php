<?php 

use Hcode\PageAdmin;
use Hcode\Model\User;

$app->get("/admin", function() {//rota principal

	User::verifyLogin();
    
	$page = new PageAdmin();

	$page->setTpl("index");
});

$app->get("/admin/login", function() {//rota principal
    
	$page = new PageAdmin([
       "header"=>false, //no login não tem o header nem o footer
       "footer"=>false

	]);

	$page->setTpl("login");
});

$app->post('/admin/login', function() {//rota principal
    
	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");

	exit;
});

$app->get("/admin/logout", function(){

	User::logout();
	header("Location: /admin/login");
	exit;
});

$app->get("/admin/forgot", function(){

	$page = new PageAdmin([
       "header"=>false, //no login não tem o header nem o footer
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
       "header"=>false, //no login não tem o header nem o footer
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



?>