
<?php

 class mayibuyeform extends controller
{
	public $objLanguage;
	protected $objMail;
	var $date;
        var $nameofreseacher;
        var $tellno;
	var $faxxno;
 	var $email;
	var $jobtitles;
	var $organization;
	var $postaladd;
	var $physicaladd;
	var $vatno;
	var $jobnno;
	var $telephone;
	var $faxnumber2;
	var $email2;
	var $nameofresi;
	var $jotitle;
	var $organizationname;
        var $postadd;
	var $tel;
	var $faxx;
	var $stuno;
	var $staffnum;
	var $colection;
	//var $image;
        //var $project;
       // var $time;


public function init()
{
   //Instantiate the language object
    	$this->objLanguage = $this->getObject('language', 'language');

  	$this->objMail = $this->getObject('email', 'mail');

 	$this->dbresearchform = $this->getObject('researchform', 'mayibuyeform');
}

public function dispatch($action){
	$this->setLayoutTemplate('research_tpl.php');

	switch($action)
             {
 	            
     	 default:
              return 'research_tpl.php';



	case 'save_researchform':
	 	return $this->SavestudentRecord();
             	return 'confirm_tpl.php';

}

}
public function SavestudentRecord()

{
      		$date = $this->getParam('date');
		$nameofreseacher = $this->getParam('name_resign');
		$tellno = $this->getParam('tellno');
                $faxxno =$this->getParam('faxno');
		$email = $this->getParam('emailaddress');
		$nameofsign = $this->getParam('resignatorname');
		$jobtitles = $this->getParam('job_title');
		$organization = $this->getParam('organization');
		$postaladd= $this->getParam('postal_address');
		$physicaladd= $this->getParam('phyiscal_address');
		$vatno= $this->getParam('vat_no');
		$jobnno= $this->getParam('job_no');
		$telephone= $this->getParam('tell_no');
		$faxnumber2= $this->getParam('faxno_2');
		$email2= $this->getParam('emails');
		$nameofresi= $this->getParam('name');
		$jotitle= $this->getParam('jobtitle');
		$organizationname= $this->getParam('orgranization2');
            	$postadd= $this->getParam('postaladdress');
		$tel= $this->getParam('tellno_3');
		$faxx= $this->getParam('faxno_3');
		$stuno= $this->getParam('uwc');
		$staffnum= $this->getParam('staffno');
		$colection= $this->getParam('dept');
		$image= $this->getParam('subheading3');
                $project= $this->getParam('publication');
              	$time= $this->getParam('project');


 		// insert into database
		$pid = $this->dbresearchform->insertStudentRecord($date, $nameofreseacher, $tellno,$faxxno, $email,$nameofsign,
                         $jobtitles,$organization,$postaladd,$physicaladd, $vatno, $jobnno,$telephone,$faxnumber2,$email2,$nameofresi,
			 $jotitle, $organizationname, $postadd,$tel,$faxx,$stuno,$staffnum,$colection,$image,$project,$time);
               
		
	$subject = "New Reseacher";
		$this->sendEmailNotification($subject, $message = ' date: ' . $date . '  ' . "\n" . ' name:  ' . 
					$nameofreseacher . '   ' . "\n" . ' telno: ' . $tellno . '   ' . "\n". 'Fax no '. 
					$faxxno. '  ' . "\n". ' email adddress: '.$email .'  ' ."\n" . ' Name of Signator: '. 
					$nameofsign . '  ' ."\n". ' job-title: '. $jobtitles. '   '. "\n" . 'organanization: '. 
					$organization. '  ' ."\n". ' postal Address: '.	$postaladd. '  '."\n". 'Physical Address: '.
					$physicaladd. '  '. "\n" . ' Vat-no: ' . $vatno. '  ' . "\n" . ' Job no '. $jobnno. ' ' . "\n". ' TelePhone: '. 
					$telephone. '  '. "\n".	' Fax number: '. $faxnumber2. '  '. "\n". 'Emaill Address'.
					$email2 . '  '. "\n". 'name of Sesignator:'. $nameofresi. '  '. "\n". 'Job title' .
					$jotitle. '  '. "\n". 'Name of Organization'. $organizationname. '  '. "\n". 'postal address'. 
					$$postadd. '  '. "\n". 'Telphone'. $tel. '  '. "\n". 'Fax no'. $faxx. '  '. "\n". 'Student No'.
					$stuno. '  '. "\n". 'Staff num'.$staffnum. '  '. "\n". 'Department'. $colection);
  
			 return "research_tpl.php";


}
	

public function sendEmailNotification($subject, $message) {

        $objMail = $this->getObject('email', 'mail');
        //send to multiple addressed   
        $list = array("pmahinga@uwc.ac.za");
        $objMail->to = ($list);
        // specify whom the email is coming from
        $objMail->from = "no-reply@uwc.ac.za";
        $objMail->from = "no-reply";
        //Give email subject and body
        //$objMail->subject=$emaill;
        $objMail->subject = $subject;
        $objMail->body = $message;
        $objMail->AltBody = $message;
        // send email
        $objMail->send();
    }

}

?>