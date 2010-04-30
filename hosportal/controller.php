<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
die("you cannot view this page directly");
}
/**
*
*Controller class for helloforms module. this class
*greets the user with a hello message. This is a demonstration
*of the Chisimba MVC architecture.
*
*@author Paul Mungai
*@copyright(c) 2005 GNU GPL
*@package hellochisimba
*@version 1
*
*/
class hosportal extends controller
{
/**
*
*Standard Chisimba constructor to set the default value of the
*$greeting properly
*
*@access public
*
*/


//public $objlanguage;

//public $objDBComments;
//public $objMessagesHandler;
private $objModuleHandler;

public function init()
{
// //Instantiate the language object
//  $this->objLanguage = $this->getObject('language','language');
//  //Instantiate the DB Object
//  $this->objDBComments = $this->getObject('dbhosportal_messages','hosportal');
  //$this->objMessagesHandler = $this->getObject('messages_handler','hosportal');
  $this->objModuleHandler = $this->getObject('modulehandler','hosportal');

}
/**
*
*Standard controller dispatch method, the dispatch calls any
*method involving logic and hands of the results to the template for display.
*
*@access public
*
*/
 public function dispatch()
 {
   //Get action from query string and set default to view
//   $action=$this->getParam('action', 'home');
//   //Convert the action into a method
//   $method = $this->__getMethod($action);
//   //Return the template determined by the method resulting from action
//   return $this->$method();
 //    return "editadd_tpl.php";
  //   return "listall_tpl.php";
    // $action = $this->getParam('action', 'home');
     return $this->objModuleHandler->manageEvents();
     //return $this->objMessagesHandler->messagesCurrentAction();
   //  $this->nextAction("view");
   //  return $this->$this->nextAction("view");
 }
// private function __validAction(& $action)
// {
//   if (method_exists($this, "__".$action)) {
//     return TRUE;
//   } else {
//     return FALSE;
//   }
// }
// private function __getMethod(& $action)
// {
//     if ($this->__validAction($action)) {
//         return "__" . $action;
//     } else {
//         return "__actionError";
//     }
// }
// private function __actionError()
// {
//     //Get action from query string
//     $action=$this->getParam('action');
//     $this->setVar('str', "<h3>"
//       . $this->objLanguage->languageText("phrase_unrecognizedaction")
//       .": " . $action . "</h3>");
//     return 'dump_tpl.php';
// }
// private function __add()
// {
//     return 'editadd_tpl.php';
// }
// private function __view()
// {
//     return 'listall_tpl.php';
// }
// private function __addnew()
// {
//    //Use getParam to fetch form data
//    $title = $this->getParam('title');
//    $comments = $this->getParam('commenttxt');
//    //Insert the data to DB
//    $id = $this->objDBComments->insertSingle($title,$comments);
//    return 'listall_tpl.php';
// }
// private function __edit()
// {
//    $id = $this->getParam('id');
//    $this->setVar('id', $id);
//    return "editadd_tpl.php";
// }
// private function __update()
// {
//    //Get the form data
//    $id = $this->getParam('id');
//    $title = $this->getParam('title');
//    $comments = $this->getParam('commenttxt');
//    //Update the comment
//    $id = $this->objDBComments->updateSingle($id,$title,$comments);
//    return "listall_tpl.php";
// }
// private function __delete()
// {
//    //Get the form data
//    $id = $this->getParam('id');
//    //Delete the comment
//    $id = $this->objDBComments->deleteSingle($id);
//    return "listall_tpl.php";
// }
}
?>