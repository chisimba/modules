<?php

$objDBIMUser = $this->getObject('dbimusers', 'im');
$objLink = $this->getObject('link','htmlelements');
$objIcon = $this->getObject('geticon','htmlelements');
$objTable = $this->getObject('htmltable','htmlelements');
$objLoggedIn = $this->getObject('loggedinusers', 'security');

$numCounsilors = count($users);
$numUsers = $this->objDbImPres->getRecordCount();

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
    echo $name."   ".$objLink->show().'  ('.$cnt.' users assigned) <br/>';

}
$objTable->width = "50%";
$objTable->addHeader(array("Name", "No. of people assigned", "Logged In", " "));
$objTable->arrayToTable($arr);
echo $objTable->show();
$objLink->href = $this->uri(array('action' => 'resetcounsillors'));
$objLink->link = "Reset Counsillors";

echo "<br/><br/>".$objLink->show();


$objLink->href = $this->uri(array('action' => 'startsession'));
$objLink->link = "Start Session (under construction)";

echo "<br/><br/>".$objLink->show();


$objLink->href = $this->uri(array('action' => 'stopsession'));
$objLink->link = "Stop Session (under construction)";

echo "<br/><br/>".$objLink->show();
//button to manage the session

?>