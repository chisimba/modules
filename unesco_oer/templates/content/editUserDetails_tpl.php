


<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('mouseoverpopup', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$user = $this->objUserAdmin->getUserDetails($id);
$userId=$this->getParam('userid');
$deals=$this->getParam('id');
$userExtra= $this->objUseExtra->getUserDetails($deals,$userId);


$header = new htmlheading();
$header->str = $this->objLanguage->languageText('mod_userdetails_name', 'userdetails').":".'&nbsp;'.$user['firstname'].'&nbsp;'.$user['surname'];
$header->type = 1;
echo $header->show();
//echo $userExtra[0]['description'];

if (isset($showconfirmation) && $showconfirmation) {
    echo '<div id="confirmationmessage">';
    if ($this->getParam('change') == 'details') {
        echo '<ul><li><span class="confirm">'.$this->objLanguage->languageText('mod_userdetails_detailssuccessfullyupdate', 'userdetails').'</span></li>';

        if ($this->getParam('passworderror') == 'passworddonotmatch') {
            echo '<li><span class="error">'.$this->objLanguage->languageText('mod_userdetails_repeatpasswordnotmatch', 'userdetails').'</span></li>';
        } else if ($this->getParam('passwordchanged') == TRUE) {
            echo '<li><span class="confirm">'.$this->objLanguage->languageText('mod_userdetails_passwordupdated', 'userdetails').'</span></li>';
        } else {
            echo '<li><span class="warning">'.$this->objLanguage->languageText('mod_userdetails_passwordnotchanged', 'userdetails').'</span></li>';
        }


        echo '</ul>';
    }

    if ($this->getParam('change') == 'image') {

        echo '<ul>';
        switch ($this->getParam('message'))
        {
            case 'nopicturegiven':
                echo '<li><span class="error">'.ucfirst($this->objLanguage->languageText('word_error')).': '.$this->objLanguage->languageText('mod_userdetails_noimageprovided', 'userdetails').'</span></li>';
                break;
            case 'fileisnotimage':
                echo '<li><span class="error">'.ucfirst($this->objLanguage->languageText('word_error')).': '.$this->objLanguage->languageText('mod_userdetails_filenotimage', 'userdetails').'</span></li>';
                break;
            case 'imagechanged':
                echo '<li><span class="confirm">'.$this->objLanguage->languageText('mod_userdetails_userimagechanged', 'userdetails').'</span></li>';
                break;
            case 'userimagereset':
                echo '<li><span class="confirm">'.$this->objLanguage->languageText('mod_userdetails_userimagereset', 'userdetails').'</span></li>';
                break;
        }
        echo '</ul>';
    }

    echo '</div>';

    echo '
    <script type="text/javascript">

    function hideConfirmation()
    {
        document.getElementById(\'confirmationmessage\').style.display="none";
    }

    setTimeout("hideConfirmation()", 10000);
    </script>
    ';

}

// Array to hold error messages
$messages = array();

// READONLY FLAG
if ($user['pass']=='6b3d7dbdce9d4d04c78473e3df832f5d785c2593'){
   $editFlag=" READONLY";
} else {
   $editFlag="";
}

//Create Form Elements, as well detect associated problems

    $firstname = new textinput ('useradmin_firstname');
    $firstname->size = 75;
    $firstname->extra = $editFlag.' maxlength="50"';
    $firstname->value = $user['firstname'];

    if ($mode == 'addfixup') {
        $firstname->value = $this->getParam('useradmin_firstname');

        if ($this->getParam('useradmin_firstname') == '') {
            $messages[] = $this->objLanguage->languageText('mod_userdetails_enterfirstname', 'userdetails');
        }
    }

    $surname = new textinput ('useradmin_surname');
    $surname->size = 75;
    $surname->extra = $editFlag.' maxlength="50"';
    $surname->value = $user['surname'];

    if ($mode == 'addfixup') {
        $surname->value = $this->getParam('useradmin_surname');

        if ($this->getParam('useradmin_surname') == '') {
            $messages[] = $this->objLanguage->languageText('mod_userdetails_entersurname', 'userdetails');
        }
    }

    $email = new textinput ('useradmin_email');
    $email->size = 75;
    $email->extra = ' maxlength="100"';
    $email->value = $user['emailaddress'];

    if ($mode == 'addfixup') {
        $email->value = $this->getParam('useradmin_email');

        if ($this->getParam('useradmin_email') == '') {
            $messages[] = $this->objLanguage->languageText('mod_userdetails_enteremailaddress', 'userdetails');
        } else if (!$this->objUrl->isValidFormedEmailAddress($this->getParam('useradmin_email'))) {
            $messages[] = $this->objLanguage->languageText('mod_userdetails_entervalidemailaddress', 'userdetails');
        }
    }

if ($mode == 'addfixup' && count($messages) > 0) {
    echo '<ul><li><span class="error">'.$this->objLanguage->languageText('mod_userdetails_infonotsavedduetoerrors', 'userdetails').'</span>';

    echo '<ul>';
        foreach ($messages as $message)
        {
            echo '<li class="error">'.$message.'</li>';
        }
    echo '</ul></li></ul>';
}
echo '<div id="formresults"></div>';





$form = new form ('updatedetails', $this->uri(array('action'=>'updateuserdetails','id'=>$this->getParam('id'),'userid'=>$this->getParam('userid'))));

echo '<div style="width:70%; float:left; padding:5px; boorder:1px solid red;">';
echo '<h3>'.$this->objLanguage->languageText('phrase_userinformation', 'userdetails').':</h3>';


$table = $this->newObject('htmltable', 'htmlelements');

// Title
$table->startRow();
    $label = new label ($this->objLanguage->languageText('word_title', 'system'), 'input_useradmin_title');

    $objDropdown = new dropdown('useradmin_title');
    $titles=array("title_mr", "title_miss", "title_mrs", "title_ms", "title_dr", "title_prof", "title_rev", "title_assocprof");
    foreach ($titles as $title)
    {
        $_title=trim($objLanguage->languageText($title));
        $objDropdown->addOption($_title,$_title);
    }

    if ($mode == 'addfixup') {
        $objDropdown->setSelected($this->getParam('useradmin_title'));
    } else {
        $objDropdown->setSelected($user['title']);
    }

   $table->addCell($label->show(), 140);
   $table->addCell($objDropdown->show());
$table->endRow();




// Firstname
$table->startRow();
$label = new label ($this->objLanguage->languageText('phrase_firstname', 'system'), 'input_useradmin_firstname');
$table->addCell($label->show());
$table->addCell($firstname->show());
$table->endRow();

// Surname
$table->startRow();
$label = new label ($this->objLanguage->languageText('word_surname', 'system'), 'input_useradmin_surname');
$table->addCell($label->show());
$table->addCell($surname->show());
$table->endRow();

//Email
$table->startRow();
$label = new label ($this->objLanguage->languageText('phrase_emailaddress', 'system'), 'input_useradmin_email');
$table->addCell($label->show());
$table->addCell($email->show());
$table->endRow();

//Cell number or Mobile Number
$textinput = new textinput('register_cellnum');
$textinput->size = 75;
$textinput->value = $user['cellnumber'];
$table->startRow();
$table->addCell('Mobile Phone');
$table->addCell($textinput->show());
$table->endRow();



//Date of Birth
$textinput = new textinput('Date_of_birth');
$textinput->size =75;
$textinput->value = $userExtra[0]['birthday'];
$table->startRow();
$table->addCell('Birth date');
$table->addCell($textinput->show());
$table->endRow();

//Address
$textinput = new textinput('Address');
$textinput->size =75;
$textinput->value = $userExtra[0]['postaladdress'];
$table->startRow();
$table->addCell('Address');
$table->addCell($textinput->show());
$table->endRow();

//City
$textinput = new textinput('city');
$textinput->size =75;
$textinput->value = $userExtra[0]['city'];
$table->startRow();
$table->addCell('City');
$table->addCell($textinput->show());
$table->endRow();
//state
$textinput = new textinput('state');
$textinput->size =75;
$textinput->value = $userExtra[0]['state'];
$table->startRow();
$table->addCell('State');
$table->addCell($textinput->show());
$table->endRow();

//Postal Address
$textinput = new textinput('postaladdress');
$textinput->size =75;
$textinput->value = $userExtra[0]['postaladdress'];
$table->startRow();
$table->addCell('Postal code');
$table->addCell($textinput->show());
$table->endRow();

//Organisation
$textinput = new textinput('organisation');
$textinput->size =75;
$textinput->value = $userExtra[0]['organisation'];
$table->startRow();
$table->addCell('Organisation/Company');
$table->addCell($textinput->show());
$table->endRow();

//Job Tittle
$textinput = new textinput('jobtittle');
$textinput->size =75;
$textinput->value = $userExtra[0]['jobtittle'];
$table->startRow();
$table->addCell('Job Tittle');
$table->addCell($textinput->show());
$table->endRow();

//Type of Occupation
$textinput = new textinput('typeofoccapation');
$textinput->size =75;
$textinput->value = $userExtra[0]['typeoccapation'];
$table->startRow();
$table->addCell('Type Of Occupation');
$table->addCell($textinput->show());
$table->endRow();

//Work phone Number
$textinput = new textinput('workingphone');
$textinput->size =75;
$textinput->value = $userExtra[0]['workingphone'];
$table->startRow();
$table->addCell('Working Phone');
$table->addCell($textinput->show());
$table->endRow();

//Description
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '150px';
$editor->width = '100%';
$editor->setBasicToolBar();
$editor->setContent($userExtra[0]['description']);
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
$table->addCell($editor->show());
$table->endRow();

//website Link
$textinput = new textinput('websitelink');
$textinput->size =75;
$textinput->value = $userExtra[0]['websitelink'];
$table->startRow();
$table->addCell('Website Link');
$table->addCell($textinput->show());
$table->endRow();


//***** this must change/
//dispay user belonging group first  and allow a dropdown of other group
$groups = $this->objDbGroups->getAllGroups();
$dd=new dropdown('groupmembership');
if(count($groups)>0){
    $i=1;
    //$dd=new dropdown('groupmembership');
    foreach ($groups as $group) {
        $dd->addOption($i,$group['name']);
        $i=$i+1;
        }
}
else{
    $dd->addOption('1', 'None');}

$table->startRow();
$table->addCell('Group Membership');
$table->addCell($dd->show());
$table->endRow();

// Sex
$table->startRow();
    $sexRadio = new radio ('useradmin_sex');
    $sexRadio->addOption('M', $this->objLanguage->languageText('word_male', 'system'));
    $sexRadio->addOption('F', $this->objLanguage->languageText('word_female', 'system'));
    $sexRadio->setBreakSpace(' &nbsp; ');

    $sexRadio->setSelected($user['sex']);

    if ($mode == 'addfixup') {
        $sexRadio->setSelected($this->getParam('useradmin_sex'));
    }

    $table->addCell($this->objLanguage->languageText('word_sex', 'system'));
  
    $table->addCell($sexRadio->show());
$table->endRow();

// Country
$table->startRow();
$objCountries=$this->getObject('languagecode','language');
$table->addCell($this->objLanguage->languageText('word_country', 'system'));
$table->addCell($objCountries->countryAlpha($user['country']));
$table->endRow();

// Spacer
$table->startRow();
    $table->addCell('&nbsp;');
    $table->addCell('&nbsp;');
    $table->addCell('&nbsp;');
$table->endRow();
// Username
$table->startRow();
$table->addCell($this->objLanguage->languageText('word_username', 'system'));
$table->addCell($user['username']);
$table->endRow();

//if (strtolower($user['howcreated']) != 'ldap') {
if ($user['pass']!='6b3d7dbdce9d4d04c78473e3df832f5d785c2593'){
 // Password
    $table->startRow();
    $label = new label($this->objLanguage->languageText('word_password', 'system'), 'input_useradmin_password');
    $textinput = new textinput('useradmin_password');
    $textinput->fldType = 'password';
    $textinput->size = 25;
    $textinput->extra = ' autocomplete="off"';
    $table->addCell($label->show());
    $table->addCell($textinput->show() . ' - ' . $this->objLanguage->languageText('phrase_leavepasswordblank', 'userdetails'));
    $table->endRow();
    // Repeat Password
    $table->startRow();
    $label = new label ($this->objLanguage->languageText('phrase_repeatpassword', 'userdetails'), 'input_useradmin_repeatpassword');
    $textinput = new textinput ('useradmin_repeatpassword');
    $textinput->fldType = 'password';
    $textinput->size = 25;
    $textinput->extra = ' autocomplete="off"';
    $table->addCell($label->show());
    $table->addCell($textinput->show());
    $table->endRow();
    } else {
    // Password
    $table->startRow();
    $table->addCell('Password');
    $table->addCell('&nbsp;');
    $table->addCell('<em>Using Network ID Password</em>');
    $table->endRow();
}



$form->addToForm($table->show());

$button = new button ('submitform', $this->objLanguage->languageText('mod_userdetails_updatedetails'));
$button->setToSubmit();
// $button->setOnClick('validateForm()');

$form->addToForm('<p>'.$button->show().'</p>');

$form->addRule('useradmin_firstname',$this->objLanguage->languageText('mod_userdetails_enterfirstname', 'userdetails'),'required');
$form->addRule('useradmin_surname',$this->objLanguage->languageText('mod_userdetails_entersurname', 'userdetails'),'required');
$form->addRule('useradmin_email',$this->objLanguage->languageText('mod_userdetails_enteremailaddress', 'userdetails'),'required');
$form->addRule('useradmin_email', $this->objLanguage->languageText('mod_userdetails_entervalidemailaddress', 'userdetails'), 'email');


echo $form->show();
echo '</div>';

//Link to Addministation
$returnlink = new link($this->uri(array('action'=>'UserListingForm')));
$returnlink->link = 'Return to Home Page';
echo '<br clear="left" />'.$returnlink->show();
?>
