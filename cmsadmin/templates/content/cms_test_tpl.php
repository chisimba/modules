<?php

//Template to view the different admin functions
// Suppress normal page elements and layout
$this->setVar('pageSuppressIM', FALSE);
$this->setVar('pageSuppressBanner', FALSE);
$this->setVar('pageSuppressToolbar', FALSE);
$this->setVar('suppressFooter', FALSE);

$objArticleBox = $this->newObject('articlebox', 'cmsadmin');

echo $treeMenu;

echo $objArticleBox->show($content);
?>
