<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_dropdown extends form_entity_handler
{
    private $objDD;
    private $ddName;
        private $ddValue;
        private $defaultValue;
        private $ddLabel;

    private $ddLabelnOptionArray;

    //private $tempWYSIWYGBoolDefaultSelected;
    //private $tempWYSIWYGLayoutOption;
    protected  $objDBddEntity;
  

    public function  init()
    {
        $this->loadClass('dropdown','htmlelements');
//        $this->objRadio = new radio('WYSIWIG radio object');

        $this->ddName = NULL;
        $this->ddLabelnOptionArray=array();
        $this->ddLabel= NULL;
        $this->objDBddEntity = $this->getObject('dbformbuilder_dropdown_entity','formbuilder');
        //$this->tempWYSIWYGBoolDefaultSelected=FALSE;
                }

    public function createFormElement($elementName="")
    {
        $this->ddName = $elementName;
        $this->objDD = new dropdown($elementName);
      
        
    }
public function getDropdownName()
{
    return $this->ddName;
}

public function insertOptionandValue($formElementName,$option,$value,$defaultSelected)
{
  //  $a['color'] = 'red';
    if ($this->objDBddEntity->checkDuplicateDropdownEntry($formElementName,$value) == TRUE)
    {
        $this->objDBddEntity->insertSingle($formElementName,$option,$value,$defaultSelected);

        $this->ddName = $formElementName;


    return TRUE;
    }
 else {
        return FALSE;
    }
}

private function buildWYSIWYGDropdownEntity()
{
//   $objElement = new radio('sex_radio');
$ddParameters = $this->objDBddEntity->listDropdownParameters($this->ddName);

  foreach($ddParameters as $thisDDParameter){
   //Store the values of the array in variables

   $ddValue = $thisDDParameter["ddoptionvalue"];
   $ddOptionLabel = $thisDDParameter["ddoptionlabel"];
   $defaultValue = $thisDDParameter["defaultvalue"];

$this->objDD->addOption($ddValue,$ddOptionLabel);
        if ($defaultValue == TRUE)
  {
      $this->objDD->setSelected($ddValue);
  }
  }
               return $this->objDD->show();
}

public function showWYSIWYGDropdownEntity()
{
    return $this->buildWYSIWYGDropdownEntity();
}




}
?>
