<?
// Create an instance of the css layout class
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(3);


$right =& $this->getObject('resblocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module'));


$left =& $this->getObject('leftblock');
$left = $left->show($id);

$cssLayout2->setRightColumnContent('<p>'.$right.'</p>');

$header = & $this->newObject('htmlheading','htmlelements');
$table =& $this->newObject('htmltable','htmlelements');
$table->width='99%';
$table->border='0';
$table->cellspacing='0';
//$table->cellpadding='10';
$applicationYear = 2006;



		$table1 = & $this->newObject('htmltable','htmlelements');
        $table1->width = '75%';
		
		$this->financialaid =& $this->getObject('dbresidence','residence');
		if(!$start==null){
		$resppl = $this->financialaid->getlimitACCOM('YEAR',2006,$start,$end);
		}else{
		$resppl = $this->financialaid->getlimitACCOM('YEAR',2006,0,6);
		}
		
		$stdinfo = $this->financialaid->getResidence('YEAR',2006);
	
/*
GENDER	Gender
PHNCDE	Phone Area Code
PHNNUM	Phone Number
RESBLKNUM	Res. Block No.
RESCDE	Residence Code
RESFLOORNO	Res. Floor No.
RESRMBDNUM	Res. Room Bed No
RESRMNUM	Res. Room No
RESRMTYP	Res. Room Type
RESSTS	Residence Status
STDNUM	STDNUM
YEAR	Year

*/

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

	$form=&$this->newObject('form','htmlelements');
	$form = new form('relist',$this->uri(array('action'=>'nextreslist','id'=>$id)));
	
    //drop down ----|
    $dropdown =& $this->newObject("dropdown", "htmlelements");
    $dropdown->name = 'nextreslist';
	$end = floor(count($stdinfo)/6)+1;
    for ($i = 1; $i <=$end; $i++){
    $dropdown->addOption($i,$i);
    }

//$form->addToForm();

   $form->addToForm('<center>'.'Page '.$dropdown->show().' of '.'<b>'.$end.'</b>'.' '.$button->show().'</center>');

/*
$appTabBox = & $this->newObject('tabbox','financialaid');
$appTabBox->tabName = 'Studentinfo';
$appTabBox->addTab('Student Info', 'Student Info', $Display='nuttin');
echo $appTabBox->show();
*/

		foreach($resppl as $app){
		/*
		$table1->startHeaderRow();
		$table1->addHeaderCell('Student Number');
		$table1->addHeaderCell('');
		$table1->addHeaderCell('Gender Residence');
		$table1->addHeaderCell('Phone Area Code');
		$table1->addHeaderCell('Phone Number');
		$table1->addHeaderCell('Res. Block No.');
		$table1->addHeaderCell('Residence Code');
		$table1->addHeaderCell('Res. Floor No.');
		$table1->addHeaderCell('Res. Room Bed No');
		$table1->addHeaderCell('Res. Room Type');
		$table1->addHeaderCell('Residence Room Number');
		$table1->addHeaderCell('Residence Status');
		$table1->addHeaderCell('');
		$table1->endHeaderRow();
*/

	
	

		//$table1->startRow();
		$table1->addRow(array('---- ' ,'----- '),'even');
		$table1->addRow(array('Student Number',$app->STDNUM),'even');
		if($app->GENDER=='M'){
		$table1->addRow(array('Gender Residence','Male'),'even');
		}else{
		$table1->addRow(array('Gender Residence','Female'),'even');
		}
		$table1->addRow(array('Phone Area Code',$app->PHNCDE),'even');
		$table1->addRow(array('Phone Number',$app->PHNNUM),'even');
		$table1->addRow(array('Res. Block No.',$app->RESBLKNUM),'even');
		$table1->addRow(array('Residence Code',$app->RESCDE),'even');
		$table1->addRow(array('Res. Floor No.',$app->RESFLOORNO),'even');
		$table1->addRow(array('Res. Room Bed No',$app->RESRMBDNUM),'even');
		$table1->addRow(array('Res. Room Type',$app->RESRMNUM),'even');
		$table1->addRow(array('Residence Room Number',$app->RESRMTYP),'even');
		$table1->addRow(array('Residence Status',$app->RESSTS),'even');
		//$table1->endRow();

		//$table1->addRow(array('----- ' ,'---- '),'even');
		}
	

$heading = '<h3>'.'All Residence'.'</h3>';
$cssLayout2->setMiddleColumnContent('<p>'.$heading.'</p>'.' '.$form->show().' '.$table1->show());

$cssLayout2->setLeftColumnContent($left);
echo $cssLayout2->show();
?>
