<?php
// include_once 'chisimba_modules_handler_class_inc.php';
 // include_once 'language_module_class_inc.php';
  // include_once 'object_core_module_class_inc.php';
include_once 'core_object_module_class_inc.php';
class editmessage extends object
{
 //public $objLanguage;
 private $objouputtext;
 private $objDBComment;
private $objbuildform;
private $objform;
private $objTitle;
private $objLabel;
private $objTextArea;
private $objBuildButton;
private $SaveButton;
private $objObject;
private $objGoBackButon;
private $messagesTable;
private $messagesHandler;
private $GoBackButton;
private $objLink;
//private $objObject;
//private $objbuildform;
//private $obj;
 public function init()
 {
     $this->objObject = new core_object_module('core_object_module','hosportal');
//    $this->objObject = new object_core_module('object_core_module','hosportal');
//     $n = new A();
  //Instantiate the language object
 // $this->objLanguage = $this->getObject('language','language');
  $this->objouputtext =$this->getObject('language_module','hosportal');
  //$this->objObject->instantiateObjectFromClass('language_module','hosportal');
// $this->objObject->instantiateObjectFromClass($this->objouputtext,'language_module','hosportal');
  //Load the DB object
  $this->objDBComment = $this->getObject('dbhosportal_messages','hosportal');
  //Load form object
  $this->objbuildform = $this->getObject('form_module','hosportal');
  //Load text input object
  $this->objTitle = $this->getObject('textinput_module','hosportal');
  //Load label object
  $this->objLabel = $this->getObject('label_module','hosportal');
  //load text area object
  $this->objTextArea = $this->getObject('htmlarea_module','hosportal');
  //load button object
  $this->objBuildButton = $this->getObject('button_module','hosportal');

    $this->objbuildform = $this->getObject('form_module','hosportal');
         //instatiate HTML table module object
  $this->objHTMLTable = $this->getObject('htmltable_module','hosportal');
 $this->messagesHandler= $this->getObject('messages_handler','hosportal');
   //instatiate link object
  $this->objLink = $this->getObject('link_module','hosportal');
 }

 private function loadElements()
 {
  //Load the form class
  //$this->loadClass('form','htmlelements');
  //Load the textinput class
  //$this->loadClass('textinput','htmlelements');
  //Load the label class
 // $this->loadClass('label','htmlelements');
  //Load the textarea class
  //$this->loadClass('textarea','htmlelements');
  //Load the button object
//  $this->loadClass('button','htmlelements');
 }
 private function buildForm()
 {
       //$this->loadElements();
       //Create the form
       $this->objform=$this->objbuildform->createNewObjectFromModule('messages',$this->getFormAction());
      // $objForm = new form('comments', $this->getFormAction());
      $id = $this->getParam('id');
      //If id is not empty, get the comment details
       if (!empty($id)){
        //Fetch the data
        $commentData = $this->objDBComment->listSingle($id);
        $title = $commentData[0]["title"];
        $comment = $commentData[0]["commenttxt"];
        $noofreplies = $commentData[0]["replies"];
        $unreplied = $commentData[0]["unreplied"];
       }else{
        $title = "";
        $comment = "";
        $noofreplies = 0;
        $unreplied = TRUE;

       }

        //Next we add the text box for the title of the comment to the form with;

        //...........TEXT INPUT.......................
        //Create a new textinput for the title of the comment
        if ($unreplied==TRUE)
            {
       $objTitleField = $this->objTitle->createNewObjectFromModule('title',$title);
        //$objTitle = new textinput('title', $title);
        //Create a new label for the text labels
       //  $titlelabel = new label ()
        //$titlelabel = new label ($this->objLanguage->languagetext("mod_hosportal_commenttitle","hosportal"),"title");
       // $titlelabel = new label ($this->objouputtext->insertTextFromConfigFile("mod_hosportal_commenttitle"),"title");
       
        $titlelabel = $this->objLabel->createNewObjectFromModule($this->objouputtext->insertTextFromConfigFile("mod_hosportal_commenttitle"),"title");
        //$titlelabel = new label ($this->objouputtext->insert_text_from_config_file(),"title");
       // $titlelabel = new label ($this->objouputtext->insert_text_from_config_file("mod_hosportal_commenttext","hosportal"),"title");
       $this->objform= $this->objbuildform->addObjectToForm($titlelabel->show(). "<br />");
       // $objForm->addToForm($titlelabel->show() . "<br />");
       $this->objform= $this->objbuildform->addObjectToForm($objTitleField->show() . "<br />");
      //  $this->objform->addToForm($objTitle->show() . "<br />");
            }
            else
                {
//                $this->obj = $this->loadClass('htmlheading','htmlelements');
//                $this->obj = $this->newObject($title);
//                //$this->obj = new htmlheading($title);
//               // $this->newObject('htmlheading','htmlelements');s
//                define($static_title, $title);
                                 //$objTitleField = $this->objTitle->createNewObjectFromModule('title',$title);
                               $objTitleField = $this->objTitle->createNewObjectFromModule('title',$title);
                $titlelabel = $this->objLabel->createNewObjectFromModule($this->objouputtext->insertTextFromConfigFile("mod_hosportal_commenttitle"),"title");
                 // $titlelabel = $this->objLabel->createNewObjectFromModule($title,"title");
                $this->objform= $this->objbuildform->addObjectToForm($titlelabel->show(). "<br />");
                $this->objform= $this->objbuildform->addObjectToForm($title . "<br />");
                //$this->objform= $this->objbuildform->addObjectToForm($objTitleField->show(). "<br />");
//                 $this->messagesTable = $this->objHTMLTable->createNewObjectFromModule("htmltable", "htmlelements");
////  $commentsTable = $this->newObject("htmltable", "htmlelements");
//  //Define the table border
//   $this->messagesTable = $this->objHTMLTable->setBorderThickness(0);
////  $commentsTable->border = 0;
//  //Set the table spacing
//     $this->messagesTable = $this->objHTMLTable->setCellPadding(12);
////  $commentsTable->cellspacing = '12';
//  //Set the table width
//       $this->messagesTable = $this->objHTMLTable->setCellWidth("60%");
//
//        $this->messagesTable = $this->objHTMLTable->beginTableRow();
//   $this->messagesTable = $this->objHTMLTable->addCellwithObject($title);
//      $this->messagesTable = $this->objHTMLTable->endTableRow();
//
//        $this->objForm= $this->objbuildform->addObjectToForm( $this->messagesTable = $this->objHTMLTable->showBuiltTable());


            }
        //Create a new label for the text labels
        //----------TEXTAREA--------------
        //Create a new textarea for the comment text
       //$objCommenttxt = $this->objTextArea->createNewObjectFromModule('commenttxt', $comment);
      // $objHTMLArea= $this->loadClass('htmlarea','htmlelements');
     //  $objHTMLArea= $this->newObject('htmlarea','htmlelements');

       $objHTMLArea = $this->objTextArea->createNewObjectFromModule('htmlarea','htmlelements');
       $objHTMLArea = $this->objTextArea->setInputVariableForHTMLArea($comment);
              $objHTMLArea = $this->objTextArea->setHTMLAreaName('commenttxt');
              $objHTMLArea = $this->objTextArea->setToolBarType();
             // $objHTMLArea = $this->objTextArea->showHTMLArea();
       // $objHTMLArea->setContent($comment);
       //  $objHTMLArea->setName('commenttxt');
       // $objCommenttxt = new textarea('commenttxt', $comment);
       // $cmtLabel = new label($this->objouputtext->insertTextFromConfigFile("mod_hosportal_commenttext"),"commenttxt");
        $cmtLabel = $this->objLabel->createNewObjectFromModule($this->objouputtext->insertTextFromConfigFile("mod_hosportal_commenttext"),"commenttxt");
        //$cmtLabel = new label($this->objLanguage->languageText("mod_hosportal_commenttext","hosportal"),"commenttxt");
        $this->objform= $this->objbuildform->addObjectToForm($cmtLabel->show() . "<br />");
       // $this->objform= $this->objbuildform->addObjectToForm($objCommenttxt = $this->objTextArea->showHTMLArea() . "<br />");
       $this->objform= $this->objbuildform->addObjectToForm($objHTMLArea = $this->objTextArea->showHTMLArea() . "<br />");
        $this->objform= $this->objbuildform->addObjectToForm($noofreplies. "<br />");
        $this->objform= $this->objbuildform->addObjectToForm( $unreplied. "<br />");
        //$this->objform= $this->objbuildform->addObjectToForm($title. "<br />");
       // $this->objForm->addToForm($cmtLabel->show() . "<br />");
       // $objForm->addToForm($objCommenttxt->show() . "<br />");
        //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
         $this->GoBackButton = $this->objBuildButton->createNewObjectFromModule('back');
       //new button('save');
        //$objButton = new button('save');
        // Set the button type to submit
      // $this->GoBackButton = $this->objBuildButton->buttonSetToSubmit();
       // $objButton->setToSubmit();
        // Use the language object to label button
        // with the word save
        $this->GoBackButton=$this->objBuildButton->setButtonLabel('  '.$this->objouputtext->insertTextFromConfigFile("mod_hosportal_goback").'  ');
       // $objButton->setValue(' '.$this->objLanguage->languageText("mod_hosportal_savecomment", "hosportal").' ');
      //  $this->objform= $this->objbuildform->addObjectToForm($this->objBuildButton->showButton());

    //=========================================================================================================================

        //$iconViewSelect = $this->objIcon->setIconType('view');

  // $iconEdSelect = $this->getObject('geticon','htmlelements');
   //$iconEdSelect->setIcon('edit');
 // $iconViewSelect = $this->objIcon->setAltTextForIcon($this->objouputtext->insertTextFromConfigFile("mod_hosportal_viewmore"));
  // $iconEdSelect->alt = "Edit Comment";
     // public function createNewObjectFromModule($url_in_array_format= NULL , $a = NULL)
    $mngGoBacklink = $this->objLink->createNewObjectFromModule($this->uri(array(
    'module'=>'hosportal',
    'action'=>'view'
   )));
//   $mngedlink = new link($this->uri(array(
//    'module'=>'hosportal',
//    'action'=>'edit',
//    'id' => $id
//   )));

   $mngGoBacklink = $this->objLink->embedLinkToObject($this->objBuildButton->showButton()) ;
   //$mngedlink->link = $iconEdSelect = $this->objIcon->showIcon();

   $this->objform= $this->objbuildform->addObjectToForm( $mngGoBacklink = $this->objLink->showLink());


       $this->SaveButton = $this->objBuildButton->createNewObjectFromModule('save');
       //new button('save');
        //$objButton = new button('save');
        // Set the button type to submit
       $this->SaveButton = $this->objBuildButton->buttonSetToSubmit();
       // $objButton->setToSubmit();
        // Use the language object to label button
        // with the word save
        $this->SaveButton=$this->objBuildButton->setButtonLabel('  '.$this->objouputtext->insertTextFromConfigFile("mod_hosportal_savecomment").'  ');
       // $objButton->setValue(' '.$this->objLanguage->languageText("mod_hosportal_savecomment", "hosportal").' ');
        $this->objform= $this->objbuildform->addObjectToForm($this->objBuildButton->showButton());
       // $this->objform->addToForm($objButton->show());
        //$objCommenttxt = new textarea('commenttxt');
      	// return $this->objForm->show();
//        $this->objGoBackButon = $this->objBuildButton->createNewObjectFromModule('goBack');
//        $this->objGoBackButon = $this->objBuildButton->

        return $this->objform = $this->objbuildform->showBuiltForm();
 }

 private function getFormAction()
 {
  //Get the action to determine if its add or edit
  $action = $this->getParam("action", "add");
  if ($action == "edit")
      {
   //Get the comment id and pass to uri
   $id = $this->getParam('id');
  //  $title = $this->getParam('title');
   // $comments = $this->getParam('commenttxt');
//        $commentData = $this->objDBComment->listSingle($id);
//       $id = $commentData[0]["id"];
//        $title = $commentData[0]["title"];
//        $comments = $commentData[0]["commenttxt"];
//        $noofreplies = $commentData[0]["replies"];
//        $unreplied = $commentData[0]["unreplied"];
////   //$replies = $this->getParam("replies");
////
//         if (!empty($comments))
//           {
//echo 'sdsddddsfsdfddfsddfsdfsdf';
  $formAction = $this->uri(array("action" => "update", "id"=>$id), "hosportal" );

           }
//       if (empty($comments))
//       {
////echo 'sdsddddsfsdfddfsddfsdfsdf';
//  return      $formAction = $this->uri(array("action" => "errormessage", "id"=>$id), "hosportal" );
//     //   $formAction = $this->uri(array("action" => "edit", "id"=>$id), "hosportal" );
//
//       }

  

  if ($action == "reply")
      {
        // $id = $this->getParam("id");

         $id = $this->getParam('id');


      $formAction = $this->uri(array("action" => "addreply","id"=>$id), "hosportal");
  }
//    if ($action == "errormessage")
//      {
//         $id = $this->getParam("id");
//      $formAction = $this->uri(array("action" => "update","id"=>$id), "hosportal");
//  }
  if ($action == "add")
      {
 $id = $this->getParam("id");


     $formAction = $this->uri(array("action" => "addnew"), "hosportal");
  }
   if ($action == "editreply")
      {
         $id = $this->getParam("id");
  $formAction = $this->uri(array("action" => "updatereply","id"=>$id), "hosportal");
  }
 return $formAction;
 }

 public function show()
 {
  return $this->buildForm();
 }

}

?>
