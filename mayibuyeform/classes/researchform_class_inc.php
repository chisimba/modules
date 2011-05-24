
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

}

private function buildForm()

{
   $this->loadElements();
   	$objForm = new form('researchform', $this->getFormAction());
	$table = $this->newObject('htmltable', 'htmlelements');
	$form = new form ('register', $this->uri(array('action'=>'register')));
	$required = '<span class="required_field"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
 
 //Create a new label for the text labels
	$titleLabel = new label($this->objLanguage->languageText("mod_mayibuyeform_commenttitle","mayibuyeform"),"title");
	$table->addCell($titleLabel->show(),'', 'center', 'left', '');
	

	$table->startRow();
	$objdate = new textinput('date');
	$objdateLabel =  new label($this->objLanguage->languageText("mod_mayibuye_commentdate","mayibuyeform"),"date"); 
	$table->addCell($objdateLabel->show(),'', 'center', 'left', '');
	//$table->addCell('&nbsp;', 2);
	$table->addCell($objdate->show().$required);
	$objForm->addRule('date', $this->objLanguage->languageText("mod_mayibuye_date_required", "mayibuyeform"), 'required');
        $table->endRow();

        $table->startRow();
	$objnameofresearcher = new textinput('name_resign');
	$objnameofReseacherLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentnameofresearch","mayibuyeform"),"name_resign");
	$table->addCell($objnameofReseacherLabel->show(),'', 'center', 'left', '');
	$table->addCell($objnameofresearcher->show().$required);
	$objForm->addRule('name_resign', $this->objLanguage->languageText("mod_mayibuye_name_required", "mayibuyeform"), 'required');
        $table->endRow();

        $table->StartRow();
	$objTelno = new textinput('tellno');
	$objTelLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commenttelno","mayibuyeform"),"Telno");
	$table->addCell($objTelLabel->show(),'', 'center', 'left', '');
	$table->addCell($objTelno->show().$required);
	$objForm->addRule('tellno', $this->objLanguage->languageText("mod_mayibuye_tell_required", "mayibuyeform"), 'required');
	$table->EndRow();

	
	$table->startRow();	
	$objFaxno = new textinput('faxno');
	$objFaxLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentfaxno","mayibuyeform"),"faxno");
	$table->addCell($objFaxLabel->show(),'', 'center', 'left', '');
	$table->addCell($objFaxno->show());
	$table->endRow(); 
	
	$table->StartRow();
	$objEmailaddress = new textinput('emailaddress');
	$objEmailaddressLabel = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentemailaddress","mayibuyeform"),"email");
	$table->addCell($objEmailaddressLabel->show(),'', 'center', 'left', '');
	$table->addCell($objEmailaddress->show().$required);
 	$objForm->addRule('emailaddress', 'Not a valid Email', 'email');
	$table->endRow(); 
	

	$fieldset = $this->newObject('fieldset', 'htmlelements');
	$fieldset->legend = $this->objLanguage->languageText('phrase_accountdetails', 'userregistration', 'Researcher Details');
	$fieldset->contents = $table->show();

	$objForm->addToForm($fieldset->show());
   
	$button = new button ('submitform', 'Continue');
	$button->setToSubmit();
        //$objForm->addToForm($table->show()); 	
	$objForm->addToForm('<p align="center"><br />'.$button->show().'</p>');


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
