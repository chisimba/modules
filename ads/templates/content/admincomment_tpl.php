<?php
$css = '<style type="text/css">
.submitLink {
  background-color:#eee;
  text-align:left;
}
</style>';
echo $css;


$this->loadClass('htmltable','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');
$objLanguage= $this->newObject('language','language');

$title=$objLanguage->languageText('mod_ads_proposals','ads');

//constructs the table
$objTable = new htmltable();
$courseProposals = $this->objCourseProposals->getCourseProposals($this->objUser->userId());

//$objTable->row_attributes=' height="10"';
$statusLink=new link();
$titleLink=new link();
$deleteLink=new link();
$editLink=new link();
$submitLink = new link();
$reviewLink=new link();
$commentlink = new link();

$objTable->startHeaderRow();
$objTable->addHeaderCell($this->objLanguage->languageText('mod_ads_title', 'ads'));
$objTable->addHeaderCell($this->objLanguage->languageText('mod_ads_datecreated', 'ads'));
$objTable->addHeaderCell($this->objLanguage->languageText('mod_ads_owner', 'ads'));
$objTable->addHeaderCell($this->objLanguage->languageText('mod_ads_status', 'ads'));
$objTable->addHeaderCell($this->objLanguage->languageText('mod_ads_currversion', 'ads'));
$objTable->addHeaderCell($this->objLanguage->languageText('mod_ads_lastedit', 'ads'));
$objTable->addHeaderCell($this->objLanguage->languageText('mod_ads_edit', 'ads'));
$objTable->endHeaderRow();

foreach($courseProposals as $courseProposal){
  $verarray = $this->objDocumentStore->getVersion($courseProposal['id'], $this->objUser->userId());
  if ($verarray['status'] == 'unsubmitted' && $verarray['currentuser'] == $this->objUser->userId()) {
    $objTable->startRow('submitLink');
  }
  else {
    $objTable->startRow();
  }

  //$titleLink->link($this->uri(array('action'=>'viewform','courseid'=>$courseProposal['id'], 'formnumber'=>$this->allForms[0])));
  //$titleLink->link=$courseProposal['title'];
  $objTable->addCell($courseProposal['title']);
  $objTable->addCell($courseProposal['creation_date']);
  $objTable->addCell($this->objUser->fullname($courseProposal['userid']));

  $statusLink->link($this->uri(array('action'=>'viewcourseproposalstatus','id'=>$courseProposal['id'])));

  /*
  switch($courseProposal['status']) {
      case 0: $statusLink->link='New';
              break;
      case 1: $statusLink->link='Under Review';
              break;
      case 2: $statusLink->link='Accepted';
              break;
      case 3: $statusLink->link='Rejected';
              break;
      default: $statusLink->link= 'New';
  }
   *
   */
  switch($courseProposal['status']) {
      case 0: $status='New';
              break;
      case 1: $status='Under Review';
              break;
      case 2: $status='Accepted';
              break;
      case 3: $status='Rejected';
              break;
      default: $status= 'New';
  }

  $objTable->addCell($status);
  $objTable->addCell($verarray['version'] . ".00");
  $objTable->addCell($this->objUser->fullname($verarray['currentuser']));
  //$deleteLink->link($this->uri(array('action'=>'deletecourseproposal','id'=>$courseProposal['id'])));
  //$objIcon->setIcon('delete');
  //$deleteLink->link=$objIcon->show();

  //$editLink->link($this->uri(array('action'=>'editcourseproposal','id'=>$courseProposal['id'])));
  //$objIcon->setIcon('edit');
  //$editLink->link=$objIcon->show();

    //review
  //$reviewLink->link($this->uri(array('action'=>'reviewcourseproposal','id'=>$courseProposal['id'])));
  //$objIcon->setIcon('view');
  //$reviewLink->link=$objIcon->show();

  //comment link
  $commentlink->link($this->uri(array('action'=>'addcomment','id'=>$courseProposal['id'])));
  $objIcon->setIcon('comment');
  $commentlink->link = $objIcon->show();

  //$objTable->addCell($editLink->show().$deleteLink->show().$reviewLink->show());
  $objTable->addCell($commentlink->show());
  $objTable->endRow();

}

echo $objTable->show();


?>