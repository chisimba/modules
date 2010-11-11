<?php
class add_form_parameters_form extends object
{
 public $objLanguage;
 
 public $objDBComment;
 
 public function init()
 {
  //Instantiate the language object
  $this->objLanguage = $this->getObject('language','language');
  //Load the DB object
  $this->objDBFormList = $this->getObject('dbformbuilder_form_list','formbuilder');
 }
 private function loadElements()
 {
  //Load the form class
  $this->loadClass('form','htmlelements');
  //Load the textinput class
  $this->loadClass('textinput','htmlelements');
  //Load the label class
  $this->loadClass('label','htmlelements');
  //Load the textarea class
  $this->loadClass('textarea','htmlelements'); 
  //Load the button object
  $this->loadClass('button','htmlelements');

  $this->loadClass('validator','htmlelements');

  $this->loadClass('htmlheading','htmlelements');

  $this->loadClass('geticon','htmlelements');

    $this->loadClass('hiddeninput','htmlelements');

    $this->loadClass('radio','htmlelements');
        $this->loadClass('dropdown','htmlelements');
 }
 private function buildForm()
 {
       $this->loadElements();
       //Create the form
       $objForm = new form('formDetails', $this->getFormAction());
       $formNumber = $this->getParam('formNumber');
       //If form numberid is not empty, get the comment details
       if (!empty($formNumber)){
        //Fetch the data
        $formMetaData = $this->objDBFormList->listSingle($formNumber);
        $formExistingDatabaseName = $formMetaData[0]["name"];
        $formExistingTitle = $formMetaData[0]["label"];
             $formExistingDescription = $formMetaData[0]["details"];
                  $existingUsersEmailAddress = $formMetaData[0]["submissionemailaddress"];
                       $existingSubmissionOption = $formMetaData[0]["submissionoption"];

       }else{
        $formExistingDatabaseName = "";
        $formExistingTitle = "";
                     $formExistingDescription = "";
                  $existingUsersEmailAddress = "";
                       $existingSubmissionOption = "";


       }

$formTitle = new htmlheading($this->objLanguage->languagetext("mod_formbuilder_addformtitle","formbuilder"),  3);
$objForm->addToForm($formTitle->show());
if ($formNumber==NULL)
{
$temp_form_number_array = array('FORMNUMBER' => $this->objDBFormList->getCurrentFormNumber());

}
else
{
    $temp_form_number_array = array('FORMNUMBER' => $formNumber);
}
$formNumberIndentifier = $this->objLanguage->code2Txt("mod_formbuilder_addformnumberindentifier","formbuilder",$temp_form_number_array,"Error. Number Not Available.");
$objForm->addToForm($formNumberIndentifier. "<br><Br>");

//$formNumberNotifier = $this->objLanguage->languagetext("mod_formbuilder_addformnumbermessage","formbuilder");
//$objForm->addToForm($formNumberNotifier. "<br><br>");

$formDatabaseNameLabel = new label ($this->objLanguage->languagetext("mod_formbuilder_addformname","formbuilder"),"formTitle");
$formDataBaseName = new textinput('formTitle', $formExistingDatabaseName, 'text', '70');
$formDataBaseName->setCss("text ui-widget-content ui-corner-all");
$iconFormName = $this->getObject('geticon','htmlelements');
$iconFormName->setIcon('help_small');
$iconFormName->extra= "id=formNameIcon";
$iconFormName->alt="This name will be used as a unique identifier for your form.";


//$objForm->addToForm($formDatabaseNameLabel->show() . "<br />");
//$objForm->addToForm($formDataBaseName->show() ."&nbsp;&nbsp;".$iconFormName->show()."<br />");

$formTitleLabel = new label ("Form Title:","formLabel");
$formTitle = new textinput('formLabel', $formExistingTitle, 'text', '70');
$formTitle->setCss("text ui-widget-content ui-corner-all");
$iconFormLabel = $this->getObject('geticon','htmlelements');
$iconFormLabel->setIcon('failed');
$iconFormLabel->extra= "id=formLabelIcon";
$iconFormLabel->alt="A null field is not allowed.";
$objForm->addToForm($formTitleLabel->show() . "<br />");
$objForm->addToForm($formTitle->show() ."<br />");

$formEmailLabel = new label ("Enter your e-mail address:","formEmail");
$formEmail = new textinput('formEmail', $existingUsersEmailAddress, 'text', '70');
$formEmail->setCss("text ui-widget-content ui-corner-all");
$iconFormEmail = $this->getObject('geticon','htmlelements');
$iconFormEmail->setIcon('failed');
$iconFormEmail->extra= "id=formLabelIcon";
$iconFormEmail->alt="A null field is not allowed.";
$objForm->addToForm($formEmailLabel->show() . "<br />");
$objForm->addToForm($formEmail->show() ."<br />");

$formSubmissionLabel = new label ("Select what to do with the submit results from your form:","formSubmissionRadio");
$formSubmissionRadio = new dropdown('formSubmissionRadio');
$formSubmissionRadio->addOption('save_in_database',$this->objLanguage->languagetext("mod_formbuilder_addformsubmitresultsindatabase","formbuilder"));
$formSubmissionRadio->addOption('send_email',"Email the results to email address entered above");
$formSubmissionRadio->addOption('both',"Save the results in the database AND email the results to me");
if ( $existingSubmissionOption == "")
{
$formSubmissionRadio->setSelected('both');
}
else
{
  $formSubmissionRadio->setSelected($existingSubmissionOption);
}
//$formSubmissionRadio->setBreakSpace("<br>");
$objForm->addToForm($formSubmissionLabel->show() . "<br />");
$objForm->addToForm("<div id='formSubmissionRadio'>". $formSubmissionRadio->show() ."</div><br>");

//	$objForm->addToForm("	<div id='formSubmissionRadio'>
//
//<input type='radio' id='radio1' name='formSubmissionResults' /><label for='radio1'>Choice 1</label><br>
//			<input type='radio' id='radio2' name='formSubmissionResults' checked='checked' /><label for='radio2'>Choice 2</label><br>
//			<input type='radio' id='radio3' name='formSubmissionResults' /><label for='radio3'>Choice 3</label><br>
//		</div><br>");


$formDescriptionLabel = new label ("Briefly describe your form below.<br> This will be the first thing displayed to users who fill in the form.","formCaption");
$formDesciption = new textarea('formCaption',$formExistingDescription,5,70);
$formDesciption->setCssClass("text ui-widget-content ui-corner-all");
$iconFormDescription = $this->getObject('geticon','htmlelements');
$iconFormDescription->setIcon('failed');
$iconFormDescription->extra= "id=formDescriptionIcon";
$iconFormDescription->alt="A null field is not allowed.";
$objForm->addToForm($formDescriptionLabel->show() . "<br />");
$objForm->addToForm($formDesciption->show()  ."&nbsp;&nbsp;". "<br />");


$formNumber = new hiddeninput('formNumber', null);
$formNumber->extra ="id=formNumberHiddenInput";
$objForm->addToForm($formNumber->show() . "<br />");
$submitButton = new button('submitNewFormDetails');
$submitButton->setIconClass(decline);
$submitButton->setValue('Submit General Form Details');
//$submitButton->setToSubmit();
//$objForm->addToForm($submitButton->show() . "<br />");


      	 return $objForm->show();	
 }

 private function getFormAction()
 {
  //Get the action to determine if its add or edit
  $action = $this->getParam("action", "addFormParameters");
 
if ($action == "editFormParameters") {
    $currentformNumber= $this->getParam('formNumber');
    $formAction = $this->uri(array("action" => "updateExistingFormParameters", "currentformNumber"=>$currentformNumber), "formbuilder");
}
else
{
    $formAction = $this->uri(array("action" => "designWYSIWYGForm"), "formbuilder");
}
//  
//   //Get the comment id and pass to uri
//   $id = $this->getParam("id");
//   $formAction = $this->uri(array("action" => "update", "id"=>$id), "helloforms" );
//  } else {
//   $formAction = $this->uri(array("action" => "addnew"), "helloforms");
//  }
 
  return $formAction;
 }
 public function show()
 {
  return $this->buildForm();
 }

}
?>


