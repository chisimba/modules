<?
/**
* Layout template for the conferenceprelogin module
* @package conferenceprelogin
*/

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(1);

$objUtils = $this->newObject('conferenceregister');
$objUtils->setModuleName('conferenceprelogin');


$cssLayout->setLeftColumnContent('');
$cssLayout->setMiddleColumnContent($this->getContent());


echo $cssLayout->show();
?>
