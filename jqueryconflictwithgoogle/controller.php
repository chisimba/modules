<?php

class jqueryconflictwithgoogle extends controller
{


    public function  init()
    {

    }

  public function dispatch()
    {
   //Get action from query string and set default to view
   $action=$this->getParam('action', 'homePage');
   //Convert the action into a method
   $method = $this->__getMethod($action);
   //Return the template determined by the method resulting from action
   return $this->$method();

        }
        private function __validAction(& $action)
 {
   if (method_exists($this, "__".$action)) {
     return TRUE;
   } else {
     return FALSE;
   }
 }
 private function __getMethod(& $action)
 {
     if ($this->__validAction($action)) {
         return "__" . $action;
     } else {
         return "__actionError";
     }
 }

 private function __homePage()
 {
     return "home.php";
 }

 private function  __loadDialogBoxWithChisimbaHeader()
 {
     ///This is to make sure that no version jQuery library gets loaded
     ///to prevent any sort of conflict.
     ///I will load my own jQuery library in the template file
     $this->setVar('JQUERY_VERSION','0.0');

    $str ="There will be no dialog box that will pop up in Chrome like I have been saying.";
    $this->setVar('str',$str);

     return "test.php";
 }
 private function __loadDialogBoxWithChisimbaHeaderSuppressed()
 {
          ///This is to make sure that no version jQuery library gets loaded
     ///to prevent any sort of conflict.
     ///I will load my own jQuery library in the template file
     $this->setVar('JQUERY_VERSION','0.0');

     ///This is to suppress the chisimba header.
     ///See the templates/page/suppressChisimbaHeader.php
      $this->setPageTemplate('suppressChisimbaHeader.php');

    $str ="With the Chisimba Header Suppressed. The dialog box will
        function properly in all web browsers like I have been saying.";
    $this->setVar('str',$str);
     return "test.php";
 }
}

?>
