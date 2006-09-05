<?php
$this->objLanguage = &$this->getObject('language','language');
$this->objUser =& $this->getObject('user','security');
$this->objFinancialAidCustomWS =& $this->getObject('financialaidcustomws','financialaid');

$content = "";
$oddEven = 'odd';
$sponsors = $this->objFinancialAidCustomWS->getAllSponsors();
$pagelinks = '';
if(isset($sponsors)){
   if(is_array($sponsors)){
        $cnt = count($sponsors);
        /*
        //***start of pages***
                //output code to var, then strip off last "|" separater
                $ncnt = $cnt/25;
                $showlinks =& $this->getObject('htmlHeading','htmlelements');
               $ncnt = strtok(($ncnt+1), ".");
                $links_code = "";
                $ncnt = $cnt/25; //assumes $total_rows is a var
                //get exact number of pages, divide by 30 and get remainder
                if ($ncnt != floor($ncnt)) {
                //sum has a remainder, so extra page needed for last rows
                $ncnt = strtok(($ncnt+1), ".");
                }
                $links_code = "";
                $viewpages = new link();
                for ($n=0; $n<$ncnt; $n++) {
                $cntr = ($n * 25) + 1;
                $num = $n + 1;
                $viewpages->href=$this->uri(array('action'=>'search','surname'=>$sponsors[0]->SURNAM,'start_at'=>$cntr,'pg'=>$num));
                $viewpages->link = "$num";
                $links_code .= $viewpages->show();
                if ($num==$ncnt)
                  $links_code .= " ";
                else if($n < $ncnt)
                  $links_code .= " | ";

                }
                $startl = $this->getParam('start_at');
                $endl = $startl + 25;
                 $viewp ="";
                 $viewn ="";

                if ($startl > 1)
                {   $page = $this->getParam('pg');
                    $page = $page - 1;
                    $cntr = $startl - 25;
                    $viewpre = new link();
                    $viewpre->href=$this->uri(array('action'=>'search','surname'=>$sponsors[0]->SURNAM,'start_at'=>$cntr,'pg'=>$page));
                    $viewpre->link = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
                    $viewp = $viewpre->show();
                }
                $vntest = $cnt - 25;
                if ($startl <= $vntest)
                {   $page = $this->getParam('pg');
                    $page = $page + 1;
                    $cntr = $startl + 25;
                    $viewnext = new link();
                    $viewnext->href=$this->uri(array('action'=>'search','surname'=>$sponsors[0]->SURNAM,'start_at'=>$cntr,'pg'=>$page));
                    $viewnext->link = $objLanguage->languagetext('mod_financialaid_next','financialaid');
                    $viewn = $viewnext->show();
                }
                $Rectbl =& $this->getObject('htmlTable','htmlelements');
                $Rectbl->startRow();
              if($this->getParam('surname'))
              {
                if($endl==25)  {
                    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_page','financialaid').":</b>", "20%");
                    $Rectbl->addCell("1");
                    $Rectbl->endRow();
                    $endl = $endl - 1;
                    $Rectbl->startRow();
                    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_record','financialaid').":</b>",  "20%");
                    $Rectbl->addCell("0  to $endl");
                    $Rectbl->endRow();
                   $sponsors = $this->studentinfo->listsurn(0);
                }
                else {
                   $page = $this->getParam('pg');
                   $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_page','financialaid').":</b>", "20%");
                   $Rectbl->addCell("$page");
                   $Rectbl->endRow();
                   $Rectbl->startRow();
                   $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_record','financialaid').":</b>", "20%");
                   $Rectbl->endRow();
                   $Rectbl->startRow();
                   if($endl < $cnt){
                       $Rectbl->addCell("$startl to $endl");  }
                   else {
                       $Rectbl->addCell("startl to $cnt");  }
                   $Rectbl->endRow();
                   $sponsors = $this->studentinfo->listsurn($startl);
                }
              }
                $Rectbl->startRow();
                $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_resfnd','financialaid').":</b>", "20%");
                $Rectbl->addCell("$cnt");
                $Rectbl->endRow();
                $records = $Rectbl->show();
                $showlinks->str = "$viewp $links_code $viewn";
                $showlinks->align="center";
                $pagelinks = "<h2>".$objLanguage->languagetext('mod_financialaid_respage','financialaid')."</h2>".$records.$showlinks->show();
                //***end of pagination***
              */
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



	if(is_array($sponsors)){
        for($i = 0; $i < count($sponsors); $i++)
        {
           $table->row_attributes = " class = \"$oddEven\"";

      		$viewdetails = new link();
			$viewdetails->href=$this->uri(array('action'=>'showsponsor','id'=>$sponsors[$i]->BRSCDE));
			$viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');

			$table->startRow();
			$table->addCell($sponsors[$i]->BRSCDE);
			$table->addCell($sponsors[$i]->XTRALNGDSC);
			$table->addCell($sponsors[$i]->BRSCTGY);
			$table->addCell($viewdetails->show());

			$table->endRow();

			$oddEven = $oddEven == 'odd'?'even':'odd';
		}
	}
    $content = $table->show();
 /*  if ($ncnt <= 1)
    {
        $pagelinks = "";
        $records = "";
    }
    */
  }
}


$content = "<center>".$pagelinks." ".$content. "</center>";

echo $content;
?>
