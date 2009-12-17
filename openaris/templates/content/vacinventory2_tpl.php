<?php 


// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

//load classes from coremodules 
$this->loadClass('layer','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('form','htmlelements');

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str =$this->objLanguage->languageText('phrase_vaccinationreport');
$objHeading->type = 2;

//create finish button
$finButton = new button('fin', $this->objLanguage->languageText('word_finish'));

$finButton->setToSubmit();

//create clear all button 

$clearButton = $this->uri(array('action'=>'vacinventory2_clear'));
$clearButton = new button('clear', $this->objLanguage->languageText('phrase_clearall'), "javascript: document.location='$clearButton'");

//create back button
$backButton = $this->uri(array('action'=>'vacinventory'));
$backButton = new button('back', $this->objLanguage->languageText('word_back'), "javascript: document.location='$backButton'");

//create next button
$nextButton = new button('fin', $this->objLanguage->languageText('word_next'));

$nextButton->setToSubmit();

//print_r($repoff);
//print_r($dataoff);
//print_r($vetoff);

//create fields for form
//text input for report officer 
$repOff = new dropdown('repoff');
$repOff->addOption('null','Select');
$repOff->addFromDB($userList, 'name', 'userid');
$repOff->setSelected($repoff);
$repOff->extra = 'disabled';

//text input for data entry officer 
$dataOff = new dropdown('dataoff');
$dataOff->addOption('null','Select');
$dataOff->addFromDB($userList, 'name', 'userid');
$dataOff->setSelected($dataoff);
$dataOff->extra = 'disabled';
//text input for vetofficer
$vetOff = new dropdown('vetoff');
$vetOff->addOption('null','Select');
$vetOff->addFromDB($userList, 'name', 'userid');
$vetOff->setSelected($vetoff);
$vetOff->extra = 'disabled';

//report date set default to today 
//print_r($repdate);
$reportDate = new textinput('repdate',$repdate);
$reportDate->extra='disabled';


//IBAR date set default to today
$ibarDate = new textinput('ibardate',$ibardate);
$ibarDate->extra='disabled';



//dropdown for outbreak ref number
$outbreakRef = new dropdown('outbreakref');
$outbreakRef->addOption('-1','Select');
$outbreakRef->addFromDB($arrayoutbreak,'outbreakcode','outbreakcode');
$outbreakRef->extra = 'onchange="javascript:changeDisease();"';
//$outbreakRef->setSelected($outbreakref);
//print_r($arraydisease); exit;
//dropdown for disease
$disease = new dropdown('diseaseId');
$disease->addOption('-1','Select');
$disease->addFromDB($arraydisease,'disease_name','id');
//$disease->setSelected($diseases);
//$disease->extra = 'onchange="javascript:changeDisease();"';
//dropdown form disease
$species = new dropdown('speciesId');
$species->addOption('-1','Select');
$species->addFromDB($arrayspecies,'speciesname','id');
//$species->setSelected($species);

//text input field for vaccine source
$vaccineSource = new textinput('vaccinesource',$vaccinesource);

//text input field for lot number
if(!isset($lotnumber)){

$lotnumber = "NULL";
}
$lotNumber = new textinput('lotnumber',$lotnumber);

//text input for manufacture date
$manDate = $this->newObject('datepicker','htmlelements');
$manDate->setName('mandate');
$manDate->setDefaultDate($mandate);

//date object for expiration date
$expDate = $this->newObject('datepicker','htmlelements');
$expDate->setName('expdate');
$expDate->setDefaultDate($expdate);





//text input field for planned pro. vaccination
if(!isset($planprovac)){

$planprovac = 0;
}
$planprovac = new textinput('planprovac',$planprovac);

//text input field for cond pro. vaccination

$condprovac = new textinput('condprovac',$condprovac);
$condprovac->extra = 'onchange = \'javascript:changeValues("provac");\'';
//text input field for cummulative pro. vaccination
$cumprovac = 0;
foreach($arraycon as $dat){
   $cumprovac = $cumprovac+$dat['condprovac'];


}
$cumprovac = new textinput('cumprovac',$cumprovac);


//text input field for planned control vaccination
if(!isset($planconvac)){

$planconvac = 0;
}
$planconvac = new textinput('planconvac',$planconvac);

//text input field for cond pro. vaccination
$condconvac = new textinput('condconvac',$condconvac);
$condconvac->extra = 'onchange = \'javascript:changeValues("convac");\'';
//text input field for cummulative pro. vaccination

$cumconvac = 0;
foreach($arraycon as $dat){
   $cumconvac = $cumconvac+$dat['condconvac'];


}
$cumconvac = new textinput('cumconvac',$cumconvac);

//text area for comments 
$comments = new textarea('comments',$comments);

//get htmltable object
$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;



//textinput field for location type
$loctype = new textinput('loctype',$loctype);

//textinput field for location name
$locname = new textinput('locname',$locname);

//textinput field for lattitude and longitude
$lattitude = new textinput('lattitude',$lattitude);
$longitude = new textinput('longitude',$longitude);

//get htmltable object
$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;

//create table rows and place text fields and labels 
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportofficer','openaris'));
$objTable->addCell($repOff->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_dataentryofficer'));
$objTable->addCell($dataOff->show());
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportdate','openaris'));
$objTable->addCell($reportDate->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('phrase_vetofficer'));
$objTable->addCell($vetOff->show());
$objTable->addCell($this->objLanguage->languageText('mod_ahis_ibardate','openaris'));
$objTable->addCell($ibarDate->show());
$objTable->endRow();



//get htmltable object
$objTable1 = new htmlTable();
$objTable1->cellpadding =4;
$objTable1->cellspacing = 2;
$objTable1->width = '90%';
$objTable1->cssClass = 'min50';

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('phrase_outbreakref'));
$objTable1->addCell($outbreakRef->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('word_disease'));
$objTable1->addCell($disease->show());
$objTable1->addCell($this->objLanguage->languageText('phrase_planprovac'));
$objTable1->addCell($planprovac->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('word_species'));
$objTable1->addCell($species->show());
$objTable1->addCell($this->objLanguage->languageText('phrase_condprovac'));
$objTable1->addCell($condprovac->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('mod_ahis_vacsource','openaris'));
$objTable1->addCell($vaccineSource->show());
$objTable1->addCell($this->objLanguage->languageText('phrase_cumprovac'));
$objTable1->addCell($cumprovac->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('phrase_lotnumber'));
$objTable1->addCell($lotNumber->show());
$objTable1->addCell($this->objLanguage->languageText('phrase_planconvac'));
$objTable1->addCell($planconvac->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('phrase_manufacturedate'));
$objTable1->addCell($manDate->show());
$objTable1->addCell($this->objLanguage->languageText('phrase_condconvac'));
$objTable1->addCell($condconvac->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('phrase_expirationdate'));
$objTable1->addCell($expDate->show());
$objTable1->addCell($this->objLanguage->languageText('phrase_cumconvac'));
$objTable1->addCell($cumconvac->show());
$objTable1->endRow();

//get htmltable object
$objTable2 = new htmlTable();
$objTable2->cellpadding =4;
$objTable2->cellspacing = 2;
$objTable2->width = '90%';
$objTable2->cssClass = 'min50';



$objTable2->startRow();
$objTable2->addCell($this->objLanguage->languageText('word_comments'));
$objTable2->addCell($comments->show());

$objTable2->endRow();


$objTable2->startRow();
$objTable2->addCell($clearButton->show());
$objTable2->addCell($backButton->show());
$objTable2->addCell($nextButton->show());
$objTable2->addCell($finButton->show());
$objTable2->endRow();


$objForm = new form('vacForm', $this->uri(array('action' => 'vacinventory2_add')));
$objForm->addToForm($objTable->show()."<hr class='openaris' />".$objTable1->show()."<hr class='openaris' />".$objTable2->show());

$objForm->addRule('condprovac', $this->objLanguage->languageText('mod_ahis_condproreq','openaris'),'numeric');
$objForm->addRule('condconvac', $this->objLanguage->languageText('mod_ahis_condconreq','openaris'),'numeric');
$objForm->addRule('outbreakref', $this->objLanguage->languageText('mod_ahis_admin1req','openaris'),'select');
$objForm->addRule('diseaseId', $this->objLanguage->languageText('mod_ahis_admin1req','openaris'),'select');
$objForm->addRule('lotnumber', $this->objLanguage->languageText('mod_ahis_validatevac', 'openaris'), 'nonnumeric');
$objForm->addRule('mandate', $this->objLanguage->languageText('mod_ahis_validatemandate', 'openaris'), 'datenotfuture');

$objForm->addRule(array('mandate','expdate'), $this->objLanguage->languageText('mod_ahis_validateexpman', 'openaris'), 'datenotbefore');

$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");

$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='openaris' />".$objForm->show());


echo $objLayer->show();
?>