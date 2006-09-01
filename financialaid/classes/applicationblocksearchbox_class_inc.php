<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table  
* Creates the right-Quick Search box.
*/
class applicationblocksearchbox extends object
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

		$surname= new textinput('surname',null,null,15);

		$stdnum= new textinput('studentNumber',null,null,15);

		$idnum= new textinput('idNumber',null,null,15);

		$save= new button('save');
		$save->setToSubmit();
		$save->setValue('Search');
        $surlabel=$this->objLanguage->languageText('mod_financialaid_surname','financialaid').":";
		$objForm->addToForm($surlabel);
		$objForm->addToForm($surname);
        $stdlabel=$this->objLanguage->languageText('mod_financialaid_stdnum2','financialaid').":";
        $objForm->addToForm($stdlabel);
		$objForm->addToForm($stdnum);
        $idlabel=$this->objLanguage->languageText('mod_financialaid_idnumber','financialaid').":";
        $objForm->addToForm($idlabel);
		$objForm->addToForm($idnum);
		$objForm->addToForm($save);
		
		$objElement = new tabbedbox();
		$objElement->addTabLabel($this->objLanguage->languageText('mod_financialaid_quicksrch','financialaid'));
		$objElement->addBoxContent($objForm->show());	
		return $objElement->show();
	}

}
