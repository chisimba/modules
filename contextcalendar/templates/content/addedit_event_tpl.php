<script language="JavaScript">

if(!document.getElementById && document.all)
document.getElementById = function(id){ return document.all[id]} 


    function toggleMultiDayInput()
    {
        if (document.eventform.multidayevent[1].checked)
            {
                showhide('cal_input2', 'visible');
                showhide('cal_label2', 'visible');
            } else{
                showhide('cal_input2', 'hidden');
                showhide('cal_label2', 'hidden');
            }
            
    }
    
    function showhide (id, visible)
    {
        var style = document.getElementById(id).style
        style.visibility = visible;
    }
</script>
<?php

$bodyParams = ' onLoad="toggleMultiDayInput();"';

//$this->setVarByRef('bodyParams',$bodyParams);

if ($mode == 'edit') {
    $action = 'updateevent';
    $title = $this->objLanguage->languageText('mod_calendarbase_editevent');
} else {
    $mode = 'add';
    $action = 'saveevent';
    $title = $this->objLanguage->languageText('mod_calendarbase_addevent');
}
$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('radio', 'htmlelements');

$headerParams=$this->getJavascriptFile('ts_picker.js','htmlelements');
$headerParams.="<script>/*Script by Denis Gritcyuk: tspicker@yahoo.com
Submitted to JavaScript Kit (http://javascriptkit.com)
Visit http://javascriptkit.com for this script*/ </script>";
$this->appendArrayVar('headerParams',$headerParams);


echo ('<h1>'.$title.'</h1>');




$table=$this->newObject('htmltable','htmlelements');


$table->width='';
$table->cellspacing='2';
$table->cellpadding='2';

$table->startRow();
$multiDayChoice = new radio('multidayevent');
$multiDayChoice->addOption('0', $this->objLanguage->languageText('word_no'));
$multiDayChoice->addOption('1', $this->objLanguage->languageText('word_yes'));
if ($mode =='edit' && $event['multiday_event'] == 1) {
    $multiDayChoice->setSelected('1');
} else {
    $multiDayChoice->setSelected('0');
}
$multiDayChoice->setBreakSpace(' / ');
$multiDayChoice->extra='onClick="toggleMultiDayInput()"';

$text = $this->objLanguage->languageText('mod_calendarbase_isthisamultidayevent', 'Is this a multiday event? ').$multiDayChoice->show();

$table->addCell($text, NULL, NULL, NULL, NULL, 'colspan="3"');
$table->endRow();

$table->startRow();
$dateLabel = new label($this->objLanguage->languageText('mod_calendarbase_dateofevent').':', 'input_date');
$table->addCell($dateLabel->show());

$dateInput = new textinput('date');
$dateInput->extra = ' READONLY';
if ($mode == 'edit') {
    $dateInput->value = $event['eventdate'];
} else {
    $dateInput->value = date('Y-m-d');
}

$objIcon=& $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('select_date');
$objIcon->title=$this->objLanguage->languageText('mod_calendarbase_selectdate');
$selectDateLink = new link("javascript:show_calendar('document.eventform.date', document.eventform.date.value);");
$selectDateLink->link = $objIcon->show();

$table->addCell($dateInput->show().' '.$selectDateLink->show());

// --- SECOND DATE --- //

$dateLabel2 = new label($this->objLanguage->languageText('mod_calendarbase_dateofevent').':', 'input_date');

if ($mode =='add' || $event['multiday_event'] != 1) {
    $style=' style="visibility:hidden"';
} else {
    $style = '';
}
$table->addCell('<div id="cal_label2" '.$style.'>'.$dateLabel2->show().'</div>');

$dateInput2 = new textinput('date2');
$dateInput2->extra = ' READONLY';
if ($mode == 'edit') {
    $dateInput2->value = $event['eventdate2'];
} else {
    $dateInput2->value = date('Y-m-d');
}

$objIcon=& $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('select_date');
$objIcon->title=$this->objLanguage->languageText('mod_calendarbase_selectdate');
$selectDateLink = new link("javascript:show_calendar('document.eventform.date2', document.eventform.date2.value);");
$selectDateLink->link = $objIcon->show();

$table->addCell('<div id="cal_input2" '.$style.'>'.$dateInput2->show().' '.$selectDateLink->show().'</div>');

$table->endRow();

// end - date inputs //

$table->startRow();
$titleLabel = new label($this->objLanguage->languageText('mod_calendarbase_eventtitle').':', 'input_title');
$table->addCell($titleLabel->show());

$titleInput = new textinput('title');
if ($mode == 'edit') {
    $titleInput->value = $event['eventtitle'];
}

$titleInput->size = '50';

$table->addCell($titleInput->show(), NULL, NULL, NULL, NULL, 'colspan="3"');
$table->endRow();
    
$table->startRow();
$detailsLabel = new label($this->objLanguage->languageText('mod_calendarbase_eventdetails').':', 'input_details');
$table->addCell($detailsLabel->show());

$detailsTextArea = new textarea('details');
if ($mode == 'edit') {
    $detailsTextArea->value = $event['eventdetails'];
}
$table->addCell($detailsTextArea->show(), NULL, NULL, NULL, NULL, 'colspan="3"');
$table->endRow();

$table->startRow();
$urlLabel = new label($this->objLanguage->languageText('mod_calendarbase_relatedwebsite').':', 'input_url');
$table->addCell($urlLabel->show());

$urlInput = new textinput('url');
if (($mode == 'edit') && ($event['eventurl'] != '')) {
    $urlInput->value = $event['eventurl'];
} else {
    $urlInput->value = 'http://';
}
$urlInput->size='50';
$table->addCell($urlInput->show(), NULL, NULL, NULL, NULL, 'colspan="3"');
$table->endRow();


$form = new form('eventform', $this->uri( array('action'=>$action)));
$form->addToForm($table->show());


if ($mode == 'edit') {
    $idInput = new textinput('id');
    $idInput->value = $event['id'];
    $idInput->fldType = 'hidden';
    $form->addToForm($idInput->show());
    
    if ($event['multiday_event'] == 1) {
        $multidayOriginalInput = new textinput('multiday_event_original');
        $multidayOriginalInput->value = $event['multiday_event_start_id'];
        $multidayOriginalInput->fldType = 'hidden';
        $form->addToForm($multidayOriginalInput->show());
    }
    
}

$submitButton = new button('submit', $this->objLanguage->languageText('mod_calendarbase_saveevent'));
$submitButton->setToSubmit();

$form->addToForm('<p>'.$submitButton->show().'</p>');

echo $form->show();
?>