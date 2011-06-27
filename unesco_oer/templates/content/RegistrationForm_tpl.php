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
// check if the site signup user string is set, if so, use it to populate the fields


if(isset($userstring))
{
    $userstring = base64_decode($userstring);
    $userstring = explode(',', $userstring);
}
else {
    $userstring = NULL;
}
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str ="Registration to Unesco_OER Product";
//echo $header->show();

echo '<div style="padding:10px;">'.$header->show();


$required = '<span class="required_field"> * '."".'</span>';

echo '<span class="required_field"> (*) '."Indicate fields that are required in order to access Unesco OER".'</span>';

//$str = $this->objLanguage->languageText('mod_userregistration_firstneedtoregister', 'userregistration', 'In order to be able to access [[SITENAME]], you first need to register');

//$str = str_replace('[[SITENAME]]', $this->objConfig->getSitename(), $str);

//echo '<p>'.$str.'<br />';
//echo $this->objLanguage->languageText('mod_userregistration_pleaseenterdetails', 'userregistration', 'Please enter your details, email address and desired user name in the form below.').'</p>';

$form = new form ('register', $this->uri(array('action'=>'saveNewUser')));
//$form = new form ('register'); //register

$messages = array();


$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$username = new textinput('register_username');
$username->extra = "maxlength=255";
$usernameLabel = new label($this->objLanguage->languageText('word_username', 'system').'&nbsp;', 'input_register_username');
$usernameContents = new label($this->objLanguage->languageText('phrase_usernamemayconsistof', 'userdetails', 'May consist of a-z, 0-9 and underscore'), 'input_register_username');

if ($mode == 'addfixup') {
    $username->value = $this->getParam('register_username');

    if ($this->getParam('register_username') == '' || strlen($this->getParam('register_username')) > 255) {
        $messages[] = $this->objLanguage->languageText('phrase_enterusername', 'system', 'Please enter a username');
    }
}

$table->addCell($usernameLabel->show());
$table->addCell('&nbsp;', 5);
$table->addCell($username->show().$required.' - <em>'.$usernameContents->show().'</em>');
//, NULL, NULL, NULL, NULL, 'colspan="2"');
$table->endRow();

$table->startRow();

$password = new textinput('register_password');
$password->fldType = 'password';
$password->extra = "maxlength=255";
$passwordLabel = new label($this->objLanguage->languageText('word_password', 'system'), 'input_register_password');
$confirmPassword = new textinput('register_confirmpassword');
$confirmPassword->fldType = 'password';
$confirmPassword->extra = 'maxlength=255';
$confirmPasswordLabel = new label($this->objLanguage->languageText('phrase_confirmpassword', 'userregistration', 'Confirm Password'), 'input_register_confirmpassword');
$table->addCell($passwordLabel->show());
$table->addCell('&nbsp;', 5);
$table->addCell($password->show().$required);

$table->endRow();

$table->startRow();

$table->addCell($confirmPasswordLabel->show());
$table->addCell('&nbsp;', 5);
$table->addCell($confirmPassword->show().$required);
$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('phrase_accountdetails', 'userregistration', 'Account Details');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');

$table = $this->newObject('htmltable', 'htmlelements');
$titlesDropdown = new dropdown('register_title');
$titlesLabel = new label($this->objLanguage->languageText('word_title', 'system').'&nbsp;', 'input_register_title');
$titles=array("title_mr", "title_miss", "title_mrs", "title_ms", "title_dr", "title_prof", "title_rev", "title_assocprof");
foreach ($titles as $title)
{
    $_title=trim($this->objLanguage->languageText($title));
    $titlesDropdown->addOption($_title,$_title);
}

if ($mode == 'addfixup') {
    $titlesDropdown->setSelected($this->getParam('register_title'));
}
$table->startRow();
$table->addCell($titlesLabel->show());
$table->addCell($titlesDropdown->show().$required);
$table->endRow();

$firstname = new textinput('register_firstname');
$firstnameLabel = new label($this->objLanguage->languageText('phrase_firstname', 'system', "First name").'&nbsp;', 'input_register_firstname');

if ($mode == 'addfixup') {
    $firstname->value = $this->getParam('register_firstname');

    if ($this->getParam('register_firstname') == '') {
        $messages[] = $this->objLanguage->languageText('mod_userdetails_enterfirstname', 'userdetails');
    }
}
if (isset($userstring) && $mode == 'add')
{
    $firstname->value = $userstring[0];
}

$table->startRow();
$table->addCell($firstnameLabel->show());
$table->addCell($firstname->show().$required);
$table->endRow();

$surname = new textinput('register_surname');
$surnameLabel = new label('Last Name');

if ($mode == 'addfixup') {
    $surname->value = $this->getParam('register_surname');

    if ($this->getParam('register_surname') == '') {
        $messages[] = $this->objLanguage->languageText('mod_userdetails_entersurname', 'userdetails');
    }
}
if (isset($userstring[1]) && $mode == 'add')
{
    $surname->value = $userstring[1];
}

$table->startRow();
$table->addCell($surnameLabel->show(),10,10, 'null');
$table->addCell($surname->show().$required);
$table->endRow();

//$staffnum = new textinput('register_staffnum');
//$staffnumLabel = new label($this->objLanguage->languageText('phrase_staffstudnumber', 'userregistration', 'Staff / Student number').'&nbsp;', 'input_register_staffnum');
//$staffnumguestLabel = new label($this->objLanguage->languageText('mod_userregistration_ifguestleaveblank', 'userregistration', 'If you are a guest, please leave this blank'), 'input_register_staffnum');
//
//if ($mode == 'addfixup') {
//    $staffnum->value = $this->getParam('register_staffnum');
//}
//
//$table->startRow();
//$table->addCell($staffnumLabel->show(), 150, NULL, 'right');
//$table->addCell($staffnum->show().' <em>'.$staffnumguestLabel->show().'</em>');
//$table->endRow();
//
//$cellnum = new textinput('register_cellnum');
//$cellnumLabel = new label($this->objLanguage->languageText('phrase_cellnumber', 'userregistration', 'Cell Number').'&nbsp;', 'input_register_cellnum');
//
//if ($mode == 'addfixup') {
//    $cellnum->value = $this->getParam('register_cellnum');
//}
//
//$table->startRow();
//$table->addCell($cellnumLabel->show(), 150, NULL, 'right');
//$table->addCell($cellnum->show());
//$table->endRow();




//
//$textinput = new textinput('register_surname');
//$textinput->size =70;
//$table->startRow();
//$table->addCell('Last Name');
//$table->addCell($textinput->show().$required);
//$table->endRow();

$textinput = new textinput('register_cellnum');
$textinput->size =70;
$table->addCell('Mobile Phone'.$required);
$table->addCell($textinput->show());
$table->endRow();

$table->startRow();
$table->addCell('Date of Birth');
$objDateTime = $this->getObject('dateandtime', 'utilities');
$objDatePicker = $this->newObject('datepicker', 'htmlelements');
$objDatePicker->name = 'Date_of_birth';
//if ($mode == 'edit') {
//    $objDatePicker->setDefaultDate(substr($document['date_recieved'], 0, 10));
//}
$table->addCell($objDatePicker->show());
$table->endRow();





//
//$textinput = new textinput('Address');
//$textinput->size =70;
//$table->startRow();
//$table->addCell('Address'.$required);
//$table->addCell($textinput->show());
//$table->endRow();

$address = new textinput('Address');
if ($mode == 'addfixup') {
    $address->value = $this->getParam('Address');

    if ($this->getParam('Address') == '') {
        $messages[] = "please enter your residential address ";
    }
}
if (isset($userstring) && $mode == 'add')
{
    $address->value = $userstring[1];
}

$table->startRow();
$table->addCell('Address'.$required);
$table->addCell($address->show());
$table->endRow();




$city = new textinput('city');
if ($mode == 'addfixup') {
    $city->value = $this->getParam('city');

    if ($this->getParam('city') == '') {
        $messages[] = "Please enter your residential city";
    }
}
if (isset($userstring) && $mode == 'add')
{
    $city->value = $userstring[2];
}

$table->startRow();
$table->addCell('City'.$required);
$table->addCell($city->show());
$table->endRow();



$state = new textinput('state');
if ($mode == 'addfixup') {
    $state->value = $this->getParam('state');

    if ($this->getParam('state') == '') {
        $messages[] = "Please provide your residential state";
    }
}
if (isset($userstring) && $mode == 'add')
{
    $state->value = $userstring[3];
}

$table->startRow();
$table->addCell('State'.$required);
$table->addCell($state->show());
$table->endRow();


$postalcode = new textinput('postaladdress');
if ($mode == 'addfixup') {
    $postalcode->value = $this->getParam('postaladdress');

    if ($this->getParam('postaladdress') == '') {
        $messages[] = "Please enter your Postal code ";
    }
}
if (isset($userstring) && $mode == 'add')
{
    $postalcode->value = $userstring[4];
}

$table->startRow();
$table->addCell('Postal code'.$required);
$table->addCell($postalcode->show());
$table->endRow();

//Organisation or company registration
$organisation = new textinput('organisation');
if ($mode == 'addfixup') {
    $organisation->value = $this->getParam('organisation');

    if ($this->getParam('organisation') == '') {
        $messages[] = "Please provide your Organisation/Company ";
    }
}
if (isset($userstring) && $mode == 'add')
{
    $organisation->value = $userstring[5];
}

$table->startRow();
$table->addCell('Organisation/Company'.$required);
$table->addCell($organisation->show());
$table->endRow();

//Job tittle registration
$jobtittle = new textinput('jobtittle');
if ($mode == 'addfixup') {
    $jobtittle->value = $this->getParam('jobtittle');

    if ($this->getParam('jobtittle') == '') {
        $messages[] = "Please provide your job tittle";
    }
}
if (isset($userstring) && $mode == 'add')
{
    $jobtittle->value = $userstring[6];
}

$table->startRow();
$table->addCell('Job Tittle'.$required);
$table->addCell($jobtittle->show());
$table->endRow();

//Type of occupation
$typeOfOccupation = new textinput('type_of_occupation');
if ($mode == 'addfixup') {
    $typeOfOccupation->value = $this->getParam('type_of_occupation');

    if ($this->getParam('type_of_occupation') == '') {
        $messages[] = "Please provide your Occupation type";
    }
}
if (isset($userstring) && $mode == 'add')
{
   $typeOfOccupation->value = $userstring[7];
}

$table->startRow();
$table->addCell('Type Of Occupation'.$required);
$table->addCell($typeOfOccupation->show());
$table->endRow();

//Register working phone number
$workPhone = new textinput('workingphone');
if ($mode == 'addfixup') {
    $workPhone->value = $this->getParam('workingphone');

    if ($this->getParam('workingphone') == '') {
        $messages[] = "Please provide your work phone number";
    }
}
if (isset($userstring) && $mode == 'add')
{
   $workPhone->value = $userstring[8];
}

$table->startRow();
$table->addCell('Working Phone'.$required);
$table->addCell($workPhone->show());
$table->endRow();

//Description registration
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '150px';
$editor->width = '80%';
$editor->setBasicToolBar();
$editor->setContent();
if ($mode == 'addfixup') {
    $editor->value = $this->getParam('description');

    if ($this->getParam('description') == '') {
        $messages[] = "Please provide a description";
    }
}
if (isset($userstring) && $mode == 'add')
{
   $editor->value = $userstring[9];
}

$table->startRow();
$table->addCell('Description'.$required);
$table->addCell($editor->show());
$table->endRow();

//Website Link
$websiteLink = new textinput('websitelink');
if ($mode == 'addfixup') {
    $websiteLink->value = $this->getParam('websitelink');

    if ($this->getParam('websitelink') == '') {
        $messages[] = "Please provide your website link";
    }
}
if (isset($userstring) && $mode == 'add')
{
   $websiteLink->value = $userstring[9];
}

$table->startRow();
$table->addCell('Website Link'.$required);
$table->addCell($websiteLink->show());
$table->endRow();

// Check that the group database is not empty and display group list dropdown
$groups = $this->objDbGroups->getAllGroups();
$dd = new dropdown('groupmembership');
if (count($groups) > 0) {
    $i = 1;
    //$dd=new dropdown('groupmembership');
    foreach ($groups as $group) {
        $dd->addOption($i, $group['name']);
        $i = $i + 1;
    }
} else {
    $dd->addOption('1', 'None');
}
$table->startRow();
$table->addCell('Group Membership'.$required);
$table->addCell($dd->show());
$table->endRow();




  

$sexRadio = new radio ('register_sex');
$sexRadio->addOption('M', $this->objLanguage->languageText('word_male', 'system'));
$sexRadio->addOption('F', $this->objLanguage->languageText('word_female', 'system'));
$sexRadio->setBreakSpace(' &nbsp; ');

if ($mode == 'addfixup') {
    $sexRadio->setSelected($this->getParam('register_sex'));
} else {
    $sexRadio->setSelected('M');
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('word_gender', 'userregistration', 'Gender').$required);
$table->addCell($sexRadio->show());
$table->endRow();

//Country
$table->startRow();
    $objCountries=&$this->getObject('languagecode','language');
    $table->addCell($this->objLanguage->languageText('word_country', 'system').$required);
    if ($mode == 'addfixup') {
        $table->addCell($objCountries->countryAlpha($this->getParam('country')));
    } else {
        $table->addCell($objCountries->countryAlpha());
    }
$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('phrase_userdetails', 'userregistration', 'User Details');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');


// Email Address

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$email = new textinput('register_email');
$emailLabel = new label($this->objLanguage->languageText('word_email', 'system', 'Email'), 'input_register_email');
$confirmEmail = new textinput('register_confirmemail');
$confirmEmailLabel = new label($this->objLanguage->languageText('phrase_confirmemail', 'system', 'Confirm Email'), 'input_register_confirmemail');
$emailInfoLabel = new label('Please Enter a Valid Email Address', 'input_register_email');

if ($mode == 'addfixup') {
    $email->value = $this->getParam('register_email');
    $confirmEmail->value = $this->getParam('register_confirmemail');
}
if (isset($userstring[2]) && $mode == 'add')
{
    $email->value = $userstring[2];
    $confirmEmail->value = $userstring[2];
}

$table->addCell($emailInfoLabel->show());
$table->addCell('&nbsp;', 10);
$table->addCell($emailLabel->show().$required.'<br />'.$email->show(), '20%');
$table->addCell($confirmEmailLabel->show().$required.'<br />'.$confirmEmail->show());
$table->endRow();



$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('phrase_emailaddress', 'system', 'Email Address');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');

// captcha
$objCaptcha = $this->getObject('captcha', 'utilities');
$captcha = new textinput('request_captcha');
$captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'input_request_captcha');

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = 'Verify Image';
$fieldset->contents = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as shown below. If you are unable to view the code, click on "Redraw" for a new one.')).'<br /><div id="captchaDiv">'.$objCaptcha->show().'</div>'.$captcha->show().$required.'  <a href="javascript:redraw();">'.$this->objLanguage->languageText('word_redraw', 'security', 'Redraw').'</a>';


$form->addToForm($fieldset->show());

$Cancelbutton = new button ('submitform', 'Cancel');
$Cancelbutton->setToSubmit();
$CancelLink = new link($this->uri(array('action' => "home")));
$CancelLink->link =$Cancelbutton->show();

$button = new button ('submitform', 'Complete Registration');
$button->setToSubmit();
//$SaveLink = new link($this->uri(array('action' => "saveNewUser",)));
//$SaveLink->link =$button->show();



$form->addToForm('<p align="right">'.$button->show().$CancelLink->show().'</p>');
//$form->addToForm('<p align="right">'.$button.$CancelLink->show().'</p>');
if ($mode == 'addfixup') {

    foreach ($problems as $problem)
    {
        $messages[] = $this->__explainProblemsInfo($problem);
    }

}


if ($mode == 'addfixup' && count($messages) > 0) {
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


echo $form->show();

echo '</div>';

?>