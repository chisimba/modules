<?php

class formbuilder extends controller
{
  //  private $objdatabase;
public $objlanguage;	
    public function init()
    {
         $this->objLanguage = $this->getObject('language','language');
        //$this->objdatabase = $this->getObject('dbformbuilder_radio_entity','formbuilder');
    }

    public function dispatch()
    {
   //Get action from query string and set default to view
   $action=$this->getParam('action', 'addFormParameters');
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
 private function __actionError()
 {
     //Get action from query string
     $action=$this->getParam('action');
     $this->setVar('str', "<h3>"
       . $this->objLanguage->languageText("phrase_unrecognizedaction")
       .": " . $action . "</h3>");
     return 'dump_tpl.php';
 }

 private function __addFormParameters()
 {
     return "add_edit_form_parameters.php";
 }
 private function __addNewFormParameters()
 {
     return "add_new_form_parameters.php";
 }
 private function __buildForm()
 {
   //  $this->objdatabase->insertSingle("radioane","radiolabel","adiooptionvalue","default slue","breakspace");
     return 'form_element_editor.php';
 }
 private function __view()
 {
     return 'output.php';
 }

 private function __test()
 {
return 'test.php';
 }

  private function __nexttest()
 {
return 'nexttest.php';
 }
 private function __ajax()
 {
     return 'ajax.php';
 }

 private function __ajaxnew()
 {
     return 'ajaxnew.php';
 }
 private function __hello()
 {
  //   $this->loadClass('radio','htmlelements');
  //    $objElement = new radio('sex_radio');

                          //      $objElement->addOption('m','Male');

if( $_REQUEST["uname"] )
{

   $name = $_REQUEST['uname'];
 //  $objElement->addOption('m',$name);
 //$objElement->show();
}
$this->setVar("name", $name);
     return 'hello.php';
 }

 private function __helloajax()
 {
     return 'helloajax.php';
 }
 private function __testajax()
 {
     return 'testajax.php';
 }

 private function __helloajaxnew()
 {
     return'helloajaxnew.php';
 }

 private function __createNewFormElement()
 {
     return "create_new_form_element.php";
 }

 private function __addEditRadioEntity()
 {
     return "add_edit_radio_entity.php";
 }
 private  function __addEditCheckboxEntity()
 {
          return "add_edit_checkbox_entity.php";
 }
  private  function __addEditDropdownEntity()
 {
          return "add_edit_dropdown_entity.php";
 }

  private  function __addEditLabelEntity()
  {
      return "add_edit_label_entity.php";
  }
  private function __addEditHTMLHeadingEntity()
  {
      return "add_edit_HTMLheading_entity.php";
  }
  private function __addEditDatePickerEntity()
  {
         return "add_edit_datepicker_entity.php";
  }
  private function __addEditTextInput()
  {
      return "add_edit_textinput_entity.php";
  }
    private function __addEditButton()
  {
      return "add_edit_button_entity.php";
  }
  private function __conflict()
  {
      return "conflicttest.php";
  }
  private function __addEditTextArea()
  {
      return "add_edit_textarea_entity.php";
  }
}


?>
