<?php
$this->objLanguage = &$this->getObject('language','language');
$this->objUser =& $this->getObject('user','security');
$this->objFinancialAidCustomWS =& $this->getObject('financialaidcustomws','financialaid');
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$content = "";
$oddEven = 'odd';


$startat = $this->getParam('startat', 0);
$dispCount = 25;


$sponsorCount = $this->objFinancialAidCustomWS->getsponsorCount();

if ($sponsorCount > 0){

    //***start of pages***
    $links_code = "";

    $pageCount = $sponsorCount/$dispCount;

    $showlinks =& $this->getObject('htmlHeading','htmlelements');
    if ($pageCount != floor($pageCount)) {
       $pageCount = strtok(($pageCount+1), ".");
    }

    $viewpages = new link();
    for ($n=0; $n < $pageCount; $n++) {
        $sponsorCountR = ($n * $dispCount);
        $num = $n + 1;
        $viewpages->href=$this->uri(array('action'=>'searchsponsors','startat'=>$sponsorCountR,'pg'=>$num, 'all'=>$all));
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
        $sponsorCountR = $startat - $dispCount;

        $viewprev = new link();
        $viewprev->href=$this->uri(array('action'=>'searchsponsors','startat'=>$sponsorCountR,'pg'=>$page, 'all'=>$all));
        $viewprev->link = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
        $viewp = $viewprev->show();
    }
    $vntest = $sponsorCount - $dispCount;
    if ($startat <= $vntest){
        $page = $this->getParam('pg');
        $page += 1;
        $sponsorCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'searchsponsors','startat'=>$sponsorCountR,'pg'=>$page, 'all'=>$all));
        $viewnext->link = $objLanguage->languagetext('mod_financialaid_next','financialaid');
        $viewn = $viewnext->show();
    }

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
        if($endl < $sponsorCount){
            $Rectbl->addCell("$startat to $endl");
        }else {
            $Rectbl->addCell("$startat to $sponsorCount");
        }
        $Rectbl->endRow();
    }
    $Rectbl->startRow();
    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_resfnd','financialaid').":</b>", "20%");
    $Rectbl->addCell("$sponsorCount");
    $Rectbl->endRow();
    $records = $Rectbl->show();

    $showlinks->str = "$viewp $links_code $viewn";
    $showlinks->align="center";

    if($sponsorCount < $dispCount){
        $pagelinks = "";
    }else{
        $pagelinks = $records.$showlinks->show();
    }
    //***end of pagination***
}else{
    $pagelinks = "";
    $records = "";
}

var_dump($startat);
var_dump($dispCount);

$sponsors = $this->objFinancialAidCustomWS->getAllSponsors('BRSCDE', $startat, $dispCount);

if(is_array($sponsors)){
   if(count($sponsors) > 0){
        $table =& $this->getObject('htmltable','htmlelements');

    	$table->width = '100%';
    	$table->cellpadding = 5;
    	$table->cellspacing = 2;

    	$table->startHeaderRow();
    	$table->addHeaderCell('Bursor Code');
    	$table->addHeaderCell('Bursor');
    	$table->addHeaderCell('Category');
    	$table->addHeaderCell($objLanguage->languagetext('mod_financialaid_details','financialaid'));
    	$table->endHeaderRow();

        for($i = 0; $i < count($sponsors); $i++)
        {
            $table->row_attributes = " class = \"$oddEven\"";

      		$viewdetails = new link();
			$viewdetails->href=$this->uri(array('action'=>'showsponsor','id'=>$sponsors[$i]->BRSCDE));
			$viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');

			$table->startRow();
			$table->addCell($sponsors[$i]->BRSCDE);
			$table->addCell(htmlspecialchars($sponsors[$i]->XTRLNGDSC));
			$table->addCell($sponsors[$i]->BRSCTGY);
			$table->addCell($viewdetails->show());
    		$table->endRow();

			$oddEven = $oddEven == 'odd'?'even':'odd';
		}
	}
    $content = $table->show();
}


$content = "<center>".$pagelinks." ".$content. "</center>";

echo $content;
?>
