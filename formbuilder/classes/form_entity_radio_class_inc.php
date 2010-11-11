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
public function getWYSIWYGRadioName()
{
    return $this->radioName;
}
 public function getWYSIWYGRadioInsertForm($formName)
    {
     $WYSIWYGRadioInsertForm="<b>Radio HTML ID and Name Menu</b>";
     $WYSIWYGRadioInsertForm.="<div id='radioIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
    $WYSIWYGRadioInsertForm.=$this->buildInsertIdForm('radio',$formName,"70")."";
         $WYSIWYGRadioInsertForm.="</div>";
         $WYSIWYGRadioInsertForm.= "<b>Radio Label Menu</b>";
         $WYSIWYGRadioInsertForm.="<div id='radioLabelContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
         $WYSIWYGRadioInsertForm.=  $this->insertFormLabelOptions("radio","labelOrientation");
         $WYSIWYGRadioInsertForm.=  "</div>";
          $WYSIWYGRadioInsertForm.="<b>Radio Option Layout Menu</b>";
     $WYSIWYGRadioInsertForm.="<div id='radioLayoutContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
    $WYSIWYGRadioInsertForm.=$this->buildLayoutForm('radio option', $formName,"radio")."";
             $WYSIWYGRadioInsertForm.="</div>";
             $WYSIWYGRadioInsertForm.="<b>Insert Radio Options Menu</b>";
             $WYSIWYGRadioInsertForm.="<div id='radioOptionAndValueContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
    $WYSIWYGRadioInsertForm.=$this->insertOptionAndValueForm('radio', 0)."";
                 $WYSIWYGRadioInsertForm.="</div>";

           return          $WYSIWYGRadioInsertForm;
    }
protected function getRadioName($radioFormName)
{
   $radioParameters = $this->objDBRadioEntity->listRadioParameters($radioFormName);
      return $radioName = $radioParameters["0"]['radioname'];
}
public function setBreakSpaceType($breakSpaceType)
{
    $this->breakSpaceType =$breakSpaceType;
}

public function insertOptionandValue($formElementName,$option,$value,$defaultSelected,$layoutOption,$formElementLabel,$labelOrientation)
{
  //  $a['color'] = 'red';
    if ($this->objDBRadioEntity->checkDuplicateRadioEntry($formElementName,$value) == TRUE)
    {
      $this->objDBRadioEntity->insertSingle($this->radioName,$option,$value,$defaultSelected,$layoutOption,$formElementLabel,$labelOrientation);
    $this->labelnOptionArray[$value]=$option;
    $this->tempWYSIWYGBoolDefaultSelected=$defaultSelected;
    $this->tempWYSIWYGLayoutOption=$layoutOption;

    return TRUE;
    }
 else {
        return FALSE;
    }
}

protected function deleteRadioEntity($formElementName)
{
    $deleteSuccess = $this->objDBRadioEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}

protected function constructRadioEntity($radioName)
{
  // return $checkboxName;
    $radioParameters = $this->objDBRadioEntity->listRadioParameters($radioName);
//$radioName = $radioParameters["radioname"]["0"];


foreach($radioParameters as $thisradioParameter){
   //Store the values of the array in variables

   $radioName = $thisradioParameter["radioname"];
  $radioOptionLabel = $thisradioParameter["radiooptionlabel"];
  $radioOptionValue = $thisradioParameter["radiooptionvalue"];
      $defaultValue = $thisradioParameter["defaultvalue"];
      $breakspace = $thisradioParameter["breakspace"];
            $formElementLabel = $thisradioParameter["label"];
                        $labelOrientation = $thisradioParameter["labelorientation"];

      $radioUnderConstruction = new radio($radioName);
$radioUnderConstruction->addOption($radioOptionValue,$radioOptionLabel);
if ($defaultValue == TRUE)
{
   $radioUnderConstruction->setSelected($radioOptionValue);
}



$currentConstructedRadio = $this->getBreakSpaceType($breakspace).$radioUnderConstruction->show();
 $constructedRadio .=$currentConstructedRadio;
}

   if (  $formElementLabel==NULL)
      {
return "<div id='".$radioName."'>".$constructedRadio."</div>" ;
      }
 else {
                $radioLabel = new label ($formElementLabel,$radioName);
     switch ($labelOrientation) {
                case 'top':
  return "<div id='".$radioName."'><div class='radioLabelContainer' style='clear:both;'> ".$radioLabel->show()."</div>"
        ."<div class='radioContainer'style='clear:left;'> ".$constructedRadio."</div></div>";
break;
                case 'bottom':
  return "<div id='".$radioName."'><div class='radioContainer'style='clear:both;'> ".$constructedRadio."</div>".
                        "<div class='radioLabelContainer' style='clear:both;'> ".$radioLabel->show()."</div></div>";
break;
                case 'left':
  return  "<div id='".$radioName."'><div style='clear:both;overflow:auto;'>"."<div class='radioLabelContainer' style='float:left;clear:left;'> ".$radioLabel->show()."</div>"
        ."<div class='radioContainer'style='float:left; clear:right;'> ".$constructedRadio."</div></div></div>";
break;
                case 'right':
  return "<div id='".$radioName."'><div style='clear:both;overflow:auto;'>"."<div class='radioContainer'style='float:left;clear:left;'> ".$constructedRadio."</div>".
                        "<div class='radioLabelContainer' style='float:left;clear:right;'> ".$radioLabel->show()."</div></div></div>";
break;
                 }
      }
}

private function buildWYSIWYGRadioEntity()
{
//   $objElement = new radio('sex_radio');
     $radioParameters = $this->objDBRadioEntity->listRadioParameters($this->radioName);

      foreach($radioParameters as $thisradioParameter){
   $radioName = $thisradioParameter["radioname"];
  $radioOptionLabel = $thisradioParameter["radiooptionlabel"];
  $radioOptionValue = $thisradioParameter["radiooptionvalue"];
      $defaultValue = $thisradioParameter["defaultvalue"];
      $breakspace = $thisradioParameter["breakspace"];
                  $formElementLabel = $thisradioParameter["label"];
                        $labelOrientation = $thisradioParameter["labelorientation"];
      $this->objRadio = new radio($radioName);
$this->objRadio->addOption($radioOptionValue,$radioOptionLabel);
if ($defaultValue == TRUE)
{
$this->objRadio->setSelected($radioOptionValue);
}



$currentConstructedRadio = $this->getBreakSpaceType($breakspace).$this->objRadio->show();
 $constructedRadio .=$currentConstructedRadio;
}



  if (  $formElementLabel==NULL)
      {
return "<div id='".$this->radioName."'>".$constructedRadio."</div>" ;
      }
 else {
                $radioLabel = new label ($formElementLabel,$this->radioName);
     switch ($labelOrientation) {
                case 'top':
  return "<div id='".$this->radioName."'><div class='radioLabelContainer' style='clear:both;'> ".$radioLabel->show()."</div>"
        ."<div class='radioContainer'style='clear:left;'> ".$constructedRadio."</div></div>";
break;
                case 'bottom':
  return "<div id='".$this->radioName."'><div class='radioContainer'style='clear:both;'> ".$constructedRadio."</div>".
                        "<div class='radioLabelContainer' style='clear:both;'> ".$radioLabel->show()."</div></div>";
break;
                case 'left':
  return  "<div id='".$this->radioName."'><div style='clear:both;overflow:auto;'>"."<div class='radioLabelContainer' style='float:left;clear:left;'> ".$radioLabel->show()."</div>"
        ."<div class='radioContainer'style='float:left; clear:right;'> ".$constructedRadio."</div></div></div>";
break;
                case 'right':
  return "<div id='".$this->radioName."'><div style='clear:both;overflow:auto;'>"."<div class='radioContainer'style='float:left;clear:left;'> ".$constructedRadio."</div>".
                        "<div class='radioLabelContainer' style='float:left;clear:right;'> ".$radioLabel->show()."</div></div></div>";
break;
                 }
      }
//return $constructeddd;
//  foreach ($this->labelnOptionArray as $radioValue => $radioOptionLabel) {
//
//      $this->objRadio->addOption($radioValue,$radioOptionLabel);
//        if ($this->tempWYSIWYGBoolDefaultSelected==TRUE)
//  {
//      $this->objRadio->setSelected($radioValue);
//  }
//  }
//
//
//               return $this->getBreakSpaceType($this->tempWYSIWYGLayoutOption).$this->objRadio->show();
}

public function showWYSIWYGRadioEntity()
{
    return $this->buildWYSIWYGRadioEntity();
}




}
?>
