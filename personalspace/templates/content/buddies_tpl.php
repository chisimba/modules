<?php
	echo "<h1>My Buddies</h1>";
	echo "<table>";
	$line = 0;
	$index = 0;
	foreach ($buddies as $buddy) {
		if (($line % 2)==0) {
			echo "<tr class=\"even\">";
		}
		else {
			echo "<tr class=\"odd\">";
		}
		echo "<td>";
		echo($objUser->fullname($buddy['buddyId']));
		echo "</td>";
		echo "<td>";
		echo "<a onclick=\"javascript:window.open('"
			. $this->uri(array(
			    	'module'=>'instantmessaging',
					'action'=>'sendMessage',
					'recipientId'=>$buddy['buddyId'],
					'closeWindow'=>'yes'
			), 'instantmessaging') 
			. "', 'IM', 'width=300, height=500, scrollbars=1')\" style=\"cursor:hand\">";
		$icon = $this->getObject('geticon','htmlelements');
		$icon->setIcon('im');
		$icon->alt = "Send Instant Message";
		$icon->align=false;
		echo $icon->show();
		echo "</a>";
		echo "</td>";
		echo "<td>";
		echo "<a href=\"" . 
			$this->uri(array(
				'action'=>'new'
			),
			'email'
			)	
		. "\">";
		$icon = $this->getObject('geticon','htmlelements');
		$icon->setIcon('notes');
		$icon->alt = "Send EMail";
		$icon->align=false;
		echo $icon->show();
		echo "</a>" . "&nbsp;";	
		echo "</td>";
		echo "<td>";
		echo "<a href=\"" . 
			$this->uri(array(
				'module'=>'personalspace',
				'action'=>'ViewHomepage',
				'userId'=>$buddy['buddyId']
			))	
		. "\">View Home Page</a>";	
		echo "</td>";
		echo "</tr>";
		$line++;
		$index++;
	}
	echo "</table>";	
	echo "<a href = \"" .
		$this->uri(array(
			'module'=>'personalspace',
			'action'=>'users',
		))	
	. "\">Add buddies</a>";
?>