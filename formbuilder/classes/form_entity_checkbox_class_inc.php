<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_checkbox extends form_entity_handler
{
    private $objCheckbox;

    private $checkboxName;
    private $checkboxValue;
    private $checkboxLabel;
    private $isCheckedBoolean;
    private  $checkboxLayoutOption;
private $tempWYSIWYGCheckBox;

    protected  $objcheckBoxEntity;
    private $objDBcheckboxEntity;

    
    private $tempWYSIWYGValue;
    private $tempWYSIWYGLabel;
    
  

    public function  init()
    {
$this->loadClass('checkbox','htmlelements');
$this->loadClass('label','htmlelements');
 $this->objDBcheckboxEntity = $this->getObject('dbformbuilder_checkbox_entity','formbuilder');

                }

    public function createFormElement($checkboxName,$checkboxValue,$checkboxLabel,$isChecked,$breakSpace)
    {

         if ($this->objDBcheckboxEntity->checkDuplicateCheckboxEntry($checkboxName,$checkboxValue) == TRUE)
         {

             $this->checkboxValue = $checkboxValue;
        $this->checkboxName = $checkboxName;
        $this->checkboxLabel = $checkboxLabel;
        $this->isCheckedBoolean = $isChecked;
        $this->checkboxLayoutOption=$breakSpace;
        $this->objDBcheckboxEntity->insertSingle($checkboxName,$checkboxValue,$checkboxLabel,$isChecked,$breakSpace);
        return TRUE;
         }
         else
         {
             return FALSE;
         }
         
    }
public function getcheckboxName()
{
    return $this->checkboxName;
}

private function buildWYSIWYGCheckboxEntity()
{
//   $objElement = new radio('sex_radio');
        $this->tempWYSIWYGCheckBox = new checkbox($this->checkboxValue,  $this->checkboxLabel,  $this->isCheckedBoolean);  // this will checked
        $this->tempWYSIWYGLabel= new label($this->checkboxLabel,  $this->checkboxValue);

        
                return $this->getBreakSpaceType($this->checkboxLayoutOption).$this->tempWYSIWYGCheckBox->show().$this->tempWYSIWYGLabel->show();

      //  return $this->checkboxLayoutOption.$this->tempWYSIWYGCheckBox.$this->tempWYSIWYGLabel;


}

public function showWYSIWYGCheckboxEntity()
{
    return $this->buildWYSIWYGCheckboxEntity();
}




}
?>
