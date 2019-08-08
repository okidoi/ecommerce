<?php
namespace Hcode;

use Rain\Tpl;
// include
//include "library/Rain/Tpl.php";

class Page{

	private $tpl;
	private $options = [];
	private $defaults = [
		"data"=>[]
	];

	public function __construct($opts = array()){

		$this->options = array_merge($this->defaults, $opts); // Mescla 2 arrays. o ultimo sobrescreve os anteriores. O $opts teria prioridade ao gravar no options. A ordem é importante.

		// config
		$config = array(
						"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/",
						"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
						"debug"         => false // set to false to improve the speed
		);

		Tpl::configure( $config );
	
		// create the Tpl object
		$this->tpl = new Tpl;

		$this->setData($this->options["data"]);


		$this->tpl->draw("header");
	
	}

	private function setData($data = array())
	{

		foreach ($data as $key => $value) {
			$this->tpl->assign($key, $value);
		}
	}

	public function setTpl($name, $data = array(), $returnHTML = false)
	{
		$this->setData($this->options["data"]);
		return $this->tpl->draw($name, $returnHTML);
	}

	public function __destruct(){
		$this->tpl->draw("footer");

	}
}

?>
