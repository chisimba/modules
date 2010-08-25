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
    
public function getDatePickerName()
{
    return $this->dpName;
}

public function getDatePickerValue()
{
    return $this->dpValue;
}

public function insertDatePickerParameters($formElementName,$formElementValue,$defaultDate,$dateFormat)
{
  //  $a['color'] = 'red';
    if ($this->objDBdpEntity->checkDuplicateDatepickerEntry($formElementName,$formElementValue) == TRUE)
    {
        $this->objDBdpEntity->insertSingle($formElementName,$formElementValue,$defaultDate,$dateFormat);

        $this->dpName = $formElementName;
        $this->dpValue = $formElementValue;


    return TRUE;
    }
 else {
        return FALSE;
    }
}

public function buildWYSIWYGDatepickerEntity($value)
{
//   $objElement = new radio('sex_radio');
$dpParameters = $this->objDBdpEntity->listDatePickerParameters($value);

  foreach($dpParameters as $thisDPParameter){
   //Store the values of the array in variables

   $dpName = $thisDDParameter["datepickername"];
   $dpValue = $thisDDParameter["datepickervalue"];
   $defaultDate = $thisDDParameter["defaultdate"];
   $dateFormat = $thisDDParameter["dateFormat"];



  }
  $this->objDP = $this->newObject('datepicker', 'htmlelements');
$this->objDP->name = $dpValue;
$this->objDP->setDefaultDate($defaultDate);
$this->objDP->setDateFormat($dateFormat);
$this->objDP->show();
               return $this->objDP;
}

public function showWYSIWYGDatepickerEntity()
{
    return $this->dpName;
   // return $this->buildWYSIWYGDatepickerEntity();
}




}
?>
