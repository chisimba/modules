<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
die("You cannot view this page directly");
}
/**
**
* Controller class for openahis module. This class
* greets the user with a hello message. 
*
* @author Frank Tumusiime
* @copyright (c) 2008 AVOIR
* @package openahis
* @version 0.1
*
*/
class openahis extends controller
{
	/**
	*
	* @var string $greeting A string to hold the text of the greeting that the
	* openahis user will enter
	*
	* @access public
	*
	*/
	public $greeting;
	/**
	*
	* Standard openahis constructor to set the default value of the
	* $greeting property
	*
	* @access public
	*
	*/
	public function init()
	{
		$this->greeting = "Open Source Animal Health Information System";
	}
	/**
	*
	* Standard controller dispatch method. The dispatch method calls any
	* methods involving logic and hands of the results to the template for
	* display.
	*
	* @access public
	*
	*/
	public function dispatch()
	{
		$this->setVar('greeting', $this->greeting);
		return "default_tpl.php";
	}
}
?>