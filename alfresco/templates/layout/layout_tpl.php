<?php

$cssLayout=&$this->newObject('csslayout','htmlelements');

$userMenuBar=& $this->getObject('sidemenu','toolbar');
$toolbar = $this->getObject('contextsidebar', 'context');
$cssLayout->setNumColumns(2);

$leftSideContent = $userMenuBar->menuContext();
$leftSideContent .= '<ul id="nav-secondary"><li class="first"><a href="'.$this->uri(array('action'=>'showcontent')).'" > Browse Alfresco content </a></li></ul>';
$cssLayout->setLeftColumnContent($leftSideContent);
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>