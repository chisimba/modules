<?php
$this->objLanguage = &$this->getObject('language','language');
$this->objDBApplication =& $this->getObject('dbapplication');
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');
$this->objUser =& $this->getObject('user','security');

$centersearch =& $this->getObject('blockcentersearchappbox');
$this->objUser =& $this->getObject('user','security');
$centersearch = $centersearch->show($this->getParam('module','studentenquiry'));
$details = "<h2>".$objLanguage->languagetext('mod_financialaid_searchapp','financialaid')."</h2>";

$content = "";
$oddEven = 'odd';
$foundStudents = false;

$stdnum = strtoupper($this->getParam('studentNumber'));
$surname = $this->getParam('surname');
$idnumber = strtoupper($this->getParam('idNumber'));
$all = $this->getParam('all', NULL);

//$startat = $this->getParam('start_at', NULL);

if (strlen($stdnum) > 0) {
    $wherefield = "studentNumber";
    $wherevalue = $stdnum;
}else if (strlen($surname) > 0){
    $wherefield = "surname";
    $wherevalue = $surname;
}else if (strlen($idnumber) > 0){
    $wherefield = "idNumber";
    $wherevalue = $idnumber;
}else{
    $wherefield = NULL;
}

if (!is_null($all)){
    $stdinfo = $this->objDBFinancialAidWS->getAllApplications();
    echo $stdinfo;
}else{
    if(!is_null($wherefield)){
        $stdinfo = $this->objDBFinancialAidWS->getApplication($wherevalue, $wherefield);
    }
}
//if (is_null($start_at)){
//    $start_at = 0;
//}
//$where .= "LIMIT 25,".$start_at ;

//$stdinfo = $this->objDBApplication->getAll($where);
if (isset($stdinfo)){
  if(count($stdinfo) > 0){
        $cnt = count($stdinfo);
        //***start of pages***
/*        echo($cnt);
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
                $viewpages->href=$this->uri(array('action'=>'searchapplications','surname'=>$stdinfo[0]->SURNAM,'start_at'=>$cntr,'pg'=>$num));
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
                    $viewpre->href=$this->uri(array('action'=>'searchapplications','surname'=>$stdinfo[0]->SURNAM,'start_at'=>$cntr,'pg'=>$page));
                    $viewpre->link = $objLanguage->languagetext('mod_financialaid_prev','financialaid');
                    $viewp = $viewpre->show();
                }
                $vntest = $cnt - 25;
                if ($startl <= $vntest)
                {   $page = $this->getParam('pg');
                    $page = $page + 1;
                    $cntr = $startl + 25;
                    $viewnext = new link();
                    $viewnext->href=$this->uri(array('action'=>'searchapplications','surname'=>$stdinfo[0]->SURNAM,'start_at'=>$cntr,'pg'=>$page));
                    $viewnext->link = $objLanguage->languagetext('mod_financialaid_next','financialaid');
                    $viewn = $viewnext->show();
                }
                $Rectbl =& $this->getObject('htmlTable','htmlelements');
              if($this->getParam('surname'))
              {
                if($endl==25)  {
                $Rectbl->startRow();
                    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_page','financialaid').":</b>", "20%");
                    $Rectbl->addCell("1");
                    $Rectbl->endRow();
                    $endl = $endl - 1;
                    $Rectbl->startRow();
                    $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_record','financialaid').":</b>",  "20%");
                    $Rectbl->addCell("0  to $endl");
                    $Rectbl->endRow();
                   $stdinfo = $this->studentinfo->listsurn(0);
                }
                else {
                   $page = $this->getParam('pg');
                $Rectbl->startRow();
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
                   $stdinfo = $this->studentinfo->listsurn($startl);
                }
              }
                $Rectbl->startRow();
                $Rectbl->addCell("<b>".$objLanguage->languagetext('mod_financialaid_resfnd','financialaid').":</b>", "20%");
                $Rectbl->addCell("$cnt");
                $Rectbl->endRow();
                $records = $Rectbl->show();
                $showlinks->str = "$viewp $links_code $viewn";
                $showlinks->align="center";
                $pagelinks = $records.$showlinks->show();
                //***end of pagination***
        */
    $table =& $this->getObject('htmltable','htmlelements');

	$table->width = '100%';
	$table->cellpadding = 5;
	$table->cellspacing = 2;

	$table->startHeaderRow();
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


            $stname = $stdinfo[$i]->firstNames;
            $stsname = $stdinfo[$i]->surname;
            $stid = $stdinfo[$i]->idNumber;
            $stnum = $stdinfo[$i]->studentNumber;

			$table->startRow();
			$table->addCell($stname);
			$table->addCell($stsname);
			$table->addCell($stid);
			$table->addCell($stnum);
			$table->addCell($viewdetails->show());

			$table->endRow();

			$oddEven = $oddEven == 'odd'?'even':'odd';

		}
        $foundStudents = TRUE;
   /*     if ($ncnt <= 1){
            $pagelinks = "";
            $records = "";
        }
        */
        $content = $table->show();
	}
  }
}

if ($foundStudents == FALSE) {

	$content = "<br /><br /><br />" . $centersearch;
    $pagelinks = '';
    $records = '';
}

$content = "<center>".$details.$pagelinks." ".$content . "</center>";

echo $content;
?>
