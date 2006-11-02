<?php
	$cssLayout =& $this->newObject('csslayout', 'htmlelements');
	$userMenuBar=& $this->getObject('sidemenu','toolbar');

	$cssLayout->setLeftColumnContent($userMenuBar->menuContext());
	$cssLayout->setMiddleColumnContent($this->getContent());

	echo $cssLayout->show() ;
?>

