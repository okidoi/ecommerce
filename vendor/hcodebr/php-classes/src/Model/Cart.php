<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;
use \Hcode\Model\User;


class Cart extends Model{


	const SESSION = "Cart";
	

	public static function getFromSession(){

		$cart = new Cart();

		//Se a sessao existir e o id > 0  significa que o carrinho já foi inserido no banco e significa que está na sessão. Então preciso apenas carregar o carrinho
		if(isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] > 0 ){

			$cart->get((int)$_SESSION[Cart::SESSION]['idcart']);
		
		} else { //tenta a partir do sessionid recuperar o carrinho

			$cart->getFromSessionID();


			//Se não conseguiu ainda então pega o sessionId do próprio PHP (que é o id unico da sessao. Para cada nova instancia do navegador será um diferente)
			if(!(int)$cart->getidcart() > 0 ){

				$data = [
					'dessessionid'=>session_id()
				];

				if(User::checkLogin(false)){

					$user = User::getFromSession();

					$data['iduser'] = $user->getiduser();
				}

				$cart->setData($data); 

				$cart->save();  //salva no banco

				$cart->setToSession();  //como é um carrinho novo devo colocá-lo na sessao


			}
		}

		return $cart;
	}

		

	public function setToSession(){

		$_SESSION[Cart::SESSION] = $this->getValues();
	}

	public function getFromSessionID(){

		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_carts WHERE dessessionid = :dessessionid", [
			':dessessionid'=>session_id()
		]);

		if(count($results) > 0 ){

			$this->setData($results[0]);
		}
	}

	public function get(int $idcart){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart", [
			':idcart'=>$idcart
		]);

		if(count($results) > 0 ){

			$this->setData($results[0]);
		}	
	}

	public function save()
	{
		
		$sql = new Sql();

		$results = $sql->select("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)", [
				':idcart'=>$this->getidcart(),
				':dessessionid'=>$this->getdessessionid(),
				':iduser'=>$this->getiduser(),
				':deszipcode'=>$this->getdeszipcode(),
				':vlfreight'=>$this->getvlfreight(),
				':nrdays'=>$this->getnrdays()
		]);

		$this->setData($results[0]);

	}

	
}

?>