<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_datepicker extends form_entity_handler
{
    private $objDP;
    private $dpName;
        private $dpValue;
        private $defaultDate;
        private $dpDateFormat;

   // private $ddLabelnOptionArray;

    //private $tempWYSIWYGBoolDefaultSelected;
    //private $tempWYSIWYGLayoutOption;
    protected  $objDBdpEntity;
  

    public function  init()
    {
        $this->loadClass('datepicker','htmlelements');
//        $this->objRadio = new radio('WYSIWIG radio object');

        $this->dpName = NULL;
        $this->dpValue=NULL;
        $this->dpDateFormat=NULL;
        $this->defaultDate=NULL;

        $this->objDBdpEntity = $this->getObject('dbformbuilder_datepicker_entity','formbuilder');
        //$this->tempWYSIWYGBoolDefaultSelected=FALSE;
                }

    public function createFormElement($elementName="",$elementValue="")
    {
        $this->dpName = $elementName;
        $this->dpValue = $elementValue;
        $this->objDP = $this->newObject('datepicker', 'htmlelements'); 
    }
    
public function getWYSIWYGDatePickerName()
{
    return $this->dpName;
}

protected function getDatePickerName($dpFormName)
{
$dpParameters = $this->objDBdpEntity->listDatePickerParameters($dpFormName);
 $dpNameArray= array();
  foreach($dpParameters as $thisDPParameter){
   //Store the values of the array in variables

 $dpName = $thisDPParameter["datepickervalue"];
 $dpNameArray[]= $dpName;
  }
  return $dpNameArray;
}

public function getDatePickerValue()
{
    return $this->dpValue;
}
public function getWYSIWYGDatePickerInsertForm($formName)
    {
  $WYSIWYGDatePickerInsertForm="<b>Date Picker HTML ID and Name Menu</b>";
  $WYSIWYGDatePickerInsertForm.="<div id='dpIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
  $WYSIWYGDatePickerInsertForm.= $this->buildInsertIdForm('datepicker',$formName,"70")."<br>";
  $WYSIWYGDatePickerInsertForm.= $this->buildInsertFormElementNameForm('datepicker', "70")."<br>";
    $WYSIWYGDatePickerInsertForm.= "</div>";
  //  $WYSIWYGDatePickerInsertForm.="<b>Date Picker Set Up Options Menu</b>";
  //$WYSIWYGDatePickerInsertForm.="<div id='dpOptionsContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
  $WYSIWYGDatePickerInsertForm.= $this->insertDatePickerFormParameters();
 //     $WYSIWYGDatePickerInsertForm.= "</div>";
           return   $WYSIWYGDatePickerInsertForm;
    }
public function insertDatePickerParameters($formElementName,$formElementValue,$defaultDate,$dateFormat)
{

    if ($this->objDBdpEntity->checkDuplicateDatepickerEntry($formElementName,$formElementValue) == TRUE)
    {
        $this->objDBdpEntity->insertSingle($formElementName,$formElementValue,$defaultDate,$dateFormat);

        $this->dpName = $formElementName;
        $this->dpValue = $formElementValue;
        $this->defaultDate=$defaultDate;
        $this->dpDateFormat=$dateFormat;

    return TRUE;
    }
 else {
        return FALSE;
    }
}

protected function deleteDatePickerEntity($formElementName)
{
    $deleteSuccess = $this->objDBdpEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}

protected function constructDatePickerEntity($dpName)
{

$dpParameters = $this->objDBdpEntity->listDatePickerParameters($dpName);

  foreach($dpParameters as $thisDPParameter){
   //Store the values of the array in variables

 $dpName = $thisDPParameter["datepickername"];
 $dpValue = $thisDPParameter["datepickervalue"];
 $defaultDate = $thisDPParameter["defaultdate"];
$dateFormat = $thisDPParameter["dateformat"];
 $datePicker = $this->newObject('datepicker', 'htmlelements');
 $datePicker->name = $dpValue;
 if ($defaultDate != "Real Time Date")
 {
 $datePicker->setDefaultDate($defaultDate);
 }
 $datePicker->setDateFormat($dateFormat);
$currentConstructedDatePicker = $datePicker->show();
$constructedDatePicker .= $currentConstructedDatePicker;
  }


 return $constructedDatePicker;
             


}

public function buildWYSIWYGDatepickerEntity()
{

$dpParameters = $this->objDBdpEntity->listDatePickerParameters($this->dpName);

$this->objDP->name = $this->dpValue;
 if ($this->defaultDate != "Real Time Date")
 {
$this->objDP->setDefaultDate($this->defaultDate);
 }

$this->objDP->setDateFormat($this->dpDateFormat);
return $this->objDP->show();

//        $this->defaultDate=$defaultDate;
//        $this->dpDateFormat=$dateFormat;
//  foreach($dpParameters as $thisDPParameter){
//   //Store the values of the array in variables
//
//   $dpName = $thisDDParameter["datepickername"];
//   $dpValue = $thisDDParameter["datepickervalue"];
//   $defaultDate = $thisDDParameter["defaultdate"];
//   $dateFormat = $thisDDParameter["dateFormat"];
//


 // }

}

public function showWYSIWYGDatepickerEntity()
{
    return $this->dpName;
   // return $this->buildWYSIWYGDatepickerEntity();
}




}
?>
