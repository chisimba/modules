
<?php
/*
echo '<applet id="presentationsapplet" width="800" height="600" code="com.sun.star.lib.loader.Loader.class">';
echo '    <param name="archive" value="'.$this->presentationsURL.'/presentations.jar,'.$this->presentationsURL.'/officebean.jar"/> ';
echo "</applet> ";
*/

// set up html elements
$objHead=$this->newObject('htmlheading','htmlelements');

$table = $this->newObject('htmltable', 'htmlelements');
$table->cellpadding = 5;

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

/**************** set up display page ********************/
$str1= 'Realtime Presentations';
$str2= 'Using this module, you can present <a href="www.openoffice.org/"> Open Office 2.0</a> or later Presentations synchronously in realtime';
$str3='<a href="'.$this->presentationsURL.'/Presenter.jnlp">Presenter Studio</a> is used to start a presentation.'; 
$str4='<a href="'.$this->presentationsURL.'/Audience.jnlp">Join Active Presentation</a>  sessions started by others.';

$objHead->type=2;
$objHead->str=$str1;

//Set the content of the left side column
$leftSideColumn = $str2;
// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$rightSideColumn = "<div align=\"left\">" . $objHead->show() . "</div>";



$table->startRow();
$table->addCell($str3);
$table->endRow();

$table->startRow();
$table->addCell($str4);
$table->endRow();


//Add the table to the centered layer
$rightSideColumn .= $table->show();

// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();


//echo $objHead2->show();
//echo $objHead3->show();
//echo $objHead4->show();



?>
