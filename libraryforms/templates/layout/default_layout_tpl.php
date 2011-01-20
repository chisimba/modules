<?php
$this->loadClass('link', 'htmlelements');

$objLandingPage = new link($this->uri(array('action'=>'Home')));
$objLandingPage->link='Home';
$landinging = $objLandingPage->show();

$objFeedbk = new link($this->uri(array('action'=>'Tell us Feed back')));
$objFeedbk->link='Tell us Feed back';
$fdb = $objFeedbk->show();


$objbookthesis = new link($this->uri(array('action'=>'ILL: Periodical books')));
$objbookthesis->link='ILL: Periodical books';
$pd =$objbookthesis->show();



$objbookthesis = new link($this->uri(array('action'=>'ILL: Thesis books')));
$objbookthesis->link='ILL: Thesis books';
$bt =$objbookthesis->show();

$cssLayout = $this->getObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent('<br />'.$landinging.'<br />'.$fdb.'<br />'.$pd.'<br />'.$bt);
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();

?>






