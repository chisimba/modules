<?php
$this->loadClass('htmltable','htmlelements');

$courseProposal = $this->objCourseProposals->getCourseProposal($_POST['id']);
$verarray = $this->objDocumentStore->getVersion($courseProposal['id'], $this->objUser->userId());

$displayTable = new htmltable();
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
$displayTable->addCell($courseProposal['title']);
$displayTable->addCell($courseProposal['creation_date']);
$displayTable->addCell($this->objUser->fullname($courseProposal['userid']));
$displayTable->addCell($status);
$displayTable->addCell($verarray['version'] . ".00");
$displayTable->addCell($this->objUser->fullname($verarray['currentuser']));
$displayTable->endRow();

echo $displayTable->show();

?>
