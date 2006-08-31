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
$this->objDbFinAid =& $this->getObject('dbfinaid');


$year = $this->getParam('year');
if(is_null($year)){
  $year = date('Y');
}

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

$sa = 0;
$stdave = 0;
if(is_array($results)){
	$table->startRow();
	$table->addCell($objLanguage->languagetext('mod_financialaid_subcode','financialaid'));
	$table->addCell($objLanguage->languagetext('word_subject'));
	$table->addCell($objLanguage->languagetext('mod_financialaid_assdate','financialaid'));
	$table->addCell($objLanguage->languagetext('mod_financialaid_mark','financialaid'));
	$table->endRow();
	$ave = 0;
    for($i = 0; $i < count($results); $i++){
        if($results[$i]->YEAR == $year){
       		$table->startRow();
       		$table->addCell($results[$i]->SBJCDE);
            $subject = $this->objDbFinAid->getSubject($results[$i]->SBJCDE);
            $table->addCell(htmlspecialchars($subject[0]->SBJDSC));
       		$table->addCell($results[$i]->YEAR);
       		$table->addCell($results[$i]->FNLMRK);
       		$table->endRow();
       		$sa++;
       		$stdave += $results[$i]->FNLMRK;
        }
    }
}else{
    $sa = 1;
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
for($j = 0; $j < count($stcrse); $j++){
    if($year == $stcrse[$j]->YEAR){
        $course = $this->objDbStudentInfo->getCourseDesc($stcrse[$j]->CRSCDE);
        $details .= "<p>".$objLanguage->languagetext('mod_financialaid_desc','financialaid').": " . $course[0]->LNGDSC;
        $details .= "<br />".$objLanguage->languagetext('mod_financialaid_year','financialaid').": ".$year."</p>";
    }
}
$years = array();
$j = 1;
$years[0] = $results[0]->YEAR;

for($i = 1; $i < count($results); $i++){
    if($results[$i]->YEAR != $results[$i-1]->YEAR){
        $years[$j] = $results[$i]->YEAR;
        $j++;
    }
}

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
    $table->addCell($links,$width=null, $valign="top", $align=null, $class=null);
    $table->endRow();
}

$content = "<center>".$details." ".$table->show()."</center>";

echo $content;
?>
