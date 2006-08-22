<?php
/**
* Handles attachments to events.
*/
class dbTev extends dbTable{
	/**
	* Constructor
	*/
	function init()
	{
		parent::init('tbl_tev');
	}

	function insert()
	{
   /*$data = array('field1' => $this->getParam('txtDate'),
                  //'field2' => $dataFromFormForField2,
                  'createdby' => $this->objUser->userId(),
                  'dateCreated' => date(dd-MM-yy-hh-mm)
                  );
   $this->insert($data); */
   
                 
  }
	
	function getTev()
	{}
}

?>
