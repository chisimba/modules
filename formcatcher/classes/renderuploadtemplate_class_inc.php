<?php
/* ----------- form catcher renderuploadtemplate class ------------*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* 
* Gets a formatted list of sound files in the current context.
* 
* @author Derek Keats
* 
*/
class renderuploadtemplate extends object
{

    /**
    * 
    * @var string object $objLanguage A string to hold the language object
    * 
    */
    var $objLanguage;
    
    /**
    * 
    * Constructor method to instantiate the database and 
    * user objects. 
    * 
    */
    function init() 
    {
        //Create an instance of the language object
        $this->objLanguage = & $this->getObject('language', 'language');
        //Create an instance of the User object
        $this->objUser =  & $this->getObject("user", "security");
    }
    
    /**
    * 
    * Method to render an upload form with data capture fields
    * 
    * @access Public
    * @return The rendered form
    * 
    */
    function renderUploadForm()
    {
        $mode = $this->getParam('mode', 'create');
        $id = $this->getParam('id', NULL);
        if ( $mode == 'edit' ) {
            $action = 'updateform';
        } else {
            $action = 'uploadform';
        }
        $paramArray=array(
          'action'=>$action,
          'mode'=>$mode,
          'id' => $id);
        $formAction=$this->uri($paramArray);
        //Load the form class 
        $this->loadClass('form','htmlelements');
        //Load the textinput class 
        $this->loadClass('textinput','htmlelements');
        //Create and instance of the form class
        $objForm = new form('formuploader');
        //Set the action for the form to the uri with paramArray
        $objForm->setAction($formAction);
        //Set the displayType to 3 for freeform
        $objForm->displayType=3;
        if ( $this->getParam('mode', 'create') !== 'edit' ) {
            //Set the enctype for multipart file upload
            $objForm->extra = " enctype=\"multipart/form-data\"";
        }
        //Add an element for the max_file_Size to form
        $objForm->addToForm($this->__getMaxFileSize());
        
        
        //Put the layout in a table
        $myTable = $this->newObject('htmltable', 'htmlelements');
        $myTable->cellspacing="2";
        $myTable->width="98%";
        $myTable->attributes="align=\"center\"";
        
        //Create an element for the input of file and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("word_file"));
        $myTable->addCell($this->__getFileElement());
        $myTable->endRow();

        //Create an element for the input of email and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("word_email"));
        $myTable->addCell($this->__getEmailElement());
        $myTable->endRow();
        
        
        //Create an element for the input of title and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("word_title"));
        $myTable->addCell($this->__getTitleElement());
        $myTable->endRow();
        
        //Create an element for the input of description and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("word_description"));
        $myTable->addCell($this->__getDescriptionElement());
        $myTable->endRow();
        
        //Create an element for the input of whether or not to use fullpage
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_formcatcher_usefullpage"));
        $myTable->addCell($this->__getUseFullpageElement());
        $myTable->endRow();
        
        //Create an element for the input of where to send email
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_formcatcher_emailtowhere"));
        $myTable->addCell($this->__getEmailToWhere());
        $myTable->endRow();
        
        //Create an element for the submit (upload) button and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->__getUploadButton());
        $myTable->addCell("");
        $myTable->endRow();
        
        $objForm->addToForm($myTable->show());

        //Render the form & return it
        return $objForm->show();
    }
    
    /**
    * 
    * Method to return a MAX_FILE_SIZE text input
    * as expected by file uploading
    * 
    * @access private
    * @return the hidden text field for the form
    * 
    */ 
    function __getMaxFileSize()
    {
        $objElement = new textinput ("MAX_FILE_SIZE");
        //Set the field type to upload
        $objElement->fldType="hidden";
        //Set the value
        $objElement->value="15000000";
        return $objElement->show();
    }
    
    /**
    * 
    * Method to return a file upload text input
    * 
    * @access private
    * @return the file upload field for the form
    * 
    */ 
    function __getFileElement()
    {
        if ( $this->getParam('mode', 'add' == 'edit') ) {
            if ( isset($this->filename) ) {
                return $this->filename;
            }
        } else {
            //Create an element for the input of title
            $objElement = new textinput ("fileupload");
            //Set the field type to upload
            $objElement->fldType="file";
            //Add the $title element to the form
            return $objElement->show();
        }
    }

    /**
    * 
    * Method to return upload (submit) button to the form
    * 
    * @access private
    * @return the upload button for the form
    * 
    */ 
    function __getUploadButton()
    {
        // Create an instance of the button object
        $this->loadClass('button', 'htmlelements');
        // Create a submit button
        $objElement = new button('submit');	
        // Set the button type to submit
        $objElement->setToSubmit();	
        // Use the language object to add the word save
        if ( $this->getParam('mode', 'create' == 'edit') ) {
            $objElement->setValue(' '
              .$this->objLanguage->languageText("word_save").' ');
        } else {
            $objElement->setValue(' '
              .$this->objLanguage->languageText("word_upload").' ');
        }
        
        // return the button to the form
        return "&nbsp;" . $objElement->show()  
          . "<br />&nbsp;";
    }

    /**
    * 
    * Method to return title text input to the form
    * 
    * @access private
    * @return the Title text input for the form
    * 
    */ 
    function __getTitleElement()
    {
        //Create an element for the input of title
        $objElement = new textinput ("title");
        //Set the field type to upload
        $objElement->fldType="text";
        $objElement->size=60;
        if (isset($this->title)) {
            $objElement->value=$this->title;
        }
        //Add the $title element to the form
        return $objElement->show();
    }
    
    /**
    * 
    * Method to return title text input to the form
    * 
    * @access private
    * @return the Title text input for the form
    * 
    */ 
    function __getEmailElement()
    {
        //Create an element for the input of title
        $objElement = new textinput ("email");
        //Set the field type to upload
        $objElement->fldType = "text";
        $objElement->size = 60;
        if (isset($this->email)) {
            $objElement->value=$this->email;
        } else {
            $objElement->value = $this->objUser->email();
        }
        //Add the $title element to the form
        return $objElement->show();
    }
    
    /**
    * 
    * Method to return description text area
    * 
    * @access private
    * @return the Description text area for the form
    * 
    */
    function __getDescriptionElement()
    {
        //Load the textarea class 
        $this->loadClass('textarea','htmlelements');
        //Create an element for the input of description
        $objElement = new textarea ("description");
        //Set the value of the element to $description
        if (isset($this->description)) {
            $objElement->setContent($this->description);
        }
        //Return the description element to the form
        return $objElement->show();
    } #getDescriptionElement
    
    
    /**
    * 
    * Method to return description text area
    * 
    * @access private
    * @return the Description text area for the form
    * 
    */
    function __getUseFullpageElement()
    {
        //Load the textarea class 
        $this->loadClass('radio','htmlelements');
        //Create an element for the input of description
        $objElement = new radio("usefullpage");
    	$objElement->addOption('0',$this->objLanguage->languageText("word_no"));
    	$objElement->addOption('1',$this->objLanguage->languageText("word_yes"));
        //Set the value of the element  
        if (isset($this->usefullpage)) {
            $objElement->setSelected($this->usefullpage);
        } else {
            $objElement->setSelected('0');
        }
        //Return the element to the form
        return $objElement->show();
    } #getDescriptionElement
    
    function __getEmailToWhere()
    {
        //Load the textarea class 
        $this->loadClass('radio','htmlelements');
        //Create an element for the input of description
        $objElement = new radio("emailtowhere");
    	$objElement->addOption('0',$this->objLanguage->languageText("mod_formcatcher_emailinternal"));
    	$objElement->addOption('1',$this->objLanguage->languageText("mod_formcatcher_emailexternal"));
        $objElement->addOption('2',$this->objLanguage->languageText("mod_formcatcher_emailboth"));
        if (isset($this->emailtowhere)) {
            $objElement->setSelected($this->emailtowhere);
        } else {
            $objElement->setSelected('0');
        }
        //Return the element to the form
        return $objElement->show();
    }
    
}#end of class
?>