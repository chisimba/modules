
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
	$objForm->addRule('date', $this->objLanguage->languageText("mod_mayibuye_date_required", "mayibuyeform"), 'required');
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
	$table->EndRow();

	
	$table->startRow();	
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
   

        


	//Submit button
        $table->startRow();
	$objButton = new button('send');
      	$objButton->setToSubmit();
	$objButton->setValue(' ' . $this->objLanguage->languageText("mod_mayibuye_commentnext", "mayibuyeform") . '');
	$table->endRow();
	$objForm->addToForm($table->show());	     	
	$objForm->addToForm($objButton->show());

	 return $objForm->show();

// captcha
        /*$objCaptcha = $this->getObject('captcha', 'utilities');
        $captcha = new textinput('researchform_captcha');
        $captchaLabel = new label($this->objLanguage->languageText('phrase_verifyrequest', 'security', 'Verify Request'), 'input_researchform_captcha');

      	$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
    	$strutil = stripslashes($this->objLanguage->languageText('mod_security_explaincaptcha', 'security', 'To prevent abuse, please enter the code as 	shown below. If you are unable to view the code, click on "Redraw" for a new one.')) . 
	'<br /><div id="researchformcaptchaDiv">' . $objCaptcha->show() . '</div>' . $captcha->show() .
	 $required . '<a href="javascript:researchformredraw();">' . $this->objLanguage->languageText('word_redraw', 'security', 'Redraw') . '</a>';
       	 $objForm->addToForm('<br/><br/>' . $strutil . '<br/><br/>');
         $objForm->addRule('feedback_captcha', $this->objLanguage->languageText
				("mod_request_captcha_unrequired", 'mayibuyeform', 'Captcha cant be empty.Captcha is missing.'), 'required');*/

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
		'emailaddress' =>$email	
        ));
        return $id;
}


private function getFormAction()
{
$formAction = $this->uri(array("action" => "send_researchform"), "mayibuyeform");
        return $formAction;
}

public function Show()
{
return $this->buildForm();

}
}
?>
