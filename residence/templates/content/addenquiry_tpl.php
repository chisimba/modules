<?
//var_dump($enquiry);

$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$table =& $this->newObject('htmltable','htmlelements');
$details = "";
$id = $this->getParam('id');
if(is_array($enquiry)){
		$details .= " <p><b>New Enquiry for ".ucfirst($enquiry[0]['name'])."  ".ucfirst($enquiry[0]['surname'])." </p></b>";
		$id = $enquiry[0]['personID'];
	foreach($enquiry as $enq){
		$datetime = new textinput('enqDateTime',date("Y-m-d H:i:s"));
		$query = new textinput('enqQuery');
		$comment = new textarea('enqComment');

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

		$ok= new button('addquery');
		$ok->setToSubmit();
		$ok->setValue('add');

		$cancel= new button('canceladd');
		$cancel->setToSubmit();
		$cancel->setValue('cancel');

		$table->startRow();
		$table->addCell($ok->show());
		$table->addCell($cancel->show());
		$table->endRow();
		
		
	}
	
}
else{
	$datetime = new textinput('enqDateTime',date("Y-m-d H:i:s"));
	$query = new textinput('enqQuery');
		$comment = new textarea('enqComment');

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

		$ok= new button('addquery');
		$ok->setToSubmit();
		$ok->setValue('add');

		$cancel= new button('canceladd');
		$cancel->setToSubmit();
		$cancel->setValue('cancel');

		$table->startRow();
		$table->addCell($ok->show());
		$table->addCell($cancel->show());
		$table->endRow();
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
$objForm->setAction($this->uri(array('action'=>'enquiry','personid'=>$id)));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();
?>
