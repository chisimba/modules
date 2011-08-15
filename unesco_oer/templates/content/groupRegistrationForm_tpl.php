
<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul><li class="first">Home</li>
            <li><a href='?module=unesco_oer&action=controlpanel' alt='Adminstrative Tools' title='Adminstrative Tools'>Adminstrative Tools</a></li>
            <li><a href='?module=unesco_oer&action=groupListingForm' alt='product groups' title='Product groups'>Product groups</a></li>
           <li><a href='?module=unesco_oer&action=groupListingForm' alt='product groups' title='Product groups'>Product groups</a></li>
            <li>Create Group</li>
            <!--<li><a href='/newsroom/2430/newsitems.html' alt='Click here to view NewsItems' title='Click here to view NewsItems'>NewsItems</a></li>
            <li><a href='#' alt='Click here to view 2011-07' title='Click here to view 2011-07'>2011-07</a></li>
            <li>witsjunction</li>
           -->
        </ul>
    </div>

</div>
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
$header->str = $this->objLanguage->languageText('mod_unesco_oer_group_heading', 'unesco_oer');
echo '<div style="padding:10px;">'.$header->show();

$required = '<span class="required_field"> * ';
echo '<span class="required_field"> (*) '."All field are required to be filled in Order to register into Unesco_OER".'</span>';

$uri=$this->uri(array('action'=>'saveNewGroup'));
$form = new form ('register', $uri);





$messages = array();

$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '100%';
$table->border = '0';
$tableable->cellspacing = '0';
$table->cellpadding = '2';


// GROUPNAME
$name = new textinput('group_name');
$name->size = 80;
if ($mode == 'addfixup') {
    $name->value = $this->getParam('group_name');

    if ($this->getParam('group_name') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_message1', 'unesco_oer');
    }
}
if (isset($userstring[0]) && $mode == 'add')
{
    $name->value = $userstring[0];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_name', 'unesco_oer').$required );
$table->addCell($name->show());
$table->endRow();

//website link
$website = new textinput('group_website');
$website->size = 80;
if ($mode == 'addfixup') {
    $website->value = $this->getParam('group_website');

    if ($this->getParam('group_website') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_message2', 'unesco_oer');
    }
}
if (isset($userstring[1]) && $mode == 'add')
{
    $website->value = $userstring[1];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_website', 'unesco_oer').$required); // obj lang
$table->addCell($website->show());
$table->endRow();

//Description registration
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '150px';
$editor->width = '85%';
$editor->setBasicToolBar();
$editor->setContent();
if ($mode == 'addfixup') {
    $editor->value = $this->getParam('description');

    if ($this->getParam('description') == '') {
        $messages[] =  $this->objLanguage->languageText('mod_unesco_oer_group_message3', 'unesco_oer');
    }
}
if (isset($userstring) && $mode == 'add')
{
   $editor->value = $userstring[2];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_description', 'unesco_oer').$required);
$table->addCell($editor->show());
$table->endRow();
//field for the thumbnail
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer').$required);
$table->addCell($this->objThumbUploader->show());
$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend =$this->objLanguage->languageText('mod_unesco_oer_group_fieldset1', 'unesco_oer');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');


//EMAIL
$table = $this->newObject('htmltable', 'htmlelements');

$email = new textinput('register_email');
$email->size = 80;
if ($mode == 'addfixup') {
    $email->value = $this->getParam('register_email');
   }
if (isset($userstring[9]) && $mode == 'add')
{
    $email->value = $userstring[9];
   }

$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_email', 'unesco_oer').$required);
$table->addCell($email->show()); //$table->addCell($email->show(), '30%');
$table->endRow();

//ADDRESS
$address = new textinput('group_address');
$address->size = 80;
if ($mode == 'addfixup') {
    $address->value = $this->getParam('group_address');

    if ($this->getParam('group_address') == '') {
        $messages[] =  $this->objLanguage->languageText('mod_unesco_oer_group_message4', 'unesco_oer');
    }
}
if (isset($userstring[3]) && $mode == 'add')
{
    $address->value = $userstring[3];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_address', 'unesco_oer').$required);
$table->addCell($address->show());
$table->endRow();

//city
$city = new textinput('group_city');
$city->size = 80;
if ($mode == 'addfixup') {
    $city->value = $this->getParam('group_city');

    if ($this->getParam('group_city') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_message5', 'unesco_oer');
        }
}
if (isset($userstring[4]) && $mode == 'add')
{
    $city->value = $userstring[4];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_city', 'unesco_oer').$required);
$table->addCell($city->show());
$table->endRow();

//group state
$state = new textinput('group_state');
$state->size = 80;
if ($mode == 'addfixup') {
    $state->value = $this->getParam('group_state');

    if ($this->getParam('group_state') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_message6', 'unesco_oer');
    }
}
if (isset($userstring[5]) && $mode == 'add')
{
    $state->value = $userstring[5];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_state', 'unesco_oer').$required);
$table->addCell($state->show());
$table->endRow();

//postal code
$code = new textinput('group_postalcode');
if ($mode == 'addfixup') {
    $code->value = $this->getParam('group_postalcode');

    if ($this->getParam('group_postalcode') == '') {
        $messages[] =  $this->objLanguage->languageText('mod_unesco_oer_group_message7', 'unesco_oer');
    }
}
if (isset($userstring[6]) && $mode == 'add')
{
    $code->value = $userstring[6];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_postalcode', 'unesco_oer').$required);
$table->addCell($code->show());
$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend =$this->objLanguage->languageText('mod_unesco_oer_group_fieldset2', 'unesco_oer');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');



//Group geographical location

//longitude
$table = $this->newObject('htmltable', 'htmlelements');
$longitude = new textinput('group_loclong');
$longitude->size = 38;
if ($mode == 'addfixup') {
    $longitude->value = $this->getParam('group_loclong');

    if ($this->getParam('group_loclong') == '') {
        $messages[] =  $this->objLanguage->languageText('mod_unesco_oer_group_message9', 'unesco_oer');
    }
}
if (isset($userstring[7]) && $mode == 'add')
{
    $longitude->value = $userstring[7];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_longitude', 'unesco_oer').$required); // obj lang
$table->addCell($longitude->show());
$table->endRow();

//latitude
$latitude = new textinput('group_loclat');
$latitude->size = 38;
if ($mode == 'addfixup') {
    $latitude->value = $this->getParam('group_loclong');

    if ($this->getParam('group_loclong') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_message8', 'unesco_oer');

        }
}
if (isset($userstring[8]) && $mode == 'add')
{
    $latitude->value = $userstring[8];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_latitude', 'unesco_oer').$required); // obj lang
$table->addCell($latitude->show());
$table->endRow();

////COUNTRY
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
$fieldset->legend = $this->objLanguage->languageText('mod_unesco_oer_group_fieldset3', 'unesco_oer');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');



//$groups = $this->objDbGroups->getAllGroups();
$table = $this->newObject('htmltable', 'htmlelements');
$Institutions = $this->objDbInstitution->getAllInstitutions();
$objSelectBox = $this->newObject('selectbox','htmlelements');
$objSelectBox->create( $form, 'leftList[]', 'Available Institutions', 'rightList[]', 'Chosen Institutions' );
$objSelectBox->insertLeftOptions(
                        $Institutions,
                        'id',
                        'name' );

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
//THEIR BUTTON

$table->startRow();

$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_institution', 'unesco_oer'));
//$table->addCell(implode( ' / ', $arrFormButtons));
$table->addCell($tblSelectBox->show());
$table->addCell();
$table->endRow();







//Register Institutions
//$table = $this->newObject('htmltable', 'htmlelements');
//$Institutions = $this->objDbInstitution->getAllInstitutions();
//$dd=new dropdown('group_institutionlink');
//$dd->addOption("None");
//if(count($Institutions)>0){
//       foreach ($Institutions as $Institution) {
//        $dd->addOption($Institution['name']);
//
//        }
//}
//
//$table->startRow();
//$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_institution', 'unesco_oer'));
//$table->addCell($dd->show());
//$table->endRow();


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('mod_unesco_oer_group_fieldset4', 'unesco_oer');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');
//$button = new button ('submitform',$this->objLanguage->languageText('mod_unesco_oer_group_save_button','unesco_oer'));
//$button->setToSubmit();

$button = new button ('submitform',$this->objLanguage->languageText('mod_unesco_oer_group_save_button', 'unesco_oer'));
//$button->setToSubmit();
$action = $objSelectBox->selectAllOptions( $objSelectBox->objRightList )." SubmitProduct();";
$button->setOnClick('javascript: ' . $action);

$Cancelbutton = new button ('submitform',$this->objLanguage->languageText('mod_unesco_oer_group_cancel_button', 'unesco_oer'));
$Cancelbutton->setToSubmit();
$CancelLink = new link($this->uri(array('action' => "groupListingForm")));
$CancelLink->link =$Cancelbutton->show();

$form->extra = 'enctype="multipart/form-data"';
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
<script type="text/javascript">
function SubmitProduct()
{
    var objForm = document.forms['register'];
    //objForm.elements[element].value = value;
    objForm.submit();
}
</script>