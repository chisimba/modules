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

//var_dump($stdres);
if(is_array($finapplication)){
	$header->str = "Residence Application of ".$finapplication[0]['name']."  ".$finapplication[0]['surname']." for $applicationYear ";
	$id = $finapplication[0]['personID'];
	
	foreach($finapplication as $app){

		if(is_array($applicationstatus)){
			$drpstatus = new dropdown('status');
			$drpstatus->addOption(' ', ' ');
				foreach($applicationstatus as $status){
				$drpstatus->addOption($status['lookupID'],$status['value']);
			}

			$drpstatus->setSelected($app['resApplicationStatus']);
		}
		
		$drpyear = new dropdown('resyear');
		$startyear = date("Y");
		$drpyear->addOption(' ',' ');
		for($i = 0;$i < 2;$i++ ){
			$drpyear->addOption($startyear,$startyear);
			$startyear++;
		}

		$drpres = new dropdown('residence');
		$drpres->addOption(' ', 'Not allocated');
		if(is_array($residence)){
			foreach($residence as $res){
				$drpres->addOption($res['residenceID'],$res['residenceName']);
			}
			
			//$drpannual->setSelected($familyperson[0]['annualgross']);
		}
	
		$resappID = "";
		if(is_array($stdres)){
			$drpres->setSelected($stdres[0]['residenceID']);
			$resappID = new textinput('txtResAppID',$stdres[0]['studentResidenceID'],'hidden');
			
			$drpyear->setSelected($stdres[0]['resyear']);
		}

		
		
		$comment = new textarea('txtComment',$app['resComment'],4,20);
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
		$table->addCell('Allocated Residence');
		$table->addCell($drpres->show());
		$table->endRow();


		$table->startRow();
		$table->addCell('Residing year');
		$table->addCell($drpyear->show());
		$table->endRow();

		$table->startRow();
		$table->addCell($applicationID->show());
		if(is_array($stdres)){
			$table->addCell($resappID->show());
		}
		else{
			$table->addCell(' ');
		}
		$table->endRow();
	}
	
	$ok= new button('edit_now');
	$ok->setToSubmit();
	$ok->setValue('finish');
}
else{
	$header->str = "This student Has no Residence Application for $applicationYear ";
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
$objForm->setAction($this->uri(array('action'=>'resapplication','id'=>$id,'year'=>$year)));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();

?>
