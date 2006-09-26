<?php
$this->appendArrayVar('headerParams','<link rel="stylesheet" type="text/css" href="modules/financialaid/resources/finaid.css" />');

$this->objLanguage = &$this->getObject('language','language');
$this->objUser =& $this->getObject('user','security');
$this->objFinancialAidCustomWS =& $this->getObject('financialaidcustomws','financialaid');
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$content = "";
$oddEven = 'odd';

$page = $this->getParam('page', 1);
$dispCount = $this->getParam('dispcount', 25);

$sponsorCount = $this->objFinancialAidCustomWS->getsponsorCount();

//Pagination
$paging = '';
$records = '';
if ($sponsorCount > 0){

    $pageCount = $sponsorCount/$dispCount;
    if ($pageCount != floor($pageCount)) {
       $pageCount = strtok(($pageCount+1), ".");
    }
    $startat = ($page - 1) * $dispCount;
    $endat = $startat + $dispCount;
    
    if ($endat > $sponsorCount){
        $endat = $sponsorCount;
    }

    $dispCountField = new textinput("dispcount", $dispCount,  "hidden", NULL);

   	$goButton = & $this->newObject('button','htmlelements');
   	$cancelButton = & $this->newObject('button','htmlelements');

   	$goButton = new button("submit",'Go');
    $goButton->setToSubmit();
	$cancelButton = new button("cancel", $objLanguage->languagetext('word_cancel'));
	$cancelButton->setToSubmit();

	$dropdown =& $this->newObject("dropdown", "htmlelements");
	$dropdown->name = 'page';
	for ($i = 1; $i <= $pageCount; $i++){
	    $dropdown->addOption($i,$i."&nbsp;&nbsp;&nbsp;");
        if ($i == $page){
            $dropdown->setSelected($i);
        }
	}

    if ($page > 1){
        $prevLink = new link();
        $prevLink->href=$this->uri(array('action'=>'searchsponsors','page'=>($page-1), 'dispcount'=>$dispCount));
        $prevLink->link = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
        $prev = $prevLink->show();
    }else{
        $prev = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
    }
    
    if ($page < $pageCount){
        $nextLink = new link();
        $nextLink->href=$this->uri(array('action'=>'searchsponsors','page'=>($page+1), 'dispcount'=>$dispCount));
        $nextLink->link = $objLanguage->languagetext('mod_financialaid_next','financialaid');
        $next = $nextLink->show();
    }else{
        $next = $objLanguage->languagetext('mod_financialaid_next','financialaid');
    }

    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'searchsponsors')));
    $objPaging->addToForm($dispCountField->show());
	$objPaging->addToForm('<center>'.'Page '.$dropdown->show().' of '.'<b>'.$pageCount.'</b>'.' '.$goButton->show()."&nbsp;&nbsp;&nbsp;&nbsp;"."<span class='menulink'>".$prev."&nbsp;&nbsp;".$next.'</span></center>');

    $paging = $objPaging->show();
    
    $rep = array(
      'RANGE' => ($startat+1).'-'.$endat,
      'TOTAL' => $sponsorCount);

    $records = $objLanguage->code2Txt('mod_financialaid_resultsfound','financialaid',$rep);
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


$content = "<center>".$records.$paging." ".$content. "</center>";

echo $content;
?>
