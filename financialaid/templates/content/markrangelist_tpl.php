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
   	$table->width = '100%';
  	$table->cellpadding = 5;
   	$table->cellspacing = 0;
    $table->startHeaderRow();
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'),null,'top','left','header');
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'),null,'top','left','header');
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_idnum','financialaid'),null,'top','left','header');
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'),null,'top','left','header');
	$table->addHeaderCell($objLanguage->languagetext('word_average'),null,'top','left','header');
	$table->endHeaderRow();
 
     $table->css_class = 'highlightrows';
     $endat = $startat + $dispCount;
     if ($endat > $appCount){
         $endat = $appCount;
     }
     
     $oddEven = 'odd';
     
     for ($i = $startat; $i < $endat; $i++){
		$table->row_attributes = " class = \"$oddEven\"";

     	$link = new link();

  		$viewdetails = new link();
  		$viewdetails->href=$this->uri(array('action'=>'applicationinfo','appid'=>$appinfo[$i]->id));

		$table->startRow();
  		$viewdetails->link = $selectedapps[$i]['FSTNAM'];
		$table->addCell($viewdetails->show(), null, 'top',null, 'widelink');
  		$viewdetails->link = $selectedapps[$i]['SURNAM'];
		$table->addCell($viewdetails->show(), null, 'top',null, 'widelink');
  		$viewdetails->link = $selectedapps[$i]['IDN'];
		$table->addCell($viewdetails->show(), null, 'top',null, 'widelink');
  		$viewdetails->link = $selectedapps[$i]['STDNUM'];
		$table->addCell($viewdetails->show(), null, 'top',null, 'widelink');
  		$viewdetails->link = $selectedapps[$i]['AVG'];
		$table->addCell($viewdetails->show(), null, 'top',null, 'widelink');
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
