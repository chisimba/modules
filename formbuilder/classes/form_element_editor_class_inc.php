<?php

class form_element_editor extends object
{
private $formElementDropDown;
    private  $objDBFormMetaDataList;

        private $checkboxConstructor;
    private $buttonConstructor;
    private $datePickerConstructor;
    private $HTMLHeadingConstructor;
    private $labelConstructor;
    private $radioConstructor;
    private $textinputConstructor;
    private $textareaConstructor;
    private $dropdownConstructor;
    private $multiselectDropDownConstructor;
    public function init()
    {
        $this->formElementDropDown = $this->getObject('form_element_inserter','formbuilder');
        $this->objDBFormMetaDataList =$this->getObject('dbformbuilder_form_list','formbuilder');

         $this->checkboxConstructor =  $this->getObject('form_entity_checkbox','formbuilder');
      $this->buttonConstructor = $this->getObject('form_entity_button','formbuilder');
      $this->datePickerConstructor = $this->getObject('form_entity_datepicker','formbuilder');
      $this->HTMLHeadingConstructor = $this->getObject('form_entity_htmlheading','formbuilder');
      $this->labelConstructor =  $this->getObject('form_entity_label','formbuilder');
      $this->radioConstructor = $this->getObject('form_entity_radio','formbuilder');
      $this->textinputConstructor = $this->getObject('form_entity_textinput','formbuilder');
      $this->textareaConstructor = $this->getObject('form_entity_textarea','formbuilder');
      $this->dropdownConstructor = $this->getObject('form_entity_dropdown','formbuilder');
      $this->multiselectDropDownConstructor = $this->getObject('form_entity_multiselect_dropdown','formbuilder');
    }
         private function buildWYSIWYGEditorHeader($formNumber)
         {

        $WYSIWYGEditorHeaderUnderConstruction =    "<div class='ui-widget-content'style='border:0px solid #CCCCCC;padding:0px 20px 0px 20px; margin: 0px 10px 10px 10px; '>";
             $WYSIWYGEditorHeaderUnderConstruction .= "<h2>WYSIWYG Form Editor</h2>";
       $formMetaDataList = $this->objDBFormMetaDataList->getFormMetaData($formNumber);
$formLabel = $formMetaDataList["0"]['label'];
             $WYSIWYGEditorHeaderUnderConstruction .= "<b>Form Number: </b>".$formNumber."<br>";
             $WYSIWYGEditorHeaderUnderConstruction .= "<b>Form Title: </b>".$formLabel."<br><br>";
//             $WYSIWYGEditorHeaderUnderConstruction .= "The toolbar below will allow you to construct your form.<br>
//
//
//Select an item from the drop down list below to insert a form element.";
             $WYSIWYGEditorHeaderUnderConstruction .= "</div>";
//The rearrange form elements toggle button allow you to change the order of your form elements.
// When toggled, click on an element and drag it to a desired postion.<br>
             return $WYSIWYGEditorHeaderUnderConstruction;
         }
    private function buildWYSIWYGToolBar()
    {
                 $toolBarUnderConstrunction = '<span id="toolbar" class="ui-state-default ui-corner-all"style="border:1px solid #CCCCCC;padding:15px 15px 15px 15px; margin: 50px 50px 50px 50px; ">';
     //   $toolBarUnderConstrunction = '<span id="toolbar" class="ui-widget-header ui-corner-all"style="border:1px solid #CCCCCC;padding:15px 15px 15px 15px; margin: 50px 50px 50px 50px; ">';
 $toolBarUnderConstrunction .= "<b>WYSIWYG Form Editor Toolbar</b>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         $toolBarUnderConstrunction .= "Insert A:   ";



         $toolBarUnderConstrunction .=$this->formElementDropDown->showFormElementInserterDropDown()."
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$toolBarUnderConstrunction .= "Rearrange Form Elements : <input type='checkbox' id='sortFormElementsButton' /><label for='sortFormElementsButton'>Toggle</label>
&nbsp;&nbsp;&nbsp;Delete Form Elements : <input type='checkbox' id='deleteFormElementsButton' /><label for='deleteFormElementsButton'>Toggle</label>
&nbsp;&nbsp;&nbsp;<button class='finishDesigningForm' id='toolBarDoneButton'>Done</button>";
   $toolBarUnderConstrunction .= '</span>';
   return $toolBarUnderConstrunction;
         }


         public function showWYSIWYGEditorHeader($formNumber)
         {
return $this->buildWYSIWYGEditorHeader($formNumber);
         }
    public function showWYSIWYGToolBar()
    {
        return $this->buildWYSIWYGToolBar();
    }

    public function showInsertLabelForm($formName)
    {
        return $this->labelConstructor->getWYSIWYGLabelInsertForm($formName);
    }
        public function showInsertHTMLHeadingForm($formName)
    {
        return $this->HTMLHeadingConstructor->getWYSIWYGHTMLHeadingInsertForm($formName);
    }
    public function showInsertTextInputForm($formName)
    {
    return $this->textinputConstructor->getWYSIWYGTextInputInsertForm($formName);
    }
    public function showInsertTextAreaForm($formName)
    {
          return  $this->textareaConstructor->getWYSIWYGTextAreaInsertForm($formName);
    }

    public function showInsertDatePickerForm($formName)
    {
    return $this->datePickerConstructor->getWYSIWYGDatePickerInsertForm($formName);
    }
    public function showInsertCheckboxForm($formName)
    {
    return $this->checkboxConstructor->getWYSIWYGCheckBoxInsertForm($formName);
    }

    public function showInsertRadioForm($formName)
    {
    return $this->radioConstructor->getWYSIWYGRadioInsertForm($formName);
    }

    public function showInsertDropDownForm($formName)
    {
        return $this->dropdownConstructor->getWYSIWYGDropDownInsertForm($formName);
    }
      public function showInsertMSDropDownForm($formName)
      {
    return $this->multiselectDropDownConstructor->getWYSIWYGMSDropDownInsertForm($formName);
      }
        public function showInsertButtonForm($formName)
      {
    //return $this->multiselectDropDownConstructor->getWYSIWYGMSDropDownInsertForm($formName);
    return $this->buttonConstructor->getWYSIWYGButtonInsertForm($formName);
      }
}

?>
