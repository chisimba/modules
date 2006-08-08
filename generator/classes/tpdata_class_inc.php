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
        $myTable->addCell($this->__getTableNameElement());
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
}
?>