<?php
$this->objLanguage = &$this->getObject('language','language');
$this->objDBApplication =& $this->getObject('dbapplication');
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');
$this->objFinancialAidCustomWS = & $this->getObject('financialaidcustomws');
$this->objUser =& $this->getObject('user','security');

$centersearch =& $this->getObject('blockcentersearchappbox');
$centersearch = $centersearch->show($this->getParam('module','studentenquiry'));

$content = "";
$oddEven = 'odd';
$foundStudents = false;

$stdnum = $this->getParam('studentNumber', '');
$surname = strtoupper($this->getParam('surname', ''));
$idnumber = $this->getParam('idNumber', '');
$all = $this->getParam('all', '');

$startat = $this->getParam('startat', 0);
$dispCount = $this->getParam('dispcount', 25);

$wherefield = '';
$wherevalue = '';
if (strlen($stdnum) > 0) {
    $wherefield = "studentNumber";
    $wherevalue = $stdnum;
}elseif (strlen($surname) > 0){
    $wherefield = "surname";
    $wherevalue = $surname;
}elseif (strlen($idnumber) > 0){
    $wherefield = "idNumber";
    $wherevalue = $idnumber;
}

if (strlen($all) > 0){
    $details = "<h2>".$objLanguage->languagetext('mod_financialaid_allapplications','financialaid')."</h2>";
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

    $Rectbl =& $this->getObject('htmlTable','htmlelements');
/*    if($endl == $dispCount)  {
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
    } */
    $Rectbl->startRow();
    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_resfnd','financialaid').":</b>", "20%");
    $Rectbl->addCell("$appCount");
    $Rectbl->endRow();
    $records = $Rectbl->show();

    $showlinks->str = "$viewp $links_code $viewn";
    $showlinks->align="center";
    
    if($appCount < $dispCount){
        $pagelinks = "";
    }else{
        $pagelinks = $records.$showlinks->show();
    }
    //***end of pagination***
}else{
    $pagelinks = "";
    $records = "";
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
	$table->addHeaderCell($objLanguage->languagetext('word_year'));
	$table->addHeaderCell($objLanguage->languagetext('word_semester'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_idnum','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_details','financialaid'));
	$table->endHeaderRow();

	if(is_array($stdinfo)){
         for($i = 0; $i < count($stdinfo); $i++)
         {
			$table->row_attributes = " class = \"$oddEven\"";

			$viewdetails = new link();
			$viewdetails->href=$this->uri(array('action'=>'applicationinfo','appid'=>$stdinfo[$i]->id));
			$viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');

			$table->startRow();
			$table->addCell($stdinfo[$i]->year);
			$table->addCell($stdinfo[$i]->semester);
			$table->addCell($stdinfo[$i]->firstNames);
			$table->addCell($stdinfo[$i]->surname);
			$table->addCell($stdinfo[$i]->idNumber);
			$table->addCell($stdinfo[$i]->studentNumber);
			$table->addCell($viewdetails->show());
			$table->endRow();

			$oddEven = $oddEven == 'odd'?'even':'odd';
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

$content = "<center>".$details.$pagelinks." ".$content . "</center>";

echo $content;
?>
