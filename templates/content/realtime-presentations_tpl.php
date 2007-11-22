
<?php
/*
echo '<applet id="presentationsapplet" width="800" height="600" code="com.sun.star.lib.loader.Loader.class">';
echo '    <param name="archive" value="'.$this->presentationsURL.'/presentations.jar,'.$this->presentationsURL.'/officebean.jar"/> ';
echo "</applet> ";
*/

// set up html elements
$this->objLanguage =& $this->getObject('language','language');
$tab =& $this->newObject('tabbedbox', 'htmlelements');
$tabcontent =& $this->newObject('tabcontent', 'htmlelements');
$tab1 =& $this->newObject('tabbedbox', 'htmlelements');
$tabcontent1 =& $this->newObject('tabcontent', 'htmlelements');
$objHead=$this->newObject('htmlheading','htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');
$objAppletTable = $this->newObject('htmltable', 'htmlelements');
$objWebStartTable = $this->newObject('htmltable', 'htmlelements');

$table->cellpadding = 5;
$table->cellpadding = 5;

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);


$str1= $this->objLanguage->languageText('mod_realtime_presentationtitle', 'realtime');
$oo="<a href='".$this->objLanguage->languageText('mod_realtime_openoffice', 'realtime')."'>".$this->objLanguage->languageText('mod_realtime_openofficetext', 'realtime')."</a>";
$str2= $this->objLanguage->languageText('mod_realtime_str2a', 'realtime').$oo.$this->objLanguage->languageText('mod_realtime_str2b', 'realtime');

//create links to the Applet presentations
$this->objLink->link($this->uri(array('action'=>'show_upload_form')));
$this->objLink->link=$this->objLanguage->languageText('mod_realtime_presenterstudio', 'realtime');
$str3=$this->objLink->show()." ".$this->objLanguage->languageText('mod_realtime_startpresentation', 'realtime');

$this->objLink->link($this->uri(array('action'=>'audience_applet')));
$this->objLink->link=$this->objLanguage->languageText('mod_realtime_joinpresentation', 'realtime');
$str4=$this->objLink->show()." ".$this->objLanguage->languageText('mod_realtime_joinpresentation', 'realtime');
$webstartstr3='<a href="'.$this->presentationsURL.'/Presenter.jnlp">'.$this->objLanguage->languageText('mod_realtime_presenterstudio', 'realtime').'</a>  '.$this->objLanguage->languageText('mod_realtime_startpresentation', 'realtime'); 
$webstartstr4='<a href="'.$this->presentationsURL.'/Audience.jnlp">'.$this->objLanguage->languageText('mod_realtime_joinpresentation', 'realtime').'</a>  '.$this->objLanguage->languageText('mod_realtime_joinpresentation', 'realtime');

$objHead->type=2;
$objHead->str=$str1;

//Set the content of the left side column
$leftSideColumn = $str2;
// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);
$rightSideColumn = "<div align=\"left\">" . $objHead->show() . "</div>";

//set contents of the table in the Applet version tab
$objAppletTable->startRow();
$objAppletTable->addCell($str3);
$objAppletTable->endRow();

$objAppletTable->startRow();
$objAppletTable->addCell($str4);
$objAppletTable->endRow();



$tab->tabbedbox();
//$tab->addTabLabel('Beta Version');//$this->objLanguage->languageText('mod_realtime_applet','realtime'));
$tb = $objAppletTable;

$tab->addBoxContent($tb->show());
//$tabcontent->addTab($this->objLanguage->languageText('mod_realtime_applet','realtime'),$tab->show());
$tabcontent->addTab('Live',$tab->show());     
$tabcontent->width = '90%';

	
$table->startRow();
$table->addCell($tabcontent->show());
	
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
