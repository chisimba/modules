<?php

	$this->loadClass('link', 'htmlelements');
	$this->loadClass('htmlheading', 'htmlelements');
	$this->loadClass('radio', 'htmlelements');
	$this->loadClass('label', 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$this->loadClass('hiddeninput', 'htmlelements');
	$this->loadClass('textinput', 'htmlelements');
	$this->loadClass('form', 'htmlelements');
	$this->loadClass('button', 'htmlelements');
	$this->loadClass('mouseoverpopup', 'htmlelements');

	$objIcon = $this->newObject('geticon', 'htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');

	$objHeading->type=1;
	$objHeading->str ='<font class="warning">'.$objLanguage->languageText("mod_eportfolio_maintitle",'eportfolio').'</font>';
	echo $objHeading->show();


$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_contact')));
$link->link = 'View Identification Details';
echo '<br clear="left" />'.$link->show();


$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_activity')));
$link->link = 'View Activities';
echo '<br clear="left" />'.$link->show();

$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_affiliation')));
$link->link = 'View Affiliation';
echo '<br clear="left" />'.$link->show();

$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_transcript')));
$link->link = 'View Transcripts';
echo '<br clear="left" />'.$link->show();

$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_qcl')));
$link->link = 'View Qualifications';
echo '<br clear="left" />'.$link->show();

$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_goals')));
$link->link = 'View Goals';
echo '<br clear="left" />'.$link->show();


$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_competency')));
$link->link = 'View Competency';
echo '<br clear="left" />'.$link->show();

$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_interest')));
$link->link = 'View Interests';
echo '<br clear="left" />'.$link->show();

$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_reflection')));
$link->link = 'View Reflections';
echo '<br clear="left" />'.$link->show();

$link = new link($this->uri(array('module'=>'eportfolio','action'=>'view_assertion')));
$link->link = 'View Assertions';
echo '<br clear="left" />'.$link->show();

$returnlink = new link($this->uri(NULL, '_default'));
$returnlink->link = 'Return to Home Page';
echo '<br clear="left" />'.$returnlink->show();
?>
