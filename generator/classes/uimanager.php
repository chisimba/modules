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
* Class to read the XML templates for the generator UI and render
* the interface for user input and post processing
* 
* @author Derek Keats
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class uimanager extends object
{
   /**
   *
   * @var string $objFormTable The table used to hold the form elements
   *
   */
   public $objFormTable;
   
   /**
   *
   * @var string $formXml The XML tree read from the XML template
   *
   */
   public $formXml;
   
    /**
    * 
    * Constructor class to initialize language 
    * 
    */
    public function init() 
    {
        //Create an instance of the language object
        $this->objLanguage = & $this->getObject('language', 'language');
    }
    


    /**
    *
    * Method to read the form to build the UI for the particular
    * itemType (e.g. controller, dbtable). 
    * It looks in modules/generators/$itemType for a file called
    * $itemType_ui_form.xml (e.g. controller_ui_form)
    * The form XML is read into memory for processing
    *
    * @param string $itemType The type of item being generated 
    *
    */
    public function readFormXml($itemType)
    {
        //Load the XML  
        $this->formXml = simplexml_load_file("modules/generator/generators/" 
          . $itemType . "/ui_form.xml"); 
    }


    /**
    *
    * Method to genearte a form from the XML read into memory
    * using readFormXml.
    *
    */
    public function generateForm()
    {
        //Create an instance of the form class
        $objForm = new form( $this->getFormName() );
        //Set the action for the form to the uri with paramArray
        $objForm->setAction( $this->getFormAction() );
        //Set the displayType to 3 for freeform so we can make our own table
        $objForm->displayType=3;
    }

    /**
    *
    * Method to genearte a form action from the XML by using XPATH
    * to read it from the DOM
    *
    * @return string The action for use in setting up the form
    *
    */
    public function getFormAction()
    {
	    $action = $this->formXml->form[0]->action;
	    $page = $this->formXml->form[0]->page;
        //Set up the form action to generate the item
        $paramArray=array(
          'action'=>$action,
          'page'=>$page);
        return $this->uri($paramArray);
    }

    /**
    *
    * Method to return the name of the form
    *
    */
    public functin getFormName()
    {
      $this->formXml->form[0]->name;
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
          'action'=>'buildcontroller',
          'page'=>'page2');
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
        $myTable->addCell( $this->objLanguage->languageText("mod_generator_controller_dbclass", "generator") );
        $myTable->addCell($this->getModuleDataTableElement());
        $myTable->endRow();
        
        //Create an element for the input of copyright info and add it to the table
        $myTable->startRow();
        $myTable->addCell( $this->objLanguage->languageText("mod_generator_controller_copyright", "generator") );
        $myTable->addCell( $this->getModuleCopyrightElement() );
        $myTable->endRow();
        //Create an element for the submit (upload) button and add it to the table
        $myTable->startRow();
        $myTable->addCell( $this->getSubmitButton() );
        $myTable->addCell("");
        $myTable->endRow();
        //Add the table
        $objFset->addContent( $myTable->show() );
		//Add the fieldset
        $objForm->addToForm( $objFset->show() );
        //Render the form & return it
        return $objForm->show();
    }
}
?>