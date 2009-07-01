<?php

//1. Create an assignment
$script = $this->getJavaScriptFile('turnitin.js', 'turnitin');
$this->appendArrayVar('headerParams', $script);
$refreshLink = $this->newObject('link', 'htmlelements');
$refreshLink->href = '#';
$refreshLink->extra = ' onclick="showLoading(\'addassignment\'); addAssignment()" ';
//$refreshLink->extra = ' onclick="a()" ';
$refreshLink->link = "Add Assignment";
print $refreshLink->show();
?>

<div id="addassignment">wes</div>