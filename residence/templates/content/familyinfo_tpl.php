<?
//require_once('columns_tpl.php');

$right =& $this->getObject('blocksearchbox','residence');
$this->objUser =& $this->getObject('user','security');
$right = $right->show($this->getParam('module'));

//$left =& $this->getObject('blockleftcolumn');
//$left = $left->show(); 

$left =& $this->getObject('leftblock');
$left = $left->show();

$this->financialaid =& $this->getObject('dbfinancialaid');

//echo "$numrecords --- $allrecords";
$content = "";
$oddEven = 'odd';

if(isset($stdinfo)){
	$table =& $this->getObject('sorttable','htmlelements');
	
	$table->width = '100%';
	$table->cellpadding = 5;
	$table->cellspacing = 2;
	
	$table->startHeaderRow();
	$table->addHeaderCell('name');		
	$table->addHeaderCell('surname');
	$table->addHeaderCell(' ');
	$table->addHeaderCell(' ');
	$table->addHeaderCell(' ');
	$table->addHeaderCell(' ');
	$table->endHeaderRow();


	if(is_array($stdinfo)){
		foreach($stdinfo as $data){
			$table->row_attributes = " class = \"$oddEven\"";
			
				
			$link = new link();
			
			$link->href=$this->uri(array('action'=>'info','id'=>$data['personID']));
			$link->link = $data['name'];

			$results = new link();
			$results->href=$this->uri(array('action'=>'results','id'=>$data['personID']));		
			$results->link = "Course Information";

			$payment = new link();
			$payment->href = $this->uri(array('action'=>'payment','id'=>$data['personID']));
			$payment->link = "Payment Information";

			$address = $this->financialaid->studentAddress($data['personID'],35);
			$title = $this->financialaid->getLookupInfo($data['titleID']);
			$student = $this->financialaid->getStudentNumber($data['personID']);

			$corre = new link();
			$corre->href=$this->uri(array('moduleTo'=>'financialaid','action'=>'new', 'moduleAction'=>'ok', 'userToName'=>$data['name'], 'userToTitle'=> $title[0]['value'], 'studentNo'=>$student[0]['studentNumber'],'fromUserId'=>$this->objUser->userId(),'detail'=>$address[0]['email'], 'type'=>'email'),'correspondence');
			
			//$corre->href="index.php?module=correspondence&action=new&extToSubject=testing&extType=email&extUserToTitle=toSimangMang";
			$corre->link = "Correspondence";

			$enquiry = new link();
			$enquiry->href=$this->uri(array('action'=>'enquiry','id'=>$data['personID']));
			$enquiry->link='Enquiry';

			$table->startRow();
			$table->addCell($link->show());
			$table->addCell($data['surname']);
			$table->addCell($results->show());
			$table->addCell($payment->show());
			$table->addCell($corre->show());
			$table->addCell($enquiry->show());

			$table->endRow();
			
			$oddEven = $oddEven == 'odd'?'even':'odd';

		}

	}
$content = $table->show();
}


$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();
		
$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();
		
$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $content;
$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();

		
/*$this->footerNav = $this->getObject('layer','htmlelements');
$this->footerNav->id = "footer";
$this->footerNav->str = $bottom;
//echo $this->footerNav->addToLayer();
*/		


	
?>
