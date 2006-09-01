<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
* Creates the centre-aligned search box
*/
class blockcentersearchappbox extends object
{
    /**
    * var object Property to hold language object
    */
    var $objLanguage;
    
	function show(){
        // Get an Instance of the language object
        $this->objLanguage = &$this->getObject('language', 'language');

        $objForm = new form('testform');
		$objForm->setAction($this->uri(array('action'=>'searchapplications'),$this->getParam('module')));
		$objForm->setDisplayType(2);

		$surname= new textinput('surname',null,null,40);
		$surname->label=$this->objLanguage->languageText('mod_financialaid_surname','financialaid').":";

		$stdnum= new textinput('studentNumber',null,null,40);
		$stdnum->label=$this->objLanguage->languageText('mod_financialaid_stdnum2','financialaid').":";
		
		$idnum= new textinput('idNumber',null,null,40);
		$idnum->label=$this->objLanguage->languageText('mod_financialaid_idnumber','financialaid').":";
		
		$save= new button($this->objLanguage->languageText('mod_studentenquiry_save','studentenquiry'));
		$save->setToSubmit();
		$save->setValue($this->objLanguage->languageText('mod_studentenquiry_search','studentenquiry'));
		
		$objForm->addToForm($surname);
		$objForm->addToForm($stdnum);
		$objForm->addToForm($idnum);
		$objForm->addToForm($save);
		
		$objElement = new tabbedbox();
		$objElement->addTabLabel($this->objLanguage->languageText('mod_studentenquiry_search','studentenquiry'));
		$objElement->addBoxContent($objForm->show());	
  
		return $objElement->show();
	}
}
