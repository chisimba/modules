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
	
	$formTable = $this->newObject('htmltable', 'htmlelements');
$formTable->cellspacing = 2;
$formTable->width = NULL;
$formTable->cssClass = 'min50';
	
	// Create Form
//$form = new form ('myForm', $this->uri(array('action'=>'livestockexport'),'htmlelements'));

$formTable = $this->newObject('htmltable', 'htmlelements');

$label_district = new label ('District Name:', 'district');
$input_district = new textinput('district',$dist);
$input_district->extra = 'readonly';
//$input_district->size = 40;

$formTable->startRow();
//$formTable->cellpadding = 5;
$formTable->addCell($label_district->show(),NULL,NULL,'right');
$formTable->addCell($input_district->show(),NULL,NULL,'left');
$formTable->endRow();

$label_pointentry = new label ('Point of Entry:', 'entrypoint');
$entrypoint= new textinput('entrypoint');
//$entrypoint->size = 40;

$formTable->startRow();
$formTable->addCell($label_pointentry->show(),NULL,NULL,'right');
$formTable->addCell($entrypoint->show(),NULL,NULL,'left');
$formTable->endRow();

// animal origin	
$label = new label ('Animal origin: ', 'origin');
$origin = new dropdown('origin');
//$origin->size = 40;
$origin->addFromDB($geo2, 'name', 'name');

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($origin->show(),NULL,NULL,'left');
$formTable->endRow();

// animal destination	
$label = new label ('Animal destination: ', 'destination');
$destination = new dropdown('destination');
$destination->addFromDB($geo2, 'name', 'name');
//$destination->size = 40;

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($destination->show(),NULL,NULL,'left');
$formTable->endRow();

// animal classification	
$label = new label ('Animal Classification: ', 'classification');
$classification = new dropdown('classification');
$classification->addFromDB($species, 'name', 'name');
//$classification->size = 40;

$formTable->startRow();
$formTable->addCell($label->show(),NULL,NULL,'right');
$formTable->addCell($classification->show(),NULL,NULL,'left');
$formTable->endRow();

//products	
$label_products = new label ('Products: ', 'products');
$label_units = new label (' :Units', 'units');
$formTable->startRow();
$formTable->addCell($label_products->show(),NULL,NULL,'right');
$formTable->addCell($label_units->show(),NULL,NULL,'left');
$formTable->endRow();

$label_eggs = new label('Eggs: ','eggs');
$eggs = new textinput('eggs');
//$eggs->extra = "onclick = 'numberVal();'";
$formTable->startRow();
$formTable->addCell($label_eggs->show(),NULL,NULL,'right');
$formTable->addCell($eggs->show(),NULL,NULL,'left');
//$eggs = array('age' => array('rule' => array('numeric')));
$formTable->endRow();

$label_milk = new label('Milk: ','milk');
$milk = new textinput('milk');
//$milk->extra = "onclick = 'numberVal();'";
$formTable->startRow();
$formTable->addCell($label_milk->show(),NULL,NULL,'right');
$formTable->addCell($milk->show(),NULL,NULL,'left');
$formTable->endRow();

$label_cheese = new label('Cheese: ','cheese');
$cheese = new textinput('cheese');
//$cheese->extra = "onclick = 'numberVal();'";
$formTable->startRow();
$formTable->addCell($label_cheese->show(),NULL,NULL,'right');
$formTable->addCell($cheese->show(),NULL,NULL,'left');
$formTable->endRow();

$label_poultry = new label('Poultry: ','poutry');
$poultry = new textinput('poultry');
//$poultry->extra = "onclick = 'numberVal();'";
$formTable->startRow();
$formTable->addCell($label_poultry->show(),NULL,NULL,'right');
$formTable->addCell($poultry->show(),NULL,NULL,'left');
$formTable->endRow();

$label_beef = new label('Beef: ','beef');
$beef = new textinput('beef');
//$beef->extra = "onclick = 'numberVal();'";
$formTable->startRow();
$formTable->addCell($label_beef->show(),NULL,NULL,'right');
$formTable->addCell($beef->show(),NULL,NULL,'left');
$formTable->endRow();

	$formAction = 'livestockexport_save';  
    $buttonText = 'Save';
	
	// Create Form
$form = new form ('add', $this->uri(array('action'=>$formAction)));

//form validations
$form->addRule('district', $this->objLanguage->languageText('mod_ahis_districterror','ahis'),'required');
$form->addRule('entrypoint', $this->objLanguage->languageText('mod_ahis_entrypointerror','ahis'),'required');
$form->addRule('origin', $this->objLanguage->languageText('mod_ahis_originerror','ahis'),'required');
$form->addRule('destination', $this->objLanguage->languageText('mod_ahis_destinationerror','ahis'),'required');
$form->addRule('classification', $this->objLanguage->languageText('mod_ahis_classificationerror','ahis'),'required');

//$form->addRule('eggs', $this->objLanguage->languageText('mod_ahis_eggserror','ahis'),'numeric');


/*if(is_numeric($eggs))
echo "Pass test";
else
echo "Please use only numbers";*/


//$form->addRule('milk', $this->objLanguage->languageText('mod_ahis_milkerror','ahis'),'numeric');
//$form->addRule('cheese', $this->objLanguage->languageText('mod_ahis_cheeseerror','ahis'),'numeric');
//$form->addRule('poultry', $this->objLanguage->languageText('mod_ahis_poultryerror','ahis'),'numeric');

$form->addRule('eggs', $this->objLanguage->languageText('mod_ahis_eggserror','ahis'),'required');
$form->addRule('eggs', $this->objLanguage->languageText('mod_ahis_eggsnumbererror','ahis'),'numeric');
$form->addRule('milk', $this->objLanguage->languageText('mod_ahis_milkerror','ahis'),'required');
$form->addRule('milk', $this->objLanguage->languageText('mod_ahis_milknumbererror','ahis'),'numeric');
$form->addRule('cheese', $this->objLanguage->languageText('mod_ahis_cheeseerror','ahis'),'required');
$form->addRule('cheese', $this->objLanguage->languageText('mod_ahis_cheesenumbererror','ahis'),'numeric');
$form->addRule('poultry', $this->objLanguage->languageText('mod_ahis_poultryerror','ahis'),'required');
$form->addRule('poultry', $this->objLanguage->languageText('mod_ahis_poultrynumbererror','ahis'),'numeric');
$form->addRule('beef', $this->objLanguage->languageText('mod_ahis_beeferror','ahis'),'required');
$form->addRule('beef', $this->objLanguage->languageText('mod_ahis_beefnumbererror','ahis'),'numeric');


if (isset($error)) {
    $formTable->startRow();
    $formTable->addCell($error, NULL, NULL, NULL, NULL, "colspan=2");
    $formTable->endRow();
}
 //container-table
$topTable = $this->newObject('htmltable', 'htmlelements');
$topTable->startRow();
$topTable->addCell($formTable->show());
$topTable->endRow();
$form->addToForm($topTable->show());



 $save = new button('livestockexport_save', 'Save');
 $save->setToSubmit();
 

 //$cancel = new button('cancel','Cancel');
//$cancel->setToSubmit();
$backUri = $this->uri(array('action' => 'select_officer'));
$bButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backUri'");

// $backUri = $this->uri(array('action'=>'select_officer'));
//$cancel = new button('back', $this->objLanguage->languageText('word_cancel'), "javascript: document.location='$backUri'");


$form->addToForm($bButton->show());

$form->addToForm($save->show(),NULL,NULL,'right');


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
<script language="javascript" type="text/javascript">

function numberVal()
{
	alert('Insert numerics only.');	
}

function isNumeric(val)
{
	if(val==isNumeric(val))
	return(parseFloat(val,10)==(val*1));
	else
		alert('Nini.');
}
</script>