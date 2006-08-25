<?

$right =& $this->getObject('blocksearchbox');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$stname = $stdinfo[0]->FSTNAM;
$stsname = $stdinfo[0]->SURNAM;

$details = "<p><b>Course Details of ".$stname."  ".$stsname."</p>";
$idnumber = $stdinfo[0]->IDN;
$stdnum = $stdinfo[0]->STDNUM;

//$details = "<p><b>Course Details of ".ucfirst($results->firstname)."  ".ucfirst($results->surname)."  for $resultsYear </b></p>";
//$idnumber = $results->idno;
$this->studentinfo =& $this->getObject('dbstudentinfo');

$year = $this->getParam('year');
if(is_null($year)){
  $year = date('Y');
}
  
/////////////////////////
$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

//echo "<br>results: ";    print_r($results);  echo "<br>";
$sa = 0;
$stdave = 0;
if(is_array($results)){
	$table->startRow();
	$table->addCell('Subject Code');
	$table->addCell('Assessment Type');
	$table->addCell('Assessment Date');
	$table->addCell('Mark');
	$table->endRow();
	//foreach($results as $values){
		$table->startRow();
		//$marks = $this->studentinfo->getCourseResuts($results[0]->SBJCDE,$results[0]->YEAR);
		$ave = 0;
		//if(is_array($marks)){
			//foreach($marks as $mark){
      // echo "count: " . count($results);
       for($i = 0; $i < count($results); $i++)
         {
             if($results[$i]->YEAR == $year)
             {
		$table->addCell($results[$i]->SBJCDE);
                $table->addCell("Final Mark");
		$table->addCell($results[$i]->YEAR);
		$table->addCell($results[$i]->FNLMRK);
		$table->endRow();
		//$a++;
		$sa++;
		//$ave += $results[$a]->FNLMRK;
		$stdave += $results[$i]->FNLMRK;
             }
			//$table->addCell($values->courseid);
        }
	/*	$table->startRow();
		$table->addCell('<b>'.$values->courseid);
		$ave = number_format(($ave/$a),2);
		$table->addCell('<b>Average Mark');
		$table->addCell('  ');
		$table->addCell("<b>$ave");
		$table->endRow();    */
}
          if($sa == 0)
          {
            $sa = 1;
           // $stdave = 0;
            $table->addCell("Final Mark");
			$table->addCell('No Marks ');
			$table->addCell('Available');
			$table->endRow();
          }

		$table->startRow();
		$table->addCell("&nbsp;");
		$table->endRow();
		$table->startRow();
		$table->addCell('<b>Student Average');
		$table->addCell('  ');
		$stdave = number_format(($stdave/$sa),2);
		$table->addCell(' ');
		$table->addCell($stdave);
		$table->endRow();


$stcrse = $this->studentinfo->getStudentCourse($results[0]->STDNUM);
for($j = 0; $j < count($stcrse); $j++)
{
   if($year == $stcrse[$j]->YEAR){
     $course = $this->studentinfo->getCourseDesc($stcrse[$j]->CRSCDE);
     $details .= "Description: " . $course[0]->LNGDSC;
     $details .= "<br>Year: ".$year."<p>";
   }
}
$years = array();
$j = 1;
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
	$table->startRow();
	$table->addCell('Other years of Study ');
	$links = "";
 for($i = 0; $i < count($years); $i++){
		$link = new link();
		$link->href = $this->uri(array('action'=>'results','id'=>$stdnum,'year'=>$years[$i]));
		$link->link = $years[$i];
		$links .=$link->show()."  ";
	}

	$table->addCell($links,$width=null, $valign="top", $align=null, $class=null, "colspan=2");
	$table->endRow();		

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
echo $this->rightNav->addToLayer();
*/
$content = "<center>".$details." ".$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'ok','id'=>$stdnum)));
$objForm->setDisplayType(2);

/*
$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');
*/

$objForm->addToForm($content);
//$objForm->addToForm($ok);

/*
$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show().'<br><br><br><br>';
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();
*/
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();


?>
