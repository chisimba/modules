=======
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
  
  $titleLink->link($this->uri(array('action'=>'viewform','courseid'=>$courseProposal['id'], 'formnumber'=>$this->allForms[0])));
  $titleLink->link=$courseProposal['title'];
  $objTable->addCell($titleLink->show());
  $objTable->addCell($courseProposal['creation_date']);
  $objTable->addCell($this->objUser->fullname($courseProposal['userid']));
  
  $statusLink->link($this->uri(array('action'=>'viewcourseproposalstatus','id'=>$courseProposal['id'])));

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

  $objTable->addCell($statusLink->show());
  $objTable->addCell($verarray['version'] . ".00");
  $objTable->addCell($this->objUser->fullname($verarray['currentuser']));
  $deleteLink->link($this->uri(array('action'=>'deletecourseproposal','id'=>$courseProposal['id'])));
  $objIcon->setIcon('delete');
  $deleteLink->link=$objIcon->show();

  $editLink->link($this->uri(array('action'=>'editcourseproposal','id'=>$courseProposal['id'])));
  $objIcon->setIcon('edit');
  $editLink->link=$objIcon->show();
  
    //review
  $reviewLink->link($this->uri(array('action'=>'reviewcourseproposal','id'=>$courseProposal['id'])));
  $objIcon->setIcon('view');
  $reviewLink->link=$objIcon->show();
  
  $objTable->addCell($editLink->show().$deleteLink->show().$reviewLink->show());
  $objTable->endRow();
 
}



/************** Build form **********************/
$addButton = new button('add','Add');
$returnUrl = $this->uri(array('action' => 'addcourseproposal'));
$addButton->setOnClick("window.location='$returnUrl'");

$objForm = new form('FormName',$this->uri(array('action'=>'addcourseproposal')));
$objForm->addToForm($addButton->show());
$objForm->addToForm('</br>');
$objForm->addToForm($objTable->show());


$tip='N.B. The course/ unit proposal format constitutes the areas of questioning that
 academics engage with, for purposes of academic integrity and to implement national
regulatory requirements, when developing or amending a course/ unit. The appendices to
 the format, which you can access separately, provide supplementary information to assist
 in responding to each of the following questions as well as offering additional information
on the overall proposal process.
';
$content='<h1>'.$title.'</h1>';
$content.='<p>'.$tip.'</p>';
//$content.= $this->message;
$content.= $objForm->show();

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
//Add the table to the centered layer
$rightSideColumn = $content;
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();



?>