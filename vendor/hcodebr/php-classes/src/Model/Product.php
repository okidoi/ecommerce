<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class Product extends Model{


	
	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_products  ORDER BY desproduct");

	}

	public static function checkList($list)
	{
		foreach ($list as &$row) {
			$p = new Product();
			$p->setData($row);
			$row = $p->getValues();
		}

		return $list;

	}

	

	public function save()
	{
		$sql = new Sql();

		$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
				":idproduct"=>$this->getidproduct(),
				":desproduct"=>$this->getdesproduct(),
				":vlprice"=>$this->getvlprice(),
				":vlwidth"=>$this->getvlwidth(),
				":vlheight"=>$this->getvlheight(),
				":vllength"=>$this->getvllength(),
				":vlweight"=>$this->getvlweight(),
				":desurl"=>$this->getdesurl()

		));
		$this->setData($results[0]);

		Category::updateFile(); //Depois de alterar as categorias já as atualizo no site
	}

	public function get($idproduct)
	{
		
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_products where idproduct = :idproduct", [
				':idproduct'=>$idproduct
		]);

		$this->setData($results[0]);
	}

	public function delete()
	{
		$sql = new Sql();

		$sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", [
			':idproduct'=>$this->getidproduct()
		]);
		
	}

	public function checkPhoto(){
			
			//se a foto já existir
			if(file_exists(
				$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
				"res" . DIRECTORY_SEPARATOR . 
				"site" . DIRECTORY_SEPARATOR . 
				"img" . DIRECTORY_SEPARATOR .
				"products" . DIRECTORY_SEPARATOR . 
				$this->getidproduct() . ".jpg"
			))
			{
				$url =  "/res/site/img/products/" . $this->getidproduct(). ".jpg"; //Aqui é URL entao nao preciso usar o directory separator
			}else{
				$url =  "/res/site/img/product.jpg"; 
			}

			$this->setdesphoto($url);
	}

	public function getValues(){

		$this->checkPhoto();

		$values = parent::getValues();

		return $values;
	}

	public function setPhoto($file){

		$extension = explode('.', $file['name']);
		$extension = end($extension);

		switch ($extension) {
			case 'jpg':
			case 'jpeg':
				$image = imagecreatefromjpeg($file["tmp_name"]);
				break;

			case 'gif':
				$image = imagecreatefromgif($file["tmp_name"]);
				break;

			case 'png':
				$image = imagecreatefrompng($file["tmp_name"]);
				break;

		}

		$dist = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
				"res" . DIRECTORY_SEPARATOR . 
				"site" . DIRECTORY_SEPARATOR . 
				"img" . DIRECTORY_SEPARATOR .
				"products" . DIRECTORY_SEPARATOR . 
				$this->getidproduct() . ".jpg";

		imagejpeg($image, $dist);

		imagedestroy($image);
		
		$this->checkPhoto();	

	}
	
}

?>