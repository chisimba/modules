<?php
$this->appendArrayVar('headerParams','<link rel="stylesheet" type="text/css" href="modules/financialaid/resources/finaid.css" />');

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
        $viewpages->href=$this->uri(array('action'=>'searchsponsors','startat'=>$sponsorCountR,'pg'=>$num));
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
        $viewprev->href=$this->uri(array('action'=>'searchsponsors','startat'=>$sponsorCountR,'pg'=>$page));
        $viewprev->link = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
        $viewp = $viewprev->show();
    }
    $vntest = $sponsorCount - $dispCount;
    if ($startat <= $vntest){
        $page = $this->getParam('pg');
        $page += 1;
        $sponsorCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'searchsponsors','startat'=>$sponsorCountR,'pg'=>$page));
        $viewnext->link = $objLanguage->languagetext('mod_financialaid_next','financialaid');
        $viewn = $viewnext->show();
    }

    $endat = $startat + $dispCount;
    if ($endat > $sponsorCount){
        $endat = $sponsorCount;
    }

    $rep = array(
      'RANGE' => ($startat+1).'-'.$endat,
      'TOTAL' => $sponsorCount);

    $records = $objLanguage->code2Txt('mod_financialaid_resultsfound','financialaid',$rep);
    $showlinks->str = "$viewp $links_code $viewn";
    $showlinks->align="center";

    if($sponsorCount <= $dispCount){
        $pagelinks = "";
    }else{
        $pagelinks = $records.$showlinks->show();
    }
    //***end of pagination***
}else{
    $pagelinks = "";
    $records = "";
}

$sponsors = $this->objFinancialAidCustomWS->getAllSponsors('BRSCDE', $startat, $dispCount);

if(is_array($sponsors)){
   if(count($sponsors) > 0){
        $table =& $this->getObject('htmltable','htmlelements');
        $table->css_class = 'highlightrows';

    	$table->width = '100%';
    	$table->cellpadding = 5;
    	$table->cellspacing = 0;

    	$table->startHeaderRow();
    	$table->addHeaderCell('Bursor Code',null,'top','left','header');
    	$table->addHeaderCell('Bursor',null,'top','left','header');
    	$table->addHeaderCell('Category',null,'top','left','header');
    	$table->endHeaderRow();

        for($i = 0; $i < count($sponsors); $i++)
        {
            $table->row_attributes = " class = \"$oddEven\"";

      		$viewdetails = new link();
			$viewdetails->href=$this->uri(array('action'=>'showsponsor','sponsorid'=>$sponsors[$i]->BRSCDE));

			$table->startRow();
			$viewdetails->link = $sponsors[$i]->BRSCDE;
			$table->addCell($viewdetails->show(), null, 'top', null, 'widelink');
			$viewdetails->link = htmlspecialchars($sponsors[$i]->XTRLNGDSC);
			$table->addCell($viewdetails->show(), null, 'top', null, 'widelink');
			$viewdetails->link = $sponsors[$i]->BRSCTGY;
			$table->addCell($viewdetails->show(), null, 'top', null, 'widelink');
    		$table->endRow();

			$oddEven = $oddEven == 'odd'?'even':'odd';
		}
	}
    $content = $table->show();
}


$content = "<center>".$pagelinks." ".$content. "</center>";

echo $content;
?>
