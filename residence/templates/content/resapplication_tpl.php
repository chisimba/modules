<?
// Create an instance of the css layout class
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(3);


$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module'));


$left =& $this->getObject('leftblock');
$left = $left->show($id);

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
$applicationYear = 2006;
$id=$this->getParam('id');
if(!$finapplication==null){




	$header->str = "Residence Application for ".$student[0]->FSTNAM." ".$student[0]->SURNAM." for ".$applicationYear;
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
	$header->str = "This student Has no Residence Application for ".$applicationYear;
		$form = new form('metadata',$this->uri(array('action'=>'confirmstudentapp','id'=>$id)));
	

		
		$textarea = & $this->newObject('textinput','htmlelements');
        $textarea->size = '30';
        $textarea->value = $id;
        $textarea->setId(null);

 		
 		//title
        $title = $textarea;
        $title->name = 'student';
        // $title->setValue($arr['title']);
        $title->label = "Student Number";
 		
		$table1 = & $this->newObject('htmltable','htmlelements');
        $table1->width = '75%';
		$table1->addRow(array($title->label,$title->show()),'even');

	$button = new button("submit",'Add');    //word_save
	$button->setToSubmit();
	$row = array($button->show());
	$table1->addRow($row);

	$form->addToForm($table1->show());

$heading = '<h3>'.'Add Student To Residence Application List'.'</h3>';
$cssLayout2->setMiddleColumnContent('<p>'.$heading.'</p>'.$form->show());
}
$cssLayout2->setLeftColumnContent($left);
echo $cssLayout2->show();
?>
