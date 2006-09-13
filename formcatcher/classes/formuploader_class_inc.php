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
* 
*/
class formuploader extends object
{

    /**
    * 
    * @var string $contextCode The code for the current context
    * 
    */
    var $contextCode;
    
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
              // Context Code
        $this->contextObject =& $this->getObject('dbcontext', 'context');
 		$this->contextCode = $this->contextObject->getContextCode();
    }
    
    function uploadForm()
    {
        if ($this->contextCode!==NULL) {
            /*
            //Create an instance of the upload class
            $objUp = & $this->getObject('upload', 'files');
            //Set it to allow overwrite
            $objUp->overWrite = TRUE;
            //Set the permitted file types
            $objUp->permittedTypes=array("htm", "html", "HTM", "HTML");
            $objUp->uploadFolder = $this->getUploadPath();
            //Check if the folder exists, if not create it
            if (!file_exists($objUp->uploadFolder)) {
                $objCreateFolder = & $this->getObject('mkdir', 'files');
                $objCreateFolder->fullFilePath = $objUp->uploadFolder;
                $objCreateFolder->makedir();
            }
            //Do the upload
            $res = $objUp->doUpload(); 
            */
            
            $filename = $this->getParam('fileupload');
            if ($filename == '') {
                $res = FALSE;
            } else {
                $objDbUpit = & $this->getObject('dbformcatcher');
                $objDbUpit->saveRecord($filename);
                $res = TRUE;
            }
            
        } else {
            $res = $this->objLanguage->code2txt("mod_formcatcher_notincontext", "formcatcher");
        }

        return $res;
    }
    
    /**
    * 
    * Method to set the upload path
    * 
    */
    function getUploadPath()
    {
        //Instantiate config to get the filesystem path to site root
        $objConfig = & $this->getObject('altconfig', 'config');
        //Set the upload path
        $ret = $objConfig->getValue('KEWL_SITEROOT_PATH')
            . $objConfig->getValue('KEWL_CONTENT_PATH') .  "/formcatcher/" 
            . $this->contextCode ."/";
        unset($objConfig);
        return $ret;
    }
    
    /**
    * 
    * Method to delete a file and delete its record from
    * the database
    * 
    * @return String Error | True
    * 
    */
    function deleteFile()
    {
        $id=$this->getParam('id', NULL);
        //Create an instance of the database class for this module
        $objDbFc = & $this->getObject("dbformcatcher");
        return $objDbFc->delete("id", $id);
        
        // $file = $this->getParam('filename', NULL);
        // $uploadFolder = $this->getUploadPath();
        // $fileToDel = $uploadFolder . $file;
        // $fileToDel = str_replace("\\", "/", $fileToDel);
        // $fileToDel = str_replace("//", "/", $fileToDel);
        // $objDel = & $this->getObject('del', 'files');
        // $objDel->fullFilePath = $fileToDel;
        // $objDel->delete();
        // if ($objDel->err != NULL) {
            // return $objDel->errMsg;
        // } else {
            // $objDbFc->delete("id", $id);
            // return TRUE;
        // }
    
    }
}#end of class
?>