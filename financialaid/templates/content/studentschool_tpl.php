<?
$stdnum = $this->getParam('studentNumber');
$applnum = $this->getParam('applicationNumber');
$surname = $this->getParam('surname');
$idnumber = $this->getParam('idNumber');
  
$stname = $stdinfo[0]->FSTNAM;
$stsname = $stdinfo[0]->SURNAM;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);

$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_matrictitle','financialaid',$rep)."</h2>";

$idnumber = $stdinfo[0]->IDN;
$stdnum = $stdinfo[0]->STDNUM;
$table =& $this->newObject('htmltable','htmlelements');

$this->studentinfo =& $this->getObject('dbstudentinfo','studentenquiry');
$this->objDbFinAid =& $this->getObject('dbfinaid');

//var_dump($student);
if(is_array($stdinfo)){
	//for($i = 0;$i < count($stdinfo);$i++){
     $race = $stdinfo[0]->RCE;
     $gender = $stdinfo[0]->SEX;
     $marsts = $stdinfo[0]->MARSTS;
     $title = $stdinfo[0]->TTL;
     $sttype = $stdinfo[0]->STDTYP;
     $stnum = $stdinfo[0]->STDNUM;
     
	//}
//echo "<br><pre>stdinfo: " ;    print_r($stdinfo);  echo "</pre><br>" ;
}

	//var_dump($stdaddress);
    $studentSchool = $this->objDbFinAid->getStudentSchool($stnum);


    $tables = "";
    if (is_array($studentSchool)){
        $tableSchool =& $this->newObject('htmltable','htmlelements');


        foreach($studentSchool as $data){

            $tableSchool->startRow();
            $schoolDetails  = $this->objDbFinAid->getSecondarySchoolDetails($data->SCLCDE);
  	        $tableSchool->addCell($objLanguage->languagetext('mod_financialaid_school','financialaid'), '15%');
  	        $tableSchool->addCell($schoolDetails[0]->LNGDSC);
            $tableSchool->endRow();
            $tableSchool->startRow();
  	        $tableSchool->addCell($objLanguage->languagetext('mod_financialaid_exemptionsts','financialaid'), '15%');
  	        $tableSchool->addCell($data->EXMPTNSTS);
            $tableSchool->endRow();
            $tableSchool->startRow();
  	        $tableSchool->addCell($objLanguage->languagetext('mod_financialaid_assessmark','financialaid'), '15%');
   	        $tableSchool->addCell($data->ASMMRK);
             $tableSchool->endRow();
            $tableSchool->startRow();
  	        $tableSchool->addCell($objLanguage->languagetext('mod_financialaid_symbol','financialaid'), '15%');
   	        $tableSchool->addCell($data->SBL);
            $tableSchool->endRow();
        }
        $tables .= "<br />" . $tableSchool->show();
    }

    $studentSchoolSubj = $this->objDbFinAid->getStudentMatricSubjects($stnum);


    if (is_array($studentSchoolSubj)){
        $tableSchoolSubj =& $this->newObject('htmltable','htmlelements');

        $tableSchoolSubj->startHeaderRow();
        $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_subjectcode','financialaid'));
  	    $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_subject','financialaid'));
  	    $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_grade','financialaid'));
  	    $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_symbol','financialaid'));
  	    $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_year','financialaid'));
        $tableSchoolSubj->endHeaderRow();
        $oddEven = 'odd';
        foreach($studentSchoolSubj as $data){

            for($i = 1; $i <= 9; $i++){
                if ($i > 1){
                    $sbjcodefield = 'MTRSBJCDE'.$i;
                    $gradefield = 'MTRSBJGRD'.$i;
                    $symbolfield = 'SBL'.$i;
                    $yearfield = 'MTRSBJYR'.$i;
                }else{
                    $sbjcodefield = 'MTRSBJCDE';
                    $gradefield = 'MTRSBJGRD';
                    $symbolfield = 'SBL';
                    $yearfield = 'MTRSBJYR';
                }
                if ($data->$sbjcodefield != 0){
                    $oddEven = $oddEven == 'odd'?'even':'odd';
                    $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
                    $tableSchoolSubj->startRow();
                    $tableSchoolSubj->addCell($data->$sbjcodefield);
                    $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->$sbjcodefield);
                    $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	                $tableSchoolSubj->addCell($data->$gradefield);
  	                $tableSchoolSubj->addCell($data->$symbolfield);
         	        $tableSchoolSubj->addCell($data->$yearfield);
                    $tableSchoolSubj->endRow();
                }
            }

            $tables .= "<br />" . $tableSchoolSubj->show();

        }
    }
  $content = "<center>".$details." ".$table->show(). $tables. "</center>";

echo $content;
?>
