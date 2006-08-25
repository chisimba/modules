<?
//require_once('columns_tpl.php');

$right =& $this->getObject('blocksearchbox');
$right = $right->show();

$left =& $this->getObject('blockleftcolumn');
$left = $left->show(); 
//echo "$numrecords --- $allrecords";
$content = "";
$oddEven = 'odd';

$entriesFnd = false;
if(is_array($entries)){
	$table =& $this->getObject('sorttable','htmlelements');
	
	$table->width = '100%';
	$table->cellpadding = 5;
	$table->cellspacing = 2;
	
	$table->startHeaderRow();
	$table->addHeaderCell('name');		
	$table->addHeaderCell('surname');
	//$table->addHeaderCell('Appl No');
	$table->addHeaderCell('Std No');
	$table->addHeaderCell('Status');
	$table->addHeaderCell(' ');
	$table->endHeaderRow();


	if(is_array($entries)){
		//foreach($entries as $data){
			$table->row_attributes = " class = \"$oddEven\"";
			
				
			$link = new link();
			$link->href= "index.php?module=studentenquiry&action=info&id=".$entries[0]->STDNUM;
			$link->link = $entries[0]->FSTNAM;

			$info = new link();
			$info->href= "index.php?module=studentenquiry&action=more_info&id=".$entries[0]->STDNUM;
			$info->link = "more info";

			$table->startRow();
			$table->addCell($link->show());
			$table->addCell($entries->SURNUM);
       //	$table->addCell($entries['APLNUM']);
			$table->addCell($entries->STDNUM);

		//	$table->addCell($entries[0]['allocated']);
			$table->addCell($info->show());
			$table->endRow();
			
			$oddEven = $oddEven == 'odd'?'even':'odd';
            $entriesFnd = true;
		//}
	
		

	} 
	
	$content = $table->show();

}

/*
$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
$this->rightNav->width="200px";

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->height="300px";
   */


/*
if ($entriesFnd == false) {
	
	$content = $right;


} else {



}

//echo $this->contentNav->addToLayer();

		
/*$this->footerNav = $this->getObject('layer','htmlelements');
$this->footerNav->id = "footer";
$this->footerNav->str = $bottom;
//echo $this->footerNav->addToLayer();
*/
//$content = $objForm->show();

//$cssLayout->setRightColumnContent($right);

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();
?>
