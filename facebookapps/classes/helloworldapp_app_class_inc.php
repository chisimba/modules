<?php
/**
* 
* Created on 24 Jun 2007
*
* To change the template for this generated file go to
* Window - Preferences - PHPeclipse - PHP - Code Templates
* 
* Chisimba class to render a simple hello world Facebook application
* 
* @author Derek keats
* @package package_name
* 
*/
class helloworldapp extends object
{
	public $hi = "Hello world from the Chisimba application framework";
	
	public function init() 
	{
		
    }
	
	public function show()
	{
		return $this->hi;
	}
}
?>
