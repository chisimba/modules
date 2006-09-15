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

		$searchval = new textinput('searchValue',null,null,15);

        $searchfield = new radio('searchfield');
        $searchfield->addOption('surname', $this->objLanguage->languageText('mod_financialaid_surname','financialaid'));
        $searchfield->addOption('studentNumber', $this->objLanguage->languageText('mod_financialaid_stdnum2','financialaid'));
        $searchfield->addOption('idNumber', $this->objLanguage->languageText('mod_financialaid_idnumber','financialaid'));
        $searchfield->setSelected('surname');
        $searchfield->setBreakSpace('<br />');


        $dispcount = new radio('dispcount', null, null, 4);
        $dispcount->addOption('10', '10');
        $dispcount->addOption('25', '25');
        $dispcount->addOption('50', '50&nbsp;&nbsp;');
        $dispcount->setSelected('25');
        $dispcount->setBreakSpace('&nbsp;&nbsp;&nbsp;');

		$save= new button('save');
		$save->setToSubmit();
		$save->setValue('Search');
        $objForm->addToForm('<b>'.$this->objLanguage->languageText('mod_financialaid_searchfield','financialaid').'</b>');
		$objForm->addToForm($searchfield);
        $objForm->addToForm('<br /><b>'.$this->objLanguage->languageText('mod_financialaid_searchvalue','financialaid').'</b>');
		$objForm->addToForm($searchval);
        $objForm->addToForm('<br /><b>'.$this->objLanguage->languageText('mod_financialaid_resultsperpage','financialaid')."</b>");
		$objForm->addToForm($dispcount);
		$objForm->addToForm($save);
		
		$objElement = new tabbedbox();
		$objElement->addTabLabel($this->objLanguage->languageText('mod_financialaid_quicksrch','financialaid'));
		$objElement->addBoxContent($objForm->show());	
		return $objElement->show();
	}

}
