<?php
$this->appendArrayVar('headerParams','<link rel="stylesheet" type="text/css" href="modules/financialaid/resources/finaid.css" />');



$this->objLanguage = &$this->getObject('language','language');
$this->objDBApplication =& $this->getObject('dbapplication');
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');
$this->objFinancialAidCustomWS = & $this->getObject('financialaidcustomws');
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');
$this->objUser =& $this->getObject('user','security');
$this->objDbStudentInfo =& $this->getObject('dbstudentinfo','studentenquiry');

$content = "";
$oddEven = 'odd';
$foundStudents = false;

$wherefield = $this->getParam('searchfield', '');
$wherevalue = strtoupper($this->getParam('searchvalue', ''));

$all = $this->getParam('all', '');

$startat = $this->getParam('startat', 0);
$dispCount = $this->getParam('dispcount', 25);

$semester = array(
             '1',$objLanguage->languagetext('word_first'),
             '2',$objLanguage->languagetext('word_second'));

if (strlen($all) > 0){
    $details = "<center><h2>".$objLanguage->languagetext('mod_financialaid_allapplications','financialaid')."</h2></center>";
}elseif (strlen($wherefield) > 0){
    $rep = array(
      'SEARCHVALUE' => $wherevalue);

    $details = "<h2>".$objLanguage->code2Txt('mod_financialaid_searchresults','financialaid',$rep)."</h2>";
}else{
    $details = "<h2>".$objLanguage->languagetext('mod_financialaid_searchapp','financialaid')."</h2>";
}

if ($wherefield == ''){
    $appCount = $this->objDBFinancialAidWS->getApplicationCount();
}else{
    $appCount = $this->objDBFinancialAidWS->getAppCount($wherefield, $wherevalue);
}

if ($appCount > 0){

    //***start of pages***
    $links_code = "";

    $pageCount = $appCount/$dispCount;

    $showlinks =& $this->getObject('htmlHeading','htmlelements');
    if ($pageCount != floor($pageCount)) {
       $pageCount = strtok(($pageCount+1), ".");
    }

    $viewpages = new link();
    $page = $this->getParam('pg', '1');
    for ($n=0; $n < $pageCount; $n++) {
        $appCountR = ($n * $dispCount);
        $num = $n + 1;
        if ($num != $page){
            if (strlen($all) > 0){
                $viewpages->href=$this->uri(array('action'=>'searchapplications','startat'=>$appCountR,'pg'=>$num, 'all'=>$all, 'dispcount'=>$dispCount));
            }else{
                $viewpages->href=$this->uri(array('action'=>'searchapplications','startat'=>$appCountR,'pg'=>$num, 'surname'=>$surname,  'idNumber'=>$idnumber,  'studentNumber'=>$stdnum, 'dispcount'=>$dispCount));
            }
            $viewpages->link = "$num";
            $links_code .= $viewpages->show();
        }else{
            $links_code .= "$num";
        }
        if ($num == $pageCount){
         //   $links_code .= " ";
        }else if($n < $pageCount){
            $links_code .= " | ";
        }
    }
    $endl = $startat + $dispCount;

    $viewp ="";
    $viewn ="";

    if ($startat > 1){
        $appCountR = $startat - $dispCount;
        $pg = $page - 1;
        $viewprev = new link();
        if (strlen($all) > 0){
            $viewprev->href=$this->uri(array('action'=>'searchapplications','startat'=>$appCountR,'pg'=>$pg, 'all'=>$all, 'dispcount'=>$dispCount));
        }else{
            $viewprev->href=$this->uri(array('action'=>'searchapplications','startat'=>$appCountR,'pg'=>$pg,  'surname'=>$surname,  'idNumber'=>$idnumber,  'studentNumber'=>$stdnum, 'dispcount'=>$dispCount));
        }
        $viewprev->link = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
        $viewp = $viewprev->show()." |";
    }
    $vntest = $appCount - $dispCount;

    if ($startat < $vntest){
        $appCountR = $startat + $dispCount;
        $pg = $page + 1;

        $viewnext = new link();
        if (strlen($all) > 0){
            $viewnext->href=$this->uri(array('action'=>'searchapplications','startat'=>$appCountR,'pg'=>$pg, 'all'=>$all, 'dispcount'=>$dispCount));
        }else{
            $viewnext->href=$this->uri(array('action'=>'searchapplications','startat'=>$appCountR,'pg'=>$pg,  'surname'=>$surname,  'idNumber'=>$idnumber,  'studentNumber'=>$stdnum, 'dispcount'=>$dispCount));
        }
        $viewnext->link = $objLanguage->languagetext('mod_financialaid_next','financialaid');
        $viewn = "| ".$viewnext->show();
    }

    $endat = $startat + $dispCount;
    if ($endat > $appCount){
        $endat = $appCount;
    }

    $rep = array(
      'RANGE' => ($startat+1).'-'.$endat,
      'TOTAL' => $appCount);

    $records = "<center>".$objLanguage->code2Txt('mod_financialaid_resultsfound','financialaid',$rep)."</center><br />";
    $showlinks->str = "$viewp $links_code $viewn";
    $showlinks->align="center";
    
    if($appCount <= $dispCount){
        $pagelinks = $records;
    }else{
        $pagelinks = $records.$showlinks->show();
    }
    //***end of pagination***
}else{
    $pagelinks = "";
}

if (strlen($all) > 0){
    $stdinfo = $this->objDBFinancialAidWS->getAllApplications($startat, $dispCount);
}else{
    if(strlen($wherefield) > 0){
        $stdinfo = $this->objDBFinancialAidWS->getApplication($wherevalue, $wherefield, $startat, $dispCount);
    }
}

if (isset($stdinfo)){
  if(count($stdinfo) > 0){

    $table =& $this->getObject('htmltable','htmlelements');

	$table->width = '100%';
	$table->cellpadding = 5;
	$table->cellspacing = 2;

	$table->startHeaderRow();
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'),null,'top','left','header');
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'),null,'top','left','header');
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'),null,'top','left','header');
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_idnum','financialaid'),null,'top','left','header');
	$table->addHeaderCell($objLanguage->languagetext('word_year'),null,'top','left','header');
	$table->addHeaderCell($objLanguage->languagetext('word_semester'),null,'top','left','header');
	$table->endHeaderRow();

	if(is_array($stdinfo)){
         for($i = 0; $i < count($stdinfo); $i++)
         {
            $studentinfo = $this->objDbStudentInfo->getPersonInfo($stdinfo[$i]->studentNumber);

            if(count($studentinfo) > 0){
    			$table->row_attributes = " class = \"$oddEven\"";
           //     $table->cellpadding = '10';
                $table->cellspacing = 0;
                
    			$viewdetails = new link();
    			$viewdetails->href=$this->uri(array('action'=>'applicationinfo','appid'=>$stdinfo[$i]->id));
               // $viewdetails->cssClass = 'tablelink';
                $table->css_class = 'highlightrows';
    			$table->startRow();
    			$viewdetails->link = $stdinfo[$i]->studentNumber;
    			$table->addCell("<span class='tablelink'>".$viewdetails->show()."</span>", null, 'top', null, 'widelink');
    			$viewdetails->link = $studentinfo[0]->SURNAM;
    			$table->addCell("<span class='tablelink'>".$viewdetails->show()."</span>", null, 'top', null, 'widelink');
    			$viewdetails->link = $studentinfo[0]->FSTNAM;
    			$table->addCell("<span class='tablelink'>".$viewdetails->show()."</span>", null, 'top', null, 'widelink');
    			$viewdetails->link = $studentinfo[0]->IDN;
    			$table->addCell("<span class='tablelink'>".$viewdetails->show()."</span>", null, 'top', null, 'widelink');
    			$viewdetails->link = $stdinfo[$i]->year;
    			$table->addCell("<span class='tablelink'>".$viewdetails->show()."</span>", null, 'top', null, 'widelink');
    			$viewdetails->link = $semester[$stdinfo[$i]->semester];
    			$table->addCell("<span class='tablelink'>".$viewdetails->show()."</span>", null, 'top', null, 'widelink');
    			$table->endRow();

    			$oddEven = $oddEven == 'odd'?'even':'odd';
            }
		}
        $foundStudents = TRUE;

        $content = $table->show();
	}
  }
}

if ($foundStudents == FALSE) {

	$content = '<div class="noRecordsMessage">'. $objLanguage->languagetext('mod_financialaid_noapplications','financialaid').'</div>';
    $pagelinks = '';
    $records = '';
}

$content = $details.$pagelinks.$content;

echo $content;
?>
