
<?php
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


if(isset($userstring))
{
    $userstring = base64_decode($userstring);
    $userstring = explode(',', $userstring);
}
else {
    $userstring = NULL;
}

$this->loadClass('form', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('fieldset','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = "Group Details:";
echo '<div style="padding:10px;">'.$header->show();

echo '<span class="required_field"> (*) '."All field are required to be filled in Order to register into Unesco_OER".'</span>';


$form = new form ('register', $this->uri(array('action'=>'saveNewGroup')));

$messages = array();

$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '70%';
//$table->border = '1';
$tableable->cellspacing = '0';
$table->cellpadding = '2';


// GROUPNAME
$name = new textinput('group_name');
if ($mode == 'addfixup') {
    $name->value = $this->getParam('group_name');

    if ($this->getParam('group_name') == '') {
        $messages[] = "No group name provided";
    }
}
if (isset($userstring[0]) && $mode == 'add')
{
    $name->value = $userstring[0];
}

$table->startRow();
$table->addCell('Group Name'); // obj lang
$table->addCell($name->show());
$table->endRow();

//website link
$website = new textinput('group_website');
if ($mode == 'addfixup') {
    $website->value = $this->getParam('group_website');

    if ($this->getParam('group_website') == '') {
        $messages[] = "Please provide your website link"; //objlang
    }
}
if (isset($userstring[1]) && $mode == 'add')
{
    $website->value = $userstring[1];
}

$table->startRow();
$table->addCell('Website'); // obj lang
$table->addCell($website->show());
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
   $editor->value = $userstring[2];
}

$table->startRow();
$table->addCell('Description');
$table->addCell($editor->show());
$table->endRow();



//$textinput = new textinput('group_name');
//$textinput->size = 70;
//$table->startRow();
//$table->addCell('Group Name');
//$table->addCell($textinput->show());
//$table->endRow();


//$textinput = new textinput('group_website');
//$textinput->size = 70;
//$table->startRow();
//$table->addCell('Website');
//$table->addCell($textinput->show());
//$table->endRow();
//
//$editor = $this->newObject('htmlarea', 'htmlelements');
//$editor->name = 'description';
//$editor->height = '150px';
//$editor->width = '99%';
//$editor->setBasicToolBar();
//$editor->setContent();
//$table->startRow();
//$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
//$table->addCell($editor->show());
//$table->endRow();


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend ='GROUP Details';
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');


//EMAIL
$table = $this->newObject('htmltable', 'htmlelements');
$textinput = new textinput('group_email');
$textinput->size = 70;
$table->startRow();
$table->addCell('E-mail');
$table->addCell($textinput->show());
$table->endRow();
//ADDRESS
$address = new textinput('group_address');
if ($mode == 'addfixup') {
    $address->value = $this->getParam('group_address');

    if ($this->getParam('group_address') == '') {
        $messages[] = "Please provide your residential  address"; //objlang
    }
}
if (isset($userstring[3]) && $mode == 'add')
{
    $address->value = $userstring[3];
}

$table->startRow();
$table->addCell('Address'); // obj lang
$table->addCell($address->show());
$table->endRow();

//city
$city = new textinput('group_city');
if ($mode == 'addfixup') {
    $city->value = $this->getParam('group_city');

    if ($this->getParam('group_city') == '') {
        $messages[] = "Please provide your residential city"; //objlang
    }
}
if (isset($userstring[4]) && $mode == 'add')
{
    $city->value = $userstring[4];
}

$table->startRow();
$table->addCell('City'); // obj lang
$table->addCell($city->show());
$table->endRow();

//group state
$state = new textinput('group_state');
if ($mode == 'addfixup') {
    $state->value = $this->getParam('group_state');

    if ($this->getParam('group_state') == '') {
        $messages[] = "Please provide your residential state"; //objlang
    }
}
if (isset($userstring[5]) && $mode == 'add')
{
    $state->value = $userstring[5];
}

$table->startRow();
$table->addCell('State'); // obj lang
$table->addCell($state->show());
$table->endRow();

//postal code
$code = new textinput('group_postalcode');
if ($mode == 'addfixup') {
    $code->value = $this->getParam('group_postalcode');

    if ($this->getParam('group_postalcode') == '') {
        $messages[] = "Please provide your residential postal code"; //objlang
    }
}
if (isset($userstring[6]) && $mode == 'add')
{
    $code->value = $userstring[6];
}

$table->startRow();
$table->addCell('Postal code'); // obj lang
$table->addCell($code->show());
$table->endRow();


//$textinput = new textinput('group_address');
//$textinput->size = 70;
//$table->startRow();
//$table->addCell('Address');
//$table->addCell($textinput->show());
//$table->endRow();
////CITY
//$textinput = new textinput('group_city');
//$textinput->size = 70;
//$table->startRow();
//$table->addCell('City');
//$table->addCell($textinput->show());
//$table->endRow();
//STATE
//$textinput = new textinput('group_state');
//$textinput->size = 70;
//$table->startRow();
//$table->addCell('State/Province');
//$table->addCell($textinput->show());
//$table->endRow();
//POSTAL CODE
//$textinput = new textinput('group_postalcode');
//$textinput->size = 70;
//$table->startRow();
//$table->addCell('Postal Code');
//$table->addCell($textinput->show());
//$table->endRow();



$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = "Group contact details";
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');



//Group geographical location

//longitude
$table = $this->newObject('htmltable', 'htmlelements');
$longitude = new textinput('group_loclong');
if ($mode == 'addfixup') {
    $longitude->value = $this->getParam('group_loclong');

    if ($this->getParam('group_loclong') == '') {
        $messages[] = "Please provide your Geographical longitude"; //objlang
    }
}
if (isset($userstring[7]) && $mode == 'add')
{
    $longitude->value = $userstring[7];
}

$table->startRow();
$table->addCell('Longitude'); // obj lang
$table->addCell($longitude->show());
$table->endRow();

//latitude
$latitude = new textinput('group_loclong');
if ($mode == 'addfixup') {
    $latitude->value = $this->getParam('group_loclong');

    if ($this->getParam('group_loclong') == '') {
        $messages[] = "Please provide your Geographical latitude"; //objlang
    }
}
if (isset($userstring[8]) && $mode == 'add')
{
    $latitude->value = $userstring[8];
}

$table->startRow();
$table->addCell('Latitude'); // obj lang
$table->addCell($latitude->show());
$table->endRow();

//LONGITUDE
//$textinput = new textinput('group_loclong');
//$textinput->size = 70;
//$table->startRow();
//$table->addCell('Longitude');
//$table->addCell($textinput->show());
//$table->endRow();
//LATITUDE
//$textinput = new textinput('group_loclat');
//$textinput->size = 70;
//$table->startRow();
//$table->addCell('Latitude');
//$table->addCell($textinput->show());
//$table->endRow();
////COUNTRY
$table->startRow();
    $objCountries=&$this->getObject('languagecode','language');
    $table->addCell($this->objLanguage->languageText('word_country', 'system'));
    if ($mode == 'addfixup') {
        $table->addCell($objCountries->countryAlpha($this->getParam('country')));
    } else {
        $table->addCell($objCountries->countryAlpha());
    }
$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = "Group Geographical Location";
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');

//Register Institutions
$table = $this->newObject('htmltable', 'htmlelements');
$Institutions = $this->objDbInstitution->getAllInstitutions();
$dd=new dropdown('group_institutionlink');
if(count($Institutions)>0){
    $i=1;
    foreach ($Institutions as $Institution) {
        $dd->addOption($i,$Institution['name']);
        $i=$i+1;
        }
}
else{
    $dd->addOption('1', 'None');}
$table->startRow();
$table->addCell('Institution');
$table->addCell($dd->show());
$table->endRow();


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend ="Group Linked Institutions";
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');
$button = new button ('submitform', 'Save');
$button->setToSubmit();

$Cancelbutton = new button ('submitform', 'Cancel');
$Cancelbutton->setToSubmit();
$CancelLink = new link($this->uri(array('action' => "groupListingForm")));
$CancelLink->link =$Cancelbutton->show();

$form->addToForm('<p align="right">'.$button->show().$CancelLink->show().'</p>');

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
