<?
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(3);

$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show();

$details = "<p><b>Details of ".ucfirst($studentdetails[0]->FSTNAM)."  ".ucfirst($studentdetails[0]->SURNAM)."</b></p>";
$idnumber = $studentdetails[0]->STDNUM;
$table =& $this->newObject('htmltable','htmlelements');
//print_r($studentdetails);
$left =& $this->getObject('leftblock','residence');

//send link thru --> left block
$id = $idnumber;
$left = $left->show($id);

//$this->financialaid =& $this->getObject('dbfinancialaid');

//var_dump($student);

if(is_array($stdlookup)){
	for($i = 0;$i < count($stdlookup);$i++){
		$table->startRow();
		$table->addCell($stdlookup[$i]['lookupType'],'70%');
		$table->addCell($stdlookup[$i]['value']);		
		$table->endRow();
	}
}

if(is_array($student)){
	$std = $this->financialaid->getLookupInfo($student[0]['studyTypeID']);
	$table->startRow();
	$table->addCell('Study Type');
	$link = "";
	if($std[0]['value'] === "Part Time"){
		$link = new link();
		$link->href=$this->uri(array('action'=>'parttime','id'=>$this->getParam('id')));
		$link->link="Part Time";
		$table->addCell($link->show());
	}
	else{
		$table->addCell($std[0]['value']);
	}

	
	$table->endRow();
}

if(!is_null($studentaddress))
$values = $studentaddress;
$num = count($studentaddress);
$num =$num -1;

//if(is_array($studentaddress) and count($studentaddress) > 0){
	//var_dump($stdaddress);
	//foreach($studentaddress as $values){
		//print_r($values);
		//die;
		$table->startRow();
		$table->addCell('Street Address');
		$table->addCell($values[$num]->AD1);
		$table->endRow();

		$table->startRow();
		$table->addCell('Suburb');
		$table->addCell($values[$num]->AD2);
		$table->endRow();

		$table->startRow();
		$table->addCell('City');
		$table->addCell($values[$num]->town);
		$table->endRow();

		$table->startRow();
		$table->addCell('Telephone');
		$table->addCell($values[$num]->PHNNUM);
		$table->endRow();

		$table->startRow();
		$table->addCell('Cellphone');
		$table->addCell($values[$num]->CELLNUM);
		$table->endRow();
		
		$table->startRow();
		$table->addCell('email');
		$table->addCell($studentdetails[0]->STDNUM.'@uwc.ac.za');
		$table->endRow();
		
		$contactType = $this->financialaid->getLookupInfo($values['contactTypeID']);
			
		$table->startRow();
		$table->addCell('Address Type');
		//switch
		switch($values[$num]->ADRTYP)
		{
		case 'R':
			$table->addCell("<b>".'Regional'."</b>");
			break;
		case 'F':
			$table->addCell("<b>".'Farm'."</b>");
			break;
		case null:
			$table->addCell("<b>".'Unknown'."</b>");
			break;
		}
		$table->endRow();

	//}
//}
/*
else{
$table->startRow();
$table->addCell('Address Type');
}
*/
/*
$types = array('35','36','37');
//$contactType = $this->financialaid->getLookupInfo($values['contactTypeID']);
//$table->startRow();
//$table->addCell(' ');
$addresstype = $this->getParam('address');
$datype = "";
for($i = 0; $i < 3; $i++){
	if($types[$i] != $addresstype){
		$link = new link();
		$link->href=$this->uri(array('action'=>'info','id'=>$idnumber,'address'=>$types[$i]));
		
		$contactType = $this->financialaid->getLookupInfo($types[$i]);
		$link->link= $contactType[0]->value;
		$datype .="<br>".$link->show()."</br>"; 
	}
}
$table->addCell("<b>".$datype."<b>");
$table->endRow();
*/

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
//echo $this->leftNav->addToLayer();


$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
//echo $this->rightNav->addToLayer();


$leftSideColumn2 = $right;//$right

// Add Left column
$cssLayout2->setRightColumnContent($leftSideColumn2);
//Output the content to the page
//echo $cssLayout2->show();


$link = new link();
if($this->getParam('module') === "financialaid"){
	$link->href=$this->uri(array('action'=>'nextofkin','id'=>$idnumber));
	$link->link="Show Family Member(s)";
}
if($this->getParam('module') === "residence"){
	$link->href=$this->uri(array('action'=>'resapp','id'=>$idnumber));
	$link->link="Click here for Application(s) for Residence";	
}
$content = "<center>".$details." ".$table->show()." "."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'ok','id'=>$idnumber)));
$objForm->setDisplayType(2);

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$objForm->addToForm($content);
//$objForm->addToForm($ok);

$appTabBox = & $this->newObject('tabbox','financialaid');
$appTabBox->tabName = 'Studentinfo';

$studentNumber = $studentdetails[0]->STDNUM;



$tabletab =& $this->newObject('htmltable','htmlelements');
		//student number
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Student Id');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->STDNUM);
				$tabletab->endRow($rowClass);
		
	
		//ACALNG name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Academic Lang.');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->ACALNG);
				$tabletab->endRow($rowClass);
		//ATRNY name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Attorney flag');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->ATRNY);
				$tabletab->endRow($rowClass);
		
		//HMELNG name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Home Language');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->HMELNG);
				$tabletab->endRow($rowClass);

		//PRVREGYR name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Prev. reg. yr.');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->PRVREGYR);
				$tabletab->endRow($rowClass);
		//STDSTS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Student Status');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->STDSTS);
				$tabletab->endRow($rowClass);
		//STDACCBAL name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Student Acc. Bal');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->STDACCBAL);
				$tabletab->endRow($rowClass);
		//STDACCSTS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Stud Acc Status');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->STDACCSTS);
				$tabletab->endRow($rowClass);
		//MARSTS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Marital Status');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->MARSTS);
				$tabletab->endRow($rowClass);
		
		//DBTPCKDTE name
	/*			$tabletab->startRow($rowClass);
				$tabletab->addCell('DBTPCKDTE');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->DBTPCKDTE);
				$tabletab->endRow($rowClass);
		//DBTPCKSTS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('DBTPCKSTS');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->DBTPCKSTS);
				$tabletab->endRow($rowClass);
	*/
		//DRVCDE name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Drivers Code');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->DRVCDE);
				$tabletab->endRow($rowClass);
		//IGNRHMS name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('IGNRHMS');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->IGNRHMS);
				$tabletab->endRow($rowClass);
		
		//INI name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Initials');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->INI);
				$tabletab->endRow($rowClass);
	/*	//LNKNUM name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('Link number');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->LNKNUM);
				$tabletab->endRow($rowClass);
		//VDATE name
				$tabletab->startRow($rowClass);
				$tabletab->addCell('VDATE');
				$tabletab->addCell(' ');
				$tabletab->addCell($studentdetails[0]->VDATE);
				$tabletab->endRow($rowClass);
*/
//-----------------------------------------------end student info
$tabletaba =& $this->newObject('htmltable','htmlelements');
$payments 	= $this->financialaid->getAccountInformation('STDNUM',$studentNumber);
//$payments 	= $this->financialaid->getAccountInformationHistory('STDNUM',$idnumber);
$loan = $this->financialaid->getAccountInformationHistory('STDNUM',$studentNumber);

if($payments==null)
{
$payments 	= $this->financialaid->getAccountInformationHistory('STDNUM',$studentNumber);
}else{
$payments 	= $this->financialaid->getAccountInformation('STDNUM',$studentNumber);
}
if(is_array($payments)){
	$tabletaba->startHeaderRow();
	$tabletaba->addHeaderCell('Date Of Payment ');
	$tabletaba->addHeaderCell('Amount Paid  &nbsp;&nbsp;&nbsp;  ');
	$tabletaba->addHeaderCell('Paid By         ');
	$tabletaba->endHeaderRow();
	$total = 0;
	foreach($payments as $pay=>$info){
	
		$tabletaba->startRow();
		$tabletaba->addCell($info->DTEYMD);
		$tabletaba->addCell(number_format($info->AMT,2));
		$tabletaba->addCell($info->DOCSRC);
		$tabletaba->endRow();
		$total += $info->AMT;		
		
	}
	$tabletaba->startRow();
	$tabletaba->addCell("<b>Total Paid</b>");
	$tabletaba->addCell(number_format($total,2));
	$tabletaba->endRow();
}

$tabletaba->startRow();
$tabletaba->addCell(' ');
$tabletaba->endRow();

$tabletaba->startRow();
$tabletaba->addCell(" ");
$tabletaba->addCell('');
$tabletaba->endRow();
//----------------------------------------------------student payment 

$tabletabb =& $this->newObject('htmltable','htmlelements');
$results = $this->financialaid->getSTCRS($studentNumber);
 $marksarr= $this->financialaid->getMarks('STDNUM',$studentNumber);
$results= $marksarr ;
if(is_array($results)){
	$tabletabb->startRow();
	$tabletabb->addCell('Course Code');
	$tabletabb->addCell('Assessment Type');
	$tabletabb->addCell('Assessment Date');
	$tabletabb->addCell('Mark');
	$tabletabb->endRow();
	
	//$results = $stdinfo;
	$dev = count($results);
	
	foreach($results as $values=>$key){
	$ave +=  $key->FNLMRK;
	}
	
	foreach($results as $values=>$key){
				//print_r($key);die;
				$tabletabb->startRow();
				$tabletabb->addCell($key->SBJCDE);
				$assessmentType = $key->STYTYP;
				//$this->financialaid->getLookupInfo($mark['assessmentTypeID']);
				$tabletabb->addCell($key->STYTYP);
				$tabletabb->addCell($key->YEAR);
				$tabletabb->addCell($key->FNLMRK);
				$tabletabb->endRow();
				
				
				
				
				
	
		
		$tabletabb->startRow();
		$tabletabb->addCell('<b>'.$values->SBJCDE.'</b>');
		$ave = number_format(($ave/$dev),2);
		$tabletabb->addCell('<b>Average Mark'.'</b>');
		$tabletabb->addCell('  ');
		$tabletabb->addCell('<b>'.$ave.'</b>');
		$tabletabb->endRow();
		
		
	}

		$tabletabb->startRow();
		$tabletabb->addCell("&nbsp;");
		$tabletabb->endRow();
		$tabletabb->startRow();
		$tabletabb->addCell('<b>'.'Student Averege'.'</b>');
		$tabletabb->addCell('  ');
		//$stdave = number_format(($stdave/$j),2);
		//$table->addCell(' ');
		//$table->addCell($stdave);
		$tabletabb->endRow();
}else{}

		$field = 'STDNUM'; //'2219065';
		$tabletabbres =& $this->newObject('htmltable','htmlelements');

		$this->financialaid->getResidence($field,$studentNumber);
		$finapplication = $this->financialaid->getResidence($field,$studentNumber);
		$student =  $this->financialaid->getPersonInfo($field,$studentNumber);
		$rowClass ='odd';



if(!$finapplication==null){
	foreach($finapplication as $app){
				$tabletabbres->startRow($rowClass);
				$tabletabbres->addCell("<p>".'Phone Code'."</p>");
				$tabletabbres->addCell(' ');
				$tabletabbres->addCell("<p>".$app->PHNCDE."</p>");
				$tabletabbres->endRow($rowClass);
		
				$tabletabbres->startRow($rowClass);
				$tabletabbres->addCell("<p>".'Phone Number'."</p>");
				$tabletabbres->addCell(' ');
				$tabletabbres->addCell("<p>".$app->PHNNUM."</p>");
				$tabletabbres->endRow($rowClass);

				$tabletabbres->startRow($rowClass);
				$tabletabbres->addCell("<p>".'Residence Block Number'."</p>");
				$tabletabbres->addCell(' ');
				$tabletabbres->addCell("<p>".$app->RESBLKNUM."</p>");
				$tabletabbres->endRow($rowClass);

				$tabletabbres->startRow($rowClass);
				$tabletabbres->addCell("<p>".'Residence Floor Number'."</p>");
				$tabletabbres->addCell(' ');
				$tabletabbres->addCell("<p>".$app->RESFLOORNO."</p>");
				$tabletabbres->endRow($rowClass);

				$tabletabbres->startRow($rowClass);
				$tabletabbres->addCell("<p>".'Residence Block Room Number'."</p>");
				$tabletabbres->addCell(' ');
				$tabletabbres->addCell("<p>".$app->RESRMBDNUM."</p>");
				$tabletabbres->endRow($rowClass);
				$tabletabbres->startRow($rowClass);
				$tabletabbres->addCell("<p>".'Residence Room Number'."</p>");
				$tabletabbres->addCell(' ');
				$tabletabbres->addCell("<p>".$app->RESRMNUM."</p>");
				$tabletabbres->endRow($rowClass);
				$tabletabbres->startRow($rowClass);
				$tabletabbres->addCell("<p>".'Residence Room Type'."</p>");
				$tabletabbres->addCell(' ');
				$tabletabbres->addCell("<p>".$app->RESRMTYP."</p>");
				$tabletabbres->endRow($rowClass);
		
				$tabletabbres->startRow($rowClass);
				$tabletabbres->addCell("<p>".'Residence Year'."</p>");
				$tabletabbres->addCell(' ');
				$tabletabbres->addCell("<p>".$app->YEAR."</p>");
				$tabletabbres->endRow($rowClass);
		
	
		}
$appTabBox->addTab('Residence Info', 'Residence Info', $Display=$tabletabbres->show());
}else{}
				
$appTabBox->addTab('Student Info', 'Student Info', $Display=$tabletab->show());
$appTabBox->addTab('Payment Info', 'Payment Info', $Display=$tabletaba->show());

		$linkcrs = new link();
		$linkcrs->href=$this->uri(array('action'=>'results','id'=>$this->getParam('id')));
		$linkcrs->link="CourseInfo";



//=================>
$this->dbres =& $this->getObject('dbresidence','residence');
$stdinfo = $this->dbres->getPersonInfo('STDNUM',$this->getParam('id'));
$results = $this->dbres->getCourseInfo4($this->getParam('id'), $year);
/////////////////////////
$table8 =& $this->newObject('htmltable','htmlelements');
$table8->cellspacing = 2;
$table8->cellpadding = 2;

//echo "<br>results: ";    print_r($results);  echo "<br>";
$sa = 0;
$stdave = 0;

$this->tabbox1 =& $this->newObject('tabsbox','studentenquiry');
$this->tabbox1->tabName='Address';
$years = array();
$j = 1;
if(isset($results[0]->YEAR))
{

  $years[0] = $results[0]->YEAR;

}
for($i = 1; $i < count($results); $i++)
{
    if($results[$i]->YEAR != $results[$i-1]->YEAR)
    {
        $years[$j] = $results[$i]->YEAR;
        $j++;
    }
}
if($year == '2006')
          {
           // $stdave = 0;
           $table8->startRow();
            $table8->addCell(" ",null,null, "center");
            $table8->addCell(" ",null,null, "center");
			$table8->addCell('<b>No Marks Available for 2006</b>',null,null, "center");
			$table8->addCell(' ',null,null, "center");
			$table8->endRow();
            $tshow1 = $table8->show();
          }
 $years = array();
$j = 1;
if(isset($results[0]->YEAR))
{

  $years[0] = $results[0]->YEAR;


for($i = 1; $i < count($results); $i++)
{
    if($results[$i]->YEAR != $results[$i-1]->YEAR)
    {
        $years[$j] = $results[$i]->YEAR;
        $j++;
    }
}

//echo "<br>count: ". count($years). "<br>years:<pre>" ;  print_r($years);   echo "</pre>";

if(is_array($years)){

	$links = "";
 for($i = count($years)-1; $i >= 0; $i--){
        if ($year != $years[$i]){

    		$link = new link();
    		$link->href = $this->uri(array('action'=>'results','id'=>$stdnum,'year'=>$years[$i]));
     		$link->link = "<strong>" . $years[$i] . "</strong>";
            $link->style = "text-decoration:none";
    		$links .=$link->show()."  ";


        }else{
            $links .=$years[$i]."  ";
        }
	}
}



$stcrse = $this->dbres->getStudentCourse($results[0]->STDNUM);
 /*
for($j = 0; $j < count($stcrse); $j++)
{
   if($year == $stcrse[$j]->YEAR){

     $course = $this->dbres->getCourseDesc($stcrse[$j]->CRSCDE);
     $details .= "<center>Year: ".$year."<br />Description: " . htmlentities($course[0]->LNGDSC);
     $details .= "</center><p></p>";
   }
   }
      if(count($years) > 1)
         $details .= "<center>Years of Study: ";
      else
         $details .= "<center>Year of Study: ";
      $details .= $links . "</center><br />";
                 */

}
if(is_array($results)){
    $table2 = new htmltable('table2');
	$table2->startRow();
	$table2->addCell('<b>Subject Code</b>',null,null, "center");
    $table2->addCell('<b>Subject</b>',null,null, "center");
	$table2->addCell('<b>Assessment Type</b>',null,null, "center");
	$table2->addCell('<b>Assessment Date</b>',null,null, "center");
	$table2->addCell('<b>Mark</b>',null,null, "center");
	$table2->endRow();
    $table2->startRow();
    $table2->addCell('<hr />', null,null,null,null,"colspan='5'");
    $table2->endRow();
	//foreach($results as $values){

		//$marks = $this->dbres->getCourseResuts($results[0]->SBJCDE,$results[0]->YEAR);
		$ave = 0;
		//if(is_array($marks)){
			//foreach($marks as $mark){
      // echo "count: " . count($results);
      $details2 = "";
         /* for($j = 0; $j < count($stcrse); $j++)
              {
               if($year == $stcrse[$j]->YEAR){
                  $course = $this->dbres->getCourseDesc($stcrse[$j]->CRSCDE);
                  $details2 .= "<center>Year: ".$year."<br />Description: " . htmlentities($course[0]->LNGDSC);
                  $details2 .= "</center><p></p>";
               }
              }
      if(count($years) > 1)
         $details2 .= "<center>Years of Study: ";
      else
         $details2 .= "<center>Year of Study: ";
      $details2 .= $links . "</center><br />";   */
       for($i = count($years)-1; $i >= 0; $i--)
         {
          //   if($results[$i]->YEAR == $year)
          //   {
                 for($j = 0; $j < count($stcrse); $j++)
              {
               if($years[$i] == $stcrse[$j]->YEAR){
                  $course = $this->dbres->getCourseDesc($stcrse[$j]->CRSCDE);
                  $details2 = "<center><b>Year: ".$years[$i]."<br />Description: " . htmlentities($course[0]->LNGDSC);
                  $details2 .= "</b></center><p></p>";
               }
              }
                $table2->startRow();
		        $table2->addCell($results[$i]->SBJCDE,null,null, "center");
                $subject = $this->dbres->getSubject($results[$i]->SBJCDE);
                $table2->addCell(htmlspecialchars($subject[0]->SBJDSC));
                $table2->addCell("Final Mark",null,null, "center");
                $table2->addCell($results[$i]->YEAR,null,null, "center");
                $table2->addCell($results[$i]->FNLMRK,null,null, "center");
  		        $table2->endRow();

		      //$a++;
		        $sa++;
    		//$ave += $results[$a]->FNLMRK;
		        $stdave += $results[$i]->FNLMRK;
              if($sa != 0)
       {
        $table3 = new htmltable('table3');
        $table3->startRow();
		$table3->addCell("&nbsp;",null,null, "center");
		$table3->endRow();
		$table3->startRow();
		$table3->addCell('<b>Student Average</b>',null,null, "center");
		$table3->addCell('  ',null,null, "center");
		$stdave = number_format(($stdave/$sa),2);
		$table3->addCell(' ');
		$table3->addCell('<b>'.$stdave.'</b>',null,null, "center");
		$table3->endRow();
        $table3->startRow();
		$table3->addCell("&nbsp;",null,null, "center");
		$table3->endRow();
        $tshow3 = $table3->show();
       }
        $tshow = $table2->show();
        $this->tabbox1->addTab("$i", "$years[$i]", "$details2 $tshow $tshow3");

        }
}

//=================>
$tab1=$this->tabbox1->show();
$appTabBox->addTab('Course Info', 'Course Info', $tab1);


$cssLayout2->setLeftColumnContent($left);
$cssLayout2->setMiddleColumnContent($objForm->show().'<p>'.$appTabBox->show().'</p>');


echo $cssLayout2->show();



//echo $this->contentNav->addToLayer();

?>
