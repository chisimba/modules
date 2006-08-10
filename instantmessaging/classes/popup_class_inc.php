<?php
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
/** 
* This class is used to provide an 'instant messaging' icon which when clicked 
* opens a new popup window pre-filled with the recipient's details to which the user can 
* send an instant message.
* @author Jeremy O'Connor
* @copyright (C) 2005 Avoir
*/
class popup extends object {
	/**
	* @var string The recipient ID
	*/
	var $recipientId;
	/**
	* @var string The recipient type
	*/
	var $recipientType;
	/**
	* @var string The alt attribute for the image
	*/
	var $alt;
	function init()
	{
	}
	/**
	* Setup the object
	* @param string The recipient ID (can be null)
	* @param string The recipient type
	* @param string The alt attribute for the image
	*/
	function setup($recipientId, $recipientType, $alt)
	{
		$this->recipientId = $recipientId;
		$this->recipientType = $recipientType;
		$this->alt = $alt;
	}
	function show()
	{
		$imParam = array(
			'module' => 'instantmessaging',
		    'action' => 'sendMessage',
		    'recipientId' => $this->recipientId,
		    'recipientType' => $this->recipientType,
		    'closeWindow' => 'yes');
		$imIcon =& $this->getObject('geticon', 'htmlelements');
		$imIcon->alt = $this->alt;
		$imIcon->setIcon('im');
		$objPopup =& $this->newObject('windowpop', 'htmlelements');
		$objPopup->set('location', $this->uri($imParam, 'instantmessaging'));
		$objPopup->set('linktext', $imIcon->show());
		$objPopup->set('width', '500');
		$objPopup->set('height', '150');
		$objPopup->set('left', '400');
		$objPopup->set('top', '300');
		$objPopup->putJs();
		return $objPopup->show(); 
	}
}
?>