<?php
	$this->loadClass('htmltable', 'htmlelements');
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
	$this->loadClass('csslayout', 'htmlelements');
	$this->loadClass('layer', 'htmlelements');

	// Create an instance of the css layout class
	$cssLayout = &$this->newObject('csslayout', 'htmlelements');
	// Set columns to 2
	$cssLayout->setNumColumns(3);
	// get the sidebar object
	$this->leftMenu = $this->newObject('usermenu', 'toolbar');
	// Initialize left column
	$leftSideColumn = $this->leftMenu->show();
	$rightSideColumn = NULL;
	$middleColumn = NULL;

	$objIcon = $this->newObject('geticon', 'htmlelements');
	$objIcon->setIcon('loader');

	$link = new link($this->uri(array('action' => 'default')));

	$loadingIcon = $objIcon->show();

	//title
	$title = $this->objLanguage->languageText('mod_ahis_exporttitle', 'ahis', 'Livestock Export');
	
	// Header
	$header = new htmlheading();
	$header->type = 2;
	$header->str = $title;
	//echo $header->show();
	
	// Create Form
$form = new form ('myForm', $this->uri(array('action'=>'valform'),'htmlelements'));

$tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

$formTable = $this->newObject('htmltable', 'htmlelements');

$label_district = new label ('<div class="labels">'.$this->objLanguage->languageText('mod_ahis_district', 'ahis', 'District Name:'), 'districtname');

$input_district = new textinput('districtname');
$input_district->size = 60;

$formTable->startRow();
$formTable->cellpadding = 5;
$formTable->addCell($label_district->show());
$formTable->addCell($input_district->show());
$formTable->endRow();

$label_pointentry = new label ('<div class="labels">'.$this->objLanguage->languageText('mod_ahis_pointofentry', 'ahis', 'Point of Entry:'), 'pointofentry');

$input_pointentry = new textinput('pointofentry');
$input_pointentry->size = 60;

$formTable->startRow();
$formTable->addCell($label_pointentry->show());
$formTable->addCell($input_pointentry->show());
$formTable->endRow();

$label_animalorigin = new label ('<div class="labels">'.$this->objLanguage->languageText('mod_ahis_animalorigin', 'ahis', 'Animal Origin: '), 'animalorigin');

$input_animalorigin = new dropdown('animalorigin');
$input_animalorigin->addOption('', $objLanguage->languageText('phrase_selectone', 'ahis', 'Select One').'...');
$formTable->startRow();
$formTable->addCell($label_animalorigin->show());
$formTable->addCell($input_animalorigin->show());
$formTable->endRow();

$label_animaldestination = new label ('<div class="labels">'.$this->objLanguage->languageText('mod_ahis_destination', 'ahis', 'Animal Destination: '), 'animaldestination');

$input_animaldestination = new dropdown('animaldestination');
$input_animaldestination->addOption('', $objLanguage->languageText('phrase_selectone', 'ahis', 'Select One').'...');
$formTable->startRow();
$formTable->addCell($label_animaldestination->show());
$formTable->addCell($input_animaldestination->show());
$formTable->endRow();

$label_animalclassification = new label ('<div class="labels">'.$this->objLanguage->languageText('mod_ahis_classification', 'ahis', 'Animal Classification: '), 'animalclassification');
$input_animalclassification = new dropdown('animalclassification');
$input_animalclassification->addOption('', $objLanguage->languageText('phrase_selectone', 'ahis', 'Select One').'...');
$formTable->startRow();
$formTable->addCell($label_animalclassification->show());
$formTable->addCell($input_animalclassification->show());
$formTable->endRow();

$label_products = new label('<div class="borderclass">'.$this->objLanguage->languageText('mod_ahis_products', 'ahis', 'Products'), 'products');
$label_units = new label($this->objLanguage->languageText('mod_ahus_units', 'ahis', 'Units'), 'units');
$formTable->startRow();
$formTable->addCell($label_products->show());
$formTable->addCell($label_units->show());
$formTable->endRow();

$label_eggs = new label('<div class="newclass">'.$this->objLanguage->languageText('mod_ahis_eggs', 'ahis', 'Eggs'), 'eggs');
$input_eggs = new textinput('eggs');
$formTable->startRow();
$formTable->addCell($label_eggs->show());
$formTable->addCell($input_eggs->show());
$formTable->endRow();

$label_milk = new label('<div class="newclass">'.$this->objLanguage->languageText('mod_ahis_milk', 'ahis', 'Milk'), 'milk');
$input_milk = new textinput('milk');
$formTable->startRow();
$formTable->addCell($label_milk->show());
$formTable->addCell($input_milk->show());
$formTable->endRow();

$label_cheese = new label('<div class="newclass">'.$this->objLanguage->languageText('mod_ahis_cheese', 'ahis', 'Cheese'), 'cheese');
$input_cheese = new textinput('cheese');
$formTable->startRow();
$formTable->addCell($label_cheese->show());
$formTable->addCell($input_cheese->show());
$formTable->endRow();

$label_poultry = new label('<div class="newclass">'.$this->objLanguage->languageText('mod_ahis_poultry', 'ahis', 'Poultry'), 'poultry');
$input_poultry = new textinput('poultry');
$formTable->startRow();
$formTable->addCell($label_poultry->show());
$formTable->addCell($input_poultry->show());
$formTable->endRow();

$label_beef = new label('<div class="newclass">'.$this->objLanguage->languageText('mod_ahis_beef', 'ahis', 'Beef'), 'beef');
$input_beef = new textinput('beef');
$formTable->startRow();
$formTable->addCell($label_beef->show());
$formTable->addCell($input_beef->show());
$formTable->endRow();

$label_skin = new label('<div class="newclass">'.$this->objLanguage->languageText('mod_ahis_skin', 'ahis', 'Skin/Hide'), 'skin');
$input_skin = new textinput('skin');
$formTable->startRow();
$formTable->addCell($label_skin->show());
$formTable->addCell($input_skin->show());
$formTable->endRow();

$save = new button ('saveform',$this->objLanguage->languageText('mod_ahis_save', 'ahis', 'Save'));
$save->setToSubmit();
 $cancel = new button ('saveform', $this->objLanguage->languageText('mod_ahis_cancel', 'ahis', 'Cancel'));


$objLayer = new layer();
$objLayer->addToStr($header->show()."<hr />".$form->show());
$objLayer->align = 'center';

echo $objLayer->show().$formTable->show().$tab.$tab.$tab.$tab.$tab.$tab.$tab.$save->show().$tab.$cancel->show();



 
 //echo $formTable->show().'<br />'.$tab.$tab.$tab.$tab.$tab.$tab.$tab.$save->show().$tab.$cancel->show().'</div>'.$cssLayout->show();

?>

<style type="text/css">
	.labels
	{
		padding-left:60px;	
	}
	.inputtextboxes
	{
		/*padding-left:200;*/
	}
	.borderclass
	{
	/*border:thin;*/
	color:#CC0000;
	/*border-width:thick;
	border:1px solid #888;
	size:portrait;*/
	padding-left:150px;
	
	}
	.newclass
	{
	padding-left:150px;
	/*border:1px solid #888;*/
	}
</style>