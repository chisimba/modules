<script language="JavaScript">

if(!document.getElementById && document.all)
document.getElementById = function(id){ return document.all[id]} 


    function toggleArchiveInput()
    {
        if (document.editForum.archivingRadio[1].checked)
            {
                showhide('dateSelect', 'visible');
            } else{
                showhide('dateSelect', 'hidden');
            }
            
    }
    
    function showhide (id, visible)
    {
        var style = document.getElementById(id).style
        style.visibility = visible;
    }
</script>
<?php

$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
echo $objHighlightLabels->show();

$header =& new htmlheading();
$header->type=3;

if ($action == 'edit') {
    $header->str=$this->objLanguage->languageText('mod_forum_editForumSettings').': '.$forum['forum_name'];
    $formAction = 'editforumsave';
} else {
    $header->str=$this->objLanguage->languageText('mod_forum_createNewForum').': '.$contextTitle;
    $formAction = 'saveforum';
}

echo $header->show();

$form =& new form('editForum', $this->uri( array('module'=>'forum', 'action'=>$formAction)));
$form->displayType = 3;

$table = $this->getObject('htmltable', 'htmlelements');
$table->width='80%';
$table->cellpadding = 10;


// --------- New Row ---------- //

$table->startRow();
$nameLabel = new label($this->objLanguage->languageText('mod_forum_nameofforum').':', 'input_name');
$table->addCell('<strong>'.$nameLabel->show().'</strong>', 120);

$nameInput = new textinput('name');
$nameInput->size = 57;
$nameInput->extra = 'maxlength="50"';

if ($action == 'edit') {
    $nameInput->value = $forum['forum_name'];
}

$table->addCell($nameInput->show(),  null,  null, null, null, ' colspan="3"');

$table->endRow();

// --------- New Row ---------- //

$table->startRow();
$nameLabel =& new label($this->objLanguage->languageText('word_description').':', 'input_description');
$table->addCell('<strong>'.$nameLabel->show().'</strong>', 100);

$nameInput = new textinput('description');
$nameInput->size = 100;
$nameInput->extra = 'maxlength="255"';
if ($action == 'edit') {
    $nameInput->value = $forum['forum_description'];
}
$table->addCell($nameInput->show(),  null,  null, null, null, ' colspan="3"' );

$table->endRow();

// --------- New Row ---------- //

if ($action == 'edit') {

    $table->startRow();
    
    $table->addCell('<strong>'.$this->objLanguage->languageText('mod_forum_lockforum').'</strong>');
    
    $radioGroup =& new radio('lockforum');
    $radioGroup->setBreakSpace(' / ');
    
    // The option NO comes before YES - as no is this preferred
    $radioGroup->addOption('N','No');
    $radioGroup->addOption('Y', $this->objLanguage->languageText('word_yes'));
    
    $radioGroup->setSelected($forum['forumlocked']);
    
    $message = ' - '.$this->objLanguage->languageText('mod_forum_explainlocking').'.';
    
    $table->addCell($radioGroup->show().$message,  null,  null, null, null, ' colspan="3"' );
    
    $table->endRow();

}


// --------- New Row - Visibility & Rating Forums ---------- //

$table->startRow();
$title = '<nobr>'.$this->objLanguage->languageText('mod_forum_visible').':</nobr>';
$table->addCell('<strong>'.$title.'</strong>', 100);

if ($action == 'edit' && $forum['defaultforum'] == 'Y') {
    $hiddenIdInput = new textinput('visible');
    $hiddenIdInput->fldType = 'hidden';
    $hiddenIdInput->value = 'default';
  
    $table->addCell($this->objLanguage->languageText('mod_forum_defaultforum').$hiddenIdInput->show());
} else {
    $radioGroup = new radio('visible');
    $radioGroup->setBreakSpace(' / ');
    $radioGroup->addOption('Y', $this->objLanguage->languageText('word_yes'));
    $radioGroup->addOption('N', $this->objLanguage->languageText('word_no'));
    
    if ($action == 'edit') {
        $radioGroup->setSelected($forum['forum_visible']);
    } else {
        $radioGroup->setSelected('Y');
    }
    
    $table->addCell($radioGroup->show());
}


$title = '<nobr><strong>'.$this->objLanguage->languageText('mod_forum_usersrateposts').':</strong></nobr>';
$table->addCell($title, 100);

$radioGroup = new radio('ratings');
$radioGroup->setBreakSpace(' / ');
$radioGroup->addOption('Y', $this->objLanguage->languageText('word_yes'));
$radioGroup->addOption('N', $this->objLanguage->languageText('word_no'));
if ($action == 'edit') {
    $radioGroup->setSelected($forum['ratingsenabled']);
} else {
    $radioGroup->setSelected('Y');
}

$table->addCell($radioGroup->show());
$table->endRow();

// --------- New Row - Students start Topics & upload attachments ---------- //

$table->startRow();
$title = '<nobr><strong>'.ucwords($this->objLanguage->code2Txt('mod_forum_studentsstartTopics')).':</strong></nobr>';
$table->addCell($title, 100);

$radioGroup = new radio('student');
$radioGroup->setBreakSpace(' / ');
$radioGroup->addOption('Y', $this->objLanguage->languageText('word_yes'));
$radioGroup->addOption('N', $this->objLanguage->languageText('word_no'));
if ($action == 'edit') {
    $radioGroup->setSelected($forum['studentstarttopic']);
} else {
    $radioGroup->setSelected('Y');
}

$table->addCell($radioGroup->show());
$title = '<nobr><strong>'.$this->objLanguage->languageText('mod_forum_usersuploadattachments').':</strong></nobr>';
$table->addCell($title, 100);

$radioGroup = new radio('attachments');
$radioGroup->setBreakSpace(' / ');
$radioGroup->addOption('Y', $this->objLanguage->languageText('word_yes'));
$radioGroup->addOption('N', $this->objLanguage->languageText('word_no'));
if ($action == 'edit') {
    $radioGroup->setSelected($forum['attachments']);
} else {
    $radioGroup->setSelected('Y');
}

$table->addCell($radioGroup->show());
$table->endRow();

// --------- New Row - Subscriptions ---------- //

$table->startRow();
$title = '<nobr><strong>'.$this->objLanguage->languageText('mod_forum_enableemailsubscription').':</strong></nobr>';
$table->addCell($title, 100);

$radioGroup = new radio('subscriptions');
$radioGroup->setBreakSpace(' / ');
$radioGroup->addOption('Y', $this->objLanguage->languageText('word_yes'));
$radioGroup->addOption('N', $this->objLanguage->languageText('word_no'));
if ($action == 'edit') {
    $radioGroup->setSelected($forum['subscriptions']);
} else {
    $radioGroup->setSelected('Y');
}

$table->addCell($radioGroup->show());

$table->addCell('&nbsp;');
$table->addCell('&nbsp;');
$table->endRow();


// --------- End Row ---------- //

// --------- New Row ---------- //

if ($action == 'edit') {
    $headerParams=$this->getJavascriptFile('ts_picker.js','htmlelements');
    $headerParams.="<script>/*Script by Denis Gritcyuk: tspicker@yahoo.com
    Submitted to JavaScript Kit (http://javascriptkit.com)
    Visit http://javascriptkit.com for this script*/ </script>";
    $this->appendArrayVar('headerParams',$headerParams);

    $table->startRow();
    
    $table->addCell('<strong><nobr>'.$this->objLanguage->languageText('mod_forum_archivelabel').':</nobr></strong>', 100);
    
    $radioGroup = new radio('archivingRadio');
    $radioGroup->setBreakSpace(' / ');
    
    // The option NO comes before YES - as no is this preferred
    $radioGroup->addOption('N', $this->objLanguage->languageText('word_no'));
    $radioGroup->addOption('Y', $this->objLanguage->languageText('word_yes'));
    $radioGroup->extra='onClick="toggleArchiveInput()"';
    
    $archiveInput = new textinput('archive');
    $archiveInput->size = 10;
    
    if ($forum['archivedate'] == '') {
        $radioGroup->setSelected('N');
    } else {
        $radioGroup->setSelected('Y');
        $archiveInput->value = $forum['archivedate'];
    }
    
    $objIcon=& $this->newObject('geticon', 'htmlelements');
    $objIcon->setIcon('select_date');
    $objIcon->title=$this->objLanguage->languageText('mod_calendarbase_selectdate');
    $selectDateLink = new link("javascript:show_calendar('document.editForum.archive', document.editForum.archive.value);");
    $selectDateLink->link = $objIcon->show();
    
    $cell = $radioGroup->show().' <span id="dateSelect"> - '.$archiveInput->show().' '.$selectDateLink->show().' <br /><span class="warning">'.$this->objLanguage->languageText('mod_forum_archivewarning').'</span></span>';
    $table->addCell($cell,  null,  null, null, null, ' colspan="3"');
    
    $table->endRow();
}

// --------- End Row ---------- //
$submitButton = new button('submitbtn', $this->objLanguage->languageText('word_submit'));
$submitButton->setToSubmit();

$cancelButton = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$returnUrl = $this->uri(array('action'=>'administration'));
$cancelButton->setOnClick("window.location='$returnUrl'");

$table->addCell($submitButton->show().' / '.$cancelButton->show(),  null,  null, null, null, ' colspan="4"');

if ($action == 'edit') {
    $hiddenIdInput =& new textinput('id');
    $hiddenIdInput->fldType = 'hidden';
    $hiddenIdInput->value = $forum['id'];
    $form->addToForm($hiddenIdInput->show());
}

$form->addToForm($table->show());

$form->addRule('name', $this->objLanguage->languageText('mod_forum_forumnameneeded', 'Please enter a Name for the Discussion Forum'), 'required');
$form->addRule('description', $this->objLanguage->languageText('mod_forum_forumdescriptionneeded', 'Please enter a Description for the Discussion Forum'), 'required');

echo $form->show();

?>