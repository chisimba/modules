<?
$appid = $this->getParam('appid');
$dependantid = $this->getParam('dependantid', '');

$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
$yesno = array(
             '0'=>$objLanguage->languagetext('word_no'),
             '1'=>$objLanguage->languagetext('word_yes'));

$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_dependantstitle','financialaid',$rep)."</h2>";
$table =& $this->newObject('htmltable','htmlelements');

$dependants = $this->objDBFinancialAidWS->getDependants($appid);

if(count($dependants) > 0){
    if (strlen($dependantid) == 0){
        $table->startHeaderRow();
        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_incomeamount','financialaid'));
        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_details','financialaid'));
        $table->endHeaderRow();

        foreach($dependants as $data)
        {
		    $viewdetails = new link();
			$viewdetails->href=$this->uri(array('action'=>'showdependants','appid'=>$appid, 'dependantid'=>$data->id));
			$viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');
            $table->startRow();
            $table->addCell($data->firstName);
            $table->addCell($data->incomeAmount);
            $table->addCell($viewdetails->show());
            $table->endRow();
        }
        $content = $table->show();
    }else{
        for($i = 0; $i < count($dependants); $i++)
        {
            if ($dependants[$i]->id == $dependantid){
                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_firstname','financialaid')."</b>");
                $table->addCell($dependants[$i]->firstName);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('word_relationship')."</b>");
                $table->addCell($dependants[$i]->relationship);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_dependantreason','financialaid')."</b>");
                $table->addCell($dependants[$i]->dependantReason);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_category','financialaid')."</b>");
                $table->addCell($dependants[$i]->category);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_hasincome','financialaid')."</b>");
                $table->addCell($yesno[$dependants[$i]->hasIncome]);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_incometype','financialaid')."</b>");
                $table->addCell($dependants[$i]->incomeType);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_incomeamount','financialaid')."</b>");
                $table->addCell($dependants[$i]->incomeAmount);
                $table->endRow();

                $table->startRow();
                $table->addCell('&nbsp;');
                $table->endRow();

                if(($i < count($dependants) - 1) && (count($dependants) > 1)){
                    $next = new link();
                    $next->href=$this->uri(array('action'=>'showdependants','appid'=>$appid,'dependantid'=>$dependants[$i+1]->id));
                	$next->link = $objLanguage->languagetext('word_next');
                    $nextlink = $next->show();
                }else{
                    $nextlink = $objLanguage->languagetext('word_next');
                }
                if(($i > 0) && (count($dependants) > 1)){
                    $prev = new link();
                    $prev->href=$this->uri(array('action'=>'showdependants','appid'=>$appid,'dependantid'=>$dependants[$i-1]->id));
          	        $prev->link = $objLanguage->languagetext('word_prev');
                    $prevlink = $prev->show();
                }else{
                    $prevlink = $objLanguage->languagetext('word_prev');
                }
            }
        }
        $content = $table->show();

        $link = new link();
        $link->href=$this->uri(array('action'=>'showdependants','appid'=>$appid));
    	$link->link = $objLanguage->languagetext('mod_financialaid_listalldependants','financialaid');


        $content .= "<br />".$prevlink."&nbsp;&nbsp;".$nextlink."<br /><br />".$link->show();
    }
}else{
    $content = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_nodependants','financialaid')."</div>";
}

$content = "<center>".$details." ".$content. "</center>";

echo $content;
?>
