<?
$right =& $this->getObject('blocksearchbox','residence');
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
	$idnumber = $enquiry[0]['STDNUM'];
	$details .= " <p><b>".ucfirst($enquiry[0]['FSTNAM'])."  ".ucfirst($enquiry[0]['SURNAM'])."'s Enquiries</p></b>";

	/*
	$link = new link();
	$link->href = $this->uri(array('action'=>'enquiry','add'=>'add','personid'=>$idnumber));
	$link->link="new enquiry";
	$details .= $link->show();
	*/

	$table->startHeaderRow();
	$table->addHeaderCell('Enquiry Date and Time');
	$table->addHeaderCell('Enquiry');
	$table->addHeaderCell(' ');
	$table->endHeaderRow();
	foreach($enquiry as $enq){
		$edit = new link();
		$edit->href=$this->uri(array('action'=>'enquiry','edit'=>'edit','queryID'=>$enq['enquiryID']));
		$edit->link="edit";

		$table->startRow();
		$table->addCell($enq['dateOfEnquiry']);
		$table->addCell($enq['enquiry']);
		$table->addCell($edit->show());
		$table->endRow();
		
	}

}



$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();
		
$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$new= new button('addenquiry');
$new->setToSubmit();
$new->setValue('New Enquiry');

$table->startRow();
$table->addCell($ok->show());
$table->addCell($new->show());
$table->endRow();

$content = "<center>".$details." ".$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'enquiry','id'=>$idnumber)));
$objForm->setDisplayType(2);



$objForm->addToForm($content);
//$objForm->addToForm($ok);


$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();


?>
