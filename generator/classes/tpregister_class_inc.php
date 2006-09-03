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
* Class to render a template component for the start page
* 
* @author Derek Keats
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class tpregister extends abtpcommon
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
          'action'=>'buildregister',
          'page'=>'page3');
        $formAction=$this->uri($paramArray);
        //Create an instance of the form class
        $objForm = new form('startform');
        //Set the action for the form to the uri with paramArray
        $objForm->setAction($formAction);
        //Set the displayType to 3 for freeform
        $objForm->displayType=3;
        //Put first data in a fieldset
        $objFset = $this->newobject('fieldset', 'htmlelements');
        $objFset->setLegend($this->objLanguage->languageText("mod_generator_controller_fs", "generator"));
        
        //Put the layout in a table
        $myTable = $this->newObject('htmltable', 'htmlelements');
        $myTable->cellspacing="2";
        $myTable->width="98%";
        $myTable->attributes="align=\"center\"";
        
        //Create an element for the input of modulecode and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_mcode", "generator"));
        $myTable->addCell($this->getModuleCodeElement());
        $myTable->endRow();
        
        //Create an element for the input of modulename and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_modname", "generator"));
        $myTable->addCell($this->getModuleNameElement());
        $myTable->endRow();
        
        //Create an element for the input of moduledescription and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_moddesc", "generator"));
        $myTable->addCell($this->getModuleDescriptionElement());
        $myTable->endRow();
        
        //Create an element for the input of Datbase table class and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_dbclass", "generator"));
        $myTable->addCell($this->getModuleDataTableElement());
        $myTable->endRow();
        
        //Create an element for the input of copyright info and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_copyright", "generator"));
        $myTable->addCell($this->getModuleCopyrightElement());
        $myTable->endRow();
        
        //Create an element for the input of menu category and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_menucat", "generator"));
        $myTable->addCell($this->__getMenuCategoryElement());
        $myTable->endRow();
        
        //Create an element for the input of sdie menu category and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_sidemenucat", "generator"));
        $myTable->addCell($this->__getSideMenuCategoryElement());
        $myTable->endRow();
        
        //Create an element for the input of dependscontext and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_dependscontext", "generator"));
        $myTable->addCell($this->__getDependsContextElement());
        $myTable->endRow();
        
        //Create an element for the input of contextaware and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_contextaware", "generator"));
        $myTable->addCell($this->__getContextAwareElement());
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
    
    /**
    * 
    * Method to return MenuCategory text input to the form
    * 
    * @access private
    * @return the module code text input for the form
    * 
    */ 
    private function __getMenuCategoryElement()
    {
        //Create an element for the input of module code
        $objElement = new textinput ("menucategory");
        //Set the field type to text
        $objElement->fldType="text";
        $objElement->size=40;
        if (isset($this->menucategory)) {
            $objElement->value=$this->menucategory;
        }
        //Add the sidemenu catetory  element to the form
        return $objElement->show();
    }
    
    /**
    * 
    * Method to return sideMenuCategory text input to the form
    * 
    * @access private
    * @return the module code text input for the form
    * 
    */ 
    private function __getSideMenuCategoryElement()
    {
        //Create an element for the input of module code
        $objElement = new textinput ("sidemenucategory");
        //Set the field type to text
        $objElement->fldType="text";
        $objElement->size=40;
        if (isset($this->sidemenucategory)) {
            $objElement->value=$this->sidemenucategory;
        }
        //Add the sidemenu catetory  element to the form
        return $objElement->show();
    }
    
    /**
    * 
    * Method to return sideMenuCategory text input to the form
    * 
    * @access private
    * @return the module code text input for the form
    * 
    */ 
    private function __getModuleCopyrightElement()
    {
    	//Check for serialized element
    	$this->copyright = $this->getSession('copyright', NULL);
        //Create an element for the input of module code
        $objElement = new textinput ("copyright");
        //Set the field type to text
        $objElement->fldType="text";
        $objElement->size=40;
        if (isset($this->copyright)) {
            return $this->copyright;
        }
        //Add the copyright element to the form
        return $objElement->show();
    }
    
    private function __getDependsContextElement()
    {
        //Create an element for the input of module code
        $objElement = new radio ("dependscontext");
        $objElement->addOption("0", " " . $this->objLanguage->languageText("mod_generator_no", "generator") . " ");
        $objElement->addOption("2", " " . $this->objLanguage->languageText("mod_generator_yes", "generator") . " ");
        //Add the element to the form
        return $objElement->show();
    }
    
    private function __getContextAwareElement()
    {
     //Create an element for the input of module code
        $objElement = new radio ("contextaware");
        $objElement->addOption("0", " " . $this->objLanguage->languageText("mod_generator_no", "generator") . " ");
        $objElement->addOption("2", " " . $this->objLanguage->languageText("mod_generator_yes", "generator") . " ");
        //Add the element to the form
        return $objElement->show();
    }

}
?>