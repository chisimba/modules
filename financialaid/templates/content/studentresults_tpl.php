<?

$stname = $stdinfo[0]->FSTNAM;
$stsname = $stdinfo[0]->SURNAM;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);

$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_resultstitle','financialaid',$rep)."</h2>";

$idnumber = $stdinfo[0]->IDN;
$stdnum = $stdinfo[0]->STDNUM;

$this->objDbStudentInfo =& $this->getObject('dbstudentinfo','studentenquiry');

$year = $this->getParam('year');
if(is_null($year)){
  $year = date('Y');
}

/////////////////////////
$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

//echo "<br />results: ";    print_r($results);  echo "<br />";
$sa = 0;
$stdave = 0;
if(is_array($results)){
	$table->startRow();
	$table->addCell($objLanguage->languagetext('mod_financialaid_subcode','financialaid'));
	$table->addCell($objLanguage->languagetext('mod_financialaid_asstype','financialaid'));
	$table->addCell($objLanguage->languagetext('mod_financialaid_assdate','financialaid'));
	$table->addCell($objLanguage->languagetext('mod_financialaid_mark','financialaid'));
	$table->endRow();
	//foreach($results as $values){
		//$marks = $this->objDbStudentInfo->getCourseResuts($results[0]->SBJCDE,$results[0]->YEAR);
		$ave = 0;
		//if(is_array($marks)){
			//foreach($marks as $mark){
      // echo "count: " . count($results);
       for($i = 0; $i < count($results); $i++)
         {
             if($results[$i]->YEAR == $year)
             {
        		$table->startRow();
        		$table->addCell($results[$i]->SBJCDE);
                $table->addCell($objLanguage->languagetext('mod_financialaid_finalmark','financialaid'));
        		$table->addCell($results[$i]->YEAR);
        		$table->addCell($results[$i]->FNLMRK);
        		$table->endRow();
        		// $a++;
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
            $table->startRow();
            $table->addCell($objLanguage->languagetext('mod_financialaid_finalmark','financialaid'));
			$table->addCell($objLanguage->languagetext('mod_financialaid_nomarks','financialaid'));
			$table->addCell($objLanguage->languagetext('mod_financialaid_available','financialaid'));
			$table->endRow();
          }

		$table->startRow();
		$table->addCell("&nbsp;");
		$table->endRow();
		$table->startRow();
		$table->addCell('<b>'.$objLanguage->languagetext('mod_financialaid_stdavg','financialaid').'</b>');
		$table->addCell('  ');
		$stdave = number_format(($stdave/$sa),2);
		$table->addCell(' ');
		$table->addCell($stdave);
		$table->endRow();


$stcrse = $this->objDbStudentInfo->getStudentCourse($results[0]->STDNUM);
for($j = 0; $j < count($stcrse); $j++)
{
   if($year == $stcrse[$j]->YEAR){
     $course = $this->objDbStudentInfo->getCourseDesc($stcrse[$j]->CRSCDE);
     $details .= "<p>".$objLanguage->languagetext('mod_financialaid_desc','financialaid').": " . $course[0]->LNGDSC;
     $details .= "<br />".$objLanguage->languagetext('mod_financialaid_year','financialaid').": ".$year."</p>";
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

//echo "<br />count: ". count($years). "<br />years:<pre>" ;  print_r($years);   echo "</pre>";

if(is_array($years)){
	$table->startRow();
	$table->addCell($objLanguage->languagetext('mod_financialaid_otheryrs','financialaid'));
	$links = "";
 for($i = 0; $i < count($years); $i++){
		$link = new link();
		$link->href = $this->uri(array('action'=>'results','id'=>$stdnum,'year'=>$years[$i]));
		$link->link = $years[$i];
		$links .=$link->show()."  ";
	}

//	$table->addCell($links,$width=null, $valign="top", $align=null, $class=null, "colspan=2");
	$table->addCell($links,$width=null, $valign="top", $align=null, $class=null);
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
$content = "<center>".$details." ".$table->show()."</center>";

echo $content;


?>
