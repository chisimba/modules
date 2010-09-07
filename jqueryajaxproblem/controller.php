<?php

class jqueryajaxproblem extends controller
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
          $this->setVar('JQUERY_VERSION','1.4.2');
     return "home.php";
 }
 private function __produceRadio()
 {
       $this->setPageTemplate('ajax_template.php');
     return "spitRadio.php";
 }
 private function __produceDatePicker()
 {
     $this->setPageTemplate('ajax_template.php');
     return "spitDatePicker.php";
 }

 private function __produceTextInput()
 {
        $this->setPageTemplate('ajax_template.php');
     return "spitTextInput.php";
 }
}

?>