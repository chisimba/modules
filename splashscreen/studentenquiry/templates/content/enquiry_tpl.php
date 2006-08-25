<?
  // Get an Instance of the language object
  $objLanguage = &$this->getObject('language', 'language');

$right =& $this->getObject('blocksearchbox');
$right = $right->show($this->getParam('module'));


$left =& $this->getObject('leftblock');
$left = $left->show();

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
//var_dump($enquiry);
$details = "";
$idnumber=$this->getParam('id');

if(is_array($enquiry)){
	$idnumber = $enquiry[0]->personID;
	$details .= " <p><b>".ucfirst($enquiry[0]->FSTNAM)."  ".ucfirst($enquiry[0]->SURNAM)."'s Enquiries</p></b>";

	/*
	$link = new link();
	$link->href = $this->uri(array('action'=>'enquiry','add'=>'add','personid'=>$idnumber));
	$link->link="new enquiry";
	$details .= $link->show();
	*/

	$table->startHeaderRow();
	$table->addHeaderCell($this->objLanguage->languageText('mod_studentenquiry_tmanddte','studentenquiry'));
	$table->addHeaderCell($this->objLanguage->languageText('mod_studentenquiry_enqlbl','studentenquiry'));
	$table->addHeaderCell(' ');
	$table->endHeaderRow();
	foreach($enquiry as $enq){
		$edit = new link();
		$edit->href=$this->uri(array('action'=>'enquiry','edit'=>'edit','queryID'=>$enq->enquiryID));
		$edit->link=$this->objLanguage->languageText('mod_studentenquiry_edit','studentenquiry');

		$table->startRow();
		$table->addCell($enq->dateOfEnquiry);
		$table->addCell($enq->enquiry);
		$table->addCell($edit->show());
		$table->endRow();
	}
}

/*
$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();
		
$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
$this->rightNav->width="200px";
echo $this->rightNav->addToLayer();
*/
$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$new= new button('addenquiry');
$new->setToSubmit();
$new->setValue($this->objLanguage->languageText('mod_studentenquiry_nenquiry','studentenquiry'));

//$this->objLanguage->languageText('mod_studentenquiry_nenquiry','studentenquiry')

$table->startRow();
//$table->addCell($ok->show());
$table->addCell($new->show());
$table->addCell('&nbsp;');
$table->endRow();

$content = $details." ".$table->show();
 $idnumber=$this->getParam('id');
$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'stenquiry','id'=>$idnumber)));
$objForm->setDisplayType(2);



$objForm->addToForm($content.'</br></br></br></br></br></br></br></br></br>');
//$objForm->addToForm($ok);

/*
$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str =
//$this->contentNav->height="300px"; */
$content = $objForm->show();

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();
?>
