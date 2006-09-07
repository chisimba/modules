<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/*
* Class to return reports on financial aid data
*/

class financialaidreports extends object
{

    var $objLanguage;
    var $objDBFinAid;
    var $objDBStudentInfo;
    var $objDBFinancialAidWS;
    var $objFinancialAidCustomWS;
    var $objUser;
    var $objStudyFeeCalc;

    
	function init(){
		parent::init();
        $this->objLanguage = &$this->getObject('language','language');
        $this->objDBFinAid =& $this->getObject('dbfinaid');
        $this->objDBStudentInfo =& $this->getObject('dbstudentinfo', 'studentenquiry');
        $this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');
        $this->objFinancialAidCustomWS = & $this->getObject('financialaidcustomws');
        $this->objUser =& $this->getObject('user','security');
        $this->objStudyFeeCalc =& $this->getObject('studyfeecalc','financialaid');

	}


    /**
    *
    * Function to retrieve means test input from the database
    *
    * @param string $value: The value to search for in the database
    * @param string $field: The field to search on in the database
    * @return array: The array of matching records from the database
    *
    */
    function getMeansTestInput($filename, $year){
        $appinfo = $this->objDBFinancialAidWS->getAllApplications();
        $contents = "";

        $contents .= "Student Number,Surname,Initials,Course,Students in family,Dependants,Income1,Income2,Income3,Postcode,Average,Fees,Hostel Fees,Alt Funding, ID Number\r\n";
        if (isset($appinfo)){
            if(count($appinfo) > 0){


                foreach($appinfo as $data){
        			$contents .= $data->studentNumber;

                    $stdinfo = $this->objDBStudentInfo->getPersonInfo($data->studentNumber);
         			$contents .= ",".$stdinfo[0]->SURNAM;
			        $contents .= ",".$stdinfo[0]->INI;

                    $courseinfo = $this->objDBStudentInfo->getStudentCourse($data->studentNumber);
                    $gotcourse = FALSE;
                    if(count($courseinfo) > 0){
                        foreach($courseinfo as $course){
                            if ($course->YEAR == $year){
                                $coursedetails = $this->objDBStudentInfo->getCourseDesc($course->CRSCDE);
                                $contents .= ",".$coursedetails[0]->LNGDSC;
                                $gotcourse = TRUE;
                                break;
                            }
                        }
                    }
                    if ($gotcourse == FALSE){
                        $contents .= ",";
                    }

                    $studentsInFamily = $this->objDBFinancialAidWS->getStudentsInFamily($data->id);
                    if (is_null($studentsInFamily)){
                        $contents .= ",".'0';
                    }else{
    			        $contents .= ",".count($studentsInFamily);
                    }
                    $dependants = $this->objDBFinancialAidWS->getDependants($data->id);
                    if (is_null($dependants)){
                        $contents .= ",".'0';
                        $contents .= ",".'0';
                        $contents .= ",".'0';
                        $contents .= ",".'0';
                    }else{
        			    $contents .= ",".count($dependants);

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
                        $contents .= ",".$income1;
                        $contents .= ",".$income2;
                        $contents .= ",".$income3;
                    }
                    $contents .= ",".$data->homePostcode;
                    $contents .= ",".$this->objFinancialAidCustomWS->getAvgMark($data->studentNumber, $year - 1);
                    $registrationfee = $this->objStudyFeeCalc->getRegistrationFee($stdnum);
                    $tuitionfee = $this->objStudyFeeCalc->getTuitionFee($stdnum);
                    $hostelfees = $this->objStudyFeeCalc->getHostelFee($stdnum);
                    $fees = $registrationfee + $tuitionfee;
                    $altfunding = 0;
       			    $contents .= ",".$fees;
                    $contents .= ",".$hostelfees;
			        $contents .= ",".$altfunding;
    	            $contents .= ",".$data->idNumber;
                    $contents .= "\r\n";
		        }
                $handle = fopen($filename, "wb")  or die("Couldn’t open $filename");;
                fwrite($handle, $contents);
                fclose($handle);
            }
        }
    }
    
}
