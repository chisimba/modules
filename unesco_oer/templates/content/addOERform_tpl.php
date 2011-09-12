
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
$required = '<span class="required_field"> * ';




// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str =$this->objDbGroups->getGroupName($this->getParam('id')).":"."add resource";  //objLang
echo $header->show();

$uri=$this->uri(array('action'=>'editGroup','id'=>$this->getParam('id')));
$form = new form ('editer',$uri);
//$form->extra = 'enctype="multipart/form-data"';
$messages = array();


$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '100%';
$table->border = '0';
$tableable->cellspacing = '0';
$table->cellpadding = '2';

$groupid=$this->getParam('id');

//Resource name
$resource_name = new textinput('resource_name');
$resource_name ->size = 80;
$resource_name->value = $group[0]['resource_name'];
if ($mode == 'addfixup') {
    $resource_name->value = $this->getParam('resource_name');

    if ($this->getParam('resource_name') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_resorce_name_message', 'unesco_oer');
    }
}
if (isset($userstring[1]) && $mode == 'add')
{
    $resource_name->value = $userstring[1];
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_resorce_name','unesco_oer').$required); // obj lang
$table->addCell($resource_name->show());
$table->endRow();

//Resource Type
$resource_type = new textinput('resource_type');
$resource_type->size = 80;
$resource_type->value = $group[0]['resource_type'];
if ($mode == 'addfixup') {
    $resource_type->value = $this->getParam('resource_type');

    if ($this->getParam('resource_type') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_resorce_type_message', 'unesco_oer');
    }
}
if (isset($userstring[1]) && $mode == 'add')
{
    $resource_type->value = $userstring[1];
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_resorce_type','unesco_oer').$required); // obj lang
$table->addCell($resource_type->show());
$table->endRow();

//Resource Author
$resource_author = new textinput('resource_author');
$resource_author->size = 80;
$resource_author->value = $group[0]['resource_author'];
if ($mode == 'addfixup') {
    $resource_author->value = $this->getParam('resource_author');

    if ($this->getParam('resource_author') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_resorce_author_message', 'unesco_oer');
    }
}
if (isset($userstring[1]) && $mode == 'add')
{
    $resource_author->value = $userstring[1];
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_resorce_author','unesco_oer').$required); // obj lang
$table->addCell($resource_author->show());
$table->endRow();

//Resource publisher
$resource_publisher = new textinput('resource_publisher');
$resource_publisher ->size = 80;
$resource_publisher->value = $group[0]['resource_publisher'];
if ($mode == 'addfixup') {
    $resource_publisher->value = $this->getParam('resource_publisher');

    if ($this->getParam('resource_author') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_resorce_author_message', 'unesco_oer');
    }
}
if (isset($userstring[1]) && $mode == 'add')
{
   $resource_publisher->value = $userstring[1];
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_resorce_author','unesco_oer').$required); // obj lang
$table->addCell($resource_publisher->show());
$table->endRow();

$fileUploader = $this->getObject('fileuploader', 'files');
$fileUploader->allowedCategories = array('documents', 'images');
$fileUploader->savePath = '/uploader/'; // This will then be saved in usrfiles/uploader
$fileUploader->overwriteExistingFile = TRUE;
$results = $fileUploader->uploadFile('fileupload1');


$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer').$required);
$table->addCell($this->objThumbUploader->show());
$table->endRow();

$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('mod_unesco_oer_group_fieldset_oer', 'unesco_oer');
$fieldset->contents = $table->show();
//File uploader


$Savebutton = new button ('submitform',$this->objLanguage->languageText('mod_unesco_oer_group_save_button', 'unesco_oer'));
$Savebutton->setToSubmit();
$SavebuttonLink = new link($this->uri(array('action' => "addOER")));
$SavebuttonLink->link=$Savebutton->show();

$Cancelbutton = new button ('cancelform',$this->objLanguage->languageText('mod_unesco_oer_group_cancel_button', 'unesco_oer'));

$form->addToForm($fieldset->show());
$form->extra = 'enctype="multipart/form-data"';
$form->addToForm('<p align="right">'.$SavebuttonLink->show().$Cancelbutton->show().'</p>');



echo $form->show();


?>

<script type="text/javascript">
function SubmitProduct()
{
    var objForm = document.forms['editer'];
    //objForm.elements[element].value = value;
    objForm.submit();
}

$('button[name=cancelform]').click(
    function() {
        window.location ='index.php?module=unesco_oer&action=11a&id=_13858_1315227715&page=10a_tpl.php';
   // $('#showhide').slideToggle();
    }
);
</script>