<?
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');
$this->objLanguage = &$this->getObject('language','language');
$this->objFinancialAidCustomWS = & $this->getObject('financialaidcustomws');
$this->objDbStudentInfo =& $this->getObject('dbstudentinfo','studentenquiry');
$this->objDbFinAid =& $this->getObject('dbfinaid');
$this->objStudyFeeCalc =& $this->getObject('studyfeecalc','financialaid');

if (!isset($appid)){
    $appid = $this->getParam('appid');
}

//Set up arrays for replacing values from database
$yesno = array(
             '0'=>$objLanguage->languagetext('word_no'),
             '1'=>$objLanguage->languagetext('word_yes'));

$semester = array(
             '1',$objLanguage->languagetext('word_first'),
             '2',$objLanguage->languagetext('word_second'));

$maritalStatus = array('1'=>$objLanguage->languagetext('word_single'),
                  '2'=>$objLanguage->languagetext('word_married'),
                  '3'=>$objLanguage->languagetext('word_divorced'),
                  '4'=>$objLanguage->languagetext('word_widowed'));
$relationship = array('1'=>$objLanguage->languagetext('word_father'),
                  '2'=>$objLanguage->languagetext('word_mother'),
                  '3'=>$objLanguage->languagetext('word_guardian'),
                  '4'=>$objLanguage->languagetext('word_spouse'));
                  
                  
$appInfo = $this->objDBFinancialAidWS->getApplication($appid);

if (count($appInfo) > 0){
    $studentInfo = $this->objDbStudentInfo->getPersonInfo($appInfo[0]->studentNumber);
    $studentNumber = $appInfo[0]->studentNumber;
    
    if (count($studentInfo) > 0){
        $rep = array(
          'FIRSTNAME' => $studentInfo[0]->FSTNAM,
          'LASTNAME' => $studentInfo[0]->SURNAM);

        $details = "<center><h2>".$objLanguage->code2Txt('mod_financialaid_apptitle','financialaid',$rep)."</h2></center>";

        $appTabBox = & $this->newObject('tabbox');
        $appTabBox->tabName = 'Application';


        //Create the personal details
        $table =& $this->newObject('htmltable','htmlelements');
        $table->startRow();
        $table->addCell("<b>".$objLanguage->languagetext('word_year')."</b>");
        $table->addCell($appInfo[0]->year);
        $table->endRow();

        $table->startRow();
        $table->addCell("<b>".$objLanguage->languagetext('word_semester')."</b>");
        $table->addCell($semester[$appInfo[0]->semester]);
        $table->endRow();

        $table->startRow();
        $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_stdnum2','financialaid')."</b>");
        $table->addCell($appInfo[0]->studentNumber);
        $table->endRow();

        $table->startRow();
        $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_idnumber','financialaid')."</b>");
        $table->addCell($studentInfo[0]->IDN);
        $table->endRow();

        $table->startRow();
        $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_gender','financialaid')."</b>");
        $table->addCell($this->objDbStudentInfo->getGender($studentInfo[0]->SEX));
        $table->endRow();

        $table->startRow();
        $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_mrtsts','financialaid')."</b>");
        $table->addCell($this->objDbStudentInfo->getMarStatus($studentInfo[0]->MARSTS));
        $table->endRow();

        $table->startRow();
        $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_supportingself','financialaid')."</b>");
        $table->addCell($yesno[$appInfo[0]->supportingSelf]);
   	    $table->endRow();

        $content = $table->show()."<br />";
        
        
        
        //Create the course details tab
        $results = $this->objDbStudentInfo->getCourseInfo($studentNumber, $appInfo->year);
        $studentCourse = $this->objDbStudentInfo->getStudentCourse($studentNumber);
        $resultsTabBox = & $this->newObject('tabbox');
        $resultsTabBox->tabName = 'Results';

        if (count($results) > 0){
            $j = 1;
            $years[0] = $results[0]->YEAR;
            for($i = 1; $i < count($results); $i++){
                if($results[$i]->YEAR != $results[$i-1]->YEAR){
                    $years[$j] = $results[$i]->YEAR;
                    $j++;
                }
            }
            $oddEven = 'odd';
            for($j = (count($years) - 1); $j >= 0; $j--){
                $table =& $this->newObject('htmltable','htmlelements');
	            $table->startHeaderRow();
    	        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_subcode','financialaid'),null,'top','left','header');
    	        $table->addHeaderCell($objLanguage->languagetext('word_subject'),null,'top','left','header');
    	        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_assdate','financialaid'),null,'top','left','header');
    	        $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_mark','financialaid'),null,'top','left','header');
    	        $table->endHeaderRow();
                for($k = 0; $k < count($studentCourse); $k++){
                    if($years[$j] == $studentCourse[$k]->YEAR){
                        $course = $this->objDbStudentInfo->getCourseDesc($studentCourse[$k]->CRSCDE);
                        $courseDetails = "<center>".$course[0]->LNGDSC."</center>";
                    }
                }


                for($i = 0; $i < count($results); $i++){
                    if($results[$i]->YEAR == $years[$j]){
                        $table->row_attributes = " class = \"$oddEven\"";
                        $oddEven = $oddEven == 'odd'?'even':'odd';
       	    	        $table->startRow();
          		        $table->addCell($results[$i]->SBJCDE);
                        $subject = $this->objDbFinAid->getSubject($results[$i]->SBJCDE);
                        $table->addCell(htmlspecialchars($subject[0]->SBJDSC));
         		        $table->addCell($results[$i]->YEAR);
         		        $table->addCell($results[$i]->FNLMRK);
         		        $table->endRow();
                    }
                }
                $table->startRow();
                $table->addCell("&nbsp;");
                $table->endRow();
                $table->startRow();
                $table->addCell('<b>'.$objLanguage->languagetext('mod_financialaid_stdavg','financialaid').'</b>');
                $table->addCell('&nbsp;');
                $table->addCell('&nbsp;');
                $table->addCell($this->objFinancialAidCustomWS->getAvgMark($appInfo[0]->studentNumber, $years[$j]));
                $table->endRow();
                $resultsTabBox->addTab($years[$j],$years[$j],$courseDetails.$table->show());
                $table = NULL;
            }
        }

        //Create the matric details tab
        $studentSchool = $this->objDbFinAid->getStudentSchool($studentNumber);

        $tables = "";
        if (count($studentSchool) > 0){
            $tableSchool =& $this->newObject('htmltable','htmlelements');

            foreach($studentSchool as $data){
                $tableSchool->startRow();
                $schoolDetails  = $this->objDbFinAid->getSecondarySchoolDetails($data->SCLCDE);
                $tableSchool->addCell("<b>".$objLanguage->languagetext('mod_financialaid_school','financialaid')."</b>");
                $tableSchool->addCell($schoolDetails[0]->LNGDSC);
                $tableSchool->endRow();
                $tableSchool->startRow();
                $tableSchool->addCell("<b>".$objLanguage->languagetext('mod_financialaid_exemptionsts','financialaid')."</b>");
                $tableSchool->addCell($data->EXMPTNSTS);
                $tableSchool->endRow();
                $tableSchool->startRow();
                $tableSchool->addCell("<b>".$objLanguage->languagetext('mod_financialaid_assessmark','financialaid')."</b>");
                $tableSchool->addCell($data->ASMMRK);
                $tableSchool->endRow();
                $tableSchool->startRow();
                $tableSchool->addCell("<b>".$objLanguage->languagetext('mod_financialaid_symbol','financialaid')."</b>");
                $tableSchool->addCell($data->SBL);
                $tableSchool->endRow();
            }
            $tables .= $tableSchool->show();

            $studentSchoolSubj = $this->objDbFinAid->getStudentMatricSubjects($studentNumber);
            if (count($studentSchoolSubj) > 0){
                $tableSchoolSubj =& $this->newObject('htmltable','htmlelements');
                $tableSchoolSubj->startHeaderRow();
                $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_subjectcode','financialaid'),null,'top','left','header');
                $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_subject','financialaid'),null,'top','left','header');
                $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_grade','financialaid'),null,'top','left','header');
                $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_symbol','financialaid'),null,'top','left','header');
                $tableSchoolSubj->addHeaderCell($objLanguage->languagetext('mod_financialaid_year','financialaid'),null,'top','left','header');
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
                            $tableSchoolSubj->row_attributes = " class = \"$oddEven\"";
                            $oddEven = $oddEven == 'odd'?'even':'odd';
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
            $resultsTabBox->addTab('matricDetails', $objLanguage->languagetext('word_matric'), $tables);
        }
        $courseDisplay = $resultsTabBox->show(FALSE);




        $appTabBox->addTab('courseDetails', $objLanguage->languagetext('word_results'), $courseDisplay);



        
        //Create the account details tab
        $accountTotal = 0;
        $oddEven = 'odd';

        $registrationfee = 0;
        $tuitionfee = 0;
        $hostelfee = 0;

        $registrationfee = $this->objStudyFeeCalc->getRegistrationFee($studentNumber);
        $tuitionfee = $this->objStudyFeeCalc->getTuitionFee($studentNumber);
        $hostelfee = $this->objStudyFeeCalc->getHostelFee($studentNumber);

        $total = $registrationfee + $tuitionfee + $hostelfee;
        $tableAccount =& $this->newObject('htmltable','htmlelements');
        $tableAccount->startRow();
        $tableAccount->addCell("<b>".'Registration Fee'."</b>");
        $tableAccount->addCell($registrationfee);
        $tableAccount->endRow();
        $tableAccount->startRow();
        $tableAccount->addCell("<b>".'Tuition Fee'."</b>");
        $tableAccount->addCell($tuitionfee);
        $tableAccount->endRow();
        $tableAccount->startRow();
        $tableAccount->addCell("<b>".'Hostel Fee'."</b>");
        $tableAccount->addCell($hostelfee);
        $tableAccount->endRow();
        $tableAccount->startRow();
        $tableAccount->addCell("&nbsp;");
        $tableAccount->endRow();
        $tableAccount->startRow();
        $tableAccount->addCell("<b>".'Total'."</b>");
        $tableAccount->addCell("<b>".$total."</b>");
        $tableAccount->endRow();
        $appTabBox->addTab('accountDetails', $objLanguage->languagetext('word_account'), $tableAccount->show());


        //Create the employement details tab
        $parttimejobs = $this->objDBFinancialAidWS->getParttimejob($appid);

        if(count($parttimejobs) > 0){
            foreach($parttimejobs as $data){
                $table =& $this->newObject('htmltable','htmlelements');
                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_jobtitle','financialaid')."</b>");
                $table->addCell($data->jobTitle);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_empdetails','financialaid')."</b>");
                $table->addCell($data->employersTelNo);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_emptelno','financialaid')."</b>");
                $table->addCell($data->employersDetails);
                $table->endRow();
                
                $table->startRow();
                $table->addCell('&nbsp;');
                $table->endRow();            }
            $tabContent = $table->show();
        }else{
            $tabContent = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_noparttimejob','financialaid')."</div>";
        }
        $appTabBox->addTab('employmentDetails', $objLanguage->languagetext('word_employment'), $tabContent);


        //Create the nextofkin details tab
        $nextofkin = $this->objDBFinancialAidWS->getNextofkin($appid);
        if(count($nextofkin) > 0){
            $nextofkinTabBox = & $this->newObject('tabbox');
            $nextofkinTabBox->tabName = 'Nextofkin';
            for($i = 0; $i < count($nextofkin); $i++){
                $table =& $this->newObject('htmltable','htmlelements');

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_idnumber','financialaid')."</b>");
                $table->addCell($nextofkin[$i]->idNumber);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('word_relationship')."</b>");
                $table->addCell($relationship[$nextofkin[$i]->relationship]);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_homeaddress','financialaid')."</b>");
                $table->addCell($nextofkin[$i]->strAddress);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_suburb','financialaid')."</b>");
                $table->addCell($nextofkin[$i]->suburb);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_city','financialaid')."</b>");
                $table->addCell($nextofkin[$i]->city);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_pcode','financialaid')."</b>");
                $table->addCell($nextofkin[$i]->postcode);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_mrtsts','financialaid')."</b>");
                $table->addCell($maritalstatus[$nextofkin[$i]->maritalStatus]);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_spouse','financialaid')."</b>");
                $table->addCell($nextofkin[$i]->spouse);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_occupation','financialaid')."</b>");
                $table->addCell($nextofkin[$i]->occupation);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_empdetails','financialaid')."</b>");
                $table->addCell($nextofkin[$i]->employersDetails);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_emptelno','financialaid')."</b>");
                $table->addCell($nextofkin[$i]->employersTelNo);
                $table->endRow();
                $nextofkinTabBox->addTab($nextofkin[$i]->id, $nextofkin[$i]->firstNames.'&nbsp;'.$nextofkin[$i]->surname, $table->show());
                $table = NULL;
            }
            $nextofkinDisplay = $nextofkinTabBox->show(FALSE);
        }else{
            $nextofkinDisplay = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_nonextofkin','financialaid')."</div>";
        }
        $appTabBox->addTab('nextofkinDetails', $objLanguage->languagetext('mod_financialaid_nextofkin','financialaid'), $nextofkinDisplay);
        
        
        //Create dependants details tab
        $dependants = $this->objDBFinancialAidWS->getDependants($appid);
        $dependantStudents = $this->objDBFinancialAidWS->getStudentsInFamily($appid);

        if(count($dependants) > 0){
            $dependantsTabBox = & $this->newObject('tabbox');
            $dependantsTabBox->tabName = 'Dependants';

            for($i = 0; $i < count($dependants); $i++){
                $table =& $this->newObject('htmltable','htmlelements');
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

                //If dependant is also a student show this info
                for($j = 0; $j < count($dependantStudents); $j++){
                    if ($dependantStudents[$j]->firstName == $dependants[$j]->firstName){

                        $table->startRow();
                        $table->addCell("&nbsp;");
                        $table->endRow();

                        $table->startRow();
                        $table->addCell("<center><b>".$objLanguage->languagetext('mod_financialaid_studentdetails','financialaid')."</b></center>");
                        $table->endRow();

                        $table->startRow();
                        $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_institution','financialaid')."</b>");
                        $table->addCell($dependantStudents[$j]->institution);
                        $table->endRow();

                        $table->startRow();
                        $table->addCell("<b>".$objLanguage->languagetext('word_course')."</b>");
                        $table->addCell($dependantStudents[$j]->course);
                        $table->endRow();

                        $table->startRow();
                        $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_yearofstudy','financialaid')."</b>");
                        $table->addCell($dependantStudents[$j]->yearOfStudy);
                        $table->endRow();

                        $table->startRow();
                        $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_stdnum2','financialaid')."</b>");
                        $table->addCell($dependantStudents[$j]->studentNumber);
                        $table->endRow();
                        
                    }
                }

                $dependantsTabBox->addTab($dependants[$i]->id, $dependants[$i]->firstName, $table->show());
            }
            $dependantsDisplay = $dependantsTabBox->show(FALSE);
        }else{
            $dependantsDisplay = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_nodependants','financialaid')."</div>";
        }
        $appTabBox->addTab('dependantsDetails', $objLanguage->languagetext('word_dependants'), $dependantsDisplay);

        //Create dependant students tab
 /*
        if(count($dependantStudents) > 0){
            $dependantsStudyingTabBox = & $this->newObject('tabbox');
            $dependantsStudyingTabBox->tabName = 'DependantsStudying';

            for($i = 0; $i < count($dependantStudents); $i++){
                $table =& $this->newObject('htmltable','htmlelements');
                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_firstname','financialaid')."</b>");
                $table->addCell($dependantStudents[$i]->firstName);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_institution','financialaid')."</b>");
                $table->addCell($dependantStudents[$i]->institution);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('word_course')."</b>");
                $table->addCell($dependantStudents[$i]->course);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_yearofstudy','financialaid')."</b>");
                $table->addCell($dependantStudents[$i]->yearOfStudy);
                $table->endRow();

                $table->startRow();
                $table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_stdnum2','financialaid')."</b>");
                $table->addCell($dependantStudents[$i]->studentNumber);
                $table->endRow();
                $dependantsStudyingTabBox->addTab($dependantStudents[$i]->id, $dependantStudents[$i]->firstName, $table->show());
            }
            $dependantsStudyingDisplay = $dependantsStudyingTabBox->show(FALSE);
        }else{
            $dependantsStudyingDisplay = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_nostudentfamily','financialaid')."</div>";
        }
   */
  //    $appTabBox->addTab('dependantsStudyingDetails', $objLanguage->languagetext('mod_financialaid_showappstudentfamilydetails','financialaid'), $dependantsStudyingDisplay);
        $content .= $appTabBox->show();
    }
}
echo $details.$content;

?>
