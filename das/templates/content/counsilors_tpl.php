<?php

$objDBIMUser = $this->getObject('dbimusers', 'im');
$objLink = $this->getObject('link','htmlelements');
$objIcon = $this->getObject('geticon','htmlelements');
$objTable = $this->getObject('htmltable','htmlelements');
$objLoggedIn = $this->getObject('loggedinusers', 'security');

$numCounsilors = count($users);
$numUsers = $this->objDbImPres->getRecordCount();
$online = False;
echo "IM User: <b>".$this->juser.'</b><br/>';
echo "Status: <span class=\"highlight\">".$online.'</span><br/>';
echo "Number of Counsilors: $numCounsilors<br/>";
echo "Number of Users: $numUsers <br/><br/>";
$arr = array();
foreach ($users as $user)
{
    $cnt = 0;
    $name = $user['firstname']." ".$user['surname'];
    if($objDBIMUser->isCounsilor($user['userid']))
    {
        $objIcon->setIcon('delete');
        $objLink->href = $this->uri(array("action" => "removecounsilor", "userid" => $user['userid']));
        $objLink->link = $objIcon->show();
        $cnt = count($this->objDbImPres->getUsers($user['userid']));
    }else{
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

    $arr[] = array($name, $cnt, $bullet, $objLink->show());
    //echo $name."   ".$objLink->show().'  ('.$cnt.' users assigned) <br/>';

}

$objLink->href = $this->uri(array('action' => 'startsession'));
$objLink->link = "Start Session";

echo "".$objLink->show();


$objLink->href = $this->uri(array('action' => 'endsession'));
$objLink->link = "Stop Session";

echo "<br/>".$objLink->show();

$objTable->width = "50%";
$objTable->addHeader(array("Name", "No. of people assigned", "Logged In", " "));
$objTable->arrayToTable($arr);
echo $objTable->show();
$objLink->href = $this->uri(array('action' => 'resetcounsillors'));
$objLink->link = "Reset Counsillors";

echo "<br/>".$objLink->show();



//button to manage the session

?>
