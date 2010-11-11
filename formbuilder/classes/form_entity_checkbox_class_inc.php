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

    public function createFormElement($checkboxName,$checkboxValue,$checkboxLabel,$isChecked,$breakSpace,$formElementLabel,$labelLayout)
    {

         if ($this->objDBcheckboxEntity->checkDuplicateCheckboxEntry($checkboxName,$checkboxValue) == TRUE)
         {

             $this->checkboxValue = $checkboxValue;
        $this->checkboxName = $checkboxName;
        $this->checkboxLabel = $checkboxLabel;
        $this->isCheckedBoolean = $isChecked;
        $this->checkboxLayoutOption=$breakSpace;

        $this->objDBcheckboxEntity->insertSingle($checkboxName,$checkboxValue,$checkboxLabel,$isChecked,$breakSpace,$formElementLabel,$labelLayout);
        return TRUE;
         }
         else
         {
             return FALSE;
         }
         
    }
public function getWYSIWYGCheckboxName()
{
    return $this->checkboxName;
    
}
protected function getCheckboxName($checkboxFormName)
{
    $checkboxParameters = $this->objDBcheckboxEntity->listCheckboxParameters($checkboxFormName);
  $checkboxNameArray= array();
  foreach($checkboxParameters as $thisCheckboxParameter){
   //Store the values of the array in variables

   //$checkboxName = $thisCheckboxParameter["checkboxname"];
  $checkboxValue = $thisCheckboxParameter["checkboxvalue"];
$checkboxNameArray[]=  $checkboxValue;
  }
  return $checkboxNameArray;
}
 public function getWYSIWYGCheckBoxInsertForm($formName)
    {
     $WYSIWYGCheckBoxInsertForm="<b>Check Box HTML ID and Name Menu</b>";
     $WYSIWYGCheckBoxInsertForm.="<div id='checkboxIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
     $WYSIWYGCheckBoxInsertForm.= $this->buildInsertIdForm('checkbox',$formName,"70")."";
          $WYSIWYGCheckBoxInsertForm.="</div>";
          $WYSIWYGCheckBoxInsertForm.= "<b>Check Box Label Menu</b>";
          $WYSIWYGCheckBoxInsertForm.="<div id='checkboxLabelContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
          $WYSIWYGCheckBoxInsertForm.= $this->insertFormLabelOptions("checkbox","labelOrientation");
          $WYSIWYGCheckBoxInsertForm.= "</div>";
          $WYSIWYGCheckBoxInsertForm.= "<b>Check Box Option Layout Menu</b>";
          $WYSIWYGCheckBoxInsertForm.= "<div id='checkboxLayoutContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
     $WYSIWYGCheckBoxInsertForm.= $this->buildLayoutForm('checkbox', $formName,"checkbox")."";
               $WYSIWYGCheckBoxInsertForm.= "</div>";
               $WYSIWYGCheckBoxInsertForm.="<b>Insert Check Box Options Menu</b>";
               $WYSIWYGCheckBoxInsertForm.="<div id='checkboxOptionAndValueContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
     $WYSIWYGCheckBoxInsertForm.= $this->insertOptionAndValueForm('checkbox', 0)."";
                    $WYSIWYGCheckBoxInsertForm.= "</div>";

           return        $WYSIWYGCheckBoxInsertForm;
    }
protected function deleteCheckBoxEntity($formElementName)
{
    $deleteSuccess = $this->objDBcheckboxEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}

protected function constructCheckBoxEntity($checkboxName)
{
  // return $checkboxName;
    $checkboxParameters = $this->objDBcheckboxEntity->listCheckboxParameters($checkboxName);

//return $checkboxParameters;
foreach($checkboxParameters as $thisCheckboxParameter){
   //Store the values of the array in variables

   //$checkboxName = $thisCheckboxParameter["checkboxname"];
  $checkboxValue = $thisCheckboxParameter["checkboxvalue"];
  $checkboxLabel = $thisCheckboxParameter["checkboxlabel"];
      $isChecked = $thisCheckboxParameter["ischecked"];
      $breakspace = $thisCheckboxParameter["breakspace"];
         $checkBoxLabel = $thisCheckboxParameter["label"];
               $labelOrientation = $thisCheckboxParameter["labelorientation"];

$checkboxUnderConstruction = new checkbox($checkboxValue, $checkboxLabel, $isChecked);
$labelUnderConstruction = new label($checkboxLabel,$checkboxValue );
$currentConstructedCheckbox = $this->getBreakSpaceType($breakspace).$checkboxUnderConstruction->show().$labelUnderConstruction->show();
 $constructedCheckbox .=$currentConstructedCheckbox;
}
 // return "checkbod";
 // return $constructedCheckbox;
    if (  $checkBoxLabel==NULL)
      {
  return "<div id='".$checkboxName."'>".$constructedCheckbox."</div>";
      }
 else {
                $checkboxLabel = new label ($checkBoxLabel,$checkboxValue);
     switch ($labelOrientation) {
                case 'top':
  return "<div id='".$checkboxName."'><div class='checkboxLabelContainer' style='clear:both;'> ".$checkboxLabel->show()."</div>"
        ."<div class='checkboxContainer'style='clear:left;'> ".$constructedCheckbox."</div></div>";
break;
                case 'bottom':
  return "<div id='".$checkboxName."'><div class='checkboxContainer'style='clear:both;'> ".$constructedCheckbox."</div>".
                        "<div class='checkboxLabelContainer' style='clear:both;'> ".$checkboxLabel->show()."</div></div>";
break;
                case 'left':
  return  "<div id='".$checkboxName."'><div style='clear:both;overflow:auto;'>"."<div class='checkboxLabelContainer' style='float:left;clear:left;'> ".$checkboxLabel->show()."</div>"
        ."<div class='checkboxContainer'style='float:left; clear:right;'> ".$constructedCheckbox."</div></div></div>";
break;
                case 'right':
  return "<div id='".$checkboxName."'><div style='clear:both;overflow:auto;'>"."<div class='checkboxContainer'style='float:left;clear:left;'> ".$constructedCheckbox."</div>".
                        "<div class='checkboxLabelContainer' style='float:left;clear:right;'> ".$checkboxLabel->show()."</div></div></div>";
break;
                 }
      }
}



private function buildWYSIWYGCheckboxEntity()
{

    $checkboxParameters = $this->objDBcheckboxEntity->listCheckboxParameters($this->checkboxName);
    foreach($checkboxParameters as $thisCheckboxParameter){
   //Store the values of the array in variables

   //$checkboxName = $thisCheckboxParameter["checkboxname"];
  $checkboxValue = $thisCheckboxParameter["checkboxvalue"];
  $checkboxLabel = $thisCheckboxParameter["checkboxlabel"];
      $isChecked = $thisCheckboxParameter["ischecked"];
      $breakspace = $thisCheckboxParameter["breakspace"];
         $checkBoxLabel = $thisCheckboxParameter["label"];
               $labelOrientation = $thisCheckboxParameter["labelorientation"];

$checkboxUnderConstruction = new checkbox($checkboxValue, $checkboxLabel, $isChecked);
$labelUnderConstruction = new label($checkboxLabel,$checkboxValue );
$currentConstructedCheckbox = $this->getBreakSpaceType($breakspace).$checkboxUnderConstruction->show().$labelUnderConstruction->show();
 $constructedCheckbox .=$currentConstructedCheckbox;
}
  //return $constructedCheckbox;


    if (  $checkBoxLabel==NULL)
      {
  return "<div id='".$this->checkboxName."'>".$constructedCheckbox."</div>";
      }
 else {
                $checkboxLabel = new label ($checkBoxLabel,$checkboxValue);
     switch ($labelOrientation) {
                case 'top':
  return "<div id='".$this->checkboxName."'><div class='checkboxLabelContainer' style='clear:both;'> ".$checkboxLabel->show()."</div>"
        ."<div class='checkboxContainer'style='clear:left;'> ".$constructedCheckbox."</div></div>";
break;
                case 'bottom':
  return "<div id='".$this->checkboxName."'><div class='checkboxContainer'style='clear:both;'> ".$constructedCheckbox."</div>".
                        "<div class='checkboxLabelContainer' style='clear:both;'> ".$checkboxLabel->show()."</div></div>";
break;
                case 'left':
  return  "<div id='".$this->checkboxName."'><div style='clear:both;overflow:auto;'>"."<div class='checkboxLabelContainer' style='float:left;clear:left;'> ".$checkboxLabel->show()."</div>"
        ."<div class='checkboxContainer'style='float:left; clear:right;'> ".$constructedCheckbox."</div></div></div>";
break;
                case 'right':
  return "<div id='".$this->checkboxName."'><div style='clear:both;overflow:auto;'>"."<div class='checkboxContainer'style='float:left;clear:left;'> ".$constructedCheckbox."</div>".
                        "<div class='checkboxLabelContainer' style='float:left;clear:right;'> ".$checkboxLabel->show()."</div></div></div>";
break;
                 }
      }


//    $this->tempWYSIWYGCheckBox = new checkbox($this->checkboxValue,  $this->checkboxLabel,  $this->isCheckedBoolean);  // this will checked
//        $this->tempWYSIWYGLabel= new label($this->checkboxLabel,  $this->checkboxValue);
//                return $this->getBreakSpaceType($this->checkboxLayoutOption).$this->tempWYSIWYGCheckBox->show().$this->tempWYSIWYGLabel->show();




}

public function showWYSIWYGCheckboxEntity()
{
    return $this->buildWYSIWYGCheckboxEntity();
}




}
?>
