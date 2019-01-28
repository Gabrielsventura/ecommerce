<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config("debug", true);//mostra detalhes do erro

$app->get('/', function() {//rota principal
    
	$page = new Page();

	$page->setTpl("index");
});

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

$app->get("/admin/users", function(){

	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
        "users"=>$users
	));
});

//--------CREATE-----------

$app->get("/admin/users/create", function(){ //CREATE

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");
});

$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

//-----------UPDATE----------------

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
    exit();

});

$app->post("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
    exit();

});




$app->get("/admin/forgot", function(){

	$page = new PageAdmin([
       "header"=>false, //no login não tem o header nem o footer
       "footer"=>false

	]);

	$page->setTpl("forgot");
});

$app->post("/admin/forgot", function(){

	
	$user = User::getForget($_POST["email"]);
});


$app->run();//executa a classe

 ?>