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
if(is_array($finapplication)){
	$header->str = "Residence Application for ".$finapplication[0]['name']."  ".$finapplication[0]['surname']." for $applicationYear ";
	$id = $finapplication[0]['personID'];
	foreach($finapplication as $app){
		$appstatus = $this->financialaid->getLookupInfo($app['resApplicationStatus']);
		$table->startRow();
		$table->addCell('Application Status');
		$table->addCell($appstatus[0]['value']);
		$table->endRow();
		
		$table->startRow();
		$table->addCell('Comment');
		$table->addCell($app['resComment']);
		$table->endRow();
	}
}
else{
	$header->str = "This student Has no Residence Application for $applicationYear ";
}

/*
if(is_array($studyYears)){
	$table->startRow();
	$table->addCell('Other years of Study ');
	$links = "";
	foreach($studyYears as $years){
		
		$link = new link();
		$link->href = $this->uri(array('action'=>'resapplication','id'=>$id,'year'=>$years['yearOfStudy']));
		$link->link = $years['yearOfStudy'];
		$links .=$link->show()."  ";

	}

	$table->addCell($links,$width=null, $valign="top", $align=null, $class=null, "colspan=2");
	$table->endRow();		

}
*/

$ok= new button('edit');
$ok->setToSubmit();
$ok->setValue('edit');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');

$table->startRow();
$table->addCell($ok->show());
$table->addCell($cancel->show());
$table->endRow();

//echo $table->show();
//var_dump($stdres);

$content = "<center>".$header->show()."  ".$table->show();
$year = "";
if(!$this->getParam('year'))$year = $applicationYear;
else $year = $this->getParam('year');

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'resapplication','id'=>$id,'year'=>$year)));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();

?>
