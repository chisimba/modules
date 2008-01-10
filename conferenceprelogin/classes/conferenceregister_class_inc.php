<?php
/**
* conferenceregister class extends object
* @package conferenceprelogin
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Class to provide functionality to register for a conference and on the site.
* An email alert is sent to the conference coordinators (lecturers).
*
* @author Megan Watson
* @copyright (c) 2004-2006 UWC
* @version 0.1
*/

class conferenceregister extends object
{
    /**
    * @var string $module The calling module - for the links
    */
    var $module = 'conferenceprelogin';

    /**
    * Constructor method
    */
    function init()
    {

	$this->dbReg = $this->newObject('dbregconfig');
	$this->dbRegister = $this->newObject('dbregister');

        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->objSqlUser = $this->getObject('sqlusers', 'security');
        $this->objDate = $this->getObject('dateandtime', 'utilities');
	$this->objCountries = $this->getObject('languagecode','language');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');


        $this->loadClass('htmltable', 'htmlelements');
	$this->loadClass('htmlheading','htmlelements');
	$this->loadClass('layer','htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('geticon', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('tabbedbox', 'htmlelements');
    }

    /**
    * Method to display the registration form.
    *
    * @access private
    * @param array config The registration configuration
    * @return
    */
    function showForm($config = NULL)
    {

   if(empty($config)){
            $error = $this->objLanguage->languageText('mod_conference_registrationnotavailable','conferenceprelogin');

            return "<p class = 'error'>".$error.'</p>';
        }

       // Check for start date
        $today =  date('Y-m-d');
        $start = $config['startreg'];
        if($today < $start){
            $start = $this->objDate->formatDate($start);
            $notOpen = $this->objLanguage->code2Txt('mod_conferenceprelogin_registrationstartfrom','conferenceprelogin', array('startdate' => $start));
            return "<p class = 'error'>".$notOpen.'</p>';
        }

        // Check for end date
        $end = $config['endreg'];
        if($today > $end){
            $end = $this->objDate->formatDate($end);
            $notOpen = $this->objLanguage->code2Txt('mod_conferenceprelogin_registrationclosedon','conferenceprelogin', array('enddate' => $end));
           return "<p class = 'error'>".$notOpen.'</p>';
        }


        $userId = rand(0, 999).date('ydi');

        // Text elements
        $hdRegister = $this->objLanguage->languageText('heading_registeryourself','conferenceprelogin');
        $lbRegister = $this->objLanguage->languageText('mod_conferenceprelogin_registermessage','conferenceprelogin');
        $lbTitle = $this->objLanguage->languageText('word_title');
        $lbSelect = $this->objLanguage->languageText('phrase_selectatitle');
        $lbName = $this->objLanguage->languageText('phrase_firstname');
        $lbSurname = $this->objLanguage->languageText('word_surname');
        $lbGender = $this->objLanguage->languageText('word_gender');
        $lbMale = $this->objLanguage->languageText('word_male');
        $lbFemale = $this->objLanguage->languageText('word_female');
        $lbCountry = $this->objLanguage->languageText('word_country');
        $lbPassword = $this->objLanguage->languageText('word_password');
        $lbUsername = $this->objLanguage->languageText('word_username');
        $lbEmail = $this->objLanguage->languageText('phrase_emailaddress');
        $lbConfirm = $this->objLanguage->languageText('phrase_confirmemail');
        $lbPassword = $this->objLanguage->languageText('word_password');
        $lbConfirmPw = $this->objLanguage->languageText('mod_conferenceprelogin_confirmpassword','conferenceprelogin');
        $btnRegister = $this->objLanguage->languageText('mod_conference_register','conferenceprelogin');

        $hdGeneral = $this->objLanguage->languageText('word_general');
        $hdRegFee = $this->objLanguage->languageText('phrase_registrationfees');
        $hdAdditional = $this->objLanguage->languageText('word_additional');

        $lbInitials = $this->objLanguage->languageText('word_initials');
        $lbOrganisation = $this->objLanguage->languageText('word_organisation');
        $lbInstitution = $this->objLanguage->languageText('word_institution');
        $lbBadge = $this->objLanguage->languageText('mod_conference_nameonbadge','conferenceprelogin');
        $lbTel = $this->objLanguage->languageText('word_tel');
        $lbFax = $this->objLanguage->languageText('phrase_faxno');
        $lbW = $this->objLanguage->languageText('mod_conference_w','conferenceprelogin');

        $endEarly = $this->objDate->formatDate($config['endEarlyBird']);
        $lbRegEarly = $this->objLanguage->code2Txt('mod_conference_registerbefore','conferenceprelogin', array('enddate' => $end));

        $lbSelectReg = $this->objLanguage->languageText('mod_conference_selectregfeetype','conferenceprelogin');
        $lbEarly = $this->objLanguage->languageText('mod_conference_earlybirdreg','conferenceprelogin');
        $lbReg = $this->objLanguage->languageText('word_registration');

        $lbRequire = $this->objLanguage->languageText('mod_conference_doyourequire','conferenceprelogin');
        $lbFlights = $this->objLanguage->languageText('word_flights');
        $lbTransfers = $this->objLanguage->languageText('word_transfers');
        $lbCarhire = $this->objLanguage->languageText('phrase_carhire');

        $lbCurrency1 = $this->objLanguage->languageText('mod_conference_'.$config['currency1'],'conferenceprelogin');
        $lbCurrency2 = $this->objLanguage->languageText('mod_conference_'.$config['currency2'],'conferenceprelogin');

        // Error messages
        $errTitle = $this->objLanguage->languageText('mod_conferenceprelogin_titlenotvalidoption','conferenceprelogin');
        $errName = $this->objLanguage->languageText('phrase_enterfirstname');
        $errSurname = $this->objLanguage->languageText('phrase_entersurname');
        $errUserName = $this->objLanguage->languageText('phrase_entervalidusername');
        $errEmail = $this->objLanguage->languageText('phrase_entervalidemail');
        $errIllName = $this->objLanguage->languageText('mod_conferenceprelogin_noillegalcharsname','conferenceprelogin');
        $errIllSurname = $this->objLanguage->languageText('mod_conferenceprelogin_noillegalcharssurname','conferenceprelogin');
        $errValidEmail = $this->objLanguage->languageText('instruction_entervalidemail');
        $errSameEmail = $this->objLanguage->languageText('phrase_emailnotsame');
        $errSamePasswd = $this->objLanguage->languageText('mod_conferenceprelogin_passwordnotsame','conferenceprelogin');
        $errPasswd = $this->objLanguage->languageText('mod_conferenceprelogin_enterpassword','conferenceprelogin');
        $errConPasswd = $this->objLanguage->languageText('mod_conferenceprelogin_confirmpassword','conferenceprelogin');

        $head = '<p>'.$lbRegister.'</p>';

        /* *** Set up table and form inputs *** */

        $objTable = new htmltable();
        $objTable->cellpadding = 5;
        $objTable->row_attributes = 'height = "45"';

        // title
        $objLabel = new label('<b>'.$lbTitle.':</b> ', 'input_title');
        $objDrop = new dropdown('title');
        $objDrop->addOption('', $lbSelect);

        $titles = array('title_mr', 'title_miss', 'title_mrs', 'title_ms', 'title_dr', 'title_prof', 'title_rev', 'title_assocprof');
        foreach($titles as $item){
            $option = $this->objLanguage->languageText($item);
            $objDrop->addOption($option, $option);
        }

        $row1[] = $objLabel->show();
        $row1[] = $objDrop->show();

        // initials
        $objLabel = new label('<b>'.$lbInitials.':</b> ', 'input_initials');
        $objInput = new textinput('initials', '', '', '5');

        $row1[] = $objLabel->show();
        $row1[] = $objInput->show();
        $objTable->addRow($row1, 'odd');

        // name
        $objLabel = new label('<b>'.$lbName.':</b> ', 'input_firstname');
        $objInput = new textinput('firstname', '','','40');

        $row2[] = $objLabel->show();
        $row2[] = $objInput->show();

        // surname
        $objLabel = new label('<b>'.$lbSurname.':</b> ', 'input_surname');
        $objInput = new textinput('surname', '','','40');

        $row2[] = $objLabel->show();
        $row2[] = $objInput->show();
        $objTable->addRow($row2, 'even');

        // username
        $objLabel = new label('<b>'.$lbUsername.':</b> ', 'input_username');
        $objInput = new textinput('username', '','','40');
        $objInput->extra = 'id1="username"';

	$objLayer = new layer();
        $objLayer->str = '';
        $objLayer->id = 'usernameDiv';

        $row3[] = $objLabel->show();
        $row3[] = $objInput->show().$objLayer->show();

        // gender
        $objLabel = new label('<b>'.$lbGender.':</b> ', 'input_gender');
        $objRadio = new radio('gender');
        $objRadio->addOption('M', $lbMale);
        $objRadio->addOption('F', $lbFemale);
        $objRadio->setSelected('M');

        $row3[] = $objLabel->show();
        $row3[] = $objRadio->show();
        $objTable->addRow($row3, 'odd');

        // organisation
        if($config['useorganisation'] == 1){
            $objLabel = new label('<b>'.$lbOrganisation.' / '.$lbInstitution.':</b> ', 'input_organisation');
            $objInput = new textinput('organisation', '','','40');

            $row3a[] = $objLabel->show();
            $row3a[] = $objInput->show();
        }

        // name badge
        if($config['usenamebadge'] == 1){
            $objLabel = new label('<b>'.$lbBadge.':</b> ', 'input_badge');
            $objInput = new textinput('badge', '','','40');

            $row3a[] = $objLabel->show();
            $row3a[] = $objInput->show();
        }
        $objTable->addRow($row3a, 'even');



        //country
        $objLabel = new label('<b>'.$lbCountry.':</b> ', 'input_country');

	$objTable->startRow();
        $objTable->addCell($objLabel->show(), '','','','odd');
	$objTable->addCell($this->objCountries->countryAlpha($this->getParam('country')),'','','','odd');
	$objTable->endRow();

        // tel
        $objLabel = new label('<b>'.$lbTel.' ('.$lbW.'):</b> ', 'input_tel');
        $objInput = new textinput('tel');

        $row4a[] = $objLabel->show();
        $row4a[] = $objInput->show();

        // fax
        $objLabel = new label('<b>'.$lbFax.':</b> ', 'input_fax');
        $objInput = new textinput('fax');

        $row4a[] = $objLabel->show();
        $row4a[] = $objInput->show();
        $objTable->addRow($row4a, 'even');

        // email
        $objLabel = new label('<b>'.$lbEmail.':</b> ', 'input_email');
        $objInput = new textinput('email', '','','40');

        $row5[] = $objLabel->show();
        $row5[] = $objInput->show();

        // confirm
        $objLabel = new label('<b>'.$lbConfirm.':</b> ', 'input_email2');
        $objInput = new textinput('email2', '','','40');

        $row5[] = $objLabel->show();
        $row5[] = $objInput->show();
        $objTable->addRow($row5, 'odd');

        // password
        $objLabel = new label('<b>'.$lbPassword.':</b> ', 'input_password');
        $objInput = new textinput('password', '', 'password', '40');

        $row6[] = $objLabel->show();
        $row6[] = $objInput->show();

        // confirm
        $objLabel = new label('<b>'.$lbConfirmPw.':</b> ', 'input_password 2');
        $objInput = new textinput('password2', '', 'password', '40');

        $row6[] = $objLabel->show();
        $row6[] = $objInput->show();
        $objTable->addRow($row6, 'even');


        // Add general details to tabbed box
        $objTab = new tabbedbox();
        $objTab->addTabLabel($hdGeneral);
        $objTab->addBoxContent($objTable->show());
        $genTab = $objTab->show();

        /* *** Registration *** */

        $objTable = new htmltable();
        $objTable->cellpadding = 5;
        $objTable->row_attributes = 'height = "45"';

        $objTable->startRow();
        $objTable->addCell('<b>'.$lbSelectReg.':</b>', '50%','','','odd');
        $objTable->addCell('', '','','','odd','colspan="2"');
	$objTable->endRow();



        $objRadio = new radio('regType');
        $objRadio->addOption('earlybird', '<b>&nbsp;&nbsp;'.$lbEarly.':</b> ('.$lbRegEarly.')');


        $row7[] = $objRadio->show();
        $row7[] = $lbCurrency1 .' '. $config['earlybirdfee'];
        $row7[] = $lbCurrency2 .' '. $config['earlybirdforeign'];
        $objTable->addRow($row7, 'even');

        $objRadio = new radio('regType');
        $objRadio->addOption('registration', '<b>&nbsp;&nbsp;'.$lbReg.':</b>');
        $objRadio->setSelected('registration');

        $row8[] = $objRadio->show();
        $row8[] = $lbCurrency1 .' '. $config['regfee'];
        $row8[] = $lbCurrency2 .' '. $config['regfeeforeign'];
        $objTable->addRow($row8, 'odd');


        // Add registration details to tabbed box
        $objTab = new tabbedbox();
        $objTab->addTabLabel($hdRegFee);
	$objTab->addBoxContent($objTable->show());
        $regTab = $objTab->show();
        //echo $objTable->show();

	/* *** Bank details *** */


	  $bankTab = $this->showBankDetails($config);

        /* ** Additional requirements ** */

        if($config['useflights'] == 1 || $config['usetransfers'] == 1 || $config['usecarhire'] == 1){

	    $objTable = new htmltable();
            $objTable->cellpadding = 5;
            $objTable->row_attributes = 'height = "45"';

            $objTable->startRow();
            $objTable->addCell('<b>'.$lbRequire.':</b>', '', '','','odd','colspan="2"');
            $objTable->endRow();

            if($config['useflights'] == 1){
                $objCheck = new checkbox('transfers[]');
                $objCheck->setValue('flights');

                $objTable->addRow(array($objCheck->show(), '<b>'.$lbFlights.'</b>'), 'even');
            }

            if($config['usetransfers'] == 1){
                $objCheck = new checkbox('transfers[]');
                $objCheck->setValue('transfers');

                $objTable->addRow(array($objCheck->show(), '<b>'.$lbTransfers.'</b>'), 'odd');
            }

            if($config['usecarhire'] == 1){
                $objCheck = new checkbox('transfers[]');
                $objCheck->setValue('carhire');

                $objTable->addRow(array($objCheck->show(), '<b>'.$lbCarhire.'</b>'), 'even');
            }
		$objButton->align = 'center';
            $objTable->row_attributes = 'height ="1"';
            $objTable->startRow();
            $objTable->addCell('', '5%', '','','');
            $objTable->addCell('', '','','','');
            $objTable->endRow();

            // Add additional - requirements

	    $objTab = new tabbedbox();
	    $objTab->addTabLabel($hdAdditional);
            $objTab->addBoxContent($objTable->show());
	    $addTab = $objTab->show();



	}

	// submit button

	$objButton = new button('save', $btnRegister);
    	$objButton->setToSubmit();


	$btn = '<center>'.$objButton->show().'</center>';


        // hidden fields - userId
        $objInput = new textinput('userId', $userId, 'hidden');
        $hidden = $objInput->show();

        /* *** Set up form and display *** */

        $url = $this->uri(array('action'=>'saveregister', 'mode' => 'register'));
        $objForm = new form('Form1', $url);
        $objForm->extra = 'autocomplete="off"';
        $objForm->addToForm($genTab.$regTab.$bankTab.$addTab.$btn.$hidden);

        // Form rules
        $objForm->addRule('title', $errTitle, 'required');
        $objForm->addRule('firstname', $errName, 'required');
        $objForm->addRule('firstname', $errIllName, 'nolillegalchars');
        $objForm->addRule('surname', $errSurname, 'required');
        $objForm->addRule('surname', $errIllSurname, 'nolillegalchars');
        $objForm->addRule('username', $errUserName, 'required');
        $objForm->addRule('email', $errEmail, 'required');
        $objForm->addRule('email', $errValidEmail, 'email');
        $objForm->addRule('email2', $errEmail, 'required');
        $objForm->addRule('email2', $errValidEmail, 'email');
        $objForm->addRule(array('email', 'email2'), $errSameEmail, 'compare');
        $objForm->addRule('password', $errPasswd, 'required');
        $objForm->addRule('password2', $errConPasswd, 'required');
        $objForm->addRule(array('password', 'password2'), $errSamePasswd, 'compare');

   return $head.$objForm->show();
    }

    /**
    * Display the banking details
    *
    * @access private
    * @param array $config The configuration details containing the bank details
    * @return string html
    */
    function showBankDetails($config)
    {
        $hdBankDetails = $this->objLanguage->languageText('phrase_bankingdetails');
        $lbHolder = $this->objLanguage->languageText('phrase_accountholder');
        $lbBank = $this->objLanguage->languageText('word_bank');
        $lbAccNum = $this->objLanguage->languageText('phrase_accountnumber');
        $lbBranch = $this->objLanguage->languageText('word_branch');
        $lbCode = $this->objLanguage->languageText('phrase_branchcode');
        $lbSwift = $this->objLanguage->languageText('phrase_swiftcode');

        $objTable = new htmltable();
        $objTable->cellpadding = 5;
        $objTable->row_attributes = 'height = "45"';


        $row9[] = '<b>'.$lbHolder.':</b>';
        $row9[] = $config['accountname'];
        $row9[] = '<b>'.$lbAccNum.':</b>';
        $row9[] = $config['accountnum'];
        $objTable->addRow($row9, 'odd');

        $row11[] = '<b>'.$lbBank.':</b>';
        $row11[] = $config['bank'];
        $row11[] = '<b>'.$lbBranch.':</b>';
        $row11[] = $config['branch'];
        $objTable->addRow($row11, 'even');

        $row12[] = '<b>'.$lbCode.':</b>';
        $row12[] = $config['branchcode'];
        $row12[] = '<b>'.$lbSwift.':</b>';
        $row12[] = $config['swiftcode'];
        $objTable->addRow($row12, 'odd');

        // Add bank details to tabbed box
        $objTab = new tabbedbox();
        $objTab->addTabLabel($hdBankDetails);
        $objTab->addBoxContent($objTable->show());
        $bankTab = $objTab->show();

        return $bankTab;
    }

    /**
    * Method to register the user on the site.
    *
    * @access private
    * @param array $info The users information as entered on the registration form
    * @param $invoice The invoice as an attachment
    * @return
    */
    function register($info, $invoice)
    {
        $userId = $info['userId'];

        // Create the user account
        $id = $this->objSqlUser->addUser($info);

        // Save additional
        $this->dbRegister->addDetails($userId);
	return $id;
	die();
        // Email the user
        $emailArr = $info;
        $emailArr['fullname'] = $info['firstName'].' '.$info['surname'];
        $emailArr['sitename'] = $this->objConfig->getinstitutionName();
        $emailArr['siteurl'] = $this->uri(array('action' => 'submissions'), '');
        $emailArr['shortsitename'] = $this->objConfig->getinstitutionShortName();
        $fromEmail = $this->objConfig->getsiteEmail();
        $fromUser = $emailArr['sitename'];

        $body = $this->objLanguage->code2Txt('mod_conferenceprelogin_acccreationconfirmemail', $emailArr);
        $subject = $this->objLanguage->code2Txt('mod_conferenceprelogin_acccreationconfirmsubject', array('sitename' => $emailArr['sitename']));
        $this->sendEmail($emailArr['fullname'], $emailArr['emailaddress'], $subject, $body, '');

        //Log in - create the users session - for later if required
	//$this->objUser->_record = $info;
	//$this->objUser->storeInSession();

        return $id;
    }
/*******************************************************************************************
    * Method to send an email via the kng mailer.
    *
    * @access public
    * @param string $name The recipients name.
    * @param string $subject The subject line of the email.
    * @param string $email The recipients email address.
    * @param string $body The content of the email.
    * @return
*********************************************************************************************/

    function sendEmail($name, $email, $subject, $body, $attachment = NULL)
    {
        $fromEmail = $this->objConfig->getsiteEmail();/****************changed**************/
        $fromUser = $this->objConfig->getsiteName();/****************changed**************/

        $this->objMailer->setup($fromEmail, $fromUser);
        $bool = $this->objMailer->sendMail($name, $subject, $email, $body, TRUE, $attachment);
        if($bool){
            return TRUE;
        }
        return FALSE;
    }

/********************************************************************************************
	* Method to set the calling module
	* Default module is the administration module
	*
	* @access public
	* @param string $module The calling module
	* @return
*********************************************************************************************/
	function setModuleName($module)
	{
	    $this->module = $module;
	}




    /**
    * Method to display a confirmation message of registration
    *
    * @access private
    * @return
    */
    function showConfirmation()
    {
        $userId = $this->getSession('userId');
        $code = $this->getSession('currentconference');
      //  $conference = $this->objDBContext->getTitle($code);
        if(!empty($userId)){
            $fullname = $this->objUser->fullName($userId);
            $array = array('conference' => $conference, 'fullname' => $fullname);
            $message = $this->objLanguage->code2Txt('mod_conferenceprelogin_confirmregistration','conferenceprelogin', $array);
        }else{
            $array = array('conference' => $conference);
            $message = $this->objLanguage->code2Txt('mod_conferenceprelogin_problemregistration','conferenceprelogin', $array);
        }

      	$objLayer = new layer();
	$objHead = new htmlheading();
	//$objLabel = new label('Login: ','');
	//echo $objLabel->show();

        $objHead->str = $message;
        $objHead->type = 2;
        $objLayer->str = $objHead->show();
        $objLayer->cssClass = 'confirm';
        $objLayer->padding = '2px';


	return $objLayer->show();


    }

    /**
    * Method to display the form for configuring the registration form
    *
    * @access private
    * @param string $data The current configuration
    * @return string html
    */
    function configureForm($data = NULL)
    {
        $head = $this->objLanguage->languageText('mod_conference_configurereg','conferenceprelogin');
        $include = $this->objLanguage->languageText('mod_conference_includethefollowing','conferenceprelogin');
        $regfees = $this->objLanguage->languageText('phrase_registrationfees');
        $bankdetails = $this->objLanguage->languageText('phrase_bankingdetails');
        $lbReg = $this->objLanguage->languageText('word_registration');
        $lbHolder = $this->objLanguage->languageText('phrase_accountholder');
        $lbNum = $this->objLanguage->languageText('phrase_accountnumber');
        $lbCode = $this->objLanguage->languageText('phrase_branchcode');
        $lbSwift = $this->objLanguage->languageText('phrase_swiftcode');
        $lbBank = $this->objLanguage->languageText('word_bank');
        $lbBranch = $this->objLanguage->languageText('word_branch');
        $lbOrganisation = $this->objLanguage->languageText('word_organisation');
        $lbBadge = $this->objLanguage->languageText('mod_conference_nameonbadge','conferenceprelogin');
        $lbEarly = $this->objLanguage->languageText('mod_conference_earlybirdreg','conferenceprelogin');
        $lbYes = $this->objLanguage->languageText('word_yes');
        $lbNo = $this->objLanguage->languageText('word_no');
        $lbRand = $this->objLanguage->languageText('word_rand');
        $lbDollar = $this->objLanguage->languageText('phrase_usdollar');
        $lbEuro = $this->objLanguage->languageText('word_euro');
        $btnSave = $this->objLanguage->languageText('word_save');
        $btnCancel = $this->objLanguage->languageText('word_cancel');
        $lbStart = $this->objLanguage->languageText('mod_conference_startdatereg','conferenceprelogin');
        $lbEndEarly = $this->objLanguage->languageText('mod_conference_enddateearlybirdreg','conferenceprelogin');
        $lbEnd = $this->objLanguage->languageText('mod_conference_enddatereg','conferenceprelogin');
        $lbFlights = $this->objLanguage->languageText('word_flights');
        $lbTransfers = $this->objLanguage->languageText('word_transfers');
        $lbCarhire = $this->objLanguage->languageText('phrase_carhire');

	$objHead = new htmlheading();

        $objHead->str = $head;
        $objHead->type = '1';
        $str = $this->objHead->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '5';
        $objTable->cellspacing = '2';

        // Registration dates
        $start = isset($data['startReg']) ? $data['startReg'] : date('Y-m-d');
        $url = $this->uri(array('action'=>'ajaxcal', 'field'=>'document.configure.start', 'fieldvalue'=>$start, 'showtime'=>'no'), 'popupcalendar');
        $onclick = "javascript:window.open('" .$url."', 'popupcal', 'width=320, height=410, scrollbars=1, resize=yes')";

        $this->objIcon->setIcon('select_date');
        $objLink = new link('#');
        $objLink->extra = "onclick=\"$onclick\"";
        $objLink->link = $this->objIcon->show();
        $dateIcon = $objLink->show();

        $objLabel = new label($lbStart.': ', 'input_start');
        $objInput = new textinput('start', $start);
        $objInput->extra = 'READONLY';

        $objTable->addRow(array($objLabel->show(), $objInput->show().'&nbsp;'.$dateIcon));

        $endEarly = isset($data['endEarlyBird']) ? $data['endEarlyBird'] : date('Y-m-d');
        $url = $this->uri(array('action'=>'ajaxcal', 'field'=>'document.configure.endearly', 'fieldvalue'=>$endEarly, 'showtime'=>'no'), 'popupcalendar');
        $onclick = "javascript:window.open('" .$url."', 'popupcal', 'width=320, height=410, scrollbars=1, resize=yes')";

        $this->objIcon->setIcon('select_date');
        $objLink = new link('#');
        $objLink->extra = "onclick=\"$onclick\"";
        $objLink->link = $this->objIcon->show();
        $dateIcon = $objLink->show();

        $objLabel = new label($lbEndEarly.': ', 'input_endearly');
        $objInput = new textinput('endearly', $endEarly);
        $objInput->extra = 'READONLY';

        $objTable->addRow(array($objLabel->show(), $objInput->show().'&nbsp;'.$dateIcon));

        $end = isset($data['endReg']) ? $data['endReg'] : date('Y-m-d');
        $url = $this->uri(array('action'=>'ajaxcal', 'field'=>'document.configure.end', 'fieldvalue'=>$end, 'showtime'=>'no'), 'popupcalendar');
        $onclick = "javascript:window.open('" .$url."', 'popupcal', 'width=320, height=410, scrollbars=1, resize=yes')";

        $this->objIcon->setIcon('select_date');
        $objLink = new link('#');
        $objLink->extra = "onclick=\"$onclick\"";
        $objLink->link = $this->objIcon->show();
        $dateIcon = $objLink->show();

        $objLabel = new label($lbEnd.': ', 'input_end');
        $objInput = new textinput('end', $end);
        $objInput->extra = 'READONLY';

        $objTable->addRow(array($objLabel->show(), $objInput->show().'&nbsp;'.$dateIcon));

        // Additional items

        $objTable->startRow();
        $objTable->addCell('<b>'.$include.':</b> ', '20%');
        $objTable->addCell('');
        $objTable->endRow();

        // general setting - organisation, badge, flights, transfers, carhire
        $objLabel = new label($lbOrganisation.': ', 'input_organisation');
        $objRadio = new radio('organisation');
        $objRadio->addOption('1', $lbYes);
        $objRadio->addOption('0', $lbNo);
        $selected = isset($data['useOrganisation']) ? $data['useOrganisation'] : '1';
        $objRadio->setSelected($selected);

        $objTable->addRow(array($objLabel->show(), $objRadio->show()));

        $objLabel = new label($lbBadge.': ', 'input_badge');
        $objRadio = new radio('badge');
        $objRadio->addOption('1', $lbYes);
        $objRadio->addOption('0', $lbNo);
        $selected = isset($data['useNameBadge']) ? $data['useNameBadge'] : '1';
        $objRadio->setSelected($selected);

        $objTable->addRow(array($objLabel->show(), $objRadio->show()));

        $objLabel = new label($lbFlights.': ', 'input_flights');
        $objRadio = new radio('flights');
        $objRadio->addOption('1', $lbYes);
        $objRadio->addOption('0', $lbNo);
        $selected = isset($data['useFlights']) ? $data['useFlights'] : '1';
        $objRadio->setSelected($selected);

        $objTable->addRow(array($objLabel->show(), $objRadio->show()));

        $objLabel = new label($lbTransfers.': ', 'input_transfers');
        $objRadio = new radio('transfers');
        $objRadio->addOption('1', $lbYes);
        $objRadio->addOption('0', $lbNo);
        $selected = isset($data['useTransfers']) ? $data['useTransfers'] : '1';
        $objRadio->setSelected($selected);

        $objTable->addRow(array($objLabel->show(), $objRadio->show()));

        $objLabel = new label($lbCarhire.': ', 'input_carhire');
        $objRadio = new radio('carhire');
        $objRadio->addOption('1', $lbYes);
        $objRadio->addOption('0', $lbNo);
        $selected = isset($data['useCarhire']) ? $data['useCarhire'] : '1';
        $objRadio->setSelected($selected);

        $objTable->addRow(array($objLabel->show(), $objRadio->show()));

        // payment - early bird, reg fees; currency and amounts
        $objTable->addRow(array('<b>'.$regfees.':</b> '));

        $earlybird = isset($data['earlyBirdFee']) ? $data['earlyBirdFee'] : '0';
        $earlyBirdForeign = isset($data['earlyBirdForeign']) ? $data['earlyBirdForeign'] : '0';
        $regfee = isset($data['regFee']) ? $data['regFee'] : '0';
        $regfeeforeign = isset($data['regFeeForeign']) ? $data['regFeeForeign'] : '0';
        $currency1 = isset($data['currency1']) ? $data['currency1'] : 'rand';
        $currency2 = isset($data['currency2']) ? $data['currency2'] : 'usdollar';

        $objLabel = new label($lbEarly.': ', 'input_earlybird');
        $objInput = new textinput('earlybird', $earlybird,'','20');
        $objDrop = new dropdown('currency1');
        $objDrop->extra = "onclick=\"javascript: document.configure.currency1a.value = document.configure.currency1.value;\"";
        $objDrop->addOption('rand', $lbRand);
        $objDrop->addOption('usdollar', $lbDollar);
        $objDrop->addOption('euro', $lbEuro);
        $objDrop->setSelected($currency1);

        $objTable->addRow(array($objLabel->show(), $objInput->show().'&nbsp;&nbsp;'.$objDrop->show()));

        $objInput = new textinput('earlybirdforeign', $earlyBirdForeign,'','20');
        $objDrop = new dropdown('currency2');
        $objDrop->extra = "onclick=\"javascript: document.configure.currency2a.value = document.configure.currency2.value;\"";
        $objDrop->addOption('rand', $lbRand);
        $objDrop->addOption('usdollar', $lbDollar);
        $objDrop->addOption('euro', $lbEuro);
        $objDrop->setSelected($currency2);
        $objTable->addRow(array('', $objInput->show().'&nbsp;&nbsp;'.$objDrop->show()));

        $objLabel = new label($lbReg.': ', 'input_regfee');
        $objInput = new textinput('regfee', $regfee,'','20');
        $objDrop = new dropdown('currency1a');
        $objDrop->extra = "onclick=\"javascript: document.configure.currency1.value = document.configure.currency1a.value;\"";
        $objDrop->addOption('rand', $lbRand);
        $objDrop->addOption('usdollar', $lbDollar);
        $objDrop->addOption('euro', $lbEuro);
        $objDrop->setSelected($currency1);

        $objTable->addRow(array($objLabel->show(), $objInput->show().'&nbsp;&nbsp;'.$objDrop->show()));

        $objInput = new textinput('regfeeforeign', $regfeeforeign,'','20');
        $objDrop = new dropdown('currency2a');
        $objDrop->extra = "onclick=\"javascript: document.configure.currency2.value = document.configure.currency2a.value;\"";
        $objDrop->addOption('rand', $lbRand);
        $objDrop->addOption('usdollar', $lbDollar);
        $objDrop->addOption('euro', $lbEuro);
        $objDrop->setSelected($currency2);
        $objTable->addRow(array('', $objInput->show().'&nbsp;&nbsp;'.$objDrop->show()));

        // bank details
        $objTable->addRow(array('<b>'.$bankdetails.':</b> '));

        $holder = isset($data['accountName']) ? $data['accountName'] : '';
        $number = isset($data['accountNum']) ? $data['accountNum'] : '';
        $bank = isset($data['bank']) ? $data['bank'] : '';
        $branch = isset($data['branch']) ? $data['branch'] : '';
        $code = isset($data['branchCode']) ? $data['branchCode'] : '';
        $swift = isset($data['swiftCode']) ? $data['swiftCode'] : '';

        $objLabel = new label($lbHolder.': ', 'input_holder');
        $objInput = new textinput('holder', $holder,'','50');

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $objLabel = new label($lbNum.': ', 'input_number');
        $objInput = new textinput('number', $number,'','50');

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $objLabel = new label($lbBank.': ', 'input_bank');
        $objInput = new textinput('bank', $bank,'','50');

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $objLabel = new label($lbBranch.': ', 'input_branch');
        $objInput = new textinput('branch', $branch,'','50');

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $objLabel = new label($lbCode.': ', 'input_code');
        $objInput = new textinput('code', $code,'','20');

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $objLabel = new label($lbSwift.': ', 'input_swift');
        $objInput = new textinput('swift', $swift,'','20');

        $objTable->addRow(array($objLabel->show(), $objInput->show()));

        $hidden = '';
        if(isset($data['id'])){
            $objInput = new textinput('id', $data['id'], 'hidden');
            $hidden = $objInput->show();
        }

        $objButton = new button('save', $btnSave);
        $objButton->setToSubmit();
        $btns = $objButton->show().'&nbsp;&nbsp;';

        $objButton = new button('cancel', $btnCancel);
        $objButton->setToSubmit();
        $btns .= $objButton->show();

        $objTable->addRow(array($hidden, $btns));

        $objForm = new form('configure', $this->uri(array('action' => 'saveconfig'), $this->module));
        $objForm->addToForm($objTable->show());
        $str .= $objForm->show();

        return $str;
    }

    /**
    * Create an invoice for the new user
    *
    * @access private
    * @param array $info The user info as captured during registration
    * @return void
    */
    function createInvoice($info)
    {
	$head = $this->objLanguage->languageText('word_invoice');
        $lbDate = strtoupper($this->objLanguage->languageText('word_date'));
        $lbTo = strtoupper($this->objLanguage->languageText('word_to'));
        $lbFrom = strtoupper($this->objLanguage->languageText('word_from'));
        $lbEmail = $this->objLanguage->languageText('word_email');
        $lbSubject = strtoupper($this->objLanguage->languageText('word_subject'));
        $lbAttention = strtoupper($this->objLanguage->languageText('word_attention'));
        $lbItem = $this->objLanguage->languageText('word_item');
        $lbAmount = $this->objLanguage->languageText('word_amount');
        $lbTotal = $this->objLanguage->languageText('word_total');
        $lbPayable = $this->objLanguage->languageText('mod_conference_tobepaidonbefore','conferenceprelogin');

        $institutionName = $this->objConfig->getinstitutionName();
        $siteEmail = $this->objConfig->getsiteEmail();
        $config = $this->dbReg->getConfig();

	$objHead = new htmlheading();
        $objHead->str = $head;
        $objHead->type = 1;
        $str = $objHead->show();// $str = $this->objHead->show();

        // To / From details
        $objTable = new htmltable();
        $objTable->cellpadding = '5';

        $objTable->addRow(array($lbDate.': ', date('Y-m-d')));
        $objTable->addRow(array('&nbsp;'));
        $objTable->addRow(array($lbTo.': ', $info['firstName'].' '.$info['surname']));
        $objTable->addRow(array('', $lbEmail.': '. $info['emailAddress']));
        $objTable->addRow(array('&nbsp;'));
        $objTable->addRow(array($lbFrom.': ', $institutionName));
        $objTable->addRow(array('', $lbEmail.': '. $siteEmail));
        $objTable->addRow(array('&nbsp;'));
        $objTable->addRow(array($lbSubject.': ', $head.' 01'));
        $objTable->addRow(array('&nbsp;'));
        $objTable->addRow(array($lbAttention.': ', $info['firstName'].' '.$info['surname']));
        $objTable->startRow();
        $objTable->addCell('&nbsp;', '20%');
        $objTable->addCell('');
        $objTable->endRow();

        $str .= '<p>'.$objTable->show().'</p>';

        // Payment details
        $regType = $this->getParam('regType');
        if($regType == 'earlybird'){
            $type = $this->objLanguage->languageText('mod_conference_earlybirdreg','conferenceprelogin');
            $amount1 = $this->objLanguage->languageText('mod_conference_'.$config['currency1'],'conferenceprelogin');
            $amount1 .= $config['earlybirdfee'];


            $amount2 = $this->objLanguage->languageText('mod_conference_'.$config['currency2'],'conferenceprelogin');
            $amount2 .= $config['earlybirdforeign'];
            $date = $this->objDate->formatDate($config['endearlybird']);
        }else{
            $type = $this->objLanguage->languageText('word_registration');
            $amount1 = $this->objLanguage->languageText('mod_conference_'.$config['currency1'],'conferenceprelogin');
            $amount1 .= $config['regfee'];

            $amount2 = $this->objLanguage->languageText('mod_conference_'.$config['currency2'],'conferenceprelogin');
            $amount2 .= $config['regfeeforeign'];
            $date = $this->objDate->formatDate($config['endreg']);
        }

        $objTable = new htmltable();
        $objTable->cellpadding = '5';
        $objTable->border = '1';

        $objTable->row_attributes = " align='center'";
        $objTable->startRow();
        $objTable->addCell('<b>'.$lbItem.'</b>');
        $objTable->addCell('<b>'.$lbAmount.'</b>', '','','','','colspan=2');
        $objTable->endRow();

        $objTable->row_attributes = " height='40'";
        $objTable->addRow(array($type, $amount1, $amount2));
        $objTable->addRow(array('<b>'.$lbTotal.'</b>', $amount1, $amount2));

        $str .= '<p>'.$objTable->show().'</p>';

        $str .= '<p><b>'.$lbPayable.':</b>&nbsp;&nbsp;'.$date.'</p>';

        // Bank details
        $str .= '<p>'.$this->showBankDetails($config).'</p>';

        return $str;
    }

    /**
    * Get the users information from the registration form
    *
    * @access private
    * @param string $userId The created user id
    * @return array $info
    */
    function getUserInfo($userId)
    {
	$info = array();
        $info['userId'] = $userId;
	$info['username'] = $this->getParam('username');
        $info['title'] = $this->getParam('title');
        $info['firstName'] = $this->getParam('firstname');
        $info['surname'] = $this->getParam('surname');
        $info['country'] = $this->getParam('country');
        $info['emailAddress'] = $this->getParam('email');
        $info['sex'] = $this->getParam('gender');
        $info['howCreated'] = 'selfregister';
        $info['password'] = $this->getParam('password');

        return $info;

    }




    /**
    * Method to display the appropriate step
    *
    * @access public
    * @param string $mode The appropriate step
    * @return string html
    */
    function show($mode = NULL)
    {
        switch($mode){
            case 'configure':
                $data = $this->dbReg->getConfig();
                return $this->configureForm($data);
                break;

            case 'saveregconfig':
                $id = $this->getParam('id');
                return $this->dbReg->addConfig($id);

            case 'register':

                $userId = $this->getParam('userId');
                $info = $this->getUserInfo($userId);
		$id = $this->register($info, $invoice);
                if(!empty($id)){
                    $this->setSession('userId', $userId);
                //$this->addToConference($id);
                }
                return '';
		break;

            case 'confirm':
		return $this->showConfirmation();

	    default:
                $config = $this->dbReg->getConfig();
                return $this->showForm($config);
        }
    }
}
?>
