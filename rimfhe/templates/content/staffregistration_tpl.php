<script type="text/javascript">
//<![CDATA[
function init () {
    $('input_redraw').onclick = function () {
        redraw();
    }
}
function redraw () {
    var url = 'index.php';
    var pars = 'module=security&action=generatenewcaptcha';
    var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showResponse} );
}
function showLoad () {
    $('load').style.display = 'block';
}
function showResponse (originalRequest) {
    var newData = originalRequest.responseText;
    $('captchaDiv').innerHTML = newData;
}
//]]>
</script>
<?php
if(!isset($mode)){
    $mode = '';
}
$this->loadClass('htmlheading', 'htmlelements');
$pageHeading = new htmlheading();
$pageHeading->type = 2;
$pageHeading->str = $this->objLanguage->languageText('mod_rimfhe_pageheading', 'rimfhe', 'Registration for Staff Members');
echo '<br />'.$pageHeading->show();


$header = new htmlheading();
$header->type = 4;
$header->str = $this->objLanguage->languageText('mod_staffregistration_forminstruction', 'rimfhe');

$header2 = $this->objLanguage->languageText('mod_rimfhe_required', 'rimfhe');
if($mode!='fixerror'){
    echo '<br />'.$header->show();
    echo '<br /><span style="color:red;font-size:12px;">'.$header2.'</span>';
}
//load the required form elements
$this->formElements->sendElements();
//create the form
$staffRegistration = new form ('register', $this->uri(array('action'=>'registerstaff'), 'rimfhe'));

/* ---------------------- Form Elements--------*/
//assign laguage objects to variables
$surname = $this->objLanguage->languageText('word_surname', 'system');
$initials= $this->objLanguage->languageText('mod_rimfhe_initials', 'rimfhe');
$firstname= $this->objLanguage->languageText('phrase_firstname', 'system');
$droptitle= $this->objLanguage->languageText('word_title', 'system');
$rank= $this->objLanguage->languageText('mod_rimfhe_rank', 'rimfhe');
$appointment= $this->objLanguage->languageText('mod_rimfhe_appointment', 'rimfhe');
$dept= $this->objLanguage->languageText('mod_rimfhe_department', 'rimfhe');
$faculty= $this->objLanguage->languageText('mod_rimfhe_faculty', 'rimfhe');
$staffNumber= $this->objLanguage->languageText('phrase_staffnumber', 'system');
$email= $this->objLanguage->languageText('phrase_emailaddress', 'system');
$confirmemail= $this->objLanguage->languageText('phrase_confirmemail', 'system');

//create table
$table =new htmltable('registration');
$table->width ='60%';
$table->startRow();
//Input and label for Surname
$objSurname = new textinput('surname');
$surnameLabel = new label($surname,'surname');
$staffRegistration->addRule('surname','Please enter your Surname','required');
if($mode == 'fixerror'){
    $objSurname->value =$this->getParam('surname');

}
$table->addCell($surnameLabel->show(), 1500, NULL, 'left');
$table->addCell($objSurname->show(), 1500, NULL, 'left');
$table->endRow();


$table->startRow();
//Input and label for Initials
$objInitials = new textinput ('initials');
$initialsLabel = new label($initials.'&nbsp;', 'initials');
$staffRegistration->addRule('initials','Please enter your Initials','required');
if($mode == 'fixerror'){
    $objInitials->value =$this->getParam('initials');
}
$table->addCell($initialsLabel->show(), 150, NULL, 'left');
$table->addCell($objInitials->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Firtstname
$table->startRow();
$objFirstname = new textinput('firstname');
if($mode == 'fixerror'){
    $objFirstname->value =$this->getParam('firstname');//set input vbalue
}
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
    if($mode == 'fixerror'){
        $objTitle->setSelected($this->getParam('title'));
    }
}

$table->addCell($titleLabel->show(), 150, NULL, 'left');
$table->addCell($objTitle->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Ranks
$table->startRow();
$objRanks = new textinput ('rank');
$rankslsLabel = new label($rank.'&nbsp;', 'rank');
if($mode == 'fixerror'){
    $objRanks->value =$this->getParam('rank');//set input vbalue
}
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
    if($mode == 'fixerror'){
        $objAppointment->setSelected($this->getParam('appointment'));
    }
}
$table->addCell($appointmentLabel->show(), 150, NULL, 'left');
$table->addCell($objAppointment->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Department/Scool/Division
$table->startRow();
$objDepartment = new dropdown ('department');
$departmentLabel = new label($dept.'&nbsp;', 'department');
$departments=array("Academic Development", "Accounting", "Afrikaans", "Anthropology & Sociology", "Biodiversity & Conservation", "Biotechnology", "Chemistry", "Computer Sciences", "Dietetics", "Earth Sciences", "Economics", "English", "Foreign Languages", "Geography", "History", "Human Ecology", "Industrial Psychology", "Information Systems", "Library & Info.", "Linguistics", "Management", "Mathematics", "Medical Bioscience", "Nursing", "Occupational Therapy", "Pharmacy", "Philosophy", "Physics", "Physiotherapy", "Political Studies", "Psychology", "Public Administration", "Religion & Theology", "Social Work", "Sport, Recreation & Exercise", "Statistics", "Women & Gender", "Xhosa", "School of Government", "School of Natural Medicine", "School of Pharmacy", "School of Public Health");
foreach ($departments as $department)
{
    $objDepartment->addOption($department,$department);
    if($mode == 'fixerror'){
        $objDepartment->setSelected($this->getParam('department'));
    }
}
$table->addCell($departmentLabel->show(), 150, NULL, 'left');
$table->addCell($objDepartment ->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Faculty
$table->startRow();
$objFaculty = new dropdown ('faculty');
$facultyLabel = new label($faculty.'&nbsp;', 'faculty');
$faculties=array("Arts", "Community & Health", "Dentistry", "Economic & Management", "Education", "Law", "Natural Science");
foreach ($faculties as $faculty)
{
    $objFaculty->addOption($faculty,$faculty);
    if($mode == 'fixerror'){
        $objFaculty->setSelected($this->getParam('faculty'));
    }
}
$table->addCell($facultyLabel->show(), 150, NULL, 'left');
$table->addCell($objFaculty ->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for Staff Number
$table->startRow();
$objStaffNumber = new textinput ('staffNumber');
$staffNumberLabel = new label($staffNumber.'&nbsp;', 'staffNumber');
if($mode == 'fixerror'){
    $objStaffNumber->value =$this->getParam('staffNumber');
}
$table->addCell($staffNumberLabel->show(), 150, NULL, 'left');
$table->addCell($objStaffNumber->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for email
$table->startRow();
$objEmail = new textinput ('email');
$emailLabel = new label($email.'&nbsp;', 'email');
$staffRegistration->addRule('email','Please enter a valid email','email');
if($mode == 'fixerror'){
    $objEmail->value =$this->getParam('email');
}
$table->addCell($emailLabel->show(), 150, NULL, 'left');
$table->addCell($objEmail->show(), 150, NULL, 'left');
$table->endRow();

//Input and label for confirm email
$table->startRow();
$objConfirmemail = new textinput ('confirmemail');
$objConfirmemailLabel = new label($confirmemail.'&nbsp;', 'confirmemail');
$staffRegistration->addRule(array('email', 'confirmemail'),'The email addresses you entered do not match', 'compare');
if($mode == 'fixerror'){
    $objEmail->value =$this->getParam('confirmemail');
}
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
$table->addCell($captcha->show().'<a href="javascript:redraw();">'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>', 150, NULL, 'left');
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

$messages=array();

if ($mode == 'fixerror') {

    foreach ($problems as $problem)
    {
        $messages[] = $this->explainProblemsInfo($problem);
    }

}

if ($mode == 'fixerror' && count($messages) > 0) {

    echo '<br /><span style="color:;font-size:18px;">'.$this->objLanguage->languageText('mod_staffregistration_required', 'rimfhe', 'Staff Member Registration').'</span>';
    echo '<ul><li><span class="error">'.$this->objLanguage->languageText('mod_userdetails_infonotsavedduetoerrors', 'userdetails').'</span>';

    echo '<ul>';
    foreach ($messages as $message)
    {
        if ($message != '') {
            echo '<li class="error">'.$message.'</li>';
        }
    }

    echo '</ul></li></ul>';
}
//display form
echo $staffRegistration->show();
?>
