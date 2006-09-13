<?php
/* ----------- form catcher main catch class ------------*/
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
* @package formcatcher
* 
*/
class viewform extends object
{

    /**
    * 
    * @var string $contextCode The code for the current context
    * 
    */
    public $contextCode;
    
    /**
    * 
    * @var string object $objLanguage A string to hold the language object
    * 
    */
    public $objLanguage;

    /**
    * 
    * @var string object $contextObject A string to hold the context object
    * 
    */
    public $contextObject;
    
    /**
    * 
    * @var string $form A string to hold the form contents
    * 
    */
    public $form; 
    
    /**
    * 
    * Constructor method to instantiate the database and 
    * user objects. 
    * 
    */
    public function init() 
    {
        //Create an instance of the language object
        $this->objLanguage = & $this->getObject('language', 'language');
        // Context Code
        $this->contextObject =& $this->getObject('dbcontext', 'context');
 		$this->contextCode = $this->contextObject->getContextCode();
        
        // Load File Class
        $this->objFile =& $this->getObject('dbfile', 'filemanager');
    }
    
    /**
    * 
    * Method to display the form 
    * 
    * @access Public
    * @return The rendered form
    *
    */
    public function show()
    {
        return $this->getContents();
    
    }
    
    /**
    * 
    * Method to get he contents of a form for
    * showing the form on the page
    * 
    * @access Public
    * @return string The contents of the form or a string error message
    * 
    */
    public function getContents()
    {
        /*
        //Get an instance of of the formuloader class
        $objFmUp = & $this->getObject('formuploader');
        //Get the path to the form
        $fuploadPath = $objFmUp->getUploadPath();
        //Convert the path to one that can be used to fopen the form
        $fuploadPath = str_replace("\\", "/", $fuploadPath);
        $fuploadPath = str_replace("//", "/", $fuploadPath);
        $fileFullPath = $fuploadPath . $this->getParam('filename', NULL);
        */
        
        //Get the path to the form
        $fileFullPath = $this->objFile->getFullFilePath($this->getParam('filename', NULL));
        
        //Open the form for reading
        $fh= @fopen($fileFullPath, "r");
        //If the file is open read it, else return error string
        if ($fh) {
            $this->form = fread($fh, filesize($fileFullPath));
            fclose($fh);
            //Check if a valid action is supplied ([[EMAIL]] [[DELIMITED]] ////--- todo [[XML]])
            if ($this->testIfValid()==TRUE) {
                //Make the submition link
                $paramArray=array(
                  'action'=>'processform',
                  'processmethod'=>$this->pMethod,
                  'id'=>$this->getParam('id', NULL));
                $formAction=$this->uri($paramArray);
                //Replace email with the actual actoin
                $this->form=str_replace("[[EMAIL]]", $formAction, $this->form);
                //Replace delimited with actual value
                $this->form=str_replace("[[DELIMITED]]", $formAction, $this->form);
                //Put code here to extract the form and parse the parts
                //........
                //........
                //........
            } else {
                //Return an error that there was no valid action
                $this->form=$this->objLanguage->languageText("mod_formcatcher_errnovalidaction");
            }
        } else {
            //Return an error that the form could not be read
            $this->form = $this->objLanguage->languageText("mod_formcatcher_unreadablefm", "formcatcher");
        }
        return $this->form;
    }
    
    /**
    * 
    * Method to test for valid action in the form.
    * Currently only [[EMAIL]] is supported.
    * 
    * @access Public
    * @return boolean TRUE | FALSE
    * 
    */
    public function testIfValid()
    {
        $pos = strpos($this->form, "[[EMAIL]]");
        if ($pos === false) {
            $pos = strpos($this->form, "[[DELIMITED]]");
            if ($pos === false) {
                $ret = FALSE;
            } else {
                $this->pMethod="delimited";
                $ret = TRUE;
            }
        } else {
            $this->pMethod="email";
            $ret = TRUE;
        }
        return $ret;
    }
    
    
    public function getFormAction()
    {
      //  $regExPattern = "/<form(.*)action";
    }
    
}#end of class
?>