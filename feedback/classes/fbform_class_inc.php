<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

class fbform extends object
{

	public function init()
	{
		try {
			$this->objLanguage = $this->getObject("language", "language");
			$this->loadClass('form', 'htmlelements');
			$this->loadClass('href', 'htmlelements');
			$this->loadClass('label', 'htmlelements');
			$this->loadClass('textinput', 'htmlelements');
			$this->loadClass('textarea', 'htmlelements');
			$this->objUser = $this->getObject('user', 'security');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}
	}

	public function dfbform()
	{
		$objCaptcha = $this->getObject('captcha', 'utilities');
		$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
		$dfbform = new form('save', $this->uri(array(
		'action' => 'save'
		)));

		//start a fieldset
		$fbfieldset = $this->getObject('fieldset', 'htmlelements');
		
		$fbadd = $this->newObject('htmltable', 'htmlelements');
		$fbadd->cellpadding = 3;

		//name textfield
		$fbadd->startRow();
		$fbnamelabel = new label($this->objLanguage->languageText('mod_feedback_thename', 'feedback') .':', 'input_fbname');
		$fbname = new textinput('fbname');
		$fbadd->addCell($fbnamelabel->show().$required);
		$fbadd->addCell($fbname->show());
		$fbadd->endRow();

		//email textfield
		$fbadd->startRow();
		$fbemaillabel = new label($this->objLanguage->languageText('mod_feedback_email', 'feedback') .':', 'input_fbemail');
		$fbemail = new textinput('fbemail');
		$fbadd->addCell($fbemaillabel->show().$required);
		$fbadd->addCell($fbemail->show());
		$fbadd->endRow();

		//worked well text field
		$fbadd->startRow();
		$fbwwlabel = new label($this->objLanguage->languageText('mod_feedback_ww', 'feedback') .':', 'input_fbww');
		$fbww = new textarea('fbww');
		$fbadd->addCell($fbwwlabel->show());
		$fbadd->addCell($fbww->show());
		$fbadd->endRow();

		//didn't work well text field
		$fbadd->startRow();
		$fbnwlabel = new label($this->objLanguage->languageText('mod_feedback_nw', 'feedback') .':', 'input_fbnw');
		$fbnw = new textarea('fbnw');
		$fbadd->addCell($fbnwlabel->show());
		$fbadd->addCell($fbnw->show());
		$fbadd->endRow();

		//what did we leave out text field
		$fbadd->startRow();
		$fblolabel = new label($this->objLanguage->languageText('mod_feedback_lo', 'feedback') .':', 'input_fblo');
		$fblo = new textarea('fblo');
		$fbadd->addCell($fblolabel->show());
		$fbadd->addCell($fblo->show());
		$fbadd->endRow();

		//speakers & programme
		$fbadd->startRow();
		$fbsplabel = new label($this->objLanguage->languageText('mod_feedback_sp', 'feedback') .':', 'input_fbsp');
		$fbsp = new textarea('fbsp');
		$fbadd->addCell($fbsplabel->show());
		$fbadd->addCell($fbsp->show());
		$fbadd->endRow();

		//exhibition and exhibitors
		$fbadd->startRow();
		$fbeelabel = new label($this->objLanguage->languageText('mod_feedback_ee', 'feedback') .':', 'input_fbee');
		$fbee = new textarea('fbee');
		$fbadd->addCell($fbeelabel->show());
		$fbadd->addCell($fbee->show());
		$fbadd->endRow();

		//website
		$fbadd->startRow();
		$fbwlabel = new label($this->objLanguage->languageText('mod_feedback_w', 'feedback') .':', 'input_fbw');
		$fbw = new textarea('fbw');
		$fbadd->addCell($fbwlabel->show());
		$fbadd->addCell($fbw->show());
		$fbadd->endRow();
		
		$fbadd->startRow();
		$captcha = new textinput('request_captcha');
		$captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'input_request_captcha');
		$fbadd->addCell(stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as shown below. If you are unable to view the code, click on "Redraw" for a new one.')).'<br /><div id="captchaDiv">'.$objCaptcha->show().'</div>'.$captcha->show().$required.'  <a href="javascript:redraw();">'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>');
		$fbadd->endRow();
		
		//add rules
		$dfbform->addRule('name', $this->objLanguage->languageText("mod_feedback_phrase_needname", "feedback") , 'required');
		$dfbform->addRule('email', $this->objLanguage->languageText("mod_feedback_phrase_needemail", "feedback") , 'required');
		$dfbform->addRule('request_captcha', $this->objLanguage->languageText("mod_feedback_captchaval",'feedback'), 'required');

		//end off the form and add the buttons
		$this->objSaveButton = &new button($this->objLanguage->languageText('word_save', 'system'));
		$this->objSaveButton->setValue($this->objLanguage->languageText('word_save', 'system'));
		$this->objSaveButton->setToSubmit();
		$fbfieldset->addContent($fbadd->show());
		$dfbform->addToForm($fbfieldset->show());
		$dfbform->addToForm($this->objSaveButton->show());
		$dfbform = $dfbform->show();

		//featurebox it...
		$objFbFeaturebox = $this->getObject('featurebox', 'navigation');
		return $objFbFeaturebox->show($this->objLanguage->languageText("mod_feedback_feedback", "feedback"), $dfbform);
	}
	
	public function thanks()
	{
		$tamsg = $this->objLanguage->languageText("mod_feedback_thanksmsg", "feedback");
		$objFbFeaturebox = $this->getObject('featurebox', 'navigation');
		return $objFbFeaturebox->show($this->objLanguage->languageText("mod_feedback_thanks", "feedback"), $tamsg);
	}
	
	 /**
     * Method to display the login box for prelogin  operations
     *
     * @param bool $featurebox
     * @return string
     */
    public function loginBox($featurebox = FALSE)
    {
        $objLogin = &$this->getObject('logininterface', 'security');
        if ($featurebox == FALSE) {
            return $objLogin->renderLoginBox('feedback');
        } else {
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            return $objFeatureBox->show($this->objLanguage->languageText("word_login", "system") , $objLogin->renderLoginBox('feedback'));
        }
    }
}
?>