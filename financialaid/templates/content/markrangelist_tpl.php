<?
$this->objStudentInfo =& $this->getObject('dbstudentinfo','studentenquiry');
$this->objDBFinancialAidWS =& $this->getObject('dbfinancialaidws');
$this->objFinancialAidCustomWS =& $this->getObject('financialaidcustomws');

$lowerMark = $this->getParam('lowermark', NULL);
$upperMark = $this->getParam('uppermark', NULL);
$year = $this->getParam('resultyear', NULL);

$rep = array(
      'LOWERMARK' => $lowerMark . "%",
      'UPPERMARK' => $upperMark . "%",
      'YEAR' => $year);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_markrangetitle','financialaid',$rep)."</h2>";

$appinfo = $this->objDBFinancialAidWS->getAllApplications();
$table =& $this->newObject('htmltable','htmlelements');
if (count($appinfo) > 0)
{

    $table->startHeaderRow();
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_idnum','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('word_average'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_details','financialaid'));
	$table->endHeaderRow();
 
    for ($i = 0; $i < count($appinfo); $i++){

        $avg = $this->objFinancialAidCustomWS->getAvgMark($appinfo[$i]->studentNumber, $year);
        if (($avg < $upperMark) && ($avg >= $lowerMark))
        {
            $stdinfo = $this->objStudentInfo->getPersonInfo($appinfo[$i]->studentNumber);
            if (count($stdinfo) > 0)
            {
     			$table->row_attributes = " class = \"$oddEven\"";

		    	$link = new link();

    			$viewdetails = new link();
    			$viewdetails->href=$this->uri(array('action'=>'applicationinfo','appid'=>$appinfo[$i]->id));
    			$viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');

                $stname = $stdinfo[0]->FSTNAM;
                $stsname = $stdinfo[0]->SURNAM;
                $stid = $stdinfo[0]->IDN;
                $title = $stdinfo[0]->TTL;
                $stnum = $stdinfo[0]->STDNUM;

    			$table->startRow();
    			$table->addCell($stname);
    			$table->addCell($stsname);
    			$table->addCell($stid);
    			$table->addCell($stnum);
    			$table->addCell($avg);
    			$table->addCell($viewdetails->show());

    			$table->endRow();

    			$oddEven = $oddEven == 'odd'?'even':'odd';
            }
        }
    }
}


$content = "<center>".$details."  ".$table->show()."</center>";

echo $content;

?>
