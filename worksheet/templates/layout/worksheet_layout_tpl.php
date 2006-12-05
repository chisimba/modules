<?
/**
* Template layout for worksheet module
* @package worksheet
*/

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$leftMenu=& $this->newObject('sidemenu','toolbar');
$objHead=& $this->newObject('htmlheading','htmlelements');

if(!isset($heading))
    $heading=$objLanguage->languageText('mod_worksheet_name').' '.$objLanguage->languageText('mod_worksheet_in')
.' '.$contextTitle;

$objHead->str=$heading;
$objHead->type=1;
$main = $objHead->show();

$main .= $this->getContent();

$cssLayout->setLeftColumnContent($leftMenu->menuContext());
$cssLayout->setMiddleColumnContent($main);

echo $cssLayout->show();
?>
