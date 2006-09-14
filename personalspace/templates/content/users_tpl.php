<?php
	echo "<h1>Add a buddy</h1>";
    $objAlphabet =& $this->getObject('alphabet','display');
	$linkarray = array('action'=>'ListUsers','how'=>'firstname','searchField'=>'LETTER');
	$url = $this->uri($linkarray,'personalspace');
	echo $objAlphabet->putAlpha($url); 
	echo "<table>";
        echo "<tr>";
        echo "<td>";
        echo "Picture";
        echo "</td>";
        echo "<td>";
        echo "Homepage";
        echo "</td>";
        echo "<td>";
        echo "EMail";
        echo "</td>";
        echo "</tr>";
	$line = 0;
        $index = 0;
	foreach ($allUsers as $user) {
		if (($line % 2)==0) {
			echo "<tr class=\"even\">";
		}
		else {
			echo "<tr class=\"odd\">";
		}
		echo "<td>";
                $objUserPic =& $this->getObject('imageupload', 'useradmin');
                echo "<image src=\"" . $objUserPic->smallUserPicture($user['userId']) . "\"/>";
		echo "</td>";
		echo "<td>";
		echo "<a href=\"" . 
			$this->uri(array(
				'module'=>'personalspace',
				'action'=>'ViewHomepage',
				'userId'=>$user['userId']
			))	
		. "\">".$user["firstName"] . "&nbsp;" . $user["surname"]."</a>";	
		echo "</td>";
		echo "<td>";
		echo "<a href=\"mailto:" . $user["emailAddress"] . "\">" . $user["emailAddress"] . "</a>";	
		echo "</td>";
		echo "<td>";
		echo "<a href = \"" .
			$this->uri(array(
				'module'=>'personalspace',
				'action'=>'AddBudy',
				'buddyId'=>$user["userId"]
			))	
		. "\">Make buddy</a>";
		echo "</td>";
		echo "<td>";
                if ($isBuddy[$index]) {
	            $icon = $this->getObject('geticon','htmlelements');
                    $icon->setIcon('user_user');
	            $icon->alt = "Is a buddy";
	            $icon->align=false;
                    echo $icon->show();
                }
                else {
                }
		echo "</td>";
		echo "</tr>";
		$line++;
                $index++;
	}
	echo "</table>";
        echo "<br/>";
	echo "<a href = \"" .
	$this->uri(array(
	'module'=>'personalspace',
	'action'=>'buddies',
	))
	. "\">Return to buddies</a>";
?>
