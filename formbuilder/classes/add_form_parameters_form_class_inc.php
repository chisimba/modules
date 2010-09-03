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
 }
 private function buildForm()
 {
       $this->loadElements();
       //Create the form
       $objForm = new form('formDetails', $this->getFormAction());
//       $id = $this->getParam('id');
//       //If id is not empty, get the comment details
//       if (!empty($id)){
//        //Fetch the data
//        $commentData = $this->objDBComment->listSingle($id);
//        $title = $commentData[0]["title"];
//        $comment = $commentData[0]["commenttxt"];
//       }else{
//        $title = "";
//        $comment = "";
//       }
$formTitle = new htmlheading($this->objLanguage->languagetext("mod_formbuilder_addformtitle","formbuilder"),  1);
$objForm->addToForm($formTitle->show() . "<br>");

$temp_form_number_array = array('FORMNUMBER' => $this->objDBFormList->getCurrentFormNumber());
$formNumberIndentifier = $this->objLanguage->code2Txt("mod_formbuilder_addformnumberindentifier","formbuilder",$temp_form_number_array,"Error. Number Not Available.");
$objForm->addToForm($formNumberIndentifier. "<br>");

$formNumberNotifier = $this->objLanguage->languagetext("mod_formbuilder_addformnumbermessage","formbuilder");
$objForm->addToForm($formNumberNotifier. "<br><br>");

$formDatabaseNameLabel = new label ($this->objLanguage->languagetext("mod_formbuilder_addformname","formbuilder"),"formTitle");
$formDataBaseName = new textinput('formTitle', "", 'text', '70');
$iconFormName = $this->getObject('geticon','htmlelements');
$iconFormName->setIcon('failed');
$iconFormName->extra= "id=formNameIcon";
$iconFormName->alt="A null field is not allowed.";


$objForm->addToForm($formDatabaseNameLabel->show() . "<br />");
$objForm->addToForm($formDataBaseName->show() ."&nbsp;&nbsp;".$iconFormName->show()."<br />");

$formTitleLabel = new label ($this->objLanguage->languagetext("mod_formbuilder_addformlabel","formbuilder"),"formLabel");
$formTitle = new textinput('formLabel', "", 'text', '70');
$iconFormLabel = $this->getObject('geticon','htmlelements');
$iconFormLabel->setIcon('failed');
$iconFormLabel->extra= "id=formLabelIcon";
$iconFormLabel->alt="A null field is not allowed.";
$objForm->addToForm($formTitleLabel->show() . "<br />");
$objForm->addToForm($formTitle->show() ."&nbsp;&nbsp;". $iconFormLabel->show()."<br />");

$formDescriptionLabel = new label ($this->objLanguage->languagetext("mod_formbuilder_addformcaption","formbuilder"),"formCaption");
$formDesciption = new textarea('formCaption','',5,70);
$iconFormDescription = $this->getObject('geticon','htmlelements');
$iconFormDescription->setIcon('failed');
$iconFormDescription->extra= "id=formDescriptionIcon";
$iconFormDescription->alt="A null field is not allowed.";
$objForm->addToForm($formDescriptionLabel->show() . "<br />");
$objForm->addToForm($formDesciption->show()  ."&nbsp;&nbsp;".$iconFormDescription->show(). "<br />");


$formNumber = new hiddeninput('formNumber', null);
$formNumber->extra ="id=formNumberHiddenInput";
$objForm->addToForm($formNumber->show() . "<br />");
$submitButton = new button('submitNewFormDetails');
$submitButton->setIconClass('decline');
$submitButton->setValue('Submit General Form Details');
//$submitButton->setToSubmit();
$objForm->addToForm($submitButton->show() . "<br />");


      	 return $objForm->show();	
 }

 private function getFormAction()
 {
  //Get the action to determine if its add or edit
//  $action = $this->getParam("action", "add");
//  if ($action == "edit") {
//   //Get the comment id and pass to uri
//   $id = $this->getParam("id");
//   $formAction = $this->uri(array("action" => "update", "id"=>$id), "helloforms" );
//  } else {
//   $formAction = $this->uri(array("action" => "addnew"), "helloforms");
//  }
  $formAction = $this->uri(array("action" => "buildform"), "formbuilder");
  return $formAction;
 }
 public function show()
 {
  return $this->buildForm();
 }

}
?>


