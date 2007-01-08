<?
/**
* Template layout for assignment management module
* @package assignmentadmin
*/

/**
* Template layout for assignment management module
*/

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$leftMenu =& $this->newObject('sidemenu','toolbar');
$objHead =& $this->newObject('htmlheading','htmlelements');

if(!isset($heading))
    $heading=$objLanguage->languageText('mod_assignmentadmin_name');
    
$objHead->str=$heading;
$objHead->type=1;
$head = $objHead->show();

$cssLayout->setLeftColumnContent($leftMenu->menuContext());
$cssLayout->setMiddleColumnContent($head.$this->getContent());

echo $cssLayout->show();
?>
