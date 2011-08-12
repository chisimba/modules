
<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul><li class="first">Home</li>
            <li><a href='?module=unesco_oer&action=controlpanel' alt='Adminstrative Tools' title='Adminstrative Tools'>Adminstrative Tools</a></li>
            <li><a href='?module=unesco_oer&action=groupListingForm' alt='Users' title='Users'>Product groups</a></li>
           <li>Edit Group</li>
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

//Get Group details
$group=$this->objDbGroups->getGroupInfo($this->getParam('id'));
$linkedInstitution=$this->objDbGroups->getLinkedInstitution($this->getParam('id'));




// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = $group[0]['name'].":"."Profile";  //objLang
echo $header->show();

$uri=$this->uri(array('action'=>'editGroup','id'=>$this->getParam('id')));
$form = new form ('editer',$uri);
$form->extra = 'enctype="multipart/form-data"';
$messages = array();


$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '100%';
$table->border = '0';
$tableable->cellspacing = '0';
$table->cellpadding = '2';

//Group name
$name = new textinput('group_name');
$name->size = 80;
$name->value = $group[0]['name'];
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
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_name', 'unesco_oer')); // obj lang
$table->addCell($name->show());
$table->endRow();


//group website
$website = new textinput('group_website');
$website->size = 80;
$website->value = $group[0]['website'];
if ($mode == 'addfixup') {
    $website->value = $this->getParam('group_website');

    if ($this->getParam('group_website') == '') {
        $messages[] =$this->objLanguage->languageText('mod_unesco_oer_group_message2', 'unesco_oer');
    }
}
if (isset($userstring[1]) && $mode == 'add')
{
    $website->value = $userstring[1];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_website', 'unesco_oer')); // obj lang
$table->addCell($website->show());
$table->endRow();

//group desctription
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '150px';
$editor->width = '85%';
$editor->setBasicToolBar();
$editor->setContent($group[0]['description']);
if ($mode == 'addfixup') {
    $editor->value = $this->getParam('description');

    if ($this->getParam('description') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_message3', 'unesco_oer');
        }
}
if (isset($userstring[2]) && $mode == 'add')
{
   $editor->value = $userstring[2];
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_description', 'unesco_oer'));
$table->addCell($editor->show());
$table->endRow();



$content.='
          <img src="' . $group[0]['thumbnail'] . '" alt="Featured" width="30" height="30"><br>

                  ';
$table->startRow();
$table->addCell($content);
//$table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer'));
$table->addCell("Change Avatar".'&nbsp;'.$this->objThumbUploader->show());
$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend =$this->objLanguage->languageText('mod_unesco_oer_group_fieldset1', 'unesco_oer');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');

//group contact details
$table = $this->newObject('htmltable', 'htmlelements');

$email = new textinput('register_email');
$email->size = 80;
$email->value = $group[0]['email'];
if ($mode == 'addfixup') {
    $email->value = $this->getParam('register_email');
   }
if (isset($userstring[3]) && $mode == 'add')
{
    $email->value = $userstring[3];
    
}

$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_email', 'unesco_oer'));
$table->addCell($email->show());
$table->endRow();

//address
$address = new textinput('group_address');
$address->size = 80;
$address->value = $group[0]['address'];
if ($mode == 'addfixup') {
    $address->value = $this->getParam('group_address');

    if ($this->getParam('group_address') == '') {
        $messages[] =$this->objLanguage->languageText('mod_unesco_oer_group_message4', 'unesco_oer');
    }
}
if (isset($userstring[4]) && $mode == 'add')
{
    $address->value = $userstring[4];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_address', 'unesco_oer'));
$table->addCell($address->show());
$table->endRow();

//CITY
$city = new textinput('group_city');
$city->size = 80;
$city->value = $group[0]['city'];
if ($mode == 'addfixup') {
    $city->value = $this->getParam('group_city');

    if ($this->getParam('group_city') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_message5', 'unesco_oer');
    }
}
if (isset($userstring[5]) && $mode == 'add')
{
    $city->value = $userstring[5];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_city', 'unesco_oer'));
$table->addCell($city->show());
$table->endRow();

//STATE
$state = new textinput('group_state');
$state->size = 80;
$state->value = $group[0]['state'];
if ($mode == 'addfixup') {
    $state->value = $this->getParam('group_state');

    if ($this->getParam('group_state') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_message6', 'unesco_oer');
    }
}
if (isset($userstring[6]) && $mode == 'add')
{
    $state->value = $userstring[6];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_state', 'unesco_oer'));// obj lang
$table->addCell($state->show());
$table->endRow();

//postal code
$code = new textinput('group_postalcode');
$code->size = 80;
$code->value = $group[0]['postalcode'];
if ($mode == 'addfixup') {
    $code->value = $this->getParam('group_postalcode');

    if ($this->getParam('group_postalcode') == '') {
        $messages[] =$this->objLanguage->languageText('mod_unesco_oer_group_message7', 'unesco_oer');
        }
}
if (isset($userstring[7]) && $mode == 'add')
{
    $code->value = $userstring[7];
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_postalcode', 'unesco_oer'));
$table->addCell($code->show());
$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend =$this->objLanguage->languageText('mod_unesco_oer_group_fieldset2', 'unesco_oer');
$fieldset->contents = $table->show();
$form->addToForm($fieldset->show());
$form->addToForm('<br />');

//latitude
$table = $this->newObject('htmltable', 'htmlelements');
$latitude = new textinput('group_loclat');
$latitude->size = 38;
$latitude->value = $group[0]['loclat'];
if ($mode == 'addfixup') {
    $latitude->value = $this->getParam('group_loclong');

    if ($this->getParam('group_loclong') == '') {
        $messages[] =$this->objLanguage->languageText('mod_unesco_oer_group_message8', 'unesco_oer');
    }
}
if (isset($userstring[8]) && $mode == 'add')
{
    $latitude->value = $userstring[8];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_latitude', 'unesco_oer'));
$table->addCell($latitude->show());
$table->endRow();
//longitude
$longitude = new textinput('group_loclong');
$longitude->size = 38;
$longitude->value = $group[0]['loclong'];
if ($mode == 'addfixup') {
    $longitude->value = $this->getParam('group_loclong');

    if ($this->getParam('group_loclong') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_message9', 'unesco_oer');
    }
}
if (isset($userstring[9]) && $mode == 'add')
{
    $longitude->value = $userstring[9];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_longitude', 'unesco_oer'));
$table->addCell($longitude->show());
$table->endRow();
//country
$table->startRow();
$objCountries = &$this->getObject('languagecode', 'language');
$table->addCell($this->objLanguage->languageText('word_country', 'system'));
if ($mode == 'addfixup') {
    $table->addCell($objCountries->countryAlpha($this->getParam('country')));
} else {
    $table->addCell($objCountries->countryAlpha());
}
$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend =$this->objLanguage->languageText('mod_unesco_oer_group_fieldset3', 'unesco_oer');
$fieldset->contents = $table->show();
$form->addToForm($fieldset->show());
$form->addToForm('<br />');







$table = $this->newObject('htmltable', 'htmlelements');
$groups =$this->objDbInstitution->getAllInstitutions();
$availablegroups=array();
foreach ($groups as $group) {
    if(count($user_current_membership)!=0){
        foreach ($user_current_membership as $membership) {
            if (strcmp($group['id'], $membership['groupid']) != 0){
                array_push($availablegroups, $group);
                }
                }
                }
        else{ /// TODO WHY IS NOT SHOWING ON EDIT ADMIN
            array_push($availablegroups, $group);
            
        }
    
}
$objSelectBox = $this->newObject('selectbox','htmlelements');
$objSelectBox->create( $form, 'leftList[]', 'Available Institutionss', 'rightList[]', 'Chosen Institutions' );
$objSelectBox->insertLeftOptions(
                        $availablegroups,
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

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_institution', 'unesco_oer'));
$table->addCell($tblSelectBox->show());
$table->endRow();














//
//$table = $this->newObject('htmltable', 'htmlelements');
//// Linked institution
//// first the belonging instituion
//// then the list of all the insstritution the thedatabase
//$Institutions=$this->objDbInstitution->getAllInstitutions();
//$dd=new dropdown('group_institutionlink');
//$dd->addOption($linkedInstitution);
//if(count($Institutions)>0){
//     foreach ($Institutions as $Institution) {
//        $dd->addOption($Institution['name']);
//       }
//    }else{
//         $dd->addOption('None');// obj lang
//
//    }
//
//$table->startRow();
//$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_institution', 'unesco_oer'));
//$table->addCell($dd->show());
//$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend =$this->objLanguage->languageText('mod_unesco_oer_group_fieldset4', 'unesco_oer');
$fieldset->contents = $table->show();
$form->addToForm($fieldset->show());
$form->addToForm('<br />');

$button = new button ('submitform', $this->objLanguage->languageText('mod_unesco_oer_group_update_details_button', 'unesco_oer') );
$button->setToSubmit();

$Cancelbutton = new button ('submitform', $this->objLanguage->languageText('mod_unesco_oer_group_cancel_button', 'unesco_oer'));
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


?>

<script type="text/javascript">
function SubmitProduct()
{
    var objForm = document.forms['editer'];
    //objForm.elements[element].value = value;
    objForm.submit();
}
</script>