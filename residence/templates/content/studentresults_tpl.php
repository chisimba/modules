<?

$this->dbres =& $this->getObject('dbresidence','residence');
$stdinfo = $this->dbres->getPersonInfo('STDNUM',$this->getParam('id'));
$results = $this->dbres->getCourseInfo4($this->getParam('id'), $year);

$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$stname = $stdinfo[0]->FSTNAM;
$stsname = $stdinfo[0]->SURNAM;

$details = "<center><p><h3>Course Details of ".$stname."  ".$stsname."</h3></p></center>";
$idnumber = $stdinfo[0]->IDN;
$stdnum = $stdinfo[0]->STDNUM;

//$details = "<p><b>Course Details of ".ucfirst($results->firstname)."  ".ucfirst($results->surname)."  for $resultsYear </b></p>";
//$idnumber = $results->idno;
//$this->dbres =& $this->getObject('dbstudentinfo');

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
           $table->startRow();
            $table->addCell(" ",null,null, "center");
            $table->addCell(" ",null,null, "center");
			$table->addCell('<b>No Marks Available for 2006</b>',null,null, "center");
			$table->addCell(' ',null,null, "center");
			$table->endRow();
            $tshow1 = $table->show();
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
//$content = " "." ".$table->show()." ";
$tab1=$this->tabbox1->show();
$content .= $details. "<h3>Years of Study</h3>" . $tab1. "<br />";

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
