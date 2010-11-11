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

    public function getWYSIWYGLabelInsertForm($formName)
    {
        $WYSIWYGLabelInsertForm.="<b>Label HTML ID and Name Menu</b>";
        $WYSIWYGLabelInsertForm.="<div id='labelNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
        $WYSIWYGLabelInsertForm.= $this->buildInsertIdForm('label', $formName,"70")."<br>";
        $WYSIWYGLabelInsertForm.="</div>";
        $WYSIWYGLabelInsertForm.="<b>Label Properties Menu</b>";
        $WYSIWYGLabelInsertForm.="<div id='labelSizeContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
        $WYSIWYGLabelInsertForm.= $this->buildLayoutForm('label', $formName,"label")."<br><br>";
          $WYSIWYGLabelInsertForm.= $this->insertTextForm('label',2,68);
                  $WYSIWYGLabelInsertForm.="</div>";
           return $WYSIWYGLabelInsertForm;
    }
public function getWYSIWYGLabelName()
{
    return $this->LabelName;
}

protected function constructLabelEntity($labelName)
{
  // return $checkboxName;
    $labelParameters = $this->objDBLabelEntity->listLabelParameters($labelName);

//return $checkboxParameters;
foreach($labelParameters as $thislabelParameter){
   //Store the values of the array in variables

   //$checkboxName = $thisCheckboxParameter["checkboxname"];
  $labelFormName = $thislabelParameter["labelname"];
  $labelText = $thislabelParameter["label"];
      $labelBreakspace = $thislabelParameter["breakspace"];


$labelUnderConstruction = new label($labelText,NULL);
$currentConstructedLabel = $labelUnderConstruction->show().$this->getBreakSpaceType($labelBreakspace);
 $constructedLabel .=$currentConstructedLabel;
}

  return  $constructedLabel;
}

protected function deleteLabelEntity($formElementName)
{
    $deleteSuccess = $this->objDBLabelEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}

private function buildWYSIWYGLabelEntity()
{
//   $objElement = new radio('sex_radio');
        $this->objLabel = new label($this->labelValue,NULL);  // this will checked
        //$this->tempWYSIWYGLabel= new label($this->checkboxLabel,  $this->checkboxValue);

        
                return $this->getBreakSpaceType($this->breakspace).$this->objLabel->show();
      //  return $this->checkboxLayoutOption.$this->tempWYSIWYGCheckBox.$this->tempWYSIWYGLabel;


}

public function showWYSIWYGLabelEntity()
{
    return $this->buildWYSIWYGLabelEntity();
}




}
?>
