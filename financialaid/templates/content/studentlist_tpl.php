<?php
$this->objLanguage = &$this->getObject('language','language');
$this->objUser =& $this->getObject('user','security');
$this->objFinancialAidCustomWS = & $this->getObject('financialaidcustomws');
$this->studentinfo =& $this->getObject('dbstudentinfo','studentenquiry');

$centersearch =& $this->getObject('blockcentersearchbox','studentenquiry');
$centersearch = $centersearch->show($this->getParam('module','studentenquiry'));

$content = "";
$oddEven = 'odd';
$foundStudents = false;



$stdnum = $this->getParam('studentNumber', '');
$surname = strtoupper($this->getParam('surname', ''));
$idnumber = $this->getParam('idNumber', '');

$wherefield = '';
if (strlen($stdnum) > 0) {
    $wherefield = "STDNUM";
    $wherevalue = $stdnum;
}elseif (strlen($surname) > 0){
    $wherefield = "SURNAM";
    $wherevalue = $surname;
}elseif (strlen($idnumber) > 0){
    $wherefield = "IDNUM";
    $wherevalue = $idnumber;
}

if (strlen($wherefield) > 0){
    $rep = array(
      'SEARCHVALUE' => $wherevalue);

    $details = "<h2>".$objLanguage->code2Txt('mod_financialaid_searchresults','financialaid',$rep)."</h2>";
}else{
    $details = "<h2>".$objLanguage->languagetext('mod_financialaid_search','financialaid')."</h2>";
}

if ($wherefield == ''){
    $stdCount = 0;
}else{
    $stdCount = $this->objFinancialAidCustomWS->getStudentCount($wherevalue, $wherefield);
}

$startat = $this->getParam('startat', 0);
$dispCount = $this->getParam('dispcount', 25);

if ($stdCount > 0){

    //***start of pages***
    $links_code = "";

    $pageCount = $stdCount/$dispCount;

    $showlinks =& $this->getObject('htmlHeading','htmlelements');
    if ($pageCount != floor($pageCount)) {
       $pageCount = strtok(($pageCount+1), ".");
    }
   $page = $this->getParam('pg', '1');


    $viewpages = new link();
    for ($n=0; $n < $pageCount; $n++) {
        $stdCountR = ($n * $dispCount);
        $num = $n + 1;
        if ($num != $page){
            $viewpages->href=$this->uri(array('action'=>'search','startat'=>$stdCountR,'pg'=>$num, 'surname'=>$surname,  'idNumber'=>$idnumber,  'studentNumber'=>$stdnum, 'dispcount'=>$dispCount));
            $viewpages->link = "$num";
            $links_code .= $viewpages->show();
        }else{
            $links_code .= "$num";
        }
        if ($num == $pageCount){
           // $links_code .= " ";
        }else if($n < $pageCount){
            $links_code .= " | ";
        }
    }
    $endl = $startat + $dispCount;

    $viewp ="";
    $viewn ="";

    if ($startat > 1){
        $page = $this->getParam('pg');
        $pg = $page - 1;
        $stdCountR = $startat - $dispCount;

        $viewprev = new link();
        $viewprev->href=$this->uri(array('action'=>'search','startat'=>$stdCountR,'pg'=>$pg,  'surname'=>$surname,  'idNumber'=>$idnumber,  'studentNumber'=>$stdnum, 'dispcount'=>$dispCount));
        $viewprev->link = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
        $viewp = $viewprev->show()." |";
    }
    $vntest = $stdCount - $dispCount;
    if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'search','startat'=>$stdCountR,'pg'=>$pg,  'surname'=>$surname,  'idNumber'=>$idnumber,  'studentNumber'=>$stdnum, 'dispcount'=>$dispCount));
        $viewnext->link = $objLanguage->languagetext('mod_financialaid_next','financialaid');
        $viewn = "| ".$viewnext->show();
    }

    $Rectbl =& $this->getObject('htmlTable','htmlelements');
 /*   if($endl == $dispCount)  {
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
        if($endl < $stdCount){
            $Rectbl->addCell("$startat to $endl");
        }else {
            $Rectbl->addCell("$startat to $stdCount");
        }
        $Rectbl->endRow();
    }
    $Rectbl->startRow();
    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_resfnd','financialaid').":</b>", "20%");
    $Rectbl->addCell("$stdCount");
    $Rectbl->endRow();
    $records = $Rectbl->show();
    */
    $endat = $startat + $dispCount;
    if ($endat > $stdCount){
        $endat = $stdCount;
    }

    $rep = array(
      'RANGE' => ($startat+1).'-'.$endat,
      'TOTAL' => $stdCount);

    $records = $objLanguage->code2Txt('mod_financialaid_resultsfound','financialaid',$rep);

    $showlinks->str = "$viewp $links_code $viewn";
    $showlinks->align="center";

    if($stdCount <= $dispCount){
        $pagelinks = "";
    }else{
        $pagelinks = $records.$showlinks->show();
    }
    //***end of pagination***

    $stdinfo = $this->studentinfo->listsurn($startat, $wherefield);
    $address = $this->getParam('address');
    if(!is_null($address)){
        $stdaddress = $this->studentinfo->studentAddress($stdinfo[0]->STDNUM);
    }else{
        $stdaddress = FALSE;
    }

    if(is_array($stdinfo)){

        $table =& $this->getObject('htmltable','htmlelements');

    	$table->width = '100%';
    	$table->cellpadding = 5;
    	$table->cellspacing = 2;

	    $table->startHeaderRow();
    	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
     	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
    	//$table->addHeaderCell('Student Number');
    	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_idnum','financialaid'));
    	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_details','financialaid'));

    	$table->endHeaderRow();

     	if(count($stdinfo) > 0){
            for($i = 0; $i < count($stdinfo); $i++)
            {
			    $table->row_attributes = " class = \"$oddEven\"";

    			$link = new link();

    			$link->href=$this->uri(array('action'=>'info','id'=>$stdinfo[$i]->STDNUM));
			    $link->link = $stdinfo[$i]->FSTNAM;

			    $viewdetails = new link();
			    $viewdetails->href=$this->uri(array('action'=>'info','id'=>$stdinfo[$i]->STDNUM));
			    $viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');

                $stname = $stdinfo[$i]->FSTNAM;
                $stsname = $stdinfo[$i]->SURNAM;
                $stid = $stdinfo[$i]->IDN;
                $title = $stdinfo[$i]->TTL;
                $stnum = $stdinfo[$i]->STDNUM;
			    $address = $this->studentinfo->studentAddress($stnum,35);

    			$enquiry = new link();
			    $enquiry->href=$this->uri(array('action'=>'enquiry','id'=>$stid));
			    $enquiry->link='Enquiry';

    			$table->startRow();
		    	$table->addCell($stname);
    			$table->addCell($stsname);
    			$table->addCell($stid);
    			$table->addCell($viewdetails->show());

     			$table->endRow();

    			$oddEven = $oddEven == 'odd'?'even':'odd';
    		}
    		$foundStudents = true;
    	}
        $content = $table->show();
    }
}else{
    $pagelinks = "";
    $records = "";
}


if ($foundStudents == false) {

	$content = '<br />'.$centersearch;
    $pagelinks = '';
    $records = '';
}

$content = "<center>".$details.$pagelinks." ".$content. "</center>";

echo $content;
?>
