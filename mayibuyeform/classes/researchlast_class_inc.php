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

}

private function buildForm()

{
   $this->loadElements();
   	$objForm = new form('researchform', $this->getFormAction());
	$table = $this->newObject('htmltable', 'htmlelements');
 
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
