<?php

//Insert script for generating tree menu
$js = $this->getJavascriptFile('tree.js', 'cmsadmin');
$this->appendArrayVar('headerParams', $js);
//Include tree menu css script
$css = '<link rel="stylesheet" type="text/css" media="all" href="modules/cmsadmin/resources/tree.css" />';
$this->appendArrayVar('headerParams', $css);
//Set to automatically render htmllist into tree menu
$this->appendArrayVar('bodyOnLoad', 'autoInit_trees()');

// Create an instance of the CSS Layout
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);
// Set the Content of middle column
$cssLayout->setLeftColumnContent($this->getCMSMenu());
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show(); 

?>
