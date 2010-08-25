<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_radio extends form_entity_handler
{
    private $objRadio;
    private $radioName;
    private $breakSpaceType;
    private $labelnOptionArray;

    private $tempWYSIWYGBoolDefaultSelected;
    private $tempWYSIWYGLayoutOption;
    protected  $objDBRadioEntity;
  

    public function  init()
    {
        $this->loadClass('radio','htmlelements');
//        $this->objRadio = new radio('WYSIWIG radio object');
        $this->breakSpaceType=NULL;
        $this->radioName = NULL;
        $this->labelnOptionArray=array();
        $this->objDBRadioEntity = $this->getObject('dbformbuilder_radio_entity','formbuilder');
        $this->tempWYSIWYGBoolDefaultSelected=FALSE;
                }

    public function createFormElement($elementName="")
    {
        $this->radioName = $elementName;
        $this->objRadio = new radio($elementName);
      
        
    }
public function getRadioName()
{
    return $this->radioName;
}


public function setBreakSpaceType($breakSpaceType)
{
    $this->breakSpaceType =$breakSpaceType;
}

public function insertOptionandValue($formElementName,$option,$value,$defaultSelected,$layoutOption)
{
  //  $a['color'] = 'red';
    if ($this->objDBRadioEntity->checkDuplicateRadioEntry($formElementName,$value) == TRUE)
    {
      $this->objDBRadioEntity->insertSingle($this->radioName,$option,$value,$defaultSelected,$layoutOption);
    $this->labelnOptionArray[$value]=$option;
    $this->tempWYSIWYGBoolDefaultSelected=$defaultSelected;
    $this->tempWYSIWYGLayoutOption=$layoutOption;
    return TRUE;
    }
 else {
        return FALSE;
    }
}

private function buildWYSIWYGRadioEntity()
{
//   $objElement = new radio('sex_radio');
  foreach ($this->labelnOptionArray as $radioValue => $radioOptionLabel) {
  
      $this->objRadio->addOption($radioValue,$radioOptionLabel);
        if ($this->tempWYSIWYGBoolDefaultSelected==TRUE)
  {
      $this->objRadio->setSelected($radioValue);
  }
  }


               return $this->getBreakSpaceType($this->tempWYSIWYGLayoutOption).$this->objRadio->show();
}

public function showWYSIWYGRadioEntity()
{
    return $this->buildWYSIWYGRadioEntity();
}




}
?>
