<?php
//$objBlocks = $this->getObject('blocks', 'blocks');
$this->loadClass('link', 'htmlelements');

//link to Staff Member Registration From
$objRegStaff = new link($this->uri(array('action'=>'Staff Member Registarion')));
$objRegStaff->link='Staff Member Registarion';
$h = $objRegStaff->show();

//link to view registered Staff Members
$objRegStaff = new link($this->uri(array('action'=>'Registered Staff Member')));
$objRegStaff->link='Registered Staff Members';
$a = $objRegStaff->show();

//link to DOE Accredited Journal Articles data entry page
$objAccrJournal = new link($this->uri(array('action'=>'DOE Accredoted Journal Articles')));
$objAccrJournal->link='Accredited Journal';
 $b = $objAccrJournal->show();

//View Accredited Journal Article
$objViewJournalf = new link($this->uri(array('action'=>'Accredted Journal Authors')));
$objViewJournalf->link='Accredted Journal Authors';
$c = $objViewJournalf->show();

//link to Entire Book data entry page
$objEntireBook = new link($this->uri(array('action'=>'Entire Book/Monogragh')));
$objEntireBook->link='Entire Book';
 $d = $objEntireBook->show();

//link to Chapter in a Book data entry page
$objChaptersInBook = new link($this->uri(array('action'=>'Chapter In a Book')));
$objChaptersInBook->link='Chapter In a Book';
 $e = $objChaptersInBook->show();

//link to Graduating Doctoral Student data entry page
$objGradDocStud = new link($this->uri(array('action'=>'Graduating Doctoral Student')));
$objGradDocStud->link='Doctoral Student';
 $f = $objGradDocStud->show();

//link to Graduating Masters Student data entry page
$objGradMasterStud = new link($this->uri(array('action'=>'Graduating Masters Student')));
$objGradMasterStud->link='Masters Student';
 $g = $objGradMasterStud->show();

$cssLayout = $this->getObject('csslayout', 'htmlelements');

//$leftColumn =$objRegStaff->show();// $this->getVar('leftContent');
//$middleColumn = $this->getVar('middleContent');

$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent('<br />'.$a.'<br />'.$b.'<br />'.$c.'<br />'.$d.'<br />'.$e.'<br />'.$f.'<br />'.$g.'<br />'.$h);
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>
