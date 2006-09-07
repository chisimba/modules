<?

$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));


$left =& $this->getObject('leftblock');
$left = $left->show();

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();

$header = & $this->newObject('htmlheading','htmlelements');
$table =& $this->newObject('htmltable','htmlelements');
$id=$this->getParam('id');
$ok = "";

if(is_array($finapplication)){
	$header->str = "Financial Aid Application of ".$finapplication[0]['name']."  ".$finapplication[0]['surname']." for $applicationYear ";
	$id = $finapplication[0]['personID'];
	
	foreach($finapplication as $app){

		if(is_array($applicationstatus)){
			$drpstatus = new dropdown('status');
			$drpstatus->addOption(' ', ' ');
				foreach($applicationstatus as $status){
				$drpstatus->addOption($status['lookupID'],$status['value']);
			}

			$drpstatus->setSelected($app['finApplicationStatus']);
		}

		$comment = new textarea('txtComment',$app['finComment'],4,20);
		$applicationID = new textinput('txtApplicationID',$app['applicationID'],'hidden');
	
		$appstatus = $this->financialaid->getLookupInfo($app['finApplicationStatus']);
		$table->startRow();
		$table->addCell('Application Status');
		$table->addCell($drpstatus->show());
		$table->endRow();
		
		$table->startRow();
		$table->addCell('Comment');
		$table->addCell($comment->show());
		$table->endRow();
		
		$table->startRow();
		$table->addCell($applicationID->show());
		$table->endRow();
	}
	
	$ok= new button('edit_now');
	$ok->setToSubmit();
	$ok->setValue('finish');
}
else{
	$header->str = "This student Has no Financial Aid Application for $applicationYear ";
	$ok= new button('cancel');
	$ok->setToSubmit();
	$ok->setValue('OK');
}



$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');

$table->startRow();
$table->addCell($ok->show());
$table->addCell($cancel->show());
$table->endRow();

//echo $table->show();

$content = "<center>".$header->show()."  ".$table->show();
$year = "";
if(!$this->getParam('year'))$year = $applicationYear;
else $year = $this->getParam('year');

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'finapplication','id'=>$id,'year'=>$year)));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();

?>
