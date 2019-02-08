<?php

namespace Hcode;

class Model {

	private $values = []; //armazena valores do campo do objeto, dados do usuario

	public function __call($name, $args)
	{

		//verifica o nome do metodo é get ou set, se for get retorna informação, se fo set atribui valor, mas como vai estar //sempre setid ou getid tem que dividar a palavra.
		
		$method = substr($name, 0, 3); //chama um metodo
		$fieldName = substr($name, 3, strlen($name)); //conta da terceira posição até o final

		switch ($method) {
			
			case "get":
			     return (isset($this->values[$fieldName])) ? $this->values[$fieldName]:NULL;
				# code...
				break;
			case "set":
			      $this->values[$fieldName] = $args[0];//args nesse caso é valor passado no atributo name
				# code...
				break;
			
		}
	}

	public function setData($data = array()){

		foreach ($data as $key => $value) {
			# code...
			$this->{"set".$key}($value);
		}
	}

	public function getValues(){

		return $this->values;
	}
}
?>