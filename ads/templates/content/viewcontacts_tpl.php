<?php
	/*$info=$this->objDbClass->getInfo();
	foreach ($info as $data)
	{
		echo $data['academicname'].'    '.$data['schoolname'].'    '.$data['headsign'].'    ';
		echo $data['telnum'].'    '.$data['emailadd'].'    '.$data['coursename'].'   '.'<br/>';
		
	}
	$str=$this->objContact->displayCourses();
        echo "<h1>".$title."</h1>";
        echo $str;*/
	$str=$this->objContact->displayForm($coursenum);



// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$leftSideColumn = $nav->getLeftContent($toSelect);
$cssLayout->setLeftColumnContent($leftSideColumn);
$rightSideColumn.='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn.='<div style="padding:10px;"><h2>Section F: Contacts</h2>';

//Add the table to the centered layer
$rightSideColumn .= $str;
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();

?>
