<?php

$this->loadClass('form','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('radio','htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('dropdown','htmlelements');

//Add the templage heading to the main layer
$objH = $this->getObject('htmlheading', 'htmlelements');
//Heading H3 tag
$objH->type=3; 
$objH->str = $this->objLanguage->languageText("mod_travel_hotelsearch","travel");

$searchForm = new form('searchForm',$this->uri(array('action'=>'hotel results','page'=>'1')));
$searchStr = new textinput('searchStr',null,null,51);

$searchButton = new button('searchButton',$this->objLanguage->languageText('word_search'));
$searchButton->setToSubmit();

$searchCheckin = $this->newObject('datepicker','htmlelements');
$searchCheckin->setName("checkin");
$searchCheckin->setDefaultDate(date('Y-m-d',time()+(1209600)));

$searchCheckout = $this->newObject('datepicker','htmlelements');
$searchCheckout->setName("checkout");
$searchCheckout->setDefaultDate(date('Y-m-d',time()+(1382400)));

$searchRooms = new dropdown('searchRooms');
$searchRooms->extra = "onchange = 'javascript:adjustRooms(this.selectedIndex)'";
for ($i=1;$i<9;$i++) {
    $searchRooms->addOption($i,$i);
}
$searchRooms->addOption(9,'9+');
$searchRooms->setSelected(1);

$searchAdults = new dropdown('searchAdults');
$searchChildren = new dropdown('searchChildren');

for ($i=0;$i<4;$i++) {
    $searchAdults->addOption($i+1,$i+1);
    $searchChildren->addOption($i,$i);
}
$searchAdults->setSelected(2);
$searchChildren->setSelected(0);

$objTable = new htmlTable();
//$objTable->border = '1';
$objTable->width = "40%";
$objTable->cellpadding = $objTable->cellspacing = '4';
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_travel_entercity','travel'),null,null,null,null,'colspan="3"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($searchStr->show()."<div id='autocomplete_choices' class='travelcomplete' style='display: none'></div>",null,null,null,null,'colspan="3"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('mod_travel_checkin','travel'));
$objTable->addCell($this->objLanguage->languageText('mod_travel_checkout','travel'));
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($searchCheckin->show());
$objTable->addCell($searchCheckout->show(),null,null,null,null,'colspan="2"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($this->objLanguage->languageText('word_rooms'));
$objTable->addCell($this->objLanguage->languageText('word_adults'));
$objTable->addCell($this->objLanguage->languageText('word_children')." <span style='color:#AAAAAA;'>(0-17)</span>");
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($searchRooms->show());
$objTable->addCell($searchAdults->show());
$objTable->addCell($searchChildren->show());
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($searchButton->show(),null,null,'right',null,'colspan="3"');
$objTable->endRow();

$searchForm->addToForm($objTable->show());
$searchForm->addRule('searchStr',$this->objLanguage->languageText('mod_travel_cityrequired','travel'),'required');

$content = $objH->show().$searchForm->show();

$jsFileUri = $this->objConfig->getModuleUri().'travel/resources/travel.js';
$ajaxUri = $this->uri(array('action'=>'country_autocomplete'),'travel');
$this->appendArrayVar('headerParams',"<script type='text/javascript' src='$jsFileUri'></script>");
$this->appendArrayVar('bodyOnLoad',"javascript:windowLoad('$ajaxUri')");

echo $content;
?>