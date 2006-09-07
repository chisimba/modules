<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/*
* Module to retrieve data for student financial aid info using web services
*/

class studyfeecalc extends object
{
    var $objFinancialAidCustomWS;

	function init(){
		parent::init();
        $this->objFinancialAidCustomWS =& $this->getObject('financialaidcustomws','financialaid');

	}

    function getFee($stdnum, $type){
        $fee = 0;
        
        $studentAccount = $this->objFinancialAidCustomWS->getStudentAccount($stdnum, $type);
        if (count($studentAccount) > 0){
            foreach($studentAccount as $data){
                 $fee += $data->AMT;
            }
        }
        
        return $fee;
    }

    /**
    *
    * Function to retrieve registration fee for sudent
    *
    * @param string $stdnum: The student number of student
    * @return int: Registration fee
    *
    */
    function getRegistrationFee($stdnum){
        $registrationfee = 0;

        $registrationfee += $this->getFee($stdnum, '2');
        $registrationfee += $this->getFee($stdnum, '3');
        $registrationfee += $this->getFee($stdnum, '4');
  		return $registrationfee;
    }
    /**
    *
    * Function to retrieve tuition fee for sudent
    *
    * @param string $stdnum: The student number of student
    * @return int: Tuition fee
    *
    */
    function getTuitionFee($stdnum){
        $tuitionfee = 0;

        $tuitionfee += $this->getFee($stdnum, '11');
        $tuitionfee += $this->getFee($stdnum, '12');
        $tuitionfee += $this->getFee($stdnum, '15');
        $tuitionfee += $this->getFee($stdnum, '52');
  		return $tuitionfee;
    }
    /**
    *
    * Function to retrieve tuition fee for sudent
    *
    * @param string $stdnum: The student number of student
    * @return int: Hostel fee
    *
    */
    function getHostelFee($stdnum){
        $hostelfee = 0;

        $hostelfee += $this->getFee($stdnum, '53');
        $hostelfee += $this->getFee($stdnum, '21');
        $hostelfee += $this->getFee($stdnum, '19');
  		return $hostelfee;
    }

}
