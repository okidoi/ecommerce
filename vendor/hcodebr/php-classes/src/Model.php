<?php

namespace Hcode;


class Model{

	private $values = []; 

	public function __call($name, $args){

		$method = substr($name, 0, 3); // A partir da posição zero, traga 0, 1 e 2
		$fieldName = substr($name, 3, strlen($name) ); // a partir de 3 até o final

		switch ($method) {
			case 'get':
				return (isset($this->values[$fieldName]))? $this->values[$fieldName] : NULL;
				break;

			case 'set':
				return $this->values[$fieldName] = $args[0];
				break;	
			
		}

	}

	//Dinamicamente carregamos cada campo que está vindo do BD
	public function setData($data = array()){

		foreach ($data as $key => $value) {
			$this->{"set".$key}($value); // Quando coloco entre chaves é dinamico. o nome set contatena com o valor que está na variavel key.
		}
	}


	public function getValues(){
		return $this->values;
	}

}	

?>