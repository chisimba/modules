
<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul><li class="first">Home</li>
            <li><a href='?module=unesco_oer&action=controlpanel' alt='Adminstrative Tools' title='Adminstrative Tools'>Adminstrative Tools</a></li>
            <li><a href='?module=unesco_oer&action=userListingForm' alt='Users' title='Users'>Users</a></li>
           <li>Edit User</li>
            <!--<li><a href='/newsroom/2430/newsitems.html' alt='Click here to view NewsItems' title='Click here to view NewsItems'>NewsItems</a></li>
            <li><a href='#' alt='Click here to view 2011-07' title='Click here to view 2011-07'>2011-07</a></li>
            <li>witsjunction</li>
           -->
        </ul>
    </div>

</div>



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
$header->str = $user['firstname'].'&nbsp;'.$user['surname'].'&nbsp;'. "Profile";
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

//    if ($this->getParam('change') == 'image') {
//
//        echo '<ul>';
//        switch ($this->getParam('message'))
//        {
//            case 'nopicturegiven':
//                echo '<li><span class="error">'.ucfirst($this->objLanguage->languageText('word_error')).': '.$this->objLanguage->languageText('mod_userdetails_noimageprovided', 'userdetails').'</span></li>';
//                break;
//            case 'fileisnotimage':
//                echo '<li><span class="error">'.ucfirst($this->objLanguage->languageText('word_error')).': '.$this->objLanguage->languageText('mod_userdetails_filenotimage', 'userdetails').'</span></li>';
//                break;
//            case 'imagechanged':
//                echo '<li><span class="confirm">'.$this->objLanguage->languageText('mod_userdetails_userimagechanged', 'userdetails').'</span></li>';
//                break;
//            case 'userimagereset':
//                echo '<li><span class="confirm">'.$this->objLanguage->languageText('mod_userdetails_userimagereset', 'userdetails').'</span></li>';
//                break;
//        }
//        echo '</ul>';
//    }
//
//    echo '</div>';
//
//    echo '
//    <script type="text/javascript">
//
//    function hideConfirmation()
//    {
//        document.getElementById(\'confirmationmessage\').style.display="none";
//    }
//
//    setTimeout("hideConfirmation()", 10000);
//    </script>
//    ';

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




$uri=$this->uri(array('action'=>'updateuserdetails','id'=>$this->getParam('id'),'userid'=>$this->getParam('userid')));
$form = new form ('updatedetails',$uri);
echo '<div style="width:70%; float:left; padding:5px; boorder:1px solid red;">';
//echo '<h3>'.$this->objLanguage->languageText('phrase_userinformation', 'userdetails').':</h3>';


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


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('phrase_userdetails', 'userregistration', 'User Details');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');


//Email
$tableC = $this->newObject('htmltable', 'htmlelements');
$tableC->startRow();
$label = new label ($this->objLanguage->languageText('phrase_emailaddress', 'system'), 'input_useradmin_email');
$tableC->addCell($label->show());
$tableC->addCell($email->show());

$tableC->endRow();

//Cell number or Mobile Number
$textinput = new textinput('register_cellnum');
$textinput->size = 75;
$textinput->value = $user['cellnumber'];
$tableC->startRow();
$tableC->addCell('Mobile Phone');
$tableC->addCell($textinput->show());
$tableC->endRow();


//Date of Birth
$textinput = new textinput('Date_of_birth'); //
$textinput->size =75;
$textinput->value = $userExtra[0]['birthday'];
$table->startRow();
$table->addCell('Birth date');
$table->addCell($textinput->show());
$table->endRow();



//$table->startRow();
//$table->addCell('Date Of Birth');
//$table->addCell($userExtra[0]['birthday']);
//$table->endRow();
//
//$table->startRow();
//$table->addCell('Change Date');
//$objDateTime = $this->getObject('dateandtime', 'utilities');
//$objDatePicker = $this->newObject('datepicker', 'htmlelements');
//$objDatePicker->name = 'birthdate';
//$table->addCell($objDatePicker->show());
//$table->endRow();


//Address
$textinput = new textinput('Address');
$textinput->size =75;
$textinput->value = $userExtra[0]['postaladdress'];
$tableC->startRow();
$tableC->addCell('Address');
$tableC->addCell($textinput->show());
$tableC->endRow();

//City
$textinput = new textinput('city');
$textinput->size =75;
$textinput->value = $userExtra[0]['city'];
$tableC->startRow();
$tableC->addCell('City');
$tableC->addCell($textinput->show());
$tableC->endRow();
//state
$textinput = new textinput('state');
$textinput->size =75;
$textinput->value = $userExtra[0]['state'];
$tableC->startRow();
$tableC->addCell('State');
$tableC->addCell($textinput->show());
$tableC->endRow();

//Postal Address
$textinput = new textinput('postaladdress');
$textinput->size =75;
$textinput->value = $userExtra[0]['postaladdress'];
$tableC->startRow();
$tableC->addCell('Postal code');
$tableC->addCell($textinput->show());
$tableC->endRow();


//Organisation
$textinput = new textinput('organisation');
$textinput->size =75;
$textinput->value = $userExtra[0]['organisation'];
$tableC->startRow();
$tableC->addCell('Organisation/Company');
$tableC->addCell($textinput->show());
$tableC->endRow();

//Job Tittle
$textinput = new textinput('jobtittle');
$textinput->size =75;
$textinput->value = $userExtra[0]['jobtittle'];
$tableC->startRow();
$tableC->addCell('Job Tittle');
$tableC->addCell($textinput->show());
$tableC->endRow();

//Type of Occupation
$textinput = new textinput('typeofoccapation');
$textinput->size =75;
$textinput->value = $userExtra[0]['typeoccapation'];
$tableC->startRow();
$tableC->addCell('Type Of Occupation');
$tableC->addCell($textinput->show());
$tableC->endRow();

//Work phone Number
$textinput = new textinput('workingphone');
$textinput->size =75;
$textinput->value = $userExtra[0]['workingphone'];
$tableC->startRow();
$tableC->addCell('Working Phone');
$tableC->addCell($textinput->show());
$tableC->endRow();

//Description
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '150px';
$editor->width = '100%';
$editor->setBasicToolBar();
$editor->setContent($userExtra[0]['description']);
$tableC->startRow();
$tableC->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
$tableC->addCell($editor->show());
$tableC->endRow();

//website Link
$textinput = new textinput('websitelink');
$textinput->size =75;
$textinput->value = $userExtra[0]['websitelink'];
$tableC->startRow();
$tableC->addCell('Website Link');
$tableC->addCell($textinput->show());
$tableC->endRow();


$user_current_membership=$this->ObjDbUserGroups->getUserGroups($this->getParam('id'));
//
//$textinput = new textinput('userGROUPMEMBERSHIP');
//$tableC->startRow();
//$tableC->addCell($this->objLanguage->languageText('mod_unesco_oer_users_group_membership', 'unesco_oer'));
//foreach($user_current_membership as $member){
//    $tableC->addCell($this->objDbGroups->getGroupName($member['groupid']));
//    }
//
//$tableC->endRow();

$currentMembership=array();


//$Current_membership_linked=$this->ObjDbUserGroups->getUserGroups($this->getParam('id'));
//$currentMembership=array();
//foreach($Current_membership_linked as $groupID){
//    $institutionArray= $this->objDbGroups->getInstitution($groupID) ;
//    array_push($currentMembership,$institutionArray);
//}
//$currentMembership=$currentMembership[0];




$groups = $this->objDbGroups->getAllGroups();
$availablegroups=array();
foreach ($groups as $group) {
    if(count($user_current_membership)!=0){
        foreach ($user_current_membership as $membership) {
            if (strcmp($group['id'], $membership['groupid']) != 0){
                array_push($availablegroups, $group);
                }
                else{
                    array_push($currentMembership, $group);
                }
                }
                }
//        else{ /// TODO WHY IS NOT SHOWING ON EDIT ADMIN
//            array_push($availablegroups, $group);
            
        //}
    
}
$objSelectBox = $this->newObject('selectbox','htmlelements');
$objSelectBox->create( $form, 'leftList[]', 'Available Groups', 'rightList[]', 'Chosen Groups' );
$objSelectBox->insertLeftOptions(
                        $availablegroups,
                        'id',
                        'name' );

$objSelectBox->insertRightOptions(
                               $currentMembership,
                               'id',
                               'name');

$tblLeft = $this->newObject( 'htmltable','htmlelements');
$objSelectBox->selectBoxTable( $tblLeft, $objSelectBox->objLeftList);
//Construct tables for right selectboxes
$tblRight = $this->newObject( 'htmltable', 'htmlelements');
$objSelectBox->selectBoxTable( $tblRight, $objSelectBox->objRightList);
//Construct tables for selectboxes and headings
$tblSelectBox = $this->newObject( 'htmltable', 'htmlelements' );
$tblSelectBox->width = '90%';
$tblSelectBox->startRow();
    $tblSelectBox->addCell( $objSelectBox->arrHeaders['hdrLeft'], '100pt' );
    $tblSelectBox->addCell( $objSelectBox->arrHeaders['hdrRight'], '100pt' );
$tblSelectBox->endRow();
$tblSelectBox->startRow();
    $tblSelectBox->addCell( $tblLeft->show(), '100pt' );
    $tblSelectBox->addCell( $tblRight->show(), '100pt' );
$tblSelectBox->endRow();

$tableC->startRow();
$tableC->addCell($this->objLanguage->languageText('mod_unesco_oer_users_group_membership', 'unesco_oer'));
$tableC->addCell($tblSelectBox->show());
$tableC->endRow();







//***** this must change/
//dispay user belonging group first  and allow a dropdown of other group
//$groups = $this->objDbGroups->getAllGroups();
//$dd=new dropdown('groupmembership');
//if(count($groups)>0){
//    $i=1;
//    foreach ($groups as $group) {
//          $dd->addOption($i,$group['name']);
//        $i=$i+1;
//        }
//}
//else{
//    $dd->addOption('1', 'None');}
//
//$tableC->startRow();
//$tableC->addCell('Group Membership');
//$tableC->addCell($dd->show());


// Sex
$tableC->startRow();
    $sexRadio = new radio ('useradmin_sex');
    $sexRadio->addOption('M', $this->objLanguage->languageText('word_male', 'system'));
    $sexRadio->addOption('F', $this->objLanguage->languageText('word_female', 'system'));
    $sexRadio->setBreakSpace(' &nbsp; ');

    $sexRadio->setSelected($user['sex']);

    if ($mode == 'addfixup') {
        $sexRadio->setSelected($this->getParam('useradmin_sex'));
    }

    $tableC->addCell($this->objLanguage->languageText('word_sex', 'system'));
  
    $tableC->addCell($sexRadio->show());
$tableC->endRow();

// Country
$tableC->startRow();
$objCountries=$this->getObject('languagecode','language');
$tableC->addCell($this->objLanguage->languageText('word_country', 'system'));
$tableC->addCell($objCountries->countryAlpha($user['country']));
$tableC->endRow();

// Spacer
$tableC->startRow();
    $tableC->addCell('&nbsp;');
    $tableC->addCell('&nbsp;');
    $tableC->addCell('&nbsp;');
$tableC->endRow();
// Username
$tableC->startRow();
$tableC->addCell($this->objLanguage->languageText('word_username', 'system'));
$tableC->addCell($user['username']);
$tableC->endRow();

//if (strtolower($user['howcreated']) != 'ldap') {
if ($user['pass']!='6b3d7dbdce9d4d04c78473e3df832f5d785c2593'){
 // Password
    $tableC->startRow();
    $label = new label($this->objLanguage->languageText('word_password', 'system'), 'input_useradmin_password');
    $textinput = new textinput('useradmin_password');
    $textinput->fldType = 'password';
    $textinput->size = 25;
    $textinput->extra = ' autocomplete="off"';
    $tableC->addCell($label->show());
    $tableC->addCell($textinput->show() . ' - ' . $this->objLanguage->languageText('phrase_leavepasswordblank', 'userdetails'));
    $tableC->endRow();
    // Repeat Password
    $table->startRow();
    $label = new label ($this->objLanguage->languageText('phrase_repeatpassword', 'userdetails'), 'input_useradmin_repeatpassword');
    $textinput = new textinput ('useradmin_repeatpassword');
    $textinput->fldType = 'password';
    $textinput->size = 25;
    $textinput->extra = ' autocomplete="off"';
    $tableC->addCell($label->show());
    $tableC->addCell($textinput->show());
    $tableC->endRow();
    } else {
    // Password
    $tableC->startRow();
    $tableC->addCell('Password');
    $tableC->addCell('&nbsp;');
    $tableC->addCell('<em>Using Network ID Password</em>');
    $tableC->endRow();
}


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = "User Information";  //objLANG
$fieldset->contents = $tableC->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');


//$form->addToForm($table->show());

$button = new button ('submitform', $this->objLanguage->languageText('mod_userdetails_updatedetails'));
//$button->setToSubmit();
$action = $objSelectBox->selectAllOptions( $objSelectBox->objRightList )." SubmitProduct();";
$button->setOnClick('javascript: ' . $action);
// $button->setOnClick('validateForm()');

$form->extra = 'enctype="multipart/form-data"';
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

<script type="text/javascript">
function SubmitProduct()
{
    var objForm = document.forms['updatedetails'];
    //objForm.elements[element].value = value;
    objForm.submit();
}
</script>