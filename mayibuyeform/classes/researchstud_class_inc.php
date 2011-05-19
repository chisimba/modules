<?php

class researchstud extends dbTable
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
   	$objForm = new form('researchft', $this->getFormAction());
	$table = $this->newObject('htmltable', 'htmlelements');

	$table->startRow();
       	$objvatLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentsubheading","mayibuyeform"),"heading");
	$table->addCell($objvatLabel->show(),'', 'center', 'left', '');
	$table->endRow(); 

	$table->startRow();
	$objname = new textinput('name');
	$objnameLabel = new label($this->objLanguage->LanguageText("mod_mayibuye_commentname2","mayibuyeform"),"name");
	$table->addCell($objnameLabel->show(),'', 'center', 'left', '');
	$table->addCell($objname->show(),'', 'center', 'left', '');
	$table->endRow();
	
	$table->startRow();
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
	$table->startRow();
	
	$table->startRow();
	$objFaxno3 = new textinput('faxno_3');
	$objFax3Label = new label($this->objLanguage->LanguageText("mod_mayibuyeform_commentfaxno","mayibuyeform"),"faxno_3");
	$table->addCell($objFax3Label->show(),'', 'center', 'left', '');
	$table->addCell($objFaxno3->show(),'', 'center', 'left', '');
	$table->startRow();
	
	
	//Submit button
        $table->startRow();
	$objButton = new button('send');
      	$objButton->setToSubmit();
	$objButton->setValue(' ' . $this->objLanguage->languageText("mod_mayibuye_commentnext", "mayibuyeform") . '');
	$table->endRow();
	$objForm->addToForm($table->show());	     	
	$objForm->addToForm($objButton->show());

	 return $objForm->show();
}

function insertResearchRecord($nameofresi,$jotitle,$organizationname,$postadd,$tel,$faxx,$stuno,$staffnum,
				$colection,$image,$project,$time)
	 {
           $id = $this->insert(array(
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
$formAction = $this->uri(array("action" => "send_researchstud"), "mayibuyeform");
        return $formAction;
}

public function Show()
{
return $this->buildForm();

}
}
?>
