<?
//print_r($stdinfo);
//require_once('columns_tpl.php');

$this->objUser =& $this->getObject('user','security');

$tabpane =& $this->newObject('tabpane', 'htmlelements');
// Create an instance of the css layout class
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(3);

// Add Right Column
//$rightSideColumn2= 'right';
//$cssLayout2->setMiddleColumnContent($rightSideColumn2);

$left =& $this->getObject('leftblock','residence');
//$left = $left->show();

//$left =& $this->getObject('blockleftcolumn');
//$left = $left->show(); 

$this->loadClass('form','htmlelements');
$this->financialaid =& $this->getObject('dbresidence','residence');
$dropdown =& $this->newObject("dropdown", "htmlelements");

	
echo '<center>'.'<h3>'.'Residence and Catering Service'.'</h3>'.'</center>';
//echo "$numrecords --- $allrecords";
$content = "";
$oddEven = 'odd';

if(isset($stdinfo)){
	
	
	$objHeading = &$this->getObject('htmlheading','htmlelements');
	$pgTitle = $objHeading;
	$pgTitle->type = 3;
	$nbsp = "&nbsp;";
	$pgTitle->str ='<center>'."Search Results for".'&nbsp;'.ucfirst($stdinfo[0]->SURNAM).'</center>';
	
	
	
	$table =& $this->getObject('htmltable','htmlelements');
	
	$table->width = '99%';
	$table->cellpadding = 3;
	$table->cellspacing = 3;
	
	//echo $pgTitle->show();
//	$table->startHeaderRow();
	//$table->addHeaderCell($pgTitle->show());		
//	$table->endHeaderRow();
	
	$table->startHeaderRow();
	$table->addHeaderCell('Name');		
	$table->addHeaderCell('Surname');
	$table->addHeaderCell('Student ID');
	$table->addHeaderCell('ID Number');
	$table->addHeaderCell(' ');
	//$table->addHeaderCell(' ');
	$table->endHeaderRow();


	if(is_array($stdinfo)){
		foreach($stdinfo as $data =>$key){
			$table->row_attributes = " class = \"$oddEven\"";
			//print_r($key);
				
			$link = new link();
			
			$link->href=$this->uri(array('action'=>'info','id'=>$key->STDNUM));

			$link->link = $key->FSTNAM;

			$results = new link();
			$results->href=$this->uri(array('action'=>'info','id'=>$key->STDNUM));		
			$results->link = $key->STDNUM;

			$payment = new link();
			$payment->href = $this->uri(array('action'=>'info','id'=>$key->STDNUM));
			$payment->link = $key->IDN;

			$address = $this->financialaid->studentAddress($key->STDNUM,35);
			$title = $this->financialaid->getLookupInfo($key->titleID);
			$student = $key->STDNUM;

			$corre = new link();
			$corre->href=$this->uri(
			array(
			'moduleTo'=>$this->getParam('module'),
			'action'=>'new', 
			'moduleAction'=>'ok', 
			'userToName'=>$key->FSTNAM, 
			'userToTitle'=> $key->TTL, 
			'studentNo'=>$key->STDNUM,
			'fromUserId'=>$this->objUser->userId(),
			'detail'=>$address[0]->email,
			'type'=>'email'),
			'correspondence');
		
			//$corre->href="index.php?module=correspondence&action=new&extToSubject=testing&extType=email&extUserToTitle=toSimangMang";
			//$corre->link = "Correspondence";
			$enquiry = new link($this->uri(array('action'=>'info','id'=>$key->STDNUM)));
			//$enquiry->href=$this->uri(array('action'=>'enquiry','id'=>$key->STDNUM));
			//$objLink = new link($this->uri(null,'contextadmin'));
			//$objLink->link='contextadmin';
			$enquiry->link=$key->SURNAM;
			$table->startRow();
			$table->addCell($link->show());
			$table->addCell($enquiry->show());
			$surname = $key->SURNAM;
		
			$table->addCell($results->show());
			$table->addCell($payment->show());
			//$table->addCell($corre->show());
			//$table->addCell($enquiry->show());
			$table->endRow();
			$oddEven = $oddEven == 'odd'?'even':'odd';

		}
		
	
	}

	$form = new form('nextlist',$this->uri(array('action'=>'nextlist','id'=>$surname)));
	
   	$surname = $this->getParam('surname');
   
		 //---------------------------------Save button------------------------------
   	$this->loadClass('button','htmlelements');
   	$button = & $this->newObject('button','htmlelements');
   	$button1 = & $this->newObject('button','htmlelements');
   
   	$button = new button("submit",
	'Go');    //word_save
	$button->setToSubmit();
	$button1 = new button("cancel",
	$objLanguage->code2Txt("word_cancel")); 
	$button1->setToSubmit();
	$row = array($button->show()."&nbsp;".$button1->show());
	//$form->addToForm($button->show());
	//---------------------------------------------------------------------------------
	
	//drop down ----|
	$dropdown =& $this->newObject("dropdown", "htmlelements");
	$dropdown->name = 'nextlist';
	for ($i = 1; $i <= count($stdinfo); $i++){
	$dropdown->addOption($i,$i);
	}
	$form->addToForm('<center>'.'Page '.$dropdown->show().' of '.'<b>'.count($stdinfo).'</b>'.' '.$button->show().'</center>');
	
	

	
$tablelist =& $this->getObject('htmltable','htmlelements');

	$searchresults =$form->show();

$content = '<p>'.$pgTitle->show().'</p>'.$searchresults.'<p>'.$table->show().'&nbsp;&nbsp;'.'</p>';
	


}

//--------Div 
/*		$this->id = null;
        $this->name = null;
        $this->cssClass = null;
        $this->width = null;
        $this->height = null;
        $this->position = null;
        $this->zIndex = null;;
        $this->position = null;
        $this->left = null;
		$this->top = null;
        $this->background_color = null;
        $this->visibility = null;
        $this->background_image = null;
        $this->border = null;
        $this->overflow = null;
        $this->align = null;
        $this->clear = null;
*/
//--------End Div
$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show();//$this->getParam('module'));

$left =& $this->getObject('leftblock','residence');
$left = $left->show($id=null);



$this->leftNav = $this->newObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
$this->leftNav->position = 'bottom';
//$this->leftNav->height =0;
//echo $this->leftNav->addToLayer();
//echo $this->leftNav->show();
		
$this->rightNav = $this->newObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
$leftSideColumn2 = $right;
//echo $this->rightNav->show();
		
$this->contentNav = $this->newObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $content;



/*
$appTabBox = & $this->newObject('tabbox','financialaid');
$appTabBox->tabName = ;
$appTabBox->tabName = '';
$appTabBox->tabName = '';

$appTabBox->addTab('courseDetails', '', $courseDisplay='ioo');
*/



/*
$content .= '<p>';
$content .= $appTabBox->show();
$content .= '</p>';
*/


$cssLayout2->setMiddleColumnContent($content);
$this->contentNav->height="70px";
//echo $this->contentNav->show();

$cssLayout2->setRightColumnContent($leftSideColumn2);
//$leftSideColumn2 = $right."<p>$left</p>";
// Add Left column

$left =& $this->getObject('leftblock','residence');
$cssLayout2->setLeftColumnContent($left->show($id=null));
//Output the content to the page



//echo $this->contentNav->addToLayer();




//echo $objElement->show();

echo $cssLayout2->show();


//$form = new form('rating',$this->uri(array('action'=>'ratingconfirm','id'=>$id)));
	
	
		
/*$this->footerNav = $this->getObject('layer','htmlelements');
$this->footerNav->id = "footer";
$this->footerNav->str = $bottom;
//echo $this->footerNav->addToLayer();
*/		


	
?>
