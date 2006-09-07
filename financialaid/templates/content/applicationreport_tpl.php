<?php
$this->objLanguage = &$this->getObject('language','language');
$this->objDBFinAid =& $this->getObject('dbfinaid');
$this->objDBStudentInfo =& $this->getObject('dbstudentinfo', 'studentenquiry');
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');
$this->objFinancialAidCustomWS = & $this->getObject('financialaidcustomws');
$this->objUser =& $this->getObject('user','security');

$details = "<h2>Application report</h2>";

$content = "";
$oddEven = 'odd';
$year = 2006;

$appinfo = $this->objDBFinancialAidWS->getAllApplications();

if (isset($appinfo)){
    if(count($appinfo) > 0){
        $table =& $this->getObject('htmltable','htmlelements');

  	    $table->width = '100%';
    	$table->cellpadding = 5;
    	$table->cellspacing = 2;

    	$table->startHeaderRow();
    	$table->addHeaderCell('Student Number');
    	$table->addHeaderCell('Surname');
    	$table->addHeaderCell('Initials');
    	$table->addHeaderCell('Course');
    	$table->addHeaderCell('Students in family');
    	$table->addHeaderCell('Dependants');
    	$table->addHeaderCell('Income1');
    	$table->addHeaderCell('Income2');
    	$table->addHeaderCell('Income3');
    	$table->addHeaderCell('Postcode');
    	$table->addHeaderCell('Average');
    	$table->addHeaderCell('Fees');
    	$table->addHeaderCell('Hostel Fees');
    	$table->addHeaderCell('Alt Funding');
    	$table->addHeaderCell('ID Number');

    	$table->endHeaderRow();

        foreach($appinfo as $data){
			$table->row_attributes = " class = \"$oddEven\"";

			$table->startRow();
			$table->addCell($data->studentNumber);
            $stdinfo = $this->objDBStudentInfo->getPersonInfo($data->studentNumber);
			$table->addCell($stdinfo[0]->SURNAM);
			$table->addCell($stdinfo[0]->INI);
   
            $courseinfo = $this->objDBStudentInfo->getStudentCourse($data->studentNumber);
            $gotcourse = FALSE;
            if(count($courseinfo) > 0){
                foreach($courseinfo as $course){
                    if ($course->YEAR == $year){
                        $coursedetails = $this->objDBStudentInfo->getCourseDesc($course->CRSCDE);
                        $table->addCell($coursedetails[0]->LNGDSC);
                        $gotcourse = TRUE;
                        break;
                    }
                }
            }
            if ($gotcourse == FALSE){
                $table->addCell('');
            }
            
            $studentsInFamily = $this->objDBFinancialAidWS->getStudentsInFamily($data->id);
            if (is_null($studentsInFamily)){
                $table->addCell('0');
            }else{
			    $table->addCell(count($studentsInFamily));
            }
            $dependants = $this->objDBFinancialAidWS->getDependants($data->id);
            if (is_null($dependants)){
                $table->addCell('0');
                $table->addCell('0');
                $table->addCell('0');
                $table->addCell('0');
            }else{
			    $table->addCell(count($dependants));
       
                $income1 = 0;
                $income2 = 0;
                $income3 = 0;
                
                foreach($dependants as $dep){
                    if ($dep->incomeAmount > 0){
                        if ($income1 == 0){
                            $income1 = $dep->incomeAmount;
                        }else if($income2 == 0){
                            $income2 = $dep->incomeAmount;
                        }else{
                            $income3 += $dep->incomeAmount;
                        }
                    }
                }
                $table->addCell($income1);
                $table->addCell($income2);
                $table->addCell($income3);
            }
			$table->addCell($data->homePostcode);
            $table->addCell($this->objFinancialAidCustomWS->getAvgMark($data->studentNumber, $year - 1));
            $altfunding = 0;
          //  $registrationfee = $this->objStudyFeeCalc->getRegistrationFee($stdnum);
          //  $tuitionfee = $this->objStudyFeeCalc->getTuitionFee($stdnum);
          //  $hostelfees = $this->objStudyFeeCalc->getHostelFee($stdnum);
          //  $fees = $registrationfee + $tuitionfee;
			$table->addCell($fees);
			$table->addCell($hostelfees);
			$table->addCell($altfunding);
			$table->addCell($data->idNumber);

			$table->endRow();

			$oddEven = $oddEven == 'odd'?'even':'odd';
		}
        $content = $table->show();
    }
}

$content = "<center>".$details." ".$content . "</center>";

echo $content;
?>
