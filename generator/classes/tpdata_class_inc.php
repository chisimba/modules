<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

///Load the abstract common template class
require_once('modules/generator/classes/abtpcommon_class_inc.php');

/**
* 
* Class to render a template component for the database generator pages
* 
* @author Derek Keats
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class tpdata extends abtpcommon
{
    
    /**
    * 
    * Constructor class to initialize language and load form elements
    * 
    */
    public function init() 
    {
        //Run the parent init methods
        parent::init();
    }
    
    /**
    * 
    * Standard show method that returns the rendered template with the 
    * form for the start template. The form allows the creation of 
    * the controller and register.conf files.
    * 
    * @return string The formatted form for creating controller and register
    * 
    */
    function show()
    {
    	//Set up the form action to generate the controller and register.conf
        $paramArray=array(
          'action'=>'builddbtable',
          'page'=>'page4');
        $formAction=$this->uri($paramArray);
        //Create an instance of the form class
        $objForm = new form('startform');
        //Set the action for the form to the uri with paramArray
        $objForm->setAction($formAction);
        //Set the displayType to 3 for freeform
        $objForm->displayType=3;
        //Put first data in a fieldset
        $objFset = $this->newobject('fieldset', 'htmlelements');
        $objFset->setLegend($this->objLanguage->languageText("mod_generator_controller_etabnam", "generator"));
        
        //Put the layout in a table
        $myTable = $this->newObject('htmltable', 'htmlelements');
        $myTable->cellspacing="2";
        $myTable->width="98%";
        $myTable->attributes="align=\"center\"";
        
        //Create an element for the input of table name and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_tablename", "generator"));
        $myTable->addCell($this->getTablesAsDropDown()); 
        $myTable->endRow();
        
        //Create an element for the input of Datbase table class and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_dbclass", "generator"));
        $myTable->addCell($this->getModuleDataTableElement());
        $myTable->endRow();
        
        //Create an element for the input of modulename and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_modname", "generator"));
        $myTable->addCell($this->getModuleNameElement());
        $myTable->endRow();
        
        //Create an element for the input of copyright info and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_copyright", "generator"));
        $myTable->addCell($this->getModuleCopyrightElement());
        $myTable->endRow();

        //Create an element for the submit (upload) button and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->getSubmitButton());
        $myTable->addCell("");
        $myTable->endRow();
        
        $objFset->addContent($myTable->show());

        $objForm->addToForm( $objFset->show() );
        //Render the form & return it
        return $objForm->show();
    }

}
?>