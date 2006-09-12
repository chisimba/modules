<?
// Create an instance of the css layout class
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(3);


$right =& $this->getObject('blocksearchbox','residence');
$right = $right->show($this->getParam('module'));


$left =& $this->getObject('leftblock');
$left = $left->show();

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
//echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
//echo $this->rightNav->addToLayer();
$cssLayout2->setRightColumnContent('<p>'.$right.'</p>');

$header = & $this->newObject('htmlheading','htmlelements');
$table =& $this->newObject('htmltable','htmlelements');
$table->width='99%';
        $table->border='0';
        $table->cellspacing='0';
        //$table->cellpadding='10';

$id=$this->getParam('id');
if(!$finapplication==null){

$applicationYear = 2006;


	$header->str = "Residence Application for ".$stundent[0]->FSTNAM." ".$stundent[0]->SURNAM." for $applicationYear ";
	$id = $finapplication[0]->STDNUM;

$table->startHeaderRow();

		$table->addHeaderCell("<center>".'Phone Code'."</center>");
		
		$table->addHeaderCell("<center>".'Phone Number'."</center>");
		
		$table->addHeaderCell("<center>".'Residence Block Number'."</center>");
		
		$table->addHeaderCell("<center>".'Residence Floor Number'."</center>");
		
		$table->addHeaderCell("<center>".'Residence Block Room Number'."</center>");
		
		$table->addHeaderCell("<center>".'Residence Room Number'."</center>");
		
		$table->addHeaderCell("<center>".'Residence Room Type'."</center>");
		
		$table->addHeaderCell("<center>".'Residence Year'."</center>");
		
		
		

		$table->endHeaderRow();

	foreach($finapplication as $app){
		//$appstatus = $this->financialaid->getLookupInfo($app->resApplicationStatus);
/*
[GENDER] => M
            [PHNCDE] => 021
            [PHNNUM] => 9592099
            [RESBLKNUM] => E
            [RESCDE] => 11
            [RESFLOORNO] => 2
            [RESRMBDNUM] => 1641
            [RESRMNUM] => 39
            [RESRMTYP] => D
            [RESSTS] => O
            [STDNUM] => 2219065
            [YEAR] => 2003

*/		
		
		

		$table->startRow();

		
		$table->addCell("<center>".$app->PHNCDE."</center>");
		
		$table->addCell("<center>".$app->PHNNUM."</center>");
		
		$table->addCell("<center>".$app->RESBLKNUM."</center>");
		
		$table->addCell("<center>".$app->RESFLOORNO."</center>");
		
		$table->addCell("<center>".$app->RESRMBDNUM."</center>");
		
		$table->addCell("<center>".$app->RESRMNUM."</center>");
		
		$table->addCell("<center>".$app->RESRMTYP."</center>");
		
		$table->addCell("<center>".$app->YEAR."</center>");
		
		

		$table->endRow();
		
		//$table->startRow();
		//$table->addCell('Comment');
		//$table->addCell($app->resComment);
		//$table->endRow();
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
/*
$table->startRow();
$table->addCell($ok->show());
$table->addCell($cancel->show());
$table->endRow();
*/

//var_dump($stdres);

$content = "<center>".$header->show()."  ".$table->show()."</center>";
$year = "";
if(!$this->getParam('year'))$year = $applicationYear;
else $year = $this->getParam('year');

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'resapplication','id'=>$id,'year'=>$year)));
$objForm->setDisplayType(1);

$objForm->addToForm('<p>'.$content.'</p>');

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
//echo $this->contentNav->addToLayer();
$cssLayout2->setMiddleColumnContent($objForm->show().'<p>'.$ok->show().$cancel->show().'</p>');

echo $cssLayout2->show();
?>
