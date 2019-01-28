<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model {

	const SESSION = "User";
  const SECRET = "gabrielsistemas_secret"

	public static function login($login, $password){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(":LOGIN"=>$login

	));

		if (count($results) === 0){

			throw new \Exception("Usuario não existe ou senha invalida.");
		}

		$data = $results[0];

		if(password_verify($password, $data["despassword"]) === true) //se a senha for igual a do banco
			{

                  $user = new User(); //criar o usuario

                  $user->setData($data);//escreve o usuarios, testando a função call

                  $_SESSION[User::SESSION] = $user->getValues();

                  return $user;
	

		     } else{

			throw new \Exception("Usuário inexistente ou senha invalida");
			
		}




	}

	public static function verifyLogin($inadmin = true){ //
	
    //se a sessão não foi definida ou se a sessão é falsa ou se o id não for maior que 0 ou se o usuario pode logar como admin
	if (!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION] || !(int)$_SESSION[User::SESSION]["iduser"] > 0 || (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin) {

		header("Location: /admin/login");
		exit;
		# code...
	}

  }

  public static function logout(){

  	$_SESSION[User::SESSION] = NULL;
  }

  public static function listAll(){

  	$sql = new Sql();

  	return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
  }

  public function save(){

  	$sql = new Sql();
 
  	$result = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)",array(
  	    ":desperson"=>$this->getdesperson(),
  	    ":deslogin"=>$this->getdeslogin(),
  	    ":despassword"=>$this->getdespassword(),
  	    ":desemail"=>$this->getdesemail(),
  	    ":nrphone"=>$this->getnrphone(),
  	    ":inadmin"=>$this->getinadmin()
  	     
  	 ));

  	$this->setData($result[0]);
  }

  public function get($iduser){

    $sql = new Sql();

    $result = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(

      ":iduser"=>$iduser
    ));

    $this->setData($result[0]);
  }

  public function update(){

    $sql = new Sql();
 
    $result = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)",array(
        ":iduser"=>$this->getiduser(),
        ":desperson"=>$this->getdesperson(),
        ":deslogin"=>$this->getdeslogin(),
        ":despassword"=>$this->getdespassword(),
        ":desemail"=>$this->getdesemail(),
        ":nrphone"=>$this->getnrphone(),
        ":inadmin"=>$this->getinadmin()
         
     ));

    $this->setData($result[0]);
  }


  public function delete(){

    $sql = new Sql();

    $sql->query("CALL sp_users_delete(:iduser)", array(
      ":iduser"=>$this->getiduser()


    ));
  }

  public static function getForgot($email){

    $sql = new Sql();

    $results = $sql->select("

      SELECT * 
      FROM tb_persons a 
      INNER JOIN tb_users b USING(idperson)
       WHERE a.desemail = :email;


      ", array(

        ":email"=>$email

    ));


    if (count($results) === 0) {

      throw new \Exception("Não foi possível recuperar a senha.");
      
      # code...
    }
    else
    {

      $data = $results[0];

      $results2 = $sql-.select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(

        ":iduser"=>$data["iduser"],
        ":desip"=>$_SERVER["REMOTE_ADDR"]//pega o ip do usuario
      ));

      if (count($results2) == 0) {

        throw new Exception("Não foi possível recuperar a senha");
        
        # code...
      }
      else
      {
        $datarecovery = $results2[0]

        base64_encode(mcrypt_decrypt(MCRYPT_RIJNDEAL_128, User::SECRET, $dataRecovery["idrecorvery"], MCRYPT_MODE_ECB));

        $link = "http://http://www.hcodecommerce.com.br/admin/forgot/reset?code=$code"
      }
      
    }
  }



}

?>