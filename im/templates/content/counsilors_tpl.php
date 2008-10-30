<?php

$objDBIMUser = $this->getObject('dbimusers', 'im');
$objLink = $this->getObject('link','htmlelements');
foreach ($users as $user)
{
	
	$name = $user['firstname']." ".$user['surname'];
	if($objDBIMUser->isCounsilor($user['userid']))
	{
		$objLink->href = $this->uri(array("action" => "removecounsilor", "userid" => $user['userid']));
		$objLink->link = "remove";
	}else{
		$objLink->href = $this->uri(array("action" => "addcounsilor", "userid" => $user['userid']));
		$objLink->link = "add";
	}
	
	echo $name."   ".$objLink->show().'<br/>';

}

?>