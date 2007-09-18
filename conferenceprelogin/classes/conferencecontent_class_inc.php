<?php
/**
* conferencecontent class extends object
* @package conferencecontent
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Class to display the conference content
*
* @author Megan Watson
* @copyright (c) 2004-2006 UWC
* @version 0.1
*/

class conferencecontent extends object
{
    /**
    * Constructor method
    */
    function init()
    {
        $this->confReg = $this->getObject('conferenceregister', 'conferenceprelogin');
        $this->objConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');

        $this->objHead = $this->newObject('htmlheading', 'htmlelements');
        $this->objLayer = $this->newObject('layer', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('form','htmlelements');
        $this->loadClass('label','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('textarea','htmlelements');
        $this->loadClass('label','htmlelements');
        $this->loadClass('htmlheading','htmlelements');
        $this->loadClass('radio','htmlelements');
    }

    /**
    * Method to display the content for a section
    * Certain sections have specific content - register displays the register form, call for papers the submissions form
    *
    * @access private
    * @param string $sectionId The section to display
    * @return string html
    */
   
	function showSection($sectionId)
    {
        $regHere = $this->objLanguage->languageText('mod_conferenceprelogin_regform','conferenceprelogin');
        $submit = $this->objLanguage->languageText('mod_conferenceprelogin_submitabstracts','conferenceprelogin');
    //    $content = $this->dbContent->getSectionContent($sectionId);
        switch($sectionId){
            case '3': // Register
                $form = $this->confReg->show();
                $content .= '<p style="padding-top:10px">'.$form.'</p>';
                break;

            case '5': // Submissions
			//if($this->objUser->isLoggedIn())
			//{
				$objLink = new link($this->uri(array('action' => 'viewcontent', 'mode' => 'submit'))); //array('action' => 'upload'), 'cmsubmissions'));
                $objLink->link = $submit;
                $content .= '<p style="padding-top:15px">'.$objLink->show().'</p>';


                break;
        }
        return $content;
    }
	

    /**
    * Method to display the form for uploading an abstract
    *
    * @access private
    * @return string html
    */
    function showUploadForm()
    {
        $head = $this->objLanguage->languageText('mod_cmsubmissions_uploadabstractspaper','conferenceprelogin');
        $lbFile = $this->objLanguage->languageText('mod_cmsubmissions_file','conferenceprelogin');
        $lbTitle = $this->objLanguage->languageText('mod_cmsubmissions_title','conferenceprelogin');
        $lbDescription = $this->objLanguage->languageText('mod_cmsubmissions_description','conferenceprelogin');
        $lbVersion = $this->objLanguage->languageText('mod_cmsubmissions_version','conferenceprelogin');
        $lbDoc = $this->objLanguage->languageText('mod_cmsubmissions_doctype','conferenceprelogin');
        $lbAbstract = $this->objLanguage->languageText('word_abstract');
        $lbPaper = $this->objLanguage->languageText('word_paper');
        $btnSubmit = $this->objLanguage->languageText('word_submit');


        $objHead = new htmlheading();
        $objHead->type = 1;
        $objHead->str = $head;
        $str = $objHead->show();

        $objTable = new htmltable();
        $objTable->cellpadding = "5";
        $objTable->cellspacing = "5";

        $objLabel = new label($lbFile.': ', 'input_file');
        $objInput = new textinput('file', '', 'file', 50);
        $objTable->addRow(array($objLabel->show(), $objInput->show()), 'odd');

        $objLabel = new label($lbTitle.': ', 'input_title');
        $objInput = new textinput('title', '', '', 50);
        $objTable->addRow(array($objLabel->show(), $objInput->show()), 'even');

        $objLabel = new label($lbDescription.': ', 'input_description');
        $objText = new textarea('description', '', 4, 50);
        $objTable->addRow(array($objLabel->show(), $objText->show()), 'odd');

        $objLabel = new label($lbVersion.': ', 'input_version');
        $objInput = new textinput('version', '', '', 50);
        $objTable->addRow(array($objLabel->show(), $objInput->show()), 'even');

        $objLabel = new label($lbDoc.': ', 'input_documenttype');
        $objRadio = new radio('documenttype');
        $objRadio->addOption('abstract', $lbAbstract);
        $objRadio->addOption('paper', $lbPaper);
        $objRadio->setSelected('abstract');
        $objTable->addRow(array($objLabel->show(), $objRadio->show()), 'odd');

        $objButton = new button('save', $btnSubmit);
        $objButton->setToSubmit();
        $objTable->addRow(array('', $objButton->show()), 'even');

        $objForm =& new form('uploadForm',$this->uri(array('action'=>'uploadfile','mode'=>$mode,'filetype'=>$filetype), 'cmsubmissions'));
        $objForm->extra=" ENCTYPE='multipart/form-data'";

        $objForm->addToForm($objTable->show());
        $str .= $objForm->show();

        return $str;
    }

    /**
    * Entry portal into the class
    *
    * @access public
    * @param string $mode The action to take
    * @return string html
    */
 
	 function show($mode)
    {
        switch($mode){
            case 'subsection':
                $subId = $this->getParam('subsectionid');
                $content = $this->dbSub->getContent($subId);
                return $content;

            case 'submit':
                $content = $this->showUploadForm();
                return $content;
                break;

            default:
                $sectionId = $this->getParam('sectionid');
                if ($sectionId == '') {
                	//get the first section id
                	$sectionId = "1";//$this->dbContent->getFirstSectionId();
                }

                $content = $this->showSection($sectionId);
                return $content;
        }
    }

}
?>