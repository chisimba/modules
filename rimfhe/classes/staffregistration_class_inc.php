<?php
/*
 * This is the accreditedjournal class for the rmfhe(Research Information Management for Higher
 * Education Module 
 * This class creates a registration form that receives and sends details of
 * DOE Accredited Journal Articles
 */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}//end security check

/**
 *
 * @package rimfhe
 * @version 0.1
 * @Copyright January 2009
 * @author Joey Akwunwa
 */
class staffregistration extends object
{	
	public $objLanguage;
	public $objUrl;
	public $dbInsert;
	
	public function init()
	{	//create a language object
		$this->objLanguage=$this->getObject('language', 'language');
		$this->objUrl = $this->getObject('url', 'strings');
		$this->dbInsert =$this->getObject('dbstaffmember', 'rimfhe');
		//instantiate formhelperclass
		$this->formElements =$this->getObject('formhelperclass', 'rimfhe');
	}//end init

	//create an instruction for the form
	public function formInstruction()
	{
		$formInstruct = $this->newObject('htmlheading', 'htmlelements');
		$formInstruct->type = '3';
		$formInstruct->str = $this->objLanguage->languageText('mod_staffregistration_forminstruction', 'rimfhe');
		return $formInstruct->show();
	
	}//end for instruction

	//method to build form and create form elements
	private function buildForm($mode = 'NULL')
	{
		//load the required form elements
		$this->formElements->sendElements();
		//create the form
		$staffRegistration = new form ('register', $this->uri(array('action'=>'registerstaff'), 'rimfhe'));
		//add instruction to form
		//$staffRegistration->addToForm($mode);
		$staffRegistration->addToForm($this->formInstruction());
		/* ---------------------- Form Elements--------*/
		//assign laguage objects to variables		
		$surname = $this->objLanguage->languageText('word_surname', 'system');
		$initials= $this->objLanguage->languageText('mod_staffregistration_initials', 'rimfhe');
		$firstname= $this->objLanguage->languageText('phrase_firstname', 'system');
		$droptitle= $this->objLanguage->languageText('word_title', 'system');
		$rank= $this->objLanguage->languageText('mod_staffregistration_rank', 'rimfhe');
		$appointment= $this->objLanguage->languageText('mod_staffregistration_appointment', 'rimfhe');
		$dept= $this->objLanguage->languageText('mod_staffregistration_department', 'rimfhe');
		$faculty= $this->objLanguage->languageText('mod_staffregistration_faculty', 'rimfhe');
		$staffNumber= $this->objLanguage->languageText('phrase_staffnumber', 'system');
		$email= $this->objLanguage->languageText('phrase_emailaddress', 'system');
		$confirmemail= $this->objLanguage->languageText('phrase_confirmemail', 'system');
	
		//create table
		$table =new htmltable('registration');
		$table->width ='80%';
		$table->startRow();
		//Input and label for Surname
		$objSurname = new textinput('surname');
		$surnameLabel = new label($surname,'surname');
		$staffRegistration->addRule('surname','Please enter your Surname','required');
		$objSurname->value =$this->getParam('surname');//set input vbalue
		$table->addCell($surnameLabel->show(), 1500, NULL, 'left');
		$table->addCell($objSurname->show(), 1500, NULL, 'left');
		$table->endRow();
		
		
		$table->startRow();
		//Input and label for Initials
		$objInitials = new textinput ('initials');
		$initialsLabel = new label($initials.'&nbsp;', 'initials');
		$staffRegistration->addRule('initials','Please enter your Initials','required');
		$objInitials->value =$this->getParam('initials');//set input vbalue
		$table->addCell($initialsLabel->show(), 150, NULL, 'left');
		$table->addCell($objInitials->show(), 150, NULL, 'left');
		$table->endRow();
		
		//Input and label for Firtstname
		$table->startRow();
		$objFirstname = new textinput('firstname');
		$objFirstname->value =$this->getParam('firstname');//set input vbalue
		$firsnameLabel = new label($firstname,'firstname');
		$staffRegistration->addRule('firstname','Please enter your First Name','required');
		$table->addCell($firsnameLabel->show(), 150, NULL, 'left');
		$table->addCell($objFirstname->show(), 150, NULL, 'left');
		$table->endRow();
		
		//Input and label for Title
		$table->startRow();
		$objTitle = new dropdown('title');
		$titleLabel = new label($droptitle.'&nbsp;', 'title');
		$titles=array("title_mr", "title_miss", "title_mrs", "title_ms", "title_dr", "title_prof");
		foreach ($titles as $title)
		{
		    $_title=trim($this->objLanguage->languageText($title));
		    $objTitle->addOption($_title,$_title);
		}
		
		$table->addCell($titleLabel->show(), 150, NULL, 'left');
		$table->addCell($objTitle->show(), 150, NULL, 'left');
		$table->endRow();
	
		//Input and label for Ranks
		$table->startRow();
		$objRanks = new textinput ('rank');
		$rankslsLabel = new label($rank.'&nbsp;', 'rank');
		$objRanks->value =$this->getParam('rank');//set input vbalue
		$staffRegistration->addRule('rank','Please enter your Rank','required');
		$table->addCell($rankslsLabel->show(), 150, NULL, 'left');
		$table->addCell($objRanks ->show(), 150, NULL, 'left');
		$table->endRow();
		
		//Input and label for Type of Appointement
		$table->startRow();
		$objAppointment = new dropdown('appointment');
		$appointmentLabel = new label($appointment.'&nbsp;', 'appointment');
		$types=array("Fulltime Permanent", "Fulltime Contract", "Part Time", "Honorary Posistion");
		foreach ($types as $type)
		{
		       $objAppointment->addOption($type,$type);
		}
		$table->addCell($appointmentLabel->show(), 150, NULL, 'left');
		$table->addCell($objAppointment->show(), 150, NULL, 'left');
		$table->endRow();
	
		//Input and label for Department/Scool/Division
		$table->startRow();
		$objDepartment = new textinput ('department');
		$departmentLabel = new label($dept.'&nbsp;', 'department');
		$staffRegistration->addRule('department','Please enter your Department','required');
		$objDepartment->value =$this->getParam('department');//set input vbalue		
		$table->addCell($departmentLabel->show(), 150, NULL, 'left');
		$table->addCell($objDepartment ->show(), 150, NULL, 'left');
		$table->endRow();
		
		//Input and label for Department/Scool/Division
		$table->startRow();
		$objFaculty = new textinput ('faculty');
		$facultyLabel = new label($faculty.'&nbsp;', 'faculty');
		$staffRegistration->addRule('faculty','Please enter your Faculty','required');
		$objFaculty->value =$this->getParam('faculty');//set input vbalue		
		$table->addCell($facultyLabel->show(), 150, NULL, 'left');
		$table->addCell($objFaculty ->show(), 150, NULL, 'left');
		$table->endRow();
		
		//Input and label for Staff Number
		$table->startRow();
		$objStaffNumber = new textinput ('staffNumber');
		$staffNumberLabel = new label($staffNumber.'&nbsp;', 'staffNumber');
		$objStaffNumber->value =$this->getParam('staffNumber');//set input vbalue		
		$table->addCell($staffNumberLabel->show(), 150, NULL, 'left');
		$table->addCell($objStaffNumber->show(), 150, NULL, 'left');
		$table->endRow();

		//Input and label for email
		$table->startRow();
		$objEmail = new textinput ('email');
		$emailLabel = new label($email.'&nbsp;', 'email');
		$staffRegistration->addRule('email','Please enter a valid email','email');
		$objEmail->value =$this->getParam('email');//set input vbalue		
		$table->addCell($emailLabel->show(), 150, NULL, 'left');
		$table->addCell($objEmail->show(), 150, NULL, 'left');
		$table->endRow();

		//Input and label for confirm email
		$table->startRow();
		$objConfirmemail = new textinput ('confirmemail');		
		$objConfirmemailLabel = new label($confirmemail.'&nbsp;', 'confirmemail');
		$staffRegistration->addRule(array('email', 'confirmemail'),'The email addresses you entered do not match', 'compare');
		$table->addCell($objConfirmemailLabel->show(), 150, NULL, 'left');
		$table->addCell($objConfirmemail->show(), 150, NULL, 'left');
		$table->endRow();
		//double line breaks
		$staffRegistration->addToForm("<br /><br />");
		
		//captcha
		$table->startRow();
		$objCaptcha = $this->getObject('captcha', 'utilities');
		$captcha = new textinput('request_captcha');
		$captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'request_captcha');
		
		$table->addCell($captchaLabel ->show(), 150, NULL, 'left');
		$content = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as shown below. If you are unable to view the code, click on "Redraw" for a new one.'));
		$table->addCell($content, 150, NULL, 'left');
		$table->endRow();
		$table->startRow();
		$table->addCell(NULL, 150, NULL, 'left');
		$table->addCell('<div id="captchaDiv">'.$objCaptcha->show().'</div>', 150, NULL, 'left');
		$table->endRow();
		$staffRegistration->addRule('request_captcha', $this->objLanguage->languageText("mod_blogcomments_captchaval",'blogcomments', 'You have not entered the right code'), 'required');
		$table->startRow();
		$table->addCell(NULL, 150, NULL, 'left');
		$table->addCell($captcha->show().'<a href='.$js.'>'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>', 150, NULL, 'left');
		$table->endRow();
		//submit button
		$table->startRow();
		$table->addCell(NULL, 150, NULL, 'left');	
		$button = new button ('submitform', 'Complete Registration');
		$button->setToSubmit();
		$table->addCell($button->show(), 150, NULL, 'left');
		$table->endRow();		
				
		//display table
		$staffRegistration->addToForm($table->show());
		//display form
		return $staffRegistration->show();
	}//end biuldForm
	
	//Method to diplay form
	public function show($mode = 'NULL')
	{	//$mode = $mode;
		return $this->buildForm($mode);
	}//end show
}
?>
