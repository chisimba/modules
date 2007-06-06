<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');

if ($mode == 'edit') {
    $formAction = 'updatestory';
    $title = 'Edit Story';
    $buttonText = 'Update Story';
} else {
    $formAction = 'savestory';
    $title = 'Add New Story';
    $buttonText = 'Save Story';
}

// Header
$header = new htmlheading();
$header->type = 1;
$header->str = $title;
echo $header->show();

// Create Form
$form = new form ('addedit', $this->uri(array('action'=>$formAction)));

$formTable = $this->newObject('htmltable', 'htmlelements');

$storyTitle = new textinput('storytitle');
$storyTitle->size = 60;

if ($mode == 'edit') {
    $storyTitle->value = htmlentities($story['storytitle']);
}

$label = new label ('Story Title', 'input_storytitle');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($storyTitle->show());
$formTable->endRow();

$datePicker = $this->newObject('datepicker', 'htmlelements');
$datePicker->name = 'storydate';

if ($mode == 'edit') {
    $datePicker->defaultDate = $story['storydate'];
}

$formTable->startRow();
$formTable->addCell('Story Date');
$formTable->addCell($datePicker->show());
$formTable->endRow();

$label = new label ('Story Category', ' input_storycategory');
$storyCategory = new dropdown('storycategory');

if (count($categories) > 0) {
	foreach ($categories as $category)
	{
		$storyCategory->addOption($category['id'], $category['categoryname']);
	}
    
    if ($mode == 'edit') {
        $storyCategory->setSelected($story['storycategory']);
    }
}


$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($storyCategory->show());
$formTable->endRow();

$label = new label ('Story Location', ' input_parentlocation');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($tree);
$formTable->endRow();


$keyTag1 = new textinput('keytag1');
$keyTag1->size = 50;
$keyTag1->cssId = 'keytag1';

if ($mode == 'edit' && isset($keywords[0])) {
    $keyTag1->value = $keywords[0];
}

$keyTag2 = new textinput('keytag2');
$keyTag2->size = 50;
$keyTag2->cssId = 'keytag2';

if ($mode == 'edit' && isset($keywords[1])) {
    $keyTag2->value = $keywords[1];
}

$keyTag3 = new textinput('keytag3');
$keyTag3->size = 50;
$keyTag3->cssId = 'keytag3';

if ($mode == 'edit' && isset($keywords[2])) {
    $keyTag3->value = $keywords[2];
}

$label = new label ('Key Tags', 'input_keytag1');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($keyTag1->show().'<div id="autocomplete_choices1" class="autocomplete"></div><br />'.$keyTag2->show().'<div id="autocomplete_choices2" class="autocomplete"></div><br />'.$keyTag3->show().'<div id="autocomplete_choices3" class="autocomplete"></div><br />&nbsp;');
$formTable->endRow();

$storyTags = new textarea('storytags');

if ($mode == 'edit' && count($tags) > 0 && is_array($tags)) {
    
    $divider = '';
    
    foreach ($tags as $tag) {
        $storyTags->value .= $divider.$tag;
        $divider = ', ';
    }
}

$label = new label ('Story Tags<br /><em>Separate with commas</em>', 'input_storytags');

$formTable->startRow();
$formTable->addCell($label->show());
$formTable->addCell($storyTags->show().'<br />&nbsp;');
$formTable->endRow();

///


$objImageSelect = $this->newObject('selectimage', 'filemanager');

if ($mode == 'edit') {
    $objImageSelect->defaultFile = $story['storyimage'];
}

$topTable = $this->newObject('htmltable', 'htmlelements');

$topTable->startRow();
$topTable->addCell($formTable->show());
$topTable->addCell($objImageSelect->show());
$topTable->endRow();


$form->addToForm($topTable->show());

$storyTable = $this->newObject('htmltable', 'htmlelements');

$htmlarea = $this->newObject('htmlarea', 'htmlelements');
$htmlarea->name = 'storytext';

if ($mode == 'edit') {
    $htmlarea->value = $story['storytext'];
}

$storyTable->startRow();
$storyTable->addCell('Story Text');
$storyTable->addCell($htmlarea->show());
$storyTable->endRow();

$storySource = new textarea('storysource');

if ($mode == 'edit') {
    $storySource->value = $story['storysource'];
}

$label = new label ('Story Source', 'input_storysource');

$storyTable->startRow();
$storyTable->addCell($label->show());
$storyTable->addCell($storySource->show());
$storyTable->endRow();

$form->addToForm($storyTable->show());

$button = new button ('savestory', $buttonText);
$button->setToSubmit();

$form->addToForm($button->show());

if ($mode == 'edit') {
    $hiddeninput = new hiddeninput('id', $story['id']);
    $form->addToForm($hiddeninput->show());
}

echo $form->show();

$homeLink = new link ($this->uri(NULL));
$homeLink->link = 'Return to News Home';
echo $homeLink->show();
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

<style type="text/css">
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