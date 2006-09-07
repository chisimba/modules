<?
//var_dump($enquiry);

$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$table =& $this->newObject('htmltable','htmlelements');
$details = "";
if(is_array($enquiry)){
		$details .= " <p><b>Edit ".ucfirst($enquiry[0]['name'])."  ".ucfirst($enquiry[0]['surname'])."'s Enquiry</p></b>";
		$id = $enquiry[0]['enquiryID'];
	foreach($enquiry as $enq){
		$datetime = new textinput('enqDateTime',$enq['dateOfEnquiry']);
		$query = new textinput('enqQuery',$enq['enquiry']);
		$comment = new textarea('enqComment',$enq['comment']);

		$table->startRow();
		$table->addCell('Time And Date',"70%");
		$table->addCell($datetime->show());
		$table->endRow();
		
		$table->startRow();
		$table->addCell('Enquiry');
		$table->addCell($query->show());
		$table->endRow();
		
		$table->startRow();
		$table->addCell('Comment');
		$table->addCell($comment->show());
		$table->endRow();

		$ok= new button('editquery');
		$ok->setToSubmit();
		$ok->setValue('edit');

		$cancel= new button('canceledit');
		$cancel->setToSubmit();
		$cancel->setValue('cancel');

		$table->startRow();
		$table->addCell($ok->show());
		$table->addCell($cancel->show());
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

$content = "<center>".$details."  ".$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'enquiry','queryid'=>$id)));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();
?>
