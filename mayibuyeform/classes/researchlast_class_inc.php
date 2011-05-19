<?php

class researchlast extends dbTable
{
   	public $objLanguage;
	


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
 
	$table->startRow();      
	$objsubheadingLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentsublabel3","mayibuyeform"),"subheading");
	$table->addCell($objsubheadingLabel->show(),'', 'center', 'left', ''); 
	$table->endRow();

	$table->startRow();	
	$objuwc= new textinput('uwc');
	$objuwcLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentuwc","mayibuyeform"),"uwc");
	$table->addCell($objuwcLabel->show(),'', 'center', 'left', '');
	$table->addCell($objuwc->show(),'', 'center', 'left', '');
	$table->endRow();

	$table->startRow();
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
	
	$table->startRow();
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
	$objButton->setValue(' ' . $this->objLanguage->languageText("mod_mayibuye_commentnext", "mayibuyeform") . '');
	$table->endRow();
	$objForm->addToForm($table->show());	     	
	$objForm->addToForm($objButton->show());

	 return $objForm->show();



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

	$objForm->addToForm($objCaptcha->show());







}

function insertStudentRecord($stuno,$staffnum,$colection,$image,$project,$time)
	 {
           $id = $this->insert(array(
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
$formAction = $this->uri(array("action" => "send_researchlast"), "mayibuyeform");
        return $formAction;
}

public function Show()
{
return $this->buildForm();

}
}
?>





















?>
