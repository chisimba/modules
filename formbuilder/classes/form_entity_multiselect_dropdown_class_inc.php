<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_multiselect_dropdown extends form_entity_handler
{
    private $objMSDD;
    private $msddName;
        private $msddValue;
        private $defaultValue;
        private $msddLabel;
        private $defaultMSValuesArray;
    private $msddLabelnOptionArray;

    //private $tempWYSIWYGBoolDefaultSelected;
    //private $tempWYSIWYGLayoutOption;
    protected  $objDBmsddEntity;
  

    public function  init()
    {
        $this->loadClass('dropdown','htmlelements');
        $this->loadClass('label','htmlelements');
        $this->msddName = NULL;
        $this->msddLabelnOptionArray=array();
        $this->defaultMSValuesArray=array();
        $this->msddLabel= NULL;
        $this->objDBmsddEntity = $this->getObject('dbformbuilder_multiselect_dropdown_entity','formbuilder');
        //$this->tempWYSIWYGBoolDefaultSelected=FALSE;
                }

    public function createFormElement($elementName="")
    {
        $this->msddName = $elementName;
        $this->objMSDD = new dropdown($elementName);
      
        
    }
public function getWYSIWYGMultiSelectDropdownName()
{
    return $this->msddName;
}

protected function getMultiSelectDropdownName($msDropDownName)
{
$msddParameters = $this->objDBmsddEntity->listMultiSelectDropdownParameters($msDropDownName);

  return  $msddName = $msddParameters["0"]['multiselectdropdownname'];
}
 public function getWYSIWYGMSDropDownInsertForm($formName)
    {
      $WYSIWYGDropDownInsertForm="<b>Multi-Selectable Drop Down List HTML ID and Name Menu</b>";
     $WYSIWYGDropDownInsertForm.="<div id='msddIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
     $WYSIWYGDropDownInsertForm.= $this->buildInsertIdForm('dropdown',$formName,"70")."";
            $WYSIWYGDropDownInsertForm.=  "</div>";
     $WYSIWYGDropDownInsertForm.= "<b>Multi-Selectable Drop Down List Label Menu</b>";
       $WYSIWYGDropDownInsertForm.= "<div id='msdropdownLabelContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
       $WYSIWYGDropDownInsertForm.=  $this->insertFormLabelOptions("msdropdown","labelOrientation");
       $WYSIWYGDropDownInsertForm.=  "</div>";
       $WYSIWYGDropDownInsertForm.="<b>Multi-Selectable Drop Down List Size Menu</b>";
       $WYSIWYGDropDownInsertForm.="<div id='msdropdownSizeContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
     $WYSIWYGDropDownInsertForm .= $this->insertMSDropDownSizeForm();
            $WYSIWYGDropDownInsertForm.=  "</div>";
              $WYSIWYGDropDownInsertForm.="<b>Insert Multi-Selectable Drop Down List Options Menu</b>";
     $WYSIWYGDropDownInsertForm.="<div id='msddOptionAndValueContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
     $WYSIWYGDropDownInsertForm.= $this->insertOptionAndValueForm('multi-selectable drop down list', 0)."<br>";
                 $WYSIWYGDropDownInsertForm.=  "</div>";
     return               $WYSIWYGDropDownInsertForm;
    }
public function insertOptionandValue($formElementName,$option,$value,$defaultSelected,$msddsize,$formElementLabel,$labelLayout)
{

    if ($this->objDBmsddEntity->checkDuplicateMultiSelectDropdownEntry($formElementName,$value) == TRUE)
    {
       $this->objDBmsddEntity->updateMenuSize( $formElementName, $msddsize);
        $this->objDBmsddEntity->insertSingle($formElementName,$option,$value,$defaultSelected,$msddsize,$formElementLabel,$labelLayout);

        $this->msddName = $formElementName;


    return TRUE;
    }
 else {
       return FALSE;
    }
}

protected function deleteMultiSelectDropDownEntity($formElementName)
{
    $deleteSuccess = $this->objDBmsddEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}

protected function constructMultiSelectDropDownEntity($msDropDownName)
{
  // return $checkboxName;
$msddParameters = $this->objDBmsddEntity->listMultiSelectDropdownParameters($msDropDownName);
    //$ddParameters = $this->objDBddEntity->listDropdownParameters($dropdownName);

    $msddName = $msddParameters["0"]['multiselectdropdownname'];
$msddUnderConstruction = new dropdown($msddName);

    foreach($msddParameters as $thisMSDDParameter){
   //Store the values of the array in variables

   $msddName= $thisMSDDParameter["multiselectdropdownname"];
   $msddOptionLabel = $thisMSDDParameter["msddoptionlabel"];
   $msddOptionValue = $thisMSDDParameter["msddoptionvalue"];
   $defaultValue = $thisMSDDParameter["defaultvalue"];
      $msddsize = $thisMSDDParameter["msddsize"];
         $msddLabel = $thisMSDDParameter["label"];
      $labelOrientation = $thisMSDDParameter["labelorientation"];


$msddUnderConstruction->addOption($msddOptionValue,$msddOptionLabel);
        if ($defaultValue == TRUE)
  {
$this->defaultMSValuesArray[]=$msddOptionValue;
           // $ddUnderConstruction->setSelected($ddValue);
  }
  }
$msddUnderConstruction->setMultiSelected($this->defaultMSValuesArray);
$msddUnderConstruction->multiple =true;
$msddUnderConstruction->size = $msddsize;




                 if (   $msddLabel==NULL)
      {
      $constructedmsdd = $msddUnderConstruction->show();
      }
      else
      {
            $msddLabels = new label ($msddLabel,$msddName);
 switch ($labelOrientation) {
                case 'top':
      $constructedmsdd = "<div id='".$msddName."'><div class='msddLabelContainer' style='clear:both;'> ".$msddLabels->show()."</div>"
        ."<div class='msddContainer'style='clear:left;'> ".$msddUnderConstruction->show()."</div></div>";
break;
                case 'bottom':
      $constructedmsdd = "<div id='".$msddName."'><div class='msddContainer'style='clear:both;'> ".$msddUnderConstruction->show()."</div>".
                        "<div class='msddLabelContainer' style='clear:both;'> ".$msddLabels->show()."</div></div>";
break;
                case 'left':
      $constructedmsdd = "<div id='".$msddName."'><div style='clear:both;overflow:auto;'>"."<div class='msddLabelContainer' style='float:left;clear:left;'> ".$msddLabels->show()."</div>"
        ."<div class='msddContainer'style='float:left; clear:right;'> ".$msddUnderConstruction->show()."</div></div></div>";
break;
                case 'right':
      $constructedmsdd = "<div id='".$msddName."'><div style='clear:both;overflow:auto;'>"."<div class='msddContainer'style='float:left;clear:left;'> ".$msddUnderConstruction->show()."</div>".
                        "<div class='msddLabelContainer' style='float:left;clear:right;'> ".$msddLabels->show()."</div></div></div>";
break;
                 }
      }



return $constructedmsdd;

}

private function buildWYSIWYGMultiSelectDropdownEntity()
{
//   $objElement = new radio('sex_radio');
$msddParameters = $this->objDBmsddEntity->listMultiSelectDropdownParameters($this->msddName);

  foreach($msddParameters as $thisDDParameter){
   //Store the values of the array in variables

   $msddName = $thisDDParameter["multiselectdropdownname"];
   $msddOptionLabel = $thisDDParameter["msddoptionlabel"];
   $msddOptionValue = $thisDDParameter["msddoptionvalue"];
   $defaultValue = $thisDDParameter["defaultvalue"];
      $msddsize = $thisDDParameter["msddsize"];
         $msddLabel = $thisDDParameter["label"];
      $labelOrientation = $thisDDParameter["labelorientation"];

$this->objMSDD->addOption($msddOptionValue,$msddOptionLabel);
        if ($defaultValue == TRUE)
  {
     // $this->objDD->setSelected($ddValue);
      $this->defaultMSValuesArray[]=$msddOptionValue;
  }
  }

$this->objMSDD->setMultiSelected($this->defaultMSValuesArray);
$this->objMSDD->multiple =true;
$this->objMSDD->size = $msddsize;




   if (   $msddLabel==NULL)
      {
 return $this->objMSDD->show();
      }
      else
      {
            $msddLabels = new label ($msddLabel,$this->msddName);
 switch ($labelOrientation) {
                case 'top':
return "<div id='".$this->msddName."'><div class='msddLabelContainer' style='clear:both;'> ".$msddLabels->show()."</div>"
        ."<div class='msddContainer'style='clear:left;'> ".$this->objMSDD->show()."</div></div>";
break;
                case 'bottom':
return "<div id='".$this->msddName."'><div class='msddContainer'style='clear:both;'> ".$this->objMSDD->show()."</div>".
                        "<div class='msddLabelContainer' style='clear:both;'> ".$msddLabels->show()."</div></div>";
break;
                case 'left':
return "<div id='".$this->msddName."'><div style='clear:both;overflow:auto;'>"."<div class='msddLabelContainer' style='float:left;clear:left;'> ".$msddLabels->show()."</div>"
        ."<div class='msddContainer'style='float:left; clear:right;'> ".$this->objMSDD->show()."</div></div></div>";
break;
                case 'right':
return "<div id='".$this->msddName."'><div style='clear:both;overflow:auto;'>"."<div class='msddContainer'style='float:left;clear:left;'> ".$this->objMSDD->show()."</div>".
                        "<div class='msddLabelContainer' style='float:left;clear:right;'> ".$msddLabels->show()."</div></div></div>";
break;
                 }
      }


}

public function showWYSIWYGMultiSelectDropdownEntity()
{
    return $this->buildWYSIWYGMultiSelectDropdownEntity();
}




}
?>
