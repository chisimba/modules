<?
$stdnum = $this->getParam('studentNumber');
$applnum = $this->getParam('applicationNumber');
$surname = $this->getParam('surname');
$idnumber = $this->getParam('idNumber');
  
$right =& $this->getObject('applicationblocksearchbox');
$right = $right->show($this->getParam('module','studentenquiry'));

$stname = $stdinfo[0]->FSTNAM;
$stsname = $stdinfo[0]->SURNAM;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);

$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_matrictitle','financialaid',$rep)."</h2>";

$idnumber = $stdinfo[0]->IDN;
$stdnum = $stdinfo[0]->STDNUM;
$table =& $this->newObject('htmltable','htmlelements');

$left =& $this->getObject('financialaidleftblock');


$left = $left->show();
$this->studentinfo =& $this->getObject('dbstudentinfo','studentenquiry');
$this->objDbFinAid =& $this->getObject('dbfinaid');

//var_dump($student);
if(is_array($stdinfo)){
	//for($i = 0;$i < count($stdinfo);$i++){
     $race = $stdinfo[0]['RCE'];
     $gender = $stdinfo[0]['SEX'];
     $marsts = $stdinfo[0]['MARSTS'];
     $title = $stdinfo[0]['TTL'];
     $sttype = $stdinfo[0]['STDTYP'];
     $stnum = $stdinfo[0]['STDNUM'];
     
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


    		$oddEven = $oddEven == 'odd'?'even':'odd';
            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
            $tableSchoolSubj->startRow();
            $tableSchoolSubj->addCell($data->MTRSBJCDE);
            $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->MTRSBJCDE);
            $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	        $tableSchoolSubj->addCell($data->MTRSBJGRD);
  	        $tableSchoolSubj->addCell($data->SBL);
  	        $tableSchoolSubj->addCell($data->MTRSBJYR);
            $tableSchoolSubj->endRow();

    		$oddEven = $oddEven == 'odd'?'even':'odd';
            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
            $tableSchoolSubj->startRow();
            $tableSchoolSubj->addCell($data->MTRSBJCDE2);
            $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->MTRSBJCDE2);
            $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	        $tableSchoolSubj->addCell($data->MTRSBJGRD2);
  	        $tableSchoolSubj->addCell($data->SBL2);
  	        $tableSchoolSubj->addCell($data->MTRSBJYR2);
            $tableSchoolSubj->endRow();

    		$oddEven = $oddEven == 'odd'?'even':'odd';
            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
            $tableSchoolSubj->startRow();
            $tableSchoolSubj->addCell($data->MTRSBJCDE3);
            $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->MTRSBJCDE3);
            $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	        $tableSchoolSubj->addCell($data->MTRSBJGRD3);
  	        $tableSchoolSubj->addCell($data->SBL3);
  	        $tableSchoolSubj->addCell($data->MTRSBJYR3);
            $tableSchoolSubj->endRow();

    		$oddEven = $oddEven == 'odd'?'even':'odd';
            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
            $tableSchoolSubj->startRow();
            $tableSchoolSubj->addCell($data->MTRSBJCDE4);
            $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->MTRSBJCDE4);
            $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	        $tableSchoolSubj->addCell($data->MTRSBJGRD4);
  	        $tableSchoolSubj->addCell($data->SBL4);
  	        $tableSchoolSubj->addCell($data->MTRSBJYR4);
            $tableSchoolSubj->endRow();

    		$oddEven = $oddEven == 'odd'?'even':'odd';
            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
            $tableSchoolSubj->startRow();
            $tableSchoolSubj->addCell($data->MTRSBJCDE5);
            $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->MTRSBJCDE5);
            $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	        $tableSchoolSubj->addCell($data->MTRSBJGRD5);
  	        $tableSchoolSubj->addCell($data->SBL5);
  	        $tableSchoolSubj->addCell($data->MTRSBJYR5);
            $tableSchoolSubj->endRow();

    		$oddEven = $oddEven == 'odd'?'even':'odd';
            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
            $tableSchoolSubj->startRow();
            $tableSchoolSubj->addCell($data->MTRSBJCDE6);
            $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->MTRSBJCDE6);
            $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	        $tableSchoolSubj->addCell($data->MTRSBJGRD6);
  	        $tableSchoolSubj->addCell($data->SBL6);
  	        $tableSchoolSubj->addCell($data->MTRSBJYR6);
            $tableSchoolSubj->endRow();

    		$oddEven = $oddEven == 'odd'?'even':'odd';
            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
            $tableSchoolSubj->startRow();
            $tableSchoolSubj->addCell($data->MTRSBJCDE7);
            $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->MTRSBJCDE7);
            $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	        $tableSchoolSubj->addCell($data->MTRSBJGRD7);
  	        $tableSchoolSubj->addCell($data->SBL7);
  	        $tableSchoolSubj->addCell($data->MTRSBJYR7);
            $tableSchoolSubj->endRow();

    		$oddEven = $oddEven == 'odd'?'even':'odd';
            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
            $tableSchoolSubj->startRow();
            $tableSchoolSubj->addCell($data->MTRSBJCDE8);
            $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->MTRSBJCDE8);
            $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	        $tableSchoolSubj->addCell($data->MTRSBJGRD8);
  	        $tableSchoolSubj->addCell($data->SBL8);
  	        $tableSchoolSubj->addCell($data->MTRSBJYR8);
            $tableSchoolSubj->endRow();

    		$oddEven = $oddEven == 'odd'?'even':'odd';
            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
            $tableSchoolSubj->startRow();
            $tableSchoolSubj->addCell($data->MTRSBJCDE9);
            $schoolSubj = $this->objDbFinAid->getMatricSubjectDetails($data->MTRSBJCDE9);
            $tableSchoolSubj->addCell($schoolSubj[0]->LNGDSC);
  	        $tableSchoolSubj->addCell($data->MTRSBJGRD9);
  	        $tableSchoolSubj->addCell($data->SBL9);
  	        $tableSchoolSubj->addCell($data->MTRSBJYR9);
            $tableSchoolSubj->endRow();

            $tables .= "<br />" . $tableSchoolSubj->show();

        }
    }
  $content = "<center>".$details." ".$table->show(). $tables. "</center>";

// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();
?>
