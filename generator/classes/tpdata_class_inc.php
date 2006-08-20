<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check


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
class tpdata extends object
{
    /**
    * 
    * @var string object $objLanguage A string to hold the language object
    * 
    */
    public $objLanguage;
    
    /**
    * 
    * Constructor class to initialize language and load form elements
    * 
    */
    public function init() 
    {
        //Create an instance of the language object
        $this->objLanguage = & $this->getObject('language', 'language');
        //Load the form class 
        $this->loadClass('form','htmlelements');
        //Load the textinput class 
        $this->loadClass('textinput','htmlelements');
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
        $objFset->setLegend($this->objLanguage->languageText("mod_generator_controller_etabnam", "generator"));
        
        //Put the layout in a table
        $myTable = $this->newObject('htmltable', 'htmlelements');
        $myTable->cellspacing="2";
        $myTable->width="98%";
        $myTable->attributes="align=\"center\"";
        
        //Create an element for the input of table name and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_tablename", "generator"));
        $myTable->addCell($this->__getTablesAsDropDown()); //__getTableNameElement()
        $myTable->endRow();
        
        //Create an element for the input of Datbase table class and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_dbclass", "generator"));
        $myTable->addCell($this->__getModuleDataTableElement());
        $myTable->endRow();
        
        //Create an element for the input of modulename and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_modname", "generator"));
        $myTable->addCell($this->__getModuleNameElement());
        $myTable->endRow();
        
        //Create an element for the input of copyright info and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_generator_controller_copyright", "generator"));
        $myTable->addCell($this->__getModuleCopyrightElement());
        $myTable->endRow();

        //Create an element for the submit (upload) button and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->__getSubmitButton());
        $myTable->addCell("");
        $myTable->endRow();
        
        $objFset->addContent($myTable->show());

        $objForm->addToForm( $objFset->show() );
        //Render the form & return it
        return $objForm->show();
    }
    
    
    /**
    * 
    * Method to return modulecode text input to the form
    * 
    * @access private
    * @return the module code text input for the form
    * 
    */ 
    private function __getTableNameElement()
    {
        //Create an element for the input of module code
        $objElement = new textinput ("tablename");
        //Set the field type to text
        $objElement->fldType="text";
        $objElement->size=40;
        if (isset($this->tablename)) {
            $objElement->value=$this->tablename;
        }
        //Add the $title element to the form
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
            $objElement->value=$this->copyright;
        }
        //Add the copyright element to the form
        return $objElement->show();
    }
    
    /**
    * 
    * Method to return database classname input to the form
    * 
    * @access private
    * @return the database classtext input for the form
    * 
    */ 
    private function __getModuleDataTableElement()
    {
    	//Check for serialized element
    	$this->databaseclass = $this->getSession('databaseclass', NULL);
        //Create an element for the input of module code
        $objElement = new textinput ("databaseclass");
        //Set the field type to text
        $objElement->fldType="text";
        $objElement->size=40;
        if (isset($this->databaseclass)) {
            $objElement->value=$this->databaseclass;
        }
        //Add the $title element to the form
        return $objElement->show();
    }
    
    /**
    * 
    * Method to return modulename text input to the form
    * 
    * @access private
    * @return the module code text input for the form
    * 
    */ 
    private function __getModuleNameElement()
    {
        //Check for serialized element
    	$this->modulename = $this->getSession('modulename', NULL);
        //Create an element for the input of module code
        $objElement = new textinput ("modulename");
        //Set the field type to text
        $objElement->fldType="text";
        $objElement->size=40;
        if (isset($this->modulename)) {
            $objElement->value=$this->modulename;
        }
        //Add the $title element to the form
        return $objElement->show();
    }
    
    
    /**
    * 
    * Method to return upload (submit) button to the form
    * 
    * @access private
    * @return the upload button for the form
    * 
    */ 
    private function __getSubmitButton()
    {
        // Create an instance of the button object
        $this->loadClass('button', 'htmlelements');
        // Create a submit button
        $objElement = new button('submit');	
        // Set the button type to submit
        $objElement->setToSubmit();	
        // Use the language object to add the word save
        $objElement->setValue(' ' . $this->objLanguage->languageText("mod_generator_generate", 
  		  "generator").' ');
        // return the button to the form
        return "&nbsp;" . $objElement->show()  
          . "<br />&nbsp;";
    }
    
    private function __getTablesAsDropDown()
    {
        //Get an instance of the schema generator
		$objSchema = $this->getObject('getschema');
		$ar = $objSchema->listDbTables();
		$objDropDown = $this->getObject('dropdown', 'htmlelements');
		$objDropDown->name='tablename';
		$objDropDown->cssId = 'input_tablename';
		foreach ($ar as $entry) {
		    $objDropDown->addOption($entry, $entry);
		}
		return $objDropDown->show();
		
    }
}
?>