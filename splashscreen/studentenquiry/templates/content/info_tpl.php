<?

$right =& $this->getObject('blocksearchbox');
$right = $right->show();

$left =& $this->getObject('blockleftcolumn');
$left = $left->show($studentid);

$table =& $this->newObject('htmltable','htmlelements');
$idnumber = "";

if(is_array($stdinfo)){
	foreach($stdinfo as $data){
		$idnumber = $data['idnumber'];

		$table->startRow();
		$table->addCell('Gender');
		$table->addCell(ucfirst($data['gender']));
		$table->endRow();

		$table->startRow();
		$table->addCell('Race');
		$table->addCell(ucfirst($data['race']));
		$table->endRow();
	
	}
}


if(is_array($address)){
	foreach($address as $data){		
		$table->startRow();
		$table->addCell('Street Address');
		$table->addCell($data['streetaddress']);
		$table->endRow();

		$table->startRow();
		$table->addCell('Suburb');
		$table->addCell($data['suburb']);
		$table->endRow();

		$table->startRow();
		$table->addCell('City');
		$table->addCell($data['city']);
		$table->endRow();

		$table->startRow();
		$table->addCell('Telephone');
		$table->addCell($data['telephone']);
		$table->endRow();

		$table->startRow();
		$table->addCell('Cellphone');
		$table->addCell($data['cellphone']);
		$table->endRow();
	}
}


if(is_array($stdinfo)){
	foreach($stdinfo as $data){	
		$drop = new dropdown('status');
		$drop->addOption('waiting_list','  ');
		$drop->addOption('allocated','allocated');
		$drop->addOption('declined','declined');
		$drop->addOption('pending','pending');
		$drop->addOption('waiting_list','waiting_list');
		$drop->setselected($data['allocated']);
	
		$table->startRow();
		$table->addCell('Residence Status');
		$table->addCell($drop->show());
		//$table->addCell('');
		$table->endRow();

		if(isset($residence)){
			if(is_array($residence) and count($residence) > 0){
				$table->startRow();
				$table->addCell('Residence');
				$table->addCell($residence[0]['resName']);
				$table->endRow();
			}

			if(is_array($residence) and count($residence) < 1){
				$drop = new dropdown('residence');
				$drop->addOption('1','Hector Peterson Residece');
				$drop->addOption('2','Basil February');
				$drop->addOption('3','Cecil Esau');
				$drop->addOption('4','Ruth First');
				$drop->addOption('5','Eduardo Dos Santos');
				$drop->addOption('6','Gorvalla');
				//$drop->setselected($data['allocated']);

				$table->startRow();
				$table->addCell('Allocate Residence');
				$table->addCell($drop->show());
				$table->endRow();
			
			}
		}
	}
}
/*
if(is_array($stdprogramme)){
	foreach($stdprogramme as $data){
		$table->startRow();
		$table->addCell('Programme');
		$table->addCell($data['programme']);
		$table->endRow();
	}
}

if(is_array($faculty)){
	
	foreach($faculty as $data){
		$table->startRow();
		$table->addCell('Faculty');
		$table->addCell($data['faculty']);
		$table->endRow();
	}
}

if(is_array($financer)){
	
	foreach($financer as $data){
		$table->startRow();
		$table->addCell('Funded By:');
		$table->addCell($data['name']." ".$data['surname']);
		$table->endRow();

		$table->startRow();
		$table->addCell('Street Address');
		$table->addCell($data['streetAddress'].",  ".$data['suburb'].",  ".$data['code']);
		$table->endRow();

		$table->startRow();
		$table->addCell('Contact Details');
		$table->addCell("(Tel)  &nbsp;".$data['telephone']." <br>(Cell) &nbsp;".$data['cellphone']);
		$table->endRow();
	}
}
else{
	$table->startRow();
	$table->addCell('Funded By:');
	$table->addCell("<FONT COLOR=RED>SELF FUNDED</FONT>");
	$table->endRow();
}
*/

$info = new link();
$info->href= "index.php?module=studentenquiry&action=more_info&id=".$idnumber;
$info->link = "more info";

$table->startRow();
$table->addCell(' ');
$table->addCell($info->show());
$table->endRow();

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->width = "300";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();

$content = "<center>".$igama." ".$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'ok','id'=>$idnumber)));
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
