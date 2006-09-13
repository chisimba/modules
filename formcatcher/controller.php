<?php
/* ------ normal controller class for formcatcher ------*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}


/**
*
* Controller class for the formcatcher module
*
* @author Derek Keats
*
*
* @version $Id$
* @copyright 2005 GNU GPL
*
*/
class formcatcher extends controller
{

    /**
    * @var string object $objUser string to hold the user object
    */
    public $objUser;
    /**
    * @var string object $objLanguage string to hold the language object
    */
    public $objLanguage;
    /**
    * @var string object $objLog string to hold the logger object
    */
    public $objLog;
    
    /**
    * @var string object $objFile string to hold the file object
    */
    public $objFile;
    
    
    /**
    * Standard constructor method 
    */
    public function init()
    {
        //Create an instance of the User object
        $this->objUser =  & $this->getObject("user", "security");
        //Create an instance of the language object
        $this->objLanguage = &$this->getObject("language", "language");
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();
        
        // Load the File Class from File Manager
        $this->objFile =& $this->getObject('dbfile', 'filemanager');
    }

    /**
    * Standard dispatch method 
    */
    public function dispatch()
    {
        $action = $this->getParam('action', 'listforms');
        switch ($action) {
            //Default view to show all forms
            case "listforms":
                //Create an instance of the database class for this module
                $objDbFc = & $this->getObject("dbformcatcher");
                $ar = $objDbFc->getCurrentContext();
                $this->setVarByRef('ar', $ar);
                return "main_tpl.php";
                break;
            //Render an interface for adding a form
            case "addform":
                $objShUp = & $this->getObject('renderuploadtemplate');
                $str = $objShUp->renderUploadForm();
                $this->setVar('str', $str);
                return "addform_tpl.php";
                break;
            //Carry out the upload
            case "uploadform":
                $objUpit = & $this->getObject('formuploader');
                $results = $objUpit->uploadForm();
                if ($results="1") {
                    $results = $this->objLanguage->languageText("mod_formcatcher_upsucceed", "formcatcher");
                }
                return $this->nextAction('listforms',array('message'=>$results));
                break;
            //Carry out an update from edit
            case "updateform":
                //Create an instance of the database class for this module
                $objDbFc = & $this->getObject("dbformcatcher");
                if ( $objDbFc->saveRecord( $this->getParam('filename', NULL)==TRUE ) ) {
                    $results = $this->objLanguage->languageText("mod_formcatcher_updatesucceed", "formcatcher");
                } else {
                    $results = $this->objLanguage->languageText("mod_formcatcher_updatefail", "formcatcher");
                }
                return $this->nextAction('listforms',array('message'=>$results));
                break;
            //Edit the form metadata
            case "edit":
                //Create an instance of the database class for this module
                $objDbFc = & $this->getObject("dbformcatcher");
                $id=$this->getParam('id', NULL);
                if ($id !== NULL) {
                    $rec = $objDbFc->getRow('id', $id);
                    $objShUp = & $this->getObject('renderuploadtemplate');
                    $objShUp->mail = $rec['email'];
                    $objShUp->title = $rec['title'];
                    $objShUp->filename = $this->objFile->getFileName($rec['filename']);
                    $objShUp->description = $rec['description'];
                    $objShUp->usefullpage = $rec['usefullpage'];
                    $objShUp->emailtowhere = $rec['emailtowhere'];
                    $str = $objShUp->renderUploadForm();
                    $this->setVar('str', $str);
                    return "addform_tpl.php";
                } else {
                    $this->setVar('str', $this->objLanguage->languageText("mod_formcatcher_errrecordnotfound", "formcatcher"));
                    return "dump_tpl.php"; 
                }   
                break;
            //Handle deleting a form
            case "delete":
                $objUpit = & $this->getObject('formuploader');
                $results = $objUpit->deleteFile();
                if ($results="1") {
                    $results = $this->objLanguage->languageText("mod_formcatcher_delsucceed", "formcatcher");
                }
                return $this->nextAction('listforms',array('message'=>$results));
                break;
            //Form handling - view form
            case "view":
                $objViewFm = & $this->getObject('viewform');
                $objDbFc = & $this->getObject("dbformcatcher");
                $id=$this->getParam('id', NULL);
                //Set the default template
                $template = "doform_tpl.php";
                //Check if they want to take over the page
                if ($id !== NULL) {
                    $rec = $objDbFc->getRow('id', $id);
                    $usefullpage = $rec['usefullpage'];
                    if ($usefullpage == "1") {
                        // Suppress Default Page Settings
                        $this->setVar('pageSuppressContainer',TRUE);
                        $this->setVar('pageSuppressIM',TRUE);
                        $this->setVar('pageSuppressBanner',TRUE);
                        $this->setVar('pageSuppressToolbar',TRUE);
                        $this->setVar('suppressFooter',TRUE);
                        $template = "plainform_tpl.php";
                    }
                } 
                $this->setVar('str', $objViewFm->show());
                
                $this->setVar('pageSuppressXML', TRUE);
                
                return $template;
                break;
            //Form handling - process incomin g form
            case "processform":
                $objCatch = & $this->getObject('catchform');
                $jobTask = $this->getParam('processmethod', 'email');
                switch ($jobTask) {
                    case "email":
                        $str = $objCatch->emailForm();
                        break;
                    case "delimited":
                        $str = $objCatch->saveToDelimitedFile();
                        break;
                    case "xml":
                        $str = $objCatch->saveToXmlFile();
                        break;
                    default:
                        $str = "WTF";
                        break;
                } #switch
                
                $this->setVarByRef('str', $str);
                return "postprocess_tpl.php";
                break;
            //When action is not recognized
            default:
                $this->setVar('str', $this->objLanguage->languageText("phrase_actionunknown", "formcatcher").": ".$action);
                return 'dump_tpl.php';
        }#switch
    } # dispatch

    /**
    * Override the default requirement for login
    */
    public function requiresLogin()
    {
        $action = $this->getParam("action", NULL);
        //Allow viewing anonymously for makefeed
        if ($action == "listforms" || $action == "showform" || $action == "processform") {
            return FALSE;  
        } else {
            return TRUE;
        }
    }
} #end of class
?>