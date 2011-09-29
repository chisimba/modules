
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
// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$parent_id=$this->getParam('parent_id');
 
$parentInfo=$this->objDbGroups->getGroupInfo($parent_id);

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
$header->str = $this->objLanguage->languageText('mod_unesco_oer_sub_group_create', 'unesco_oer');
echo '<div style="padding:10px;">'.$header->show();

$required = '<span class="required_field"> * ';
echo '<span class="required_field"> (*) '."All field are required to be filled in Order to register into Unesco_OER".'</span>';

$uri=$this->uri(array('action'=>'saveSubgroup','parent_id'=>$this->getParam('parent_id'),"page"=>"10a_tpl.php"));
$form = new form ('register', $uri);





$messages = array();

$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '100%';
$table->border = '0';
$tableable->cellspacing = '0';
$table->cellpadding = '2';


// SUBGROUPNAME
$name = new textinput('subgroup_name');
$name->size = 80;
if ($mode == 'addfixup') {
    $name->value = $this->getParam('subgroup_name');

    if ($this->getParam('subgroup_name') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_sub_group_message1', 'unesco_oer');
    }
}
if (isset($userstring[0]) && $mode == 'add')
{
    $name->value = $userstring[0];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_sub_group_name', 'unesco_oer').$required );
$table->addCell($name->show());
$table->endRow();



//Description
$description = new textinput('description');
$description ->size = 80;
if ($mode == 'addfixup') {
    $description->value = $this->getParam('description');

    if ($this->getParam('description') == '') {
        $messages[] = $this->objLanguage->languageText('mod_unesco_oer_group_description_line', 'unesco_oer');
    }
}
if (isset($userstring[1]) && $mode == 'add')
{
    $description->value = $userstring[1];
}
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_sub_group_description', 'unesco_oer').$required); // obj lang
$table->addCell($description ->show());
$table->endRow();

//Brief descrption
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'briefdescription';
$editor->height = '150px';
$editor->width = '85%';
$editor->setBasicToolBar();
$editor->setContent();
if ($mode == 'addfixup') {
    $editor->value = $this->getParam('briefdescription');

    if ($this->getParam('briefdescription') == '') {
        $messages[] =  $this->objLanguage->languageText('mod_unesco_oer_group_message3', 'unesco_oer');
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


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend =$this->objLanguage->languageText('mod_unesco_oer_group_fieldset1', 'unesco_oer');
$fieldset->contents = $table->show();
$form->addToForm($fieldset->show());
$form->addToForm('<br />');




$table = $this->newObject('htmltable', 'htmlelements');

//Interest
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'interest';
$editor->height = '150px';
$editor->width = '85%';
$editor->setBasicToolBar();
$editor->setContent();
if ($mode == 'addfixup') {
    $editor->value = $this->getParam('interest');

    if ($this->getParam('interest') == '') {
        $messages[] =  $this->objLanguage->languageText('mod_unesco_oer_group_message3', 'unesco_oer');
    }
}
if (isset($userstring[2]) && $mode == 'add')
{
   $editor->value = $userstring[2];
}

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_interest', 'unesco_oer'));
$table->addCell($editor->show());
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
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_website', 'unesco_oer')); // obj lang
$table->addCell($website->show());
$table->endRow();



//Descriprion line two
//$description_two = new textinput('description_two');
//$description_two->size = 80;
//$table->startRow();
//$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_description_two', 'unesco_oer')); // obj lang
//$table->addCell($description_two->show());
//$table->endRow();



$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend =$this->objLanguage->languageText('mod_unesco_oer_group_fieldset5', 'unesco_oer');
$fieldset->contents = $table->show();
$form->addToForm($fieldset->show());
$form->addToForm('<br />');



$button = new button ('submitform',$this->objLanguage->languageText('mod_unesco_oer_group_save_button', 'unesco_oer'));
$button->setToSubmit();

$Cancelbutton = new button ('cancelform',$this->objLanguage->languageText('mod_unesco_oer_group_cancel_button', 'unesco_oer'));


$form->extra = 'enctype="multipart/form-data"';
$form->addToForm('<p align="right">'.$button->show().$Cancelbutton->show().'</p>');

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

$('button[name=cancelform]').click(
    function() {
        window.location ='/index.php?module=unesco_oer&action=8a&id=<?php echo $this->getParam('parent_id'); ?>&page=10a_tpl.php';

    }
);
</script>