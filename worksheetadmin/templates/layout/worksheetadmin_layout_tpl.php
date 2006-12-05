<?
/**
* Template layout for worksheet module
* @package worksheetadmin
*/

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$leftMenu=& $this->newObject('sidemenu','toolbar');
$objHead=& $this->newObject('htmlheading','htmlelements');

if(!isset($heading))
    $heading=$objLanguage->languageText('mod_worksheetadmin_name', 'worksheetadmin').' '.$objLanguage->languageText('mod_worksheetadmin_in', 'worksheetadmin')
.' '.$contextTitle;

$objHead->str=$heading;
$objHead->type=1;
$main = $objHead->show();

$main .= $this->getContent();

$cssLayout->setLeftColumnContent($leftMenu->menuContext());
$cssLayout->setMiddleColumnContent($main);

echo $cssLayout->show();

?>