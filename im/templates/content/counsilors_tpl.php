<?php

$objDBIMUser = $this->getObject('dbimusers', 'im');
$objLink = $this->getObject('link','htmlelements');
foreach ($users as $user)
{
	
	$name = $user['firstname']." ".$user['surname'];
	if($objDBIMUser->isCounsilor($user['id']))
	{
		$objLink->href = $this->uri(array("action" => "removecounsilor", "userid" => $user['id']));
		$objLink->link = "remove";
	}else{
		$objLink->href = $this->uri(array("action" => "addcounsilor", "userid" => $user['id']));
		$objLink->link = "add";
	}
	
	echo $name."   ".$objLink->show().'<br/>';

}

?>