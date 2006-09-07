<?

$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

/*
$details = "<p><b>Details of ".ucfirst($stdinfo[0]['name'])."  ".ucfirst($stdinfo[0]['surname'])."</p>";
$idnumber = $stdinfo[0]['personID'];
*/
$details = "<p><b>List Of Sponsors</b></p>";
$newsponsor = new link();
$newsponsor->href=$this->uri(array('action'=>'newsponsor'));
$newsponsor->link = "Add New Sponsor";

$details .= "<p>".$newsponsor->show()."</p>";

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

$this->financialaid =& $this->getObject('dbfinancialaid');

if(is_array($sponsorlist)){
	$table->startHeaderRow();
	$table->addHeaderCell('Name',"50%");
	$table->addHeaderCell('Number Of Students Sponsored');
	$table->addHeaderCell('Total Amount Sponsored');
	$table->addHeaderCell('  ');
	$table->endHeaderRow();

	foreach($sponsorlist as $value){
		$table->startRow();
		$link = new link();
		$link->href=$this->uri(array('action'=>'studentssponsored','id'=>$value['personID']));
		$link->link= $value['name']."  ".$value['surname'];
		$table->addCell($link->show());
		$stats = $this->financialaid->sponsorStats($value['personID']);
		$table->addCell($stats[0]['numStdSponsored']);
		$table->addCell(number_format($stats[0]['amount'],2));
		$edit = new link();
		$edit->href=$this->uri(array('action'=>'sponsorinfo','id'=>$value['personID']));
		$edit->link = "edit";
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

$content = "<center>".$details." ".$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'ok')));
$objForm->setDisplayType(2);

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$objForm->addToForm($content);
$objForm->addToForm($ok);


$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();


?>
