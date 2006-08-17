<?php
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


?>