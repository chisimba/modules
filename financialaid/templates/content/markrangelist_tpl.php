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
      
$details = "<center><h2>".$objLanguage->code2Txt('mod_financialaid_markrangetitle','financialaid',$rep)."</h2></center>";

$appinfo = $this->objDBFinancialAidWS->getAllApplications();

$selectedapps = array();

if (count($appinfo) > 0)
{
    $j = 0;
    for ($i = 0; $i < count($appinfo); $i++){
        $avg = $this->objFinancialAidCustomWS->getAvgMark($appinfo[$i]->studentNumber, $year);
        if (($avg < $upperMark) && ($avg >= $lowerMark))
        {
            $stdinfo = $this->objStudentInfo->getPersonInfo($appinfo[$i]->studentNumber);
            if (count($stdinfo) > 0)
            {
                $selectedapps[$j]['FSTNAM'] = $stdinfo[0]->FSTNAM;
                $selectedapps[$j]['SURNAM'] = $stdinfo[0]->SURNAM;
                $selectedapps[$j]['IDN'] = $stdinfo[0]->IDN;
                $selectedapps[$j]['TTL'] = $stdinfo[0]->TTL;
                $selectedapps[$j]['STDNUM'] = $stdinfo[0]->STDNUM;
                $selectedapps[$j]['AVG'] = $avg;
                $j++;
            }
        }
    }
}


if (count($selectedapps) > 0)
{
    $startat = $this->getParam('startat', 0);
    $dispCount = $this->getParam('dispcount',25);
    //***start of pages***
    $appCount = count($selectedapps);
    $links_code = "";

    $pageCount = $appCount/$dispCount;

    $showlinks =& $this->getObject('htmlHeading','htmlelements');
    if ($pageCount != floor($pageCount)) {
       $pageCount = strtok(($pageCount+1), ".");
    }

    $viewpages = new link();
    for ($n=0; $n < $pageCount; $n++) {
        $appCountR = ($n * $dispCount);
        $num = $n + 1;
        $viewpages->href=$this->uri(array('action'=>'searchmarkrange','startat'=>$appCountR,'pg'=>$num, 'lowermark'=>$lowerMark, 'uppermark'=>$upperMark, 'year'=>$year));
        $viewpages->link = "$num";
        $links_code .= $viewpages->show();
        if ($num == $pageCount){
            $links_code .= " ";
        }else if($n < $pageCount){
            $links_code .= " | ";
        }
    }
    $endl = $startat + $dispCount;

    $viewp ="";
    $viewn ="";

    if ($startat > 1){
        $page = $this->getParam('pg');
        $page -= 1;
        $appCountR = $startat - $dispCount;

        $viewprev = new link();
        $viewprev->href=$this->uri(array('action'=>'searchmarkrange','startat'=>$appCountR,'pg'=>$page, 'lowermark'=>$lowerMark, 'uppermark'=>$upperMark, 'year'=>$year));
        $viewprev->link = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
        $viewp = $viewprev->show();
    }
    $vntest = $appCount - $dispCount;
    if ($startat <= $vntest){
        $page = $this->getParam('pg');
        $page += 1;
        $appCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'searchmarkrange','startat'=>$appCountR,'pg'=>$page, 'lowermark'=>$lowerMark, 'uppermark'=>$upperMark, 'year'=>$year));
        $viewnext->link = $objLanguage->languagetext('mod_financialaid_next','financialaid');
        $viewn = $viewnext->show();
    }
    /*
    $Rectbl =& $this->getObject('htmlTable','htmlelements');
    if($endl == $dispCount)  {
        $Rectbl->startRow();
        $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_page','financialaid').":</b>", "20%");
        $Rectbl->addCell("1");
        $Rectbl->endRow();

        $endl -= 1;

        $Rectbl->startRow();
        $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_record','financialaid').":</b>",  "20%");
        $Rectbl->addCell("0  to $endl");
        $Rectbl->endRow();
    }else{
        $page = $this->getParam('pg');
        $Rectbl->startRow();
        $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_page','financialaid').":</b>", "20%");
        $Rectbl->addCell("$page");
        $Rectbl->endRow();

        $endl -= 1;
        $Rectbl->startRow();
        $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_record','financialaid').":</b>", "20%");
        if($endl < $appCount){
            $Rectbl->addCell("$startat to $endl");
        }else {
            $Rectbl->addCell("$startat to $appCount");
        }
        $Rectbl->endRow();
    }
    $Rectbl->startRow();
    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_resfnd','financialaid').":</b>", "20%");
    $Rectbl->addCell("$appCount");
    $Rectbl->endRow();
    $records = $Rectbl->show();
    */
    $endat = $startat + $dispCount;
    if ($endat > $appCount){
        $endat = $appCount;
    }

    $rep = array(
      'RANGE' => ($startat+1).'-'.$endat,
      'TOTAL' => $appCount);

    $records = $objLanguage->code2Txt('mod_financialaid_resultsfound','financialaid',$rep);
    $showlinks->str = "$viewp $links_code $viewn";
    $showlinks->align="center";

    if($appCount <= $dispCount){
        $pagelinks = "";
    }else{
        $pagelinks = $records.$showlinks->show();
    }
    //***end of pagination***

    $table =& $this->newObject('htmltable','htmlelements');

    $table->startHeaderRow();
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_idnum','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('word_average'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_details','financialaid'));
	$table->endHeaderRow();
 

     $endat = $startat + $dispCount;
     if ($endat > $appCount){
         $endat = $appCount;
     }
     
     for ($i = $startat; $i < $endat; $i++){
		$table->row_attributes = " class = \"$oddEven\"";

     	$link = new link();

  		$viewdetails = new link();
  		$viewdetails->href=$this->uri(array('action'=>'applicationinfo','appid'=>$appinfo[$i]->id));
  		$viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');

		$table->startRow();
		$table->addCell($selectedapps[$i]['FSTNAM']);
		$table->addCell($selectedapps[$i]['SURNAM']);
		$table->addCell($selectedapps[$i]['IDN']);
		$table->addCell($selectedapps[$i]['STDNUM']);
		$table->addCell($selectedapps[$i]['AVG']);
		$table->addCell($viewdetails->show());
		$table->endRow();
		$oddEven = $oddEven == 'odd'?'even':'odd';
    }
    $content = $table->show();
}else{
	$content = '<div class="noRecordsMessage">'. $objLanguage->languagetext('mod_financialaid_noapplications','financialaid').'</div>';
}


$content = $details.$content;

echo $content;

?>
