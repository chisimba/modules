<?php 
/* A PHP template for the helloworld module output. */
?>
<?php
	// This iframe needs to be included in the system wide page/layout template
	$this->loadClass("button","htmlelements");
	$button = new button("launch", "Launch IM", "window.open('" 
		. $this->uri(array(
		    	'module'=>'instantmessaging',
				'action'=>'showUsers',
		)) 
		. "', 'IM', 'width=300, height=420, scrollbars=1')");
	echo $button->show();
?>
