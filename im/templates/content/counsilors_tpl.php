<?php
var_dump($users);
$objDBIMUser = $this->getObject('dbimuser', 'im');
foreach ($users as $user)
{
	
	$name = $user['firstname']." ".$user['surname'];
	if($objDBIMUser->isCounsilor($user['id']))
	{
		
	}else{
		
	}
	
	echo $name."   ".$icon.'<br/>';

}

?>