<div style="padding:5px">
<?php
/**
* @package popupcalendar
*/

/**
* Template to display the date picker
*/

// Suppress page elements
$this->setVar('pageSuppressContainer', TRUE);
$this->setVar('pageSuppressBanner', TRUE);
$this->setVar('pageSuppressToolbar', TRUE);
$this->setVar('pageSuppressIM', TRUE);
$this->setVar('suppressFooter', TRUE);

// Set up html elements
$objLayer =& $this->newObject('layer', 'htmlelements');
$objButton =& $this->newObject('button', 'htmlelements');
$objIcon =& $this->newObject('geticon', 'htmlelements');
$objHead =& $this->newObject('htmlheading', 'htmlelements');

// Set up heading
$heading = $this->objLanguage->languageText('phrase_selectdate');

$objHead->str = $heading;
$objHead->type = 1;
echo $objHead->show();

// Calendar
$objLayer->str = $str;
$objLayer->id = 'calDiv';
$objLayer->align = 'center';
$objLayer->padding = '5px';
$objLayer->width = '280px';
echo $objLayer->show();

// Clock
$objLayer->str = $timeStr;
$objLayer->id = 'timeDiv';
$objLayer->align = 'center';
$objLayer->padding = '5px';
$objLayer->width = '280px';
echo $objLayer->show();

// Hidden form elements
$objLayer->str = $formStr;
$objLayer->id = 'formDiv';
$objLayer->align = 'center';
echo $objLayer->show();

// Close button
$objIcon->setIcon('close');
$objIcon->extra=" onclick='javascript:window.close()'";

$closeStr = $objIcon->show();

$objLayer->str = $closeStr;
$objLayer->id = 'close';
$objLayer->align = 'center';
$objLayer->padding = '5px';
$objLayer->width = '280px';
echo $objLayer->show();
?>
</div>