<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loader');

$loadingIcon = $objIcon->show();

echo $this->objNewsMenu->toolbar('photos');

$header = new htmlheading();
$header->type = 1;

if ($mode == 'edit') {
    $header->str = $this->objLanguage->languageText('phrase_editalbum', 'phrase', 'Edit Album').' - '.$album['albumname'];
    $formAction = 'updatealbum';
} else {
    $header->str = $this->objLanguage->languageText('phrase_addalbum', 'phrase', 'Add Album').' Album';
    $formAction = 'savealbum';
}

echo $header->show();


$form = new form ('addalbum', $this->uri(array('action'=>$formAction)));

$table = $this->newObject('htmltable', 'htmlelements');

$table->startRow();
$label = new label ($this->objLanguage->languageText('mod_news_nameofalbum', 'news', 'Name of Album'), 'input_albumname');
$albumName = new textinput('albumname');

if ($mode == 'edit') {
    $albumName->value = $album['albumname'];
}

$table->addCell($label->show());
$table->addCell($albumName->show());
$table->endRow();

$table->startRow();
$label = new label ($this->objLanguage->languageText('mod_news_descriptionofalbum', 'news', 'Description of Album'), 'input_albumname');
$albumDescription = new textarea('albumdescription');
if ($mode == 'edit') {
    $albumDescription->value = $album['albumdescription'];
}
$table->addCell($label->show());
$table->addCell($albumDescription->show());
$table->endRow();

$table->startRow();
$objDate = $this->newObject('datepicker', 'htmlelements');
$objDate->name = 'albumdate';
if ($mode == 'edit') {
    $objDate->defaultDate = $album['albumdate'];
}
$table->addCell($this->objLanguage->languageText('mod_news_albumdate', 'news', 'Album Date'));
$table->addCell($objDate->show());
$table->endRow();

$label = new label ($this->objLanguage->languageText('mod_news_albumlocation', 'news', 'Album Location'), ' input_parentlocation');

$locationInput = new textinput('albumlocation');
$locationInput->size = 50;
$locationInput->cssId = 'albumlocation';

$locationInput->extra = ' onkeypress="return submitenter(this,event);"';

if ($mode == 'edit' && $album['name'] != NULL) {
    $locationInput->value = $album['name'];
}

$checkLocation = new button ('checklocation', 'Check');
$checkLocation->setOnClick('checkLocation();');

if ($mode == 'edit' && $album['name'] != NULL) {
    $location = new radio ('location');
    $location->addOption($album['albumlocation'], $album['name'].' <em>- original</em>');
    $location->setSelected($album['albumlocation']);
    $locationExtra = '<br />'.$location->show().'<br />';
} else {
    $locationExtra = '';
}


$table->startRow();
$table->addCell($label->show());
$table->addCell($locationInput->show().' '.$checkLocation->show().$locationExtra.'<div id="locationloading">'.$loadingIcon.'</div><div id="locationdiv" ></div>');
$table->endRow();


$keyTag1 = new textinput('keytag1');
$keyTag1->size = 50;
$keyTag1->cssId = 'keytag1';

if ($mode == 'edit' && isset($keywords[0])) {
    $keyTag1->value = $keywords[0]['keyword'];
}

$keyTag2 = new textinput('keytag2');
$keyTag2->size = 50;
$keyTag2->cssId = 'keytag2';

if ($mode == 'edit' && isset($keywords[1])) {
    $keyTag2->value = $keywords[1]['keyword'];
}

$keyTag3 = new textinput('keytag3');
$keyTag3->size = 50;
$keyTag3->cssId = 'keytag3';

if ($mode == 'edit' && isset($keywords[2])) {
    $keyTag3->value = $keywords[2]['keyword'];
}

$label = new label ($this->objLanguage->languageText('mod_news_keytags', 'news', 'Key Tags'), 'input_keytag1');

$table->startRow();
$table->addCell($label->show());
$table->addCell($keyTag1->show().'<div id="autocomplete_choices1" class="autocomplete"></div><br />'.$keyTag2->show().'<div id="autocomplete_choices2" class="autocomplete"></div><br />'.$keyTag3->show().'<div id="autocomplete_choices3" class="autocomplete"></div><br />&nbsp;');
$table->endRow();


$table->startRow();
if ($mode == 'edit') {
    $button = new button ('submitform', $this->objLanguage->languageText('mod_news_updatealbum', 'news', 'Update Album'));
} else {
    $button = new button ('submitform', $this->objLanguage->languageText('mod_news_createalbum', 'news', 'Create Album'));
}
$button->setToSubmit();
$table->addCell('&nbsp;');
$table->addCell($button->show());
$table->endRow();

$form->addToForm($table->show());

if ($mode == 'edit') {
    $hiddeninput = new hiddeninput('id', $album['id']);
    $form->addToForm($hiddeninput->show());
}


echo $form->show();

?>
<script type="text/javascript">

//<![CDATA[
    var pars   = 'module=news&action=ajaxkeywords&tag=keytag1';
	new Ajax.Autocompleter("keytag1", "autocomplete_choices1", "index.php", {parameters: pars});
	var pars   = 'module=news&action=ajaxkeywords&tag=keytag2';
	new Ajax.Autocompleter("keytag2", "autocomplete_choices2", "index.php", {parameters: pars});
	var pars   = 'module=news&action=ajaxkeywords&tag=keytag3';
	new Ajax.Autocompleter("keytag3", "autocomplete_choices3", "index.php", {parameters: pars});

//new Ajax.Autocompleter("input_keytag1", "autocomplete_choices", "index.php", {paramName: "value", minChars: 2, updateElement: addItemToList, indicator: 'indicator1'});

//]]>
</script>
<script type="text/javascript">
//<![CDATA[



function checkLocation () {
	
    if ($('albumlocation').value == '') {
        alert('Please enter the location');
        $('storylocation').focus();
    } else {
    
    var url = 'index.php';
	var pars = 'module=news&action=checklocation&location='+$('albumlocation').value;
    
    $('locationloading').style.display='block';
	
    var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showLocationResponse} );
    }
}

function ck(location)
{
    $('albumlocation').value = location;
    checkLocation();
}

function showLocationResponse (originalRequest) {
	var newData = originalRequest.responseText;
    $('locationloading').style.display='none';
    if (newData != '') {
        $('locationdiv').innerHTML = newData;
        adjustLayout();
    }
}

function submitenter(myfield,e)
{
    var keycode;
    if (window.event) keycode = window.event.keyCode;
    else if (e) keycode = e.which;
    else return true;

    if (keycode == 13)
       {
       checkLocation();
       return false;
       }
    else
       return true;
    }
//]]>
</script>
<style type="text/css">

div#locationloading {
    display: none;
}

div.autocomplete {
	position:absolute;
	width:250px;
	background-color:white;
	border:1px solid #888;
	margin:0px;
	padding:0px;
}
div.autocomplete ul {
	list-style-type:none;
	margin:0px;
	padding:0px;
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
	list-style-type:none;
	background-image: none;
	display:block;
	margin:0;
	padding:2px;
	height:32px;
	cursor:pointer;
}

</style>