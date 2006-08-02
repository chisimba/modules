<?php
/* ----------- form catcher main catch class ------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* 
* Catches an uploaded form and processes it, either sending
* via email or saving it to a delimited file.
* 
* @author Derek Keats
* 
*/
class catchform extends object
{

    /**
    * 
    * @var string $contextCode The code for the current context
    * 
    */
    public $contextCode;
    /**
    * 
    * @var string array $arFields An array of all the field 
    *  names in the form
    * 
    */
    public $arFields;
    
    /**
    * 
    * @var string object $objLanguage A string to hold the language object
    * 
    */
    public $objLanguage;
    
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
        //Create an instance of the User object
        $this->objUser =  & $this->getObject("user", "security");
        //Get the ID that identifies the form metadata
        $this->id=$this->getParam('id', NULL);
    } #init
    
    /**
    * 
    * Method to send the form by email
    *
    * @access Public
    *
    * @return boolean True | False 
    * 
    */
    public function emailForm()
    {
        //Make sure they are sending a POST form
        if (!$this->__validateForPost()==TRUE) {
             return $this->objLanguage->languageText("mod_formcatcher_errpostnotused", "formcatcher");
        } else {
            if ($this->id !== NULL) {
                $body = $this->extractDataAsText() 
                  . "\n\n-------------------------------------------------------------\n"
                  . $this->objLanguage->languageText("mod_formcatcher_formresults", "formcatcher");
                //Create an instance of the database class for this module
                $objDbFc = & $this->getObject("dbformcatcher");
                $rec = $objDbFc->getRow('id', $this->id);
                $emailtowhere = $rec['emailtowhere'];
                $to = $rec['email'];
                $subject = $rec['title'];
                $fm = $this->objUser->email();
                //Allow for anonymous posting
                if ($fm == "") {
                    $fm = $this->objLanguage->languageText("mod_formcatcher_anon", "formcatcher");
                }
                //If they want to use internal email or both
                if ($emailtowhere == "0" || $emailtowhere == "2") {
                    $toInternal = $rec['creatorId'];
                    $fmInternal = $this->objUser->userId();
                    //Get the mailer and sent the mail
                    $objInternalEmail =& $this->getObject('kngmail', 'email');
                    $objInternalEmail->sendMail($fmInternal, $toInternal, $subject, $body);
                }
                //If they want to use external email or both
                if ($emailtowhere == "1" || $emailtowhere == "2") {
                    //Get the mailer and sent the mail
                    $objExternalEmail =& $this->getObject('kngemail', 'utilities');
                    $objExternalEmail->sendMail($name, $subject, $to, $body, FALSE);
                }
                


                //Send back a confirmation page
                $fullPost = "<b>" . $this->objLanguage->languageText("word_to", "formcatcher") 
                  . "</b>: " . $to . "<br />"
                  . "<b>" . $this->objLanguage->languageText("word_from", "formcatcher") 
                  . "</b>: " . $fm  . "<br />"
                  . "<b>" . $this->objLanguage->languageText("word_subject", "formcatcher") 
                  . "</b>: " . $subject  . "<br />"
                  . "<b>" . $this->objLanguage->languageText("mod_formcatcher_formresults", "formcatcher") 
                  . "</b>:<br /><br />" . str_replace("\n", "<br />", $body) . "<br />";
                
                
                //$this->objMailer->sendMail('', $subject, $to, $body, FALSE);
                
                //sendMail($name, $subject, $email, $body, $html = TRUE, $attachment = NULL, $attachment_descrip=NULL)
                return $fullPost;
            } else {
                return $this->objLanguage->languageText("mod_formcatcher_errcantfinddata", "formcatcher");
            }
        }
    } #emailForm
    
    
    /**
    * 
    * Method to save the form into a growing comma delimited text file
    * 
    */
    public function saveToDelimitedFile()
    {
        //Make sure they are sending a POST form
        if (!$this->__validateForPost()==TRUE) {
             return $this->objLanguage->languageText("mod_formcatcher_errpostnotused", "formcatcher");
        } else {
            //Retrieve the name of the file
            $objDbFc = & $this->getObject("dbformcatcher");
            $rec = $objDbFc->getRow('id', $this->id);
            $fileName = $rec['filename'];
            //Open the file
            $objPath = & $this->getObject('formuploader');
            $fullFile = $objPath->getUploadPath()
              . $fileName . ".dat";
            //Open the file and write
            $fh = @fopen($fullFile, "a+");
            $ret = $this->extractDataAsDelimited();
            if ($fh) {
                $objDbFc = & $this->getObject("dbformcatcher");
                fwrite($fh, $ret);
                fclose($fh);
                return $ret;
            } else {
                //We failed
                return FALSE;
            }
        }
    }
    
    /**
    * 
    * Method to save the form into a growing comma delimited text file
    * 
    */
    public function saveToXmlFile()
    {
        //Make sure they are sending a POST form
        if (!$this->__validateForPost()==TRUE) {
             return $this->objLanguage->languageText("mod_formcatcher_errpostnotused", "formcatcher");
        } else {
            //Retrieve the name of the file
            $objDbFc = & $this->getObject("dbformcatcher");
            $rec = $objDbFc->getRow('id', $this->id);
            $fileName = $rec['filename'];
            //Open the file
            $objPath = & $this->getObject('formuploader');
            $fullFile = $objPath->getUploadPath()
              . $fileName . ".xml";
            //Open the file and write
          
        }
    }
    
    
    /**
    * 
    * Method to extract the fields from the form and build 
    * a text for sending by email
    * 
    * @access Public
    * @return string array An array containing all the listed 
    *   fields on the form.
    * 
    */
    public function extractDataAsText()
    {
        if (count($_POST > 0)) {
            //Go over the fields and build an associative array
            $str="";
            foreach ($_POST as $key => $value) {
                if ($key !=="Submit" && $key !=="Save" ) {
                    $str .= $key . "\n" . $value . "\n\n";
                }
            }
            return $str;
        } else {
            return NULL;
        }
    }
    
    /**
    * 
    * Method to extract the fields from the form and build 
    * a delimited record
    * 
    * @access Public
    * @return string array An array containing all the listed 
    *   fields on the form.
    * 
    */
    public function extractDataAsDelimited()
    {
        $defaultDelim = $this->__getDelimiter();
        if (count($_POST > 0)) {
            //Go over the fields and build a record
            $str="";
            //Loop once to build an array
            foreach ($_POST as $key => $value) {
                if ($key !== "Submit" 
                  && $key !== "Save" 
                  && $key !== "form-catcher-fields") {
                    $ar[] = $value;
                }
            }
            $arCount = count($ar);
            $c=0;
            foreach ($ar as $value) {
                $c++;
                if ($c==$arCount) {
                    $delimiter="\r\n";
                } else {
                    $delimiter = $defaultDelim;
                }
                //Insert quotes if the config param asks for it
                $quotes="";
                if ($this->__getQuotes()==TRUE) {
                    $quotes = "\"";
                }
                $str .= $quotes . $value 
                  . $quotes . $delimiter;
            }
            return $str;
        } else {
            return NULL;
        }
    }
    /**
    * 
    * Method to extract the fields from the form and build 
    * am XML representation
    * 
    * @access Public
    * @return string XML string with data
    * 
    */
    public function extractDataAsXml()
    {
        if (count($_POST > 0)) {
            //Go over the fields and build an associative array
            $str="";
            if ($key !=="Submit" && $key !=="Save" ) {
                foreach ($_POST as $key => $value) {
                    $str .= "--<" . $key . ">\n  " . $value . "\n</" . $key . ">--\n\n";
                }
            }
            return $str . "\n";
        } else {
            return NULL;
        }
    }
    
    
    /**
    * 
    * Method to get a list of the fields in the form
    * 
    * @access Public
    * @return string array An array of the fields in the database | Null if field not present
    * 
    */
    public function getArrayOfFields()
    {
        if (count($_POST > 0)) {
            foreach ($_POST as $key => $value) {
                $key = trim($key);
                //Eliminate submit and save
                if ($key !=="Submit" && $key !=="Save" ) {
                    $this->arFields[] = $key;
                }
                
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function __getSpecifiedFields()
    {
        $keyFieldsArray = explode(",", $_POST['form-catcher-fields']);
        $count = count($keyFieldsArray);
        
 
    }

    /**
    * 
    * Validate for the post method
    * 
    * @access Private
    * @return boolean TRUE | FALSE
    * 
    */
    public function __validateForPost()
    {
        if ($_SERVER['REQUEST_METHOD'] != "POST"){
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    /**
    * 
    * Return the delimiter character
    * 
    * @access Private
    * @return string The delimiter character
    * 
    */
    public function __getDelimiter()
    {
        //Get an instance of the config object
        $objConfig=&$this->newObject('config','config');
        //Get the value of the delimiter
        $delim = $objConfig->getValue('DELIMITER', 'formcatcher');
        switch ($delim) {
            case "[PIPE]":
                $delim = "|";
                break;
            default:
                break;
        }
        return $delim;
    }
    
    /**
    * 
    * Return the whether or not to use quotes
    * 
    * @access Private
    * @return string The delimiter character
    * 
    */
    public function __getQuotes()
    {
        //Get an instance of the config object
        $objConfig=&$this->newObject('config','config');
        //Get the value of the delimiter
        $uq = $objConfig->getValue('USE_QUOTES', 'formcatcher');
        if (strtolower($uq) == "true" ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    
} #end of class
?>