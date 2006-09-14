<?
$appid = $this->getParam('appid');
$nextofkinid = $this->getParam('nextofkinid', '');

$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$maritalstatus = array('1'=>$objLanguage->languagetext('word_single'),
                  '2'=>$objLanguage->languagetext('word_married'),
                  '3'=>$objLanguage->languagetext('word_divorced'),
                  '4'=>$objLanguage->languagetext('word_widowed'));
$relationship = array('1'=>$objLanguage->languagetext('word_father'),
                  '2'=>$objLanguage->languagetext('word_mother'),
                  '3'=>$objLanguage->languagetext('word_guardian'),
                  '4'=>$objLanguage->languagetext('word_spouse'));


$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_nextofkintitle','financialaid',$rep)."</h2>";

$nextofkin = $this->objDBFinancialAidWS->getNextofkin($appid);
$content = '';
$table =& $this->newObject('htmltable','htmlelements');
if(count($nextofkin) > 0){
    if (strlen($nextofkinid) == 0){
        $table->startHeaderRow();
        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstnames','financialaid'));
        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
        $table->addHeaderCell($objLanguage->languagetext('word_relationship'));
        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_details','financialaid'));
        $table->endHeaderRow();

        foreach($nextofkin as $data)
        {
		    $viewdetails = new link();
			$viewdetails->href=$this->uri(array('action'=>'shownextofkin','appid'=>$appid, 'nextofkinid'=>$data->id));
			$viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');
            $table->startRow();
            $table->addCell($data->firstNames);
            $table->addCell($data->surname);
            $table->addCell($data->relationship);
            $table->addCell($viewdetails->show());
            $table->endRow();
        }
        $content = $table->show();
    }else{
        for($i = 0; $i < count($nextofkin); $i++)
        {
            if ($nextofkin[$i]->id == $nextofkinid){
                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_idnumber','financialaid'));
                $table->addCell($nextofkin[$i]->idNumber);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_surname','financialaid'));
                $table->addCell($nextofkin[$i]->surname);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_firstnames','financialaid'));
                $table->addCell($nextofkin[$i]->firstNames);
                $table->endRow();
        
                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('word_relationship'));
                $table->addCell($relationship[$nextofkin[$i]->relationship]);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_homeaddress','financialaid'));
                $table->addCell($nextofkin[$i]->strAddress);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_suburb','financialaid'));
                $table->addCell($nextofkin[$i]->suburb);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_city','financialaid'));
                $table->addCell($nextofkin[$i]->city);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_pcode','financialaid'));
                $table->addCell($nextofkin[$i]->postcode);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_mrtsts','financialaid'));
                $table->addCell($maritalstatus[$nextofkin[$i]->maritalStatus]);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_spouse','financialaid'));
                $table->addCell($nextofkin[$i]->spouse);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_occupation','financialaid'));
                $table->addCell($nextofkin[$i]->occupation);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_empdetails','financialaid'));
                $table->addCell($nextofkin[$i]->employersDetails);
                $table->endRow();

                $table->startRow();
                $table->addCell($objLanguage->languagetext('mod_financialaid_emptelno','financialaid'));
                $table->addCell($nextofkin[$i]->employersTelNo);
                $table->endRow();
        
                $table->startRow();
                $table->addCell('&nbsp;');
                $table->endRow();

                if(($i < count($nextofkin) - 1) && (count($nextofkin) > 1)){
                    $next = new link();
                    $next->href=$this->uri(array('action'=>'showdependants','appid'=>$appid,'nextofkinid'=>$nextofkin[$i+1]->id));
                	$next->link = $objLanguage->languagetext('word_next');
                    $nextlink = $next->show();
                }else{
                    $nextlink = $objLanguage->languagetext('word_next');
                }
                if(($i > 0) && (count($nextofkin) > 1)){
                    $prev = new link();
                    $prev->href=$this->uri(array('action'=>'showdependants','appid'=>$appid,'nextofkinid'=>$nextofkin[$i-1]->id));
          	        $prev->link = $objLanguage->languagetext('word_prev');
                    $prevlink = $prev->show();
                }else{
                    $prevlink = $objLanguage->languagetext('word_prev');
                }
            }
           $content .= $table->show();
        }
        $link = new link();
        $link->href=$this->uri(array('action'=>'shownextofkin','appid'=>$appid));
    	$link->link = $objLanguage->languagetext('mod_financialaid_listallnextofkin','financialaid');

        $content .= "<br />".$prevlink."&nbsp;&nbsp;".$nextlink."<br /><br />".$link->show();
    }
}else{
    $content = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_nonextofkin','financialaid')."</div>";
}

$content = "<center>".$details. "</center>".$content;

echo $content;
?>
