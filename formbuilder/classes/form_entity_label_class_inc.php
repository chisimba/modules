<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_label extends form_entity_handler
{
    private $objLabel;

    private $LabelName;
    private $labelValue;
    private $breakspace;
   


    
    private $tempWYSIWYGLabelValue;
    private $tempWYSIWYGLabel;
    
    protected $objDBLabelEntity;

    public function  init()
    {

$this->loadClass('label','htmlelements');
 $this->objDBLabelEntity = $this->getObject('dbformbuilder_label_entity','formbuilder');

                }

    public function createFormElement($labelName,$label,$breakSpace)
    {

         if ($this->objDBLabelEntity->checkDuplicateLabelEntry($labelName,$label) == TRUE)
         {

             $this->LabelName = $labelName;
             $this->labelValue = $label;
             $this->breakspace = $breakSpace;
             $this->objDBLabelEntity->insertSingle($labelName,$label,$breakSpace);

        return TRUE;
         }
         else
         {
             return FALSE;
         }
         
    }
public function getLabelName()
{
    return $this->LabelName;
}

private function buildWYSIWYGLabelEntity()
{
//   $objElement = new radio('sex_radio');
        $this->objLabel = new label($this->labelValue,NULL);  // this will checked
        //$this->tempWYSIWYGLabel= new label($this->checkboxLabel,  $this->checkboxValue);

        
                return $this->objLabel->show().$this->getBreakSpaceType($this->breakspace);
      //  return $this->checkboxLayoutOption.$this->tempWYSIWYGCheckBox.$this->tempWYSIWYGLabel;


}

public function showWYSIWYGLabelEntity()
{
    return $this->buildWYSIWYGLabelEntity();
}




}
?>
