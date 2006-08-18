<?php
$userMenu  = &$this->newObject('usermenu','toolbar');
//user cal
$userCal = &$this->newObject('usercalendar','calendar');
// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
// Set columns to 2
$cssLayout->setNumColumns(2);

// Add Post login menu to left column
$leftSideColumn ='';
$leftSideColumn = $userMenu->show();
//$rightSideColumn = "Simple calendar plus menu for other folders like calendars etc";
$middleColumn = NULL;

// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);
// Add Right Column
//$cssLayout->setRightColumnContent($rightSideColumn);

$this->href = $this->getObject('href', 'htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');
$table->border = 1;

$table->attributes="align=\"center\"";
//Create the array for the table header
$tableRow=array();
$tableHd[]="From";
$tableHd[]="Subject";
$tableHd[]="Date";

function etrimstr($s)
{
  $maxlen = 30;
  $str_to_count = html_entity_decode($s);
  if (strlen($str_to_count) <= $maxlen) {
   return htmlentities($s);
  }
  $s2 = substr($str_to_count, 0, $maxlen - 3);
  $s2 .= "...";
  return htmlentities($s2);
}

function fixdate($date)
{
	return $date;
}

$table->addHeader($tableHd, "heading");
//Loop through and display the records
$rowcount = 0;
if (isset($data))
{
    if (count($data) > 0)
    {
        foreach ($data as $line)
        {
        	$oddOrEven = ($rowcount == 0) ? "odd" : "even";
        	$tableRow[]=etrimstr($line['address']);
        	$tableRow[]= etrimstr($line['subject']);
			$tableRow[]=etrimstr(fixdate($line['date']));
			$table->addRow($tableRow, $oddOrEven);
            $tableRow=array();
            $rowcount = ($rowcount == 0) ? 1 : 0;
        }
    }
}

//add middle column
$cssLayout->setMiddleColumnContent($table->show());

echo $cssLayout->show();

/**
// Create an instance of the postlogin menu on the side
$userMenu  = &$this->newObject('usermenu','toolbar');
$msgList = &$this->newObject('iframe','htmlelements');
$msgView = &$this->newObject('iframe','htmlelements');

//set up the iFrames
$msgList->width = '100%';
$msgView->width = '100%';
$msgList->height = 150;
$msgView->height = 400;

$msgList->frameborder = 1;
$msgView->frameborder = 1;

$msgList->name = "msglist";
$msgView->name = "msgview";
$msgList->id = "msglist";
$msgView->id = "msgview";

$msgList->src = $this->uri(array(
            'module'=>'webmail',
            'action'=>'msglist',
        ));
//user cal
$userCal = &$this->newObject('usercalendar','calendar');
// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
// Set columns to 2
$cssLayout->setNumColumns(3);

// Add Post login menu to left column
$leftSideColumn ='';
$leftSideColumn = $userMenu->show();
$rightSideColumn = "Simple calendar plus menu for other folders like calendars etc";
$middleColumn = $msgList->show(); //$folders;
$middleColumn .= $msgView->show();

// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);
// Add Right Column
$cssLayout->setRightColumnContent($rightSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();

//print_r(($infoArr));

*/
?>