<?php

$objDBIMUser = $this->getObject('dbimusers', 'im');
$objLink = $this->getObject('link','htmlelements');
foreach ($users as $user)
{
    $cnt = 0;
    $name = $user['id'].' '.$user['firstname']." ".$user['surname'];
    if($objDBIMUser->isCounsilor($user['userid']))
    {
        $objLink->href = $this->uri(array("action" => "removecounsilor", "userid" => $user['userid']));
        $objLink->link = "remove";
        $cnt = count($this->objDbImPres->getUsers($user['userid']));
    }else{
        $objLink->href = $this->uri(array("action" => "addcounsilor", "userid" => $user['userid']));
        $objLink->link = "add";
    }

    echo $name."   ".$objLink->show().'  ('.$cnt.' users assigned) <br/>';

}


?>