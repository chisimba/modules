<?php
foreach($ret as $users)
{
	//grab the user info from the user object
	$id = $users['userid'];
	$name = $this->objUser->fullName($id);
	$laston = $this->objUser->getLastLoginDate($id);
	$img = $this->objUser->getUserImage($id, FALSE);

	$uinfo[] = array('id' => $id, 'name' => $name, 'laston' => $laston, 'img' => $img);
}

print_r($uinfo);

