<?
$appid = $this->getParam('appid');
$studentid = $this->getParam('studentid', '');

$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<center><h2>".$objLanguage->code2Txt('mod_financialaid_studentfamilytitle','financialaid',$rep)."</h2></center>";
$table =& $this->newObject('htmltable','htmlelements');

$studentfamily = $this->objDBFinancialAidWS->getStudentsInFamily($appid);

if(count($studentfamily) > 0){
    if (strlen($studentid) == 0){
        $table->startHeaderRow();
        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_institution','financialaid'));
        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_details','financialaid'));
        $table->endHeaderRow();

        foreach($studentfamily as $data)
        {
		    $viewdetails = new link();
			$viewdetails->href=$this->uri(array('action'=>'showstudentfamily','appid'=>$appid, 'studentid'=>$data->id));
			$viewdetails->link = $objLanguage->languagetext('mod_financialaid_view','financialaid');
            $table->startRow();
            $table->addCell($data->firstName);
            $table->addCell($data->institution);
            $table->addCell($viewdetails->show());
            $table->endRow();
        }
        $content = $table->show();
    }else{
        for($i = 0; $i < count($studentfamily); $i++)
        {
            if ($studentfamily[$i]->id == $studentid){
                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_firstname','financialaid')."</b>");
                $table->addCell($studentfamily[$i]->firstName);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_institution','financialaid')."</b>");
                $table->addCell($studentfamily[$i]->institution);
                $table->endRow();
                
                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('word_course')."</b>");
                $table->addCell($studentfamily[$i]->course);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_yearofstudy','financialaid')."</b>");
                $table->addCell($studentfamily[$i]->yearOfStudy);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_stdnum2','financialaid')."</b>");
                $table->addCell($studentfamily[$i]->studentNumber);
                $table->endRow();

                if(($i < count($studentfamily) - 1) && (count($studentfamily) > 1)){
                    $next = new link();
                    $next->href=$this->uri(array('action'=>'showstudentfamily','appid'=>$appid,'studentid'=>$studentfamily[$i+1]->id));
                	$next->link = $objLanguage->languagetext('word_next');
                    $nextlink = $next->show();
                }else{
                    $nextlink = $objLanguage->languagetext('word_next');
                }
                if(($i > 0) && (count($studentfamily) > 1)){
                    $prev = new link();
                    $prev->href=$this->uri(array('action'=>'showstudentfamily','appid'=>$appid,'studentid'=>$studentfamily[$i-1]->id));
          	        $prev->link = $objLanguage->languagetext('word_prev');
                    $prevlink = $prev->show();
                }else{
                    $prevlink = $objLanguage->languagetext('word_prev');
                }
            }
        }
        $content = $table->show();

        $link = new link();
        $link->href=$this->uri(array('action'=>'showstudentfamily','appid'=>$appid));
    	$link->link = $objLanguage->languagetext('mod_financialaid_listalldependantsstudying','financialaid');


        $content .= "<br />".$prevlink."&nbsp;&nbsp;".$nextlink."<br /><br />".$link->show();

    }
}else{
    $content = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_nostudentfamily','financialaid')."</div>";
}
$content = $details.$content;

echo $content;
?>
