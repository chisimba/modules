<?
$stdnum = $this->getParam('studentNumber');
$applnum = $this->getParam('applicationNumber');
$surname = $this->getParam('surname');
$idnumber = $this->getParam('idNumber');
  
$right =& $this->getObject('applicationblocksearchbox');
$right = $right->show($this->getParam('module','studentenquiry'));

$stname = $stdinfo[0]['FSTNAM'];
$stsname = $stdinfo[0]['SURNAM'];


$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);

$details = "<p><b>".$objLanguage->code2Txt('mod_financialaid_accounttitle','financialaid',$rep)."</b></p>";

$idnumber = $stdinfo[0]['IDN'];
$stdnum = $stdinfo[0]['STDNUM'];
$table =& $this->newObject('htmltable','htmlelements');

$left =& $this->getObject('financialaidleftblock');


$left = $left->show();
$this->studentinfo =& $this->getObject('dbstudentinfo','studentenquiry');
$this->objDbFinAid =& $this->getObject('dbfinaid');

$year = $this->getParam('year');
if(is_null($year)){
  $year = date('Y');
}

    $studentAccount = $this->objDbFinAid->getStudentAccountHistory($stdnum);
    $tableAccount =& $this->newObject('htmltable','htmlelements');

    $accountTotal = 0;
    $oddEven = 'odd';
   // var_dump($studentAccount);
    if (is_array($studentAccount)){
        $tableAccount =& $this->newObject('htmltable','htmlelements');
        $tableAccount->startHeaderRow();
        $tableAccount->addHeaderCell($objLanguage->languagetext('mod_financialaid_date','financialaid'));
        $tableAccount->addHeaderCell($objLanguage->languagetext('mod_financialaid_docnum','financialaid'));
        $tableAccount->addHeaderCell($objLanguage->languagetext('mod_financialaid_docsource','financialaid'));
        $tableAccount->addHeaderCell($objLanguage->languagetext('mod_financialaid_transcode','financialaid'));
        $tableAccount->addHeaderCell($objLanguage->languagetext('mod_financialaid_transaction','financialaid'));
        $tableAccount->addHeaderCell($objLanguage->languagetext('mod_financialaid_amount','financialaid'));
        $tableAccount->addHeaderCell($objLanguage->languagetext('mod_financialaid_total','financialaid'));
        $tableAccount->endHeaderRow();
        foreach($studentAccount as $data){
  			    $oddEven = $oddEven == 'odd'?'even':'odd';
                $tableAccount->row_attributes = " class = \"$oddEven\"";
                $tableAccount->startRow();
  	            $tableAccount->addCell($data->DTEYMD);
  	            $tableAccount->addCell($data->DOCNUM);
      	        $tableAccount->addCell($data->DOCSRC);
  	            $tableAccount->addCell($data->TRNCDE);

                $transDetails = $this->objDbFinAid->getTransactionDetails($data->TRNCDE);
  	            $tableAccount->addCell($transDetails[0]->MEDDSC);

     	        $tableAccount->addCell($data->AMT);
                $accountTotal += $data->AMT;
  	            $tableAccount->addCell($accountTotal);
                $tableAccount->endRow();
        }
    }

    $studentAccount = $this->objDbFinAid->getStudentAccountDetails($stdnum);

    if (is_array($studentAccount)){
        foreach($studentAccount as $data){
     			$oddEven = $oddEven == 'odd'?'even':'odd';
                $tableAccount->row_attributes = " class = \"$oddEven\"";
                $tableAccount->startRow();
     	        $tableAccount->addCell($data->DTEYMD);
  	            $tableAccount->addCell($data->DOCNUM);
  	            $tableAccount->addCell($data->DOCSRC);
      	        $tableAccount->addCell($data->TRNCDE);

                $transDetails = $this->objDbFinAid->getTransactionDetails($data->TRNCDE);
  	            $tableAccount->addCell($transDetails[0]->MEDDSC);

      	        $tableAccount->addCell($data->AMT);

                $accountTotal += $data->AMT;
  	            $tableAccount->addCell($accountTotal);
                $tableAccount->endRow();
        }
    }



$content = "<center>".$details." ".$table->show(). "<br />" . $tableAccount->show()."</center>";




// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();

?>
