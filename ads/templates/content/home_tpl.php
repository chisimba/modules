
<?php
// set up html elements
$this->objLanguage =& $this->getObject('language','language');
$objHead=$this->newObject('htmlheading','htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');
$this->loadclass('link','htmlelements');

$table->cellpadding = 5;
$table->cellpadding = 5;

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$title= $this->objLanguage->code2Txt('mod_ads_ads_title', 'ads');
$objHead->type=1;
$objHead->str=$title;;

$nav = $this->getObject('nav', 'ads');

//Set the content of the left side column
$leftSideColumn = $nav->getLeftContent();
// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$rightSideColumn = "<div align=\"left\">" . $objHead->show() . "</div>";

$content='The course/unit proposal format constitutes the areas that the academics engage with,
         for purposes of academic integrity and to implement national regulatory requirements,
       when developing or amending a course/unit';

$table->startRow();
$table->addCell($content);

$table->endRow();

$this->objLink = new link($this->uri(array('action'=>'default')));
$this->objLink->title='Home';


$table->startRow();
$table->addCell($this->objLink->show());
$table->endRow();

//Add the table to the centered layer
$rightSideColumn .= $table->show();

// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>
