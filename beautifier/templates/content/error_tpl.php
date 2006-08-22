<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('dropdown','htmlelements');
$userMenu  = &$this->newObject('usermenu','toolbar');

// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
// Set columns to 2
$cssLayout->setNumColumns(2);

// Add Post login menu to left column
$leftSideColumn ='';
$leftSideColumn = $userMenu->show();

$middleColumn = NULL;


$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_beautifier_heading', 'beautifier');

$instructions = new htmlheading();
$instructions->type = 4;
$instructions->str = $this->objLanguage->languageText('mod_beautifier_instructions', 'beautifier');

$middleColumn = $header->show();
//$leftSideColumn .= $instructions->show();
$modarr = array();

//echo $header->show();
$modarr = array();

if ($handle = opendir('modules/')) {
   while (false !== ($file = readdir($handle))) {
       if ($file != "." && $file != ".." && $file != "CVS") {
           $modarr[] = $file;
       }
   }
   closedir($handle);
}

$dd = & new dropdown('mod');
foreach($modarr as $options)
{
	$dd->addOption($options, $options);
}

$middleColumn .= $instructions->show();
$middleColumn .= $dd->show();


//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();
?>