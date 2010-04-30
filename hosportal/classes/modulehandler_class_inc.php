<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class modulehandler extends controller
{

private $objMessagesHandler;
private $objLanguage;
    public function init()
    {
   
$this->objLanguage = $this->getObject('language_module','hosportal');
 $this->objMessagesHandler = $this->getObject('messages_handler','hosportal');
    }

    public function manageEvents()
    {

   //    $this->objMessagesHandler = $this->getObject('messages_handler','hosportal');
   // return $this->objMessagesHandler->messagesCurrentAction();
//           //Get action from query string and set default to view
 //    $action = $this->getParam('action', 'home');
//   $method = $this->__getMethod($action);
//   return $this->$method();
   //Convert the action into a method
 //  $method = $this->__getMethod($action);
    // return $this->objMessagesHandler->messagesCurrentAction();
           $action=$this->getParam('action', 'home');
   //Convert the action into a method
   $method = $this->__getMethod($action);
   //Return the template determined by the method resulting from action

   return $this->$method($action);
//        switch ($action)
//        {
//            case 'home': return $this->__homes();
//                break;
//            case 'gotoforum': return $this->objMessagesHandler->messagesCurrentAction();
//                break;
//            default:
//                return $this->__homes();
//                break;
//        }
      //return $this->__home();
    }

private function __validAction(& $action)
 {
   if (method_exists($this, "__".$action)) {
     return TRUE;
   } else {
     return FALSE;
   }
 }
 private function __getMethod( $action)
 {
     if ($this->__validAction($action)) {
         return "__" . $action;
     } else {
         return "__view";
     }
 }
// private function __actionError()
// {
//     //Get action from query string
//     $action=$this->getParam('action');
//     $this->setVar('str', "<h3>"
//       . $this->objLanguage->insertTextFromConfigFile("mod_hosportal_unrecognized")
//       ." : ". $action . "</h3>");
//     return 'dump_tpl.php';
// }

 public function __home($action)
 {

 $txtFile=$this->getParam('textFile');
$myFile = "packages/hosportal/text files/$txtFile.txt";
$fh = fopen($myFile, 'r');
$theData = fread($fh, filesize($myFile));
fclose($fh);
$this->setVar('theData', $theData);

     return 'home_tpl.php';
 }

  public function __outputPage($action)
 {

// $txtFile=$this->getParam('textFile');
//$myFile = "/var/www/chisimba/packages/hosportal/text files/$txtFile.txt";
//$fh = fopen($myFile, 'r');
//$theData = fread($fh, filesize($myFile));
//fclose($fh);
//$this->setVar('theData', $theData);
//
//     return 'page_tpl.php';
 }
 
  public function __view($action)
 {
//$this->nextAction('view');
      

   return $this->objMessagesHandler->messagesCurrentAction($action);
     //return 'home_tpl.php';
 }



}

?>