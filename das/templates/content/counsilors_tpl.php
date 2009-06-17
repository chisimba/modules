<?php
header("Content-Type: text/html;charset=utf-8");
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);

$middleColumn = NULL;
$leftColumn = NULL;
$rightColumn = NULL;

$objDBIMUser = $this->getObject('dbimusers', 'im');
$objLink = $this->getObject('link','htmlelements');
$objIcon = $this->getObject('geticon','htmlelements');
$objTable = $this->getObject('htmltable','htmlelements');
$objLoggedIn = $this->getObject('loggedinusers', 'security');
$objFB = $this->getObject('featurebox', 'navigation');

$numCounsilors = count($users);
$numUsers = $this->objDbImPres->getRecordCount();
$online = False;
$str = "IM User: <b>".$this->juser.'</b><br/>';
$str .= "Status: <span class=\"highlight\">".$online.'</span><br/>';
$str .= "Number of Counsilors: $numCounsilors<br/>";
$str .= "Number of Users: $numUsers <br/><br/>";


$leftColumn .= $objFB->show('',$str);
$arr = array();
foreach ($users as $user)
{
    $cnt = 0;
    $name = $user['firstname']." ".$user['surname'];

    if($objDBIMUser->isCounsilor($user['userid']))
    {
        $objDBIMUser->manualAssign($user['userid']) ? $objIcon->setIcon('grey_bullet'): $objIcon->setIcon('green_bullet');
        $reassignIcon = $objIcon->show();
        $objLink->href = $this->uri(array("action" => "togglereassign", "userid" => $user['userid']));
        $objLink->link = $objIcon->show();
        $reassign = $objLink->show();

        $objIcon->setIcon('delete');
        $objLink->href = $this->uri(array("action" => "removecounsilor", "userid" => $user['userid']));
        $objLink->link = $objIcon->show();
        $cnt = count($this->objDbImPres->getUsers($user['userid']));
    }else{
        $reassign = "";
        $objIcon->setIcon('add');
        $objLink->href = $this->uri(array("action" => "addcounsilor", "userid" => $user['userid']));
        $objLink->link = $objIcon->show();
    }


    if ($objLoggedIn->isUserOnline($user['userid']))
    {
        $objIcon->setIcon('green_bullet');
    }else{
        $objIcon->setIcon('grey_bullet');
    }
    $bullet = $objIcon->show();

    $arr[] = array($name, $cnt, /*$bullet,*/ $reassign, $objLink->show());
    //echo $name."   ".$objLink->show().'  ('.$cnt.' users assigned) <br/>';

}

$objLink->href = $this->uri(array('action' => 'startsession'));
$objLink->link = "Start Session";

$admin = "".$objLink->show();


$objLink->href = $this->uri(array('action' => 'endsession'));
$objLink->link = "Stop Session";

$admin .= "<br/>".$objLink->show();

$objTable->width = "90%";
$objTable->addHeader(array("Name"/*, "No. of people assigned"*/, "Logged In", "Auto Assign"));
$objTable->arrayToTable($arr);
$middleColumn .= $objTable->show();
$objLink->href = $this->uri(array('action' => 'resetcounsillors'));
$objLink->link = "Reset Counsillors";

$admin.= "<br/>".$objLink->show();

$rightColumn .= $objFB->show('', $admin);
$rightColumn .= $objFB->show("Settings",  $this->objImOps->getConfigBlock());
$leftColumn .= $objFB->show("Status",  $this->objImOps->getStatusBlock());
$leftColumn .= $this->objImOps->massMessage();
$rightColumn .= $this->objImOps->sendToSubscribers();
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
$cssLayout->setRightColumnContent($rightColumn);
echo $cssLayout->show();
//button to manage the session

?>
