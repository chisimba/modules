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
echo $this->getJavaScriptFile('jquery/1.3.2/jquery-1.3.2.min.js', 'htmlelements');
 ?>
 <script>
 $(document).ready(function(){
 $("#input_dataoff").each(function){
 $(this).hide();
 });
 
  }); </script>
   <?php
//create next button 

$clearButton = $this->uri(array('action'=>'vacinventory_clear'));
$clearButton = new button('clear', $this->objLanguage->languageText('phrase_clearall'), "javascript: document.location='$clearButton'");

//create fields for form
//text input for report officer 
$repOff = new dropdown('repoff');
$repOff->addOption('null','Select');
$repOff->addFromDB($userList, 'name', 'name');
$repOff->setSelected($officerId);


//text input for data entry officer 
$dataOff = new dropdown('dataoff',$dataoff);
$dataOff->addOption('null','Select');
$dataOff->addFromDB($userList, 'name', 'name');
$dataOff->setSelected($dataoff);


//text input for vetofficer
$vetOff = new dropdown('vetoff',$vetoff);
$vetOff->addOption('null','Select');
$vetOff->addFromDB($userList, 'name', 'name');
$vetOff->setSelected($vetoff);



//report date set default to today 
$reportDate = $this->getObject('datepicker','htmlelements');
$reportDate->setName('repdate');
$reportDate->setDefaultDate($repdate);


//IBAR date set default to today
$ibarDate = $this->getObject('datepicker','htmlelements');
$ibarDate->setName('ibardate');
$ibarDate->setDefaultDate($ibardate);

// drop down for Country
$country = new dropdown('country');
$country->addOption('null','Select');
$country->addFromDB($arraycountry,'official_name','official_name');
$country->setSelected($count);
 
//date picker for month and year 
//$dateMonth = new datepicker($datemonth);
//$dateYear = new datepicker($dateyear);
//drop down for month
$monthdate = new dropdown('month');
for ($i=1; $i<=12; $i++) {
    $date = strtotime("01-$i-01");
    $monthdate->addOption(date('m', $date), date('F', $date));
}

//dropdown for year

$year = date('Y',strtotime($dateyear));
$yeardate =new dropdown('year');
	for($i=$year;$i>=$year-10;$i--){
$date = strtotime("01-01-$i");
$yeardate->addOption(date('y',$date),date('Y',$date));
}
//dropdown for admin1
$admin1 = new dropdown('admin1');
$admin1->addOption('null',Select);
$admin1->addFromDB($arraypartitiontype, 'partitioncategory', 'partitioncategory');
$admin1->setSelected($ptype);
//print_r($admin1);echo jl;exit;

 
   
//get htmltable object
$objTable = $this->getObject('htmltable','htmlelements');
$objTable->cellspacing = 2;
$objTable->width = NULL;
//dropdown for admin2
$admin2 = new dropdown('admin2');
$admin2->addOption('null',Select);
$admin2->addFromDB($arraypartitionlevel, 'partitionlevel', 'partitionlevel');
$admin2->setSelected($plevel);
//dropdown for admin3
$admin3 = new dropdown('admin3');
$admin3->addOption('null',Select);
$admin3->addFromDB($arraypartition, 'partitionname', 'partitionname');
$admin3->setSelected($pname);
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
$objTable->addCell('&nbsp');
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
$objTable1->addCell($this->objLanguage->languageText('word_longitude'));
$objTable1->addCell($longitude->show());
$objTable1->endRow();

$objTable1->startRow();
$objTable1->addCell($clearButton->show());
$objTable1->addCell($nextButton->show());
$objTable1->endRow();


$objForm = new form('vacForm', $this->uri(array('action' => 'vacinventory_add')));
$objForm->addToForm($objTable->show()."<hr class='openaris' />".$objTable1->show());


$objLayer = new layer();
$objLayer->addToStr($objHeading->show()."<hr class='openaris' />".$objForm->show());


echo $objLayer->show();
?>