<?php

$this->loadClass('htmltable','htmlelements');
$this->loadClass('textarea','htmlelements');

$courseID = $this->getParam('id');
$courseProposal = $this->objCourseProposals->getCourseProposal($_POST['id']);
$verarray = $this->objDocumentStore->getVersion($courseProposal['id'], $this->objUser->userId());

$displayTable = new htmltable();
$displayTable->width = '75%';
$displayTable->startHeaderRow();
$displayTable->addHeaderCell($this->objLanguage->languageText('mod_ads_title', 'ads'));
$displayTable->addHeaderCell($this->objLanguage->languageText('mod_ads_datecreated', 'ads'));
$displayTable->addHeaderCell($this->objLanguage->languageText('mod_ads_owner', 'ads'));
$displayTable->addHeaderCell($this->objLanguage->languageText('mod_ads_status', 'ads'));
$displayTable->addHeaderCell($this->objLanguage->languageText('mod_ads_currversion', 'ads'));
$displayTable->addHeaderCell($this->objLanguage->languageText('mod_ads_lastedit', 'ads'));
$displayTable->endHeaderRow();

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

$displayTable->startRow();
$displayTable->addCell($this->getParam('title'));
$displayTable->addCell($this->getParam('date'));
$displayTable->addCell($this->getParam('owner'));
$displayTable->addCell($this->getParam('status'));
$displayTable->addCell($this->getParam('version') . ".00");
$displayTable->addCell($this->getParam('lastedit'));
$displayTable->endRow();

echo $displayTable->show();

$txtComment = new textarea('admComment','',10,110);
$submit = new button('submitbutton','Submit');
$submit->setToSubmit();

$frmComment = new form('Commentform',$this->uri(array('action'=>'savecomment','id'=>$courseID)));
$frmComment->addToForm($txtComment->show().'<br>');
$frmComment->addToForm($submit->show());

echo '<b>Add Comment</b><br>'.$frmComment->show();
?>
