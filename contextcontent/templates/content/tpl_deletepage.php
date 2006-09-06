<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$this->setVar('pageTitle', $this->objContext->getTitle().' - Delete Page: '.htmlentities($page['menutitle']));

if (trim($page['headerscripts']) != '') {

    $header = '
<![CDATA[
'.$page['headerscripts'].'
]]>
';
    $this->appendArrayVar('headerParams', $header);


}

$title = 'Delete Page: '.htmlentities($page['menutitle']);


$form = new form('deletepage', $this->uri(array('action'=>'deletepageconfirm')));

$form->addToForm('<p><strong>Are you SURE you want to delete this page?</strong></p>');

$radio = new radio ('confirmation');
$radio->addOption('N', ' No - Do not delete this page');
$radio->addOption('Y', ' Yes - Delete this page');
$radio->setSelected('N');
$radio->setBreakSpace('</p><p>');

$form->addToForm('<p>'.$radio->show().'</p>');

$button = new button ('confirm', 'Confirm Delete');
$button->setToSubmit();

$hiddeninput = new hiddeninput('id', $page['id']);

$form->addToForm('<p>'.$button->show().$hiddeninput->show().'</p>');

$hiddeninput = new hiddeninput('context', $this->contextCode);
$form->addToForm($hiddeninput->show());

$objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
echo $objHighlightLabels->show();

$featurebox = $this->newObject('featurebox', 'navigation');
echo $featurebox->show($title, $form->show());
?>