<?php

// Create an instance of the CSS Layout
$cssLayout = $this->getObject('csslayout', 'htmlelements');
$this->appendArrayVar('headerParams', $this->getJavascriptFile('tree.js', 'cmsadmin'));
$css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("tree.css", 'cmsadmin').'" />';
$this->appendArrayVar('headerParams', $css);
//Set to automatically render htmllist into tree menu
$this->appendArrayVar('bodyOnLoad', 'autoInit_trees()');
$cssLayout->setNumColumns(2);
// Set the Content of middle column
$cssLayout->setLeftColumnContent($this->getCMSMenu());
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show();

?>