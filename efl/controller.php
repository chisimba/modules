<?php
class efl extends controller{
	public $objLanguage;

	function init(){

		  //Instantiate the language object
  		$this->objLanguage = $this->getObject('language', 'language');
	}


	function dispatch(){

		return "essayedit_tpl.php";
	}

}
?>
