
<?php
if (!
        /**
         * The $GLOBALS is an array used to control access to certain constants.
         * Here it is used to check if the file is opening in engine, if not it
         * stops the file from running.
         *
         * @global entry point $GLOBALS['kewl_entry_point_run']
         * @name   $kewl_entry_point_run
         *
         */
        $GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 *
 *mayibuyeform

 * mayibuyeform  application to produce material of the robben island museum archives
 * @category  Chisimba
 * @package   mayibuyeform
 *Author  Brenda
 */



class researchform extends dbTable
{
   	public $objLanguage;
	var $captcha;



 public function init()

{
 $this->objLanguage = $this->getObject('language', 'language');
 	parent::init('tbl_mayibuyeform_researchform');
}

private function loadElements()

{
// load form class
$this->loadClass('form','htmlelements');

// load textbox or input class
$this->loadClass('textinput','htmlelements');

// load label text class
$this->loadClass('label', 'htmlelements');

// load button class
$this->loadClass('button', 'htmlelements');

$strjs = '<script type="text/javascript">
		//<![CDATA[

 
   
	/***********************************************
        *                                              *
        *              FEEDBACK CLASS                  *
        *                                              *
        ***********************************************/
        //<![CDATA[

		function init () {
			$(\'input_researchformredraw\').onclick = function () {
				feedbackredraw();
			}
		}
		function feedbackredraw () {
			var url = \'index.php\';
			var pars = \'module=security&action=generatenewcaptcha\';
			var myAjax = new Ajax.Request( url, {method: \'get\', parameters: pars, onComplete: researchformShowResponse} );
		}
		function researchformLoad () {
			$(\'load\').style.display = \'block\';
		}
		function researchformResponse (originalRequest) {
			var newData = originalRequest.responseText;
			$(\'researchformcaptchaDiv\').innerHTML = newData;
		}
		//]]>
		</script>';

        $this->appendArrayVar('headerParams', $strjs);


}

private function buildForm()

{
   $this->loadElements();
   	$objForm = new form('researchform', $this->getFormAction());
	$table = $this->newObject('htmltable', 'htmlelements');
 
 //Create a new label for the text labels
	$titleLabel = new label($this->objLanguage->languageText("mod_mayibuyeform_commenttitle","mayibuyeform"),"title");
	$table->addCell($titleLabel->show(),'', 'center', 'left', '');
	
	$table->startRow();
	$objdate = new textinput('date');
	$objdateLabel =  new label($this->objLanguage->languageText("mod_mayibuye_commentdate","mayibuyeform"),"date"); 
	$table->addCell($objdateLabel->show(),'', 'center', 'left', '');
	$table->addCell($objdate->show(),'', 'center', 'left', '');
	$objForm->addRule('date', $this->objLanguage->languageText("mod_author2_required", "mayibuyeform"), 'required');
        $table->endRow();

        $table->startRow();
	$objnameofresearcher = new textinput('name_resign');
	$objnameofReseacherLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentnameofresearch","mayibuyeform"),"name_resign");
	$table->addCell($objnameofReseacherLabel->show(),'', 'center', 'left', '');
	$table->addCell($objnameofresearcher->show(),'', 'center', 'left', '');
        $table->endRow();

        $table->StartRow();
	$objTelno = new textinput('tellno');
	$objTelLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commenttelno","mayibuyeform"),"Telno");
	$table->addCell($objTelLabel->show(),'', 'center', 'left', '');
	$table->addCell($objTelno->show(),'', 'center', 'left', '');
	
	$objFaxno = new textinput('faxno');
	$objFaxLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentfaxno","mayibuyeform"),"faxno");
	$table->addCell($objFaxLabel->show(),'', 'center', 'left', '');
	$table->addCell($objFaxno->show(),'', 'center', 'left', '');
	$table->endRow(); 
	
	$table->StartRow();
	$objEmailaddress = new textinput('emailaddress');
	$objEmailaddressLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentemailaddress","mayibuyeform"),"email");
	$table->addCell($objEmailaddressLabel->show(),'', 'center', 'left', '');
	$table->addCell($objEmailaddress->show(),'', 'center', 'left', '');
 	$objForm->addRule('emailaddress', 'Not a valid Email', 'email');
	$table->endRow();  
  
	$table->startRow();
	$objsubheadingLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentlabel","mayibuyeform"),"heading");
	$table->addCell($objsubheadingLabel->show(),'', 'center', 'left', '');
        $table->endRow();
	
	$table->startRow();
	$objNameofResignator = new textinput('resignatorname');
	$objResignatorLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentnameofsignotor","mayibuyeform"),"name of resignator");
	$table->addCell($objResignatorLabel->show(),'', 'center', 'left', '');
        $table->addCell($objNameofResignator->show(),'', 'center', 'left', '');
	$table->endRow();
	
	$table->startRow();
	$objjobtitle = new textinput('job_title');
	$objjobtitleLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentjobtitle","mayibuyeform"),"job_title");
	$table->addCell($objjobtitleLabel->show(),'', 'center', 'left', '');         
	$table->addCell($objjobtitle->show(),'', 'center', 'left', '');
	$table->endRow();

	$table->startRow();
	$objorganisation = new textinput('organization');
	$objorganizationLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentorganizationname","mayibuyeform"),"organazation");
	$table->addCell($objorganizationLabel->show(),'', 'center', 'left', '');         
	$table->addCell($objorganisation->show(),'', 'center', 'left', '');
	$table->endRow();

	$table->startRow();
       	$objpostal = new textinput('postal_address');
	$objpostalLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentpostaladrres","mayibuyeform"),"postaladdress");
	$table->addcell($objpostalLabel->show(),'', 'center', 'left', '');         
	$table->addcell($objpostal->show(),'', 'center', 'left', '');
	$table->endRow();   

	$table->startRow();
       	$objphysical = new textinput('phyiscal_address');
	$objphysicalLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentphysicaladdress","mayibuyeform"),"physicaladdress");
	$table->addcell($objphysicalLabel->show(),'', 'center', 'left', '');         
	$table->addcell($objphysical->show(),'', 'center', 'left', '');
	$table->endRow(); 
         
	$table->startRow();
	$objvat = new textinput('vat_no');
	$objvatLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentvatno","mayibuyeform"),"vat_no");
	$table->addCell($objvatLabel->show(),'', 'center', 'left', '');         
	$table->addCell($objvat->show(),'', 'center', 'left', '');
	
	
	$objjobno = new textinput('job_no');
	$objjobnoLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentjobno","mayibuyeform"),"job no");
	$table->addCell($objjobnoLabel->show());         
	$table->addCell($objjobno->show()."<br />");
	$table->endRow();

      	$table->StartRow();
	$objTelno2 = new textinput('tell_no');
	$objTel2Label = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commenttelno","mayibuyeform"),"tel_no");
	$table->addCell($objTel2Label->show(),'', 'center', 'left', '');
	$table->addCell($objTelno2->show(),'', 'center', 'left', '');
	
	$objFaxno2 = new textinput('faxno_2');
	$objFax2Label = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentfaxno","mayibuyeform"),"faxno_2");
	$table->addCell($objFax2Label->show(),'', 'center', 'left', '');
	$table->addCell($objFaxno2->show(),'', 'center', 'left', '');
	$table->endRow();

    	$table->startRow();
	$objEmail = new textinput('emails');
	$objEmailLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentemailaddress","mayibuyeform"),"email");
	$table->addCell($objEmailLabel->show(),'', 'center', 'left', '');
	$table->addCell($objEmail->show(),'', 'center', 'left', '');
	
	$table->startRow();
       	$objvatLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentsubheading","mayibuyeform"),"heading");
	$table->addCell($objvatLabel->show(),'', 'center', 'left', '');
	$table->endRow();         

	$table->startRow();
	$objname = new textinput('name');
	$objnameLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentname2","mayibuyeform"),"email");
	$table->addCell($objnameLabel->show(),'', 'center', 'left', '');
	$table->addCell($objname->show(),'', 'center', 'left', '');
	
	$objjob = new textinput('jobtitle');
	$objjobLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentjobtitle","mayibuyeform"),"jobtitle");
	$table->addCell($objjobLabel->show(),'', 'center', 'left', '');         
	$table->addCell($objjob->show(),'', 'center', 'left', '');
	$table->endRow();

	$table->startRow();	
	$objorg = new textinput('orgranization2');
	$objorgLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentorganizationname","mayibuyeform"),"organazation");
	$table->addCell($objorgLabel->show(),'', 'center', 'left', '');         
	$table->addCell($objorg->show(),'', 'center', 'left', '');
	$table->endRow();
	
	$table->startRow();
       	$objpostaladdress = new textinput('postaladdress');
	$objpostaladLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentpostaladrres","mayibuyeform"),"postaladdress");
	$table->addCell($objpostaladLabel->show(),'', 'center', 'left', '');         
	$table->addCell($objpostaladdress->show(),'', 'center', 'left', '');
	$table->endRow();

	$table->startRow();
	$objTelno3 = new textinput('tellno_3');
	$objTel3Label = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commenttelno","mayibuyeform"),"telno_3");
	$table->addCell($objTel3Label->show(),'', 'center', 'left', '');
	$table->addCell($objTelno3->show(),'', 'center', 'left', '');
	

	$objFaxno3 = new textinput('faxno_3');
	$objFax3Label = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentfaxno","mayibuyeform"),"faxno_3");
	$table->addCell($objFax3Label->show(),'', 'center', 'left', '');
	$table->addCell($objFaxno3->show(),'', 'center', 'left', '');
	$table->startRow();
	
	$table->startRow();      
	$objsubheadingLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentsublabel3","mayibuyeform"),"subheading");
	$table->addCell($objsubheadingLabel->show(),'', 'center', 'left', ''); 
	$table->endRow();        

	$table->startRow();	
	$objuwc= new textinput('uwc');
	$objuwcLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentuwc","mayibuyeform"),"uwc");
	$table->addCell($objuwcLabel->show(),'', 'center', 'left', '');
	$table->addCell($objuwc->show(),'', 'center', 'left', '');
	
	$objstaffno = new textinput('staffno');
	$objstaffLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentsforstaffno","mayibuyeform"),"staffno");
	$table->addCell($objstaffLabel->show(),'', 'center', 'left', '');
	$table->addCell($objstaffno->show(),'', 'center', 'left', '');
	$table->endRow();

	$table->startRow();
	$objdept= new textinput('dept');
	$objdeptLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentsDepartment","mayibuyeform"),"dept");
	$table->addCell($objdeptLabel->show(),'', 'center', 'left', '');
	$table->addCell($objdept->show(),'', 'center', 'left', '');
	$table->endRow();	

	$table->startRow();
	$objsubheading3 = new textinput('subheading3');
	$objsubheading3Label = new label($this->objLanguage->LanguageText("mod_mayibuye_commentsubheading4","mayibuyeform"),"subheading3");
	$table->addCell($objsubheading3Label->show(),'', 'center', 'left', '');
	$table->addCell($objsubheading3->show(),'', 'center', 'left', '');	
	$table->endRow();	

	$objpublication = new textinput('publication');
	$objpublicationLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentpublication","mayibuyeform"),"publications");
	$table->addCell($objpublicationLabel->show(),'', 'center', 'left', '');
	$table->addCell($objpublication->show(),'', 'center', 'left', '');
	$table->endRow();

	$table->startRow();
	$objproject= new textinput('project');
	$objprojectLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentproject","mayibuyeform"),"project");
	$table->addCell($objprojectLabel->show(),'', 'center', 'left', '');
	$table->addCell($objproject->show(),'', 'center', 'left', '');
	$table->endRow();	

	$table->startRow();
	$objrights = new textinput('rights');
	$objrightsLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentrights","mayibuyeform"),"rights");
	$table->addCell($objrightsLabel->show(),'', 'center', 'left', '');
	$table->addCell($objrights->show(),'', 'center', 'left', '');	
	$table->endRow();	

	$table->startRow();
	$objtermsLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_comentterms","mayibuyeform"),"terms");
	$table->addCell($objtermsLabel->show(),'', 'center', 'left', '');
	$table->endRow();

	//Submit button
        $table->startRow();
	$objButton = new button('send');
      	$objButton->setToSubmit();
	$objButton->setValue(' ' . $this->objLanguage->languageText("mod_mayibuye_commentsend", "mayibuyeform") . '');
	$table->endRow();
	$objForm->addToForm($table->show());
	// captcha
        $objCaptcha = $this->getObject('captcha', 'utilities');
        $captcha = new textinput('researchform_captcha');
        $captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'input_researchform_captcha');

      	$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
    	$strutil = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as 	shown below. If you are unable to view the code, click on "Redraw" for a new one.')) . 
	'<br /><div id="researchformcaptchaDiv">' . $objCaptcha->show() . '</div>' . $captcha->show() .
	 $required . '<a href="javascript:researchformredraw();">' . $this->objLanguage->languageText('word_redraw', 'security', 'Redraw') . '</a>';
       	 $objForm->addToForm('<br/><br/>' . $strutil . '<br/><br/>');
         $objForm->addRule('feedback_captcha', $this->objLanguage->languageText
				("mod_request_captcha_unrequired", 'mayibuyeform', 'Captcha cant be empty.Captcha is missing.'), 'required');
         	
	$objForm->addToForm($objButton->show());

	 return $objForm->show();

}

function insertStudentRecord($date, $nameofreseacher, $tellno, $faxxno, $email, $nameofsign, $jobtitles, $organization,$postaladd,$physicaladd,$vatno,
				$jobnno,$telephone,$faxnumber2,$email2,$nameofresi,$jotitle,$organizationname,$postadd,$tel,$faxx,$stuno,$staffnum,
				$colection,$image,$project,$time)
	 {
           $id = $this->insert(array(
                'date'=>$date,
		'name'=>$nameofreseacher,
		'telno' =>$tellno,
		'faxno' =>$faxxno,
		'emailaddress' =>$email,
		'nameofsignotory' =>$nameofsign,
		'jobtitle' => $jobtitles,
		'nameoforganization' =>$organization,
		'postaladdress'=>$postaladd,
		'physicaladdress'=>$physicaladd,
		'vatnum'=>$vatno,
		'jobno'=>$jobnno,
		'telephone'=>$telephone,
		'faxnumber'=>$faxnumber2,
		'email'=>$email2,
		'nameofresgn'=>$nameofresi,
		'jobtitle2'=>$jotitle,
		'organizationname'=>$organizationname,
                'postalddress2'=>$postadd,
		'tell'=>$tel,
		'fax'=>$faxx,
		'studentno'=>$stuno,
		'staffno'=>$staffnum,
		'collection'=>$colection,
		'imageaudio'=>$image,
                'projectname'=>$project,
              	'timeperido'=>$time
        ));
        return $id;
}


private function getFormAction()
{
$formAction = $this->uri(array("action" => "save_researchform"), "mayibuyeform");
        return $formAction;
}

public function Show()
{
return $this->buildForm();

}
}
?>
