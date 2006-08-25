<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table  
* Creates the centre-aligned search box
*/
class blockcentersearchbox extends object
{
    /**
    * var object Property to hold language object
    */
    var $objLanguage;
    
	function show(){
        // Get an Instance of the language object
        $this->objLanguage = &$this->getObject('language', 'language');

        $objForm = new form('testform');
		$objForm->setAction($this->uri(array('action'=>'search'),$this->getParam('module')));
		$objForm->setDisplayType(2);

		$surname= new textinput('surname',null,null,40);
		$surname->label=$this->objLanguage->languageText('mod_studentenquiry_surname','studentenquiry').":";

		$stdnum= new textinput('studentNumber',null,null,40);
		$stdnum->label=$this->objLanguage->languageText('mod_studentenquiry_stdnum','studentenquiry').":";
		
		//$applicationum= new textinput('applicationNumber',null,null,40);
		//$applicationum->label='App Num:';

		$idnum= new textinput('idNumber',null,null,40);
		$idnum->label='&nbsp;&nbsp;'.$this->objLanguage->languageText('mod_studentenquiry_idnum','studentenquiry').":";
		
		$save= new button($this->objLanguage->languageText('mod_studentenquiry_save','studentenquiry'));
		$save->setToSubmit();
		$save->setValue($this->objLanguage->languageText('mod_studentenquiry_search','studentenquiry'));
		
		$objForm->addToForm($surname);
		$objForm->addToForm($stdnum);
		//$objForm->addToForm($applicationum);
		$objForm->addToForm($idnum);
		$objForm->addToForm($save);
		
		$objElement = new tabbedbox();
		$objElement->addTabLabel($this->objLanguage->languageText('mod_studentenquiry_search','studentenquiry'));
		$objElement->addBoxContent($objForm->show());	
  
		return $objElement->show();
	}

}
