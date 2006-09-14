<?
// Create an instance of the css layout class
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(3);

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
if(is_array($stdinfo)){
	$idnumber = $stdinfo[0]->STDNUM;
	$details = '<p>'.ucfirst($stdinfo[0]->FSTNAM)."  ".ucfirst($stdinfo[0]->SURNAM)."'s Enquiry".'</p>';

	/*
	$link = new link();
	$link->href = $this->uri(array('action'=>'enquiry','add'=>'add','personid'=>$idnumber));
	$link->link="new enquiry";
	$details .= $link->show();
	*/

	$table->startHeaderRow();
	$table->addHeaderCell(' ');
	$table->addHeaderCell(' ');
	$table->addHeaderCell(' ');
	$table->addHeaderCell(' ');
	$table->addHeaderCell(' ');
	$table->addHeaderCell(' ');
	$table->addHeaderCell(' ');

	$table->endHeaderRow();
	
$rowclass ='odd';

		//student number
				$table->startRow($rowClass);
				$table->addCell('<p>'.'Student Id'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->STDNUM);
				$table->endRow($rowClass);
		
		//first name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'First Name'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->FSTNAM);
				$table->endRow($rowClass);
		//second name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'Surname'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->SURNAM);
				$table->endRow($rowClass);
		//BTHYMD name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'BTHYMD'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->BTHYMD);
				$table->endRow($rowClass);

		//RCE name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'RCE'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->RCE);
				$table->endRow($rowClass);

		//SEX name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'SEX'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->SEX);
				$table->endRow($rowClass);
		//IDN name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'IDN'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->IDN);
				$table->endRow($rowClass);
		//TTL name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'TTL'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->TTL);
				$table->endRow($rowClass);
		//ACALNG name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'ACALNG'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->ACALNG);
				$table->endRow($rowClass);
		//ATRNY name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'ATRNY'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->ATRNY);
				$table->endRow($rowClass);
		
		//HMELNG name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'HMELNG'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->HMELNG);
				$table->endRow($rowClass);

		//PRVREGYR name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'PRVREGYR'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->PRVREGYR);
				$table->endRow($rowClass);
		//STDSTS name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'STDSTS'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->STDSTS);
				$table->endRow($rowClass);
		//STDACCBAL name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'STDACCBAL'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->STDACCBAL);
				$table->endRow($rowClass);
		//STDACCSTS name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'STDACCSTS'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->STDACCSTS);
				$table->endRow($rowClass);
		//MARSTS name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'MARSTS'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->MARSTS);
				$table->endRow($rowClass);
		//ATRNY name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'ATRNY'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->ATRNY);
				$table->endRow($rowClass);
		//DBTPCKDTE name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'DBTPCKDTE'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->DBTPCKDTE);
				$table->endRow($rowClass);
		//DBTPCKSTS name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'DBTPCKSTS'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->DBTPCKSTS);
				$table->endRow($rowClass);
		//DRVCDE name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'DRVCDE'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->DRVCDE);
				$table->endRow($rowClass);
		//IGNRHMS name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'IGNRHMS'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->IGNRHMS);
				$table->endRow($rowClass);
		//DRVCDE name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'DRVCDE'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->DRVCDE);
				$table->endRow($rowClass);
		//INI name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'INI'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->INI);
				$table->endRow($rowClass);
		//LNKNUM name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'LNKNUM'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->LNKNUM);
				$table->endRow($rowClass);
		//VDATE name
				$table->startRow($rowClass);
				$table->addCell('<p>'.'VDATE'.'</p>');
				$table->addCell(' ');
				$table->addCell($stdinfo[0]->VDATE);
				$table->endRow($rowClass);


		

	

}



$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();
		
$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
//echo $this->rightNav->addToLayer();
$cssLayout2->setRightColumnContent($right);
$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$new= new button('addenquiry');
$new->setToSubmit();
$new->setValue('New Enquiry');
/*
$table->startRow();
$table->addCell($ok->show());
$table->addCell($new->show());
$table->endRow();
*/
$content = "<center><b>".$details.'</b>'." ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'enquiry','id'=>$idnumber)));
$objForm->setDisplayType(2);



$objForm->addToForm($content);

//$objForm->addToForm($ok);


$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
$cssLayout2->setMiddleColumnContent($objForm->show());
//$this->contentNav->height="300px";
//echo $this->contentNav->addToLayer();
$home = new link($this->uri(array('action'=>' ')));
$home->link = 'Home';
$cssLayout2->setLeftColumnContent($home->show());


echo $cssLayout2->show();

?>
