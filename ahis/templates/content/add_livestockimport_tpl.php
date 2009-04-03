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
	$title = $this->objLanguage->languageText('mod_ahis_importtitle', 'ahis', 'Livestock Import');
	
	// Header
	$header = new htmlheading();
	$header->type = 2;
	$header->str = $title;
	
	
	// Create Form
$form = new form ('myForm', $this->uri(array('action'=>'valform'),'htmlelements'));

$formTable = $this->newObject('htmltable', 'htmlelements');

$label_district = new label ('<div class="labels">'.$this->objLanguage->languageText('mod_ahis_district', 'ahis', 'District Name:'), 'districtname');

$input_district = new textinput('districtname');
$input_district->size = 60;

$formTable->startRow();
$formTable->cellpadding = 5;
$formTable->addCell($label_district->show(),NULL,NULL,'right');
$formTable->addCell($input_district->show(),NULL,NULL,'left');
$formTable->endRow();

$label_pointentry = new label ('<div class="labels">'.$this->objLanguage->languageText('mod_ahis_pointofentry', 'ahis', 'Point of Entry:'), 'pointofentry');

$input_pointentry = new textinput('pointofentry');
$input_pointentry->size = 60;

$formTable->startRow();
$formTable->addCell($label_pointentry->show(),NULL,NULL,'right');
$formTable->addCell($input_pointentry->show(),NULL,NULL,'left');
$formTable->endRow();

// animal origin	
$label = new label ('Animal origin: ', 'input_source');
$origin = new dropdown('origin');
$origin->addOption('Select...','Select...');
$origin->addOption('A','A');
$origin->addOption('B','B');
$origin->addOption('C','C');

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($origin->show(),NULL,NULL,'left');
$formTable->endRow();

// animal destination	
$label = new label ('Animal destination: ', 'input_destination');
$destination = new dropdown('destination');
$destination->addOption('Select...','Select...');
$destination->addOption('A','A');
$destination->addOption('B','B');
$destination->addOption('C','C');

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($destination->show(),NULL,NULL,'left');
$formTable->endRow();

// animal classification	
$label = new label ('Animal Classification: ', 'input_classification');
$classification = new dropdown('classifiction');
$classification->addOption('Select...','Select...');
$classification->addOption('A','A');
$classification->addOption('B','B');
$classification->addOption('C','C');

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($classification->show(),NULL,NULL,'left');
$formTable->endRow();

//products	
$label = new label ('Product: ', 'input_product');
$classification = new dropdown('origin');
$classification->addOption('Select...','Select...');
$classification->addOption('Eggs','Eggs');
$classification->addOption('Milk','Milk');
$classification->addOption('Cheese','Cheese');
$classification->addOption('Poultry','Poultry');
$classification->addOption('Beef','Beef');
$classification->addOption('Skin','Skin');
$classification->addOption('Hide','Hide');

$units_label = new label('Product Quantity: ', 'input_unit');
$units = new dropdown('unit');
$units->addOption('Select...','Select...');
$units->addOption('1','1');
$units->addOption('2','2');
$units->addOption('3','3');
$units->addOption('4','4');
$units->addOption('5','5');
$units->addOption('6','6');
$units->addOption('7','7');

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($classification->show(),NULL,NULL,'left');
$formTable->endRow();

$formTable->startRow();
$formTable->addCell($units_label->show(),NULL,NULL,'right');
$formTable->addCell($units->show(),NULL,NULL,'left');
$formTable->endRow();

 //container-table
$topTable = $this->newObject('htmltable', 'htmlelements');
 $topTable->startRow();
$topTable->addCell($formTable->show());
$topTable->endRow();
$form->addToForm($topTable->show());
 
 $save = new button('livestock_export_save', 'Save');
 $save->setToSubmit();
 
 $cancel = new button('cancel','Cancel');
$cancel->setToSubmit();

$form->addToForm($save->show(),NULL,NULL,'right');
$form->addToForm($cancel->show());

$objLayer = new layer();
$objLayer->addToStr($header->show()."<hr />".$form->show());
$objLayer->align = 'center';

echo $objLayer->show(); 
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