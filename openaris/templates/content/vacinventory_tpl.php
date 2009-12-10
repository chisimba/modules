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
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('form','htmlelements');

$objHeading = $this->getObject('htmlheading','htmlelements');
$objHeading->str =$this->objLanguage->languageText('phrase_vaccinationreport');
$objHeading->type = 2;

//create clear all button
$nextButton = new button('next', $this->objLanguage->languageText('word_next'));

$nextButton->setToSubmit();

//create next button 

$clearButton = $this->uri(array('action'=>'vacinventory_clear'));
$clearButton = new button('clear', $this->objLanguage->languageText('phrase_clearall'), "javascript: document.location='$clearButton'");

//create fields for form
//text input for report officer 
$repOff = new dropdown('repOfficerId');
$repOff->addOption('-1','Select');
$repOff->addFromDB($userList, 'name', 'userid');
$repOff->setSelected($officerId);
$repOff->extra = 'onchange = \'javascript:getOfficerInfo("rep");\'';

//text input for data entry officer 
$dataOff = new dropdown('dataOfficerId');
$dataOff->addOption('-1','Select');
$dataOff->addFromDB($userList, 'name', 'userid');
$dataOff->setSelected($dataoff);
$dataOff->extra = 'onchange = \'javascript:getOfficerInfo("data");\'';

//text input for vetofficer
$vetOff = new dropdown('vetOfficerId');
$vetOff->addOption('-1','Select');
$vetOff->addFromDB($userList, 'name', 'userid');
$vetOff->setSelected($vetoff);
$vetOff->extra = 'onchange = \'javascript:getOfficerInfo("vet");\'';

//report date set default to today 
$reportDate = $this->newObject('datepicker','htmlelements');
$reportDate->setName('repdate');
$reportDate->setDefaultDate($repdate);


//IBAR date set default to today
$ibarDate = $this->newObject('datepicker','htmlelements');
$ibarDate->setName('ibardate');
$ibarDate->setDefaultDate($ibardate);

// drop down for Country
$country = new dropdown('country');
$country->addOption('-1','Select');
$country->addFromDB($arraycountry,'common_name','id');
$country->setSelected($count);
$country->extra =  'onchange="javascript:changeNames();"';
 
//date picker for month and year 
//$dateMonth = new datepicker($datemonth);
//$dateYear = new datepicker($dateyear);
//drop down for month
$monthdate = new dropdown('month');
for ($i=1; $i<=12; $i++) {
    $date = strtotime("01-$i-01");
    $monthdate->addOption(date('m', $date), date('F', $date));
}
$monthdate->setSelected(date('m'));

//dropdown for year

$year = date('Y',strtotime($dateyear));
$yeardate =new dropdown('year');
	for($i=$year;$i>=$year-10;$i--){
$date = strtotime("01-01-$i");
$yeardate->addOption(date('y',$date),date('Y',$date));
}
//dropdown for admin1
$admin1 = new dropdown('partitionTypeId');
$admin1->addOption('-1',Select);
$admin1->addFromDB($arraypartitiontype, 'partitioncategory', 'id');
$admin1->setSelected($ptype);
$admin1->extra = 'onchange="javascript:changePartitionType();"';
//print_r($admin1);echo jl;exit;

 //text field for phone
 $phone= new textinput('dataOfficerTel',$phone);
 
 //text field for fax
 $fax = new textinput('dataOfficerFax',$fax);
  
  //text field for email
  $email = new textinput('dataOfficerEmail',$email);
  
  //text field for phone
 $phone1= new textinput('vetOfficerTel',$phone1);
 
 //text field for fax
 $fax1 = new textinput('vetOfficerFax',$fax1);
  
  //text field for email
  $email1 = new textinput('vetOfficerEmail',$email1); 
//get htmltable object

//dropdown for admin2
$admin2 = new dropdown('partitionLevelId');
$admin2->addOption('-1',Select);
$admin2->addFromDB($arraypartitionlevel, 'partitionlevel', 'id');
$admin2->setSelected($plevel);
$admin2->extra = 'onchange="javascript:changeNames();"';
//dropdown for admin3
$admin3 = new dropdown('partitionId');
$admin3->addOption('-1',Select);
$admin3->addFromDB($arraypartition, 'partitionname', 'id');
$admin3->setSelected($pname);
$admin3->extra = 'onchange="javascript:changePartition()"';
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
$objTable->addCell('&nbsp');
$objTable->addCell($this->objLanguage->languageText('mod_ahis_reportdate','openaris'));
$objTable->addCell($reportDate->show());
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('&nbsp');
$objTable->addCell('&nbsp');
$objTable->addCell('&nbsp');
$objTable->addCell($this->objLanguage->languageText('mod_ahis_ibardate','openaris'));
$objTable->addCell($ibarDate->show());
$objTable->endRow();

//get htmltable object
$objTable2 = new htmlTable();
$objTable2->cellpadding =4;
$objTable2->cellspacing = 2;
$objTable2->width = '90%';
$objTable2->cssClass = 'min50';
$objTable2->startRow();
$objTable2->addCell($this->objLanguage->languageText('phrase_dataentryofficer'));
$objTable2->addCell($dataOff->show());

$objTable2->endRow();

$objTable2->startRow();
$objTable2->addCell($this->objLanguage->languageText('mod_ahis_word_phone','openaris'));
$objTable2->addCell($phone->show());
$objTable2->addCell($this->objLanguage->languageText('mod_ahis_word_fax','openaris'));
$objTable2->addCell($fax->show());
$objTable2->addCell($this->objLanguage->languageText('mod_ahis_word_email','openaris'));
$objTable2->addCell($email->show());

$objTable2->endRow();

//get htmltable object
$objTable3 = new htmlTable();
$objTable3->cellpadding =4;
$objTable3->cellspacing = 2;
$objTable3->width = '90%';
$objTable3->cssClass = 'min50';
$objTable3->startRow();
$objTable3->addCell($this->objLanguage->languageText('phrase_vetofficer'));
$objTable3->addCell($vetOff->show());
$objTable3->endRow();

$objTable3->startRow();
$objTable3->addCell($this->objLanguage->languageText('mod_ahis_word_phone','openaris'));
$objTable3->addCell($phone1->show());
$objTable3->addCell($this->objLanguage->languageText('mod_ahis_word_fax','openaris'));
$objTable3->addCell($fax1->show());
$objTable3->addCell($this->objLanguage->languageText('mod_ahis_word_email','openaris'));
$objTable3->addCell($email1->show());
$objTable3->endRow();



//get htmltable object
$objTable1 = new htmlTable();
$objTable1->cellpadding =4;
$objTable1->cellspacing = 2;
$objTable1->width = '90%';
$objTable1->cssClass = 'min50';

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('word_country'));
$objTable1->addCell($country->show());
$objTable1->addCell($this->objLanguage->languageText('phrase_partitiontype'));
$objTable1->addCell($admin1->show());
$objTable1->addCell($this->objLanguage->languageText('word_location')." ".$this->objLanguage->languageText('word_type'));
$objTable1->addCell($loctype->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('word_month'));
$objTable1->addCell($monthdate->show());
$objTable1->addCell($this->objLanguage->languageText('mod_ahis_partitionlevel','openaris'));
$objTable1->addCell($admin2->show());
$objTable1->addCell($this->objLanguage->languageText('word_location')." ".$this->objLanguage->languageText('word_name'));
$objTable1->addCell($locname->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($this->objLanguage->languageText('word_year'));
$objTable1->addCell($yeardate->show());
$objTable1->addCell($this->objLanguage->languageText('mod_ahis_partitionname','openaris'));
$objTable1->addCell($admin3->show());
$objTable1->addCell($this->objLanguage->languageText('word_latitude'));
$objTable1->addCell($lattitude->show());
$objTable1->endRow();

$objTable1->startRow();

$objTable1->endRow();

$objTable1->startRow();

$objTable1->endRow();



$objTable1->startRow();
$objTable->addCell('&nbsp');
$objTable->addCell('&nbsp');
$objTable->addCell('&nbsp');
$objTable1->addCell($this->objLanguage->languageText('word_longitude'));
$objTable1->addCell($longitude->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($clearButton->show());
$objTable1->addCell($nextButton->show());
$objTable1->endRow();


$objForm = new form('vacForm', $this->uri(array('action' => 'vacinventory_add')));
$objForm->addToForm($objTable->show()."<hr class='openaris' />".$objTable2->show()."<hr class='openaris' />".$objTable3->show()."<hr class='openaris' />".$objTable1->show());
$objForm->addRule('repOfficerId',$this->objLanguage->languageText('mod_ahis_reportoffreq','openaris'),'select');
$objForm->addRule('dataOfficerId', $this->objLanguage->languageText('mod_ahis_dataoffreq','openaris'),'select');
$objForm->addRule('vetOfficerId', $this->objLanguage->languageText('mod_ahis_vetoffreq','openaris'),'select');
$objForm->addRule('admin1', $this->objLanguage->languageText('mod_ahis_admin1req','openaris'),'select');
$objForm->addRule('admin2', $this->objLanguage->languageText('mod_ahis_admin2req','openaris'),'select');
$objForm->addRule('admin3', $this->objLanguage->languageText('mod_ahis_admin3req','openaris'),'select');
//$objForm->addRule('lattitude', $this->objLanguage->languageText('mod_ahis_lattitudereq','openaris'),'numeric');
//$objForm->addRule('longitude', $this->objLanguage->languageText('mod_ahis_longitudereq','openaris'),'numeric');
$objForm->addRule('dataOfficerTel', $this->objLanguage->languageText('mod_ahis_validatedatatel', 'openaris'), 'required');
$objForm->addRule('vetOfficerTel', $this->objLanguage->languageText('mod_ahis_validatevettel', 'openaris'), 'required');

$objForm->addRule('repdate', $this->objLanguage->languageText('mod_ahis_validaterepdate', 'openaris'), 'datenotfuture');
$objForm->addRule('ibardate', $this->objLanguage->languageText('mod_ahis_validateibardate', 'openaris'), 'datenotfuture');
$objForm->addRule(array('repdate','ibardate'), $this->objLanguage->languageText('mod_ahis_validaterepdate', 'openaris'), 'datenotbefore');

$scriptUri = $this->getResourceURI('util.js');
$this->appendArrayVar('headerParams', "<script type='text/javascript' src='$scriptUri'></script>");


$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='openaris' />".$objForm->show());


echo $objLayer->show();
?>