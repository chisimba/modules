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
          $this->loadClass('label','htmlelements');
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
public function getWYSIWYGDropdownName()
{
    return $this->ddName;
}
protected function getDropdownName($dropdownName)
{
  $ddParameters = $this->objDBddEntity->listDropdownParameters($dropdownName);

   return $ddName = $ddParameters["0"]['dropdownname'];
}

 public function getWYSIWYGDropDownInsertForm($formName)
    {
     $WYSIWYGDropDownInsertForm="<b>Drop Down HTML ID and Name Menu</b>";
     $WYSIWYGDropDownInsertForm.="<div id='ddIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
     $WYSIWYGDropDownInsertForm.= $this->buildInsertIdForm('dropdown',$formName,"70")."";
          $WYSIWYGDropDownInsertForm.= "</div>";
       $WYSIWYGDropDownInsertForm.= "<b>Drop Down Label Menu</b>";
       $WYSIWYGDropDownInsertForm.= "<div id='dropdownLabelContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
       $WYSIWYGDropDownInsertForm.=  $this->insertFormLabelOptions("dropdown","labelOrientation");
       $WYSIWYGDropDownInsertForm.=  "</div>";
           $WYSIWYGDropDownInsertForm.= "<div id='optionAndValueContainer'>";
           $WYSIWYGDropDownInsertForm.="<b>Insert Drop Down Options Menu</b>";
     $WYSIWYGDropDownInsertForm.="<div id='ddOptionAndValueContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
     $WYSIWYGDropDownInsertForm.= $this->insertOptionAndValueForm('drop down list', 0)."<br>";
               $WYSIWYGDropDownInsertForm.= "</div>";
                         $WYSIWYGDropDownInsertForm.= "</div>";
           return               $WYSIWYGDropDownInsertForm;
    }


public function insertOptionandValue($formElementName,$option,$value,$defaultSelected,$label,$labelOrientation)
{
  //  $a['color'] = 'red';
    if ($this->objDBddEntity->checkDuplicateDropdownEntry($formElementName,$value) == TRUE)
    {
        $this->objDBddEntity->insertSingle($formElementName,$option,$value,$defaultSelected,$label,$labelOrientation);

        $this->ddName = $formElementName;


    return TRUE;
    }
 else {
        return FALSE;
    }
}
protected function deleteDropDownEntity($formElementName)
{
    $deleteSuccess = $this->objDBddEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}

protected function constructDropDownEntity($dropdownName)
{
  // return $checkboxName;
    $ddParameters = $this->objDBddEntity->listDropdownParameters($dropdownName);

    $ddName = $ddParameters["0"]['dropdownname'];
$ddUnderConstruction = new dropdown($ddName);

    foreach($ddParameters as $thisDDParameter){
   //Store the values of the array in variables

   $ddValue = $thisDDParameter["ddoptionvalue"];
   $ddOptionLabel = $thisDDParameter["ddoptionlabel"];
   $defaultValue = $thisDDParameter["defaultvalue"];
   $ddLabel=$thisDDParameter["label"];
      $labelOrientation=$thisDDParameter["labelorientation"];

$ddUnderConstruction->addOption($ddValue,$ddOptionLabel);
        if ($defaultValue == TRUE)
  {
$ddUnderConstruction->setSelected($ddValue);
  }
  }
        if (   $ddLabel==NULL)
      {
  $constructeddd = "<div id='".$ddName."'>".$ddUnderConstruction->show()."</div>";
      }
 else {
                $ddLabels = new label ($ddLabel,$ddName);
     switch ($labelOrientation) {
                case 'top':
  $constructeddd = "<div id='".$ddName."'><div class='ddLabelContainer' style='clear:both;'> ".$ddLabels->show()."</div>"
        ."<div class='ddContainer'style='clear:left;'> ".$ddUnderConstruction->show()."</div></div>";
break;
                case 'bottom':
  $constructeddd =  "<div id='".$ddName."'><div class='ddContainer'style='clear:both;'> ".$ddUnderConstruction->show()."</div>".
                        "<div class='ddLabelContainer' style='clear:both;'> ".$ddLabels->show()."</div></div>";
break;
                case 'left':
  $constructeddd =  "<div id='".$ddName."'><div style='clear:both;overflow:auto;'>"."<div class='ddLabelContainer' style='float:left;clear:left;'> ".$ddLabels->show()."</div>"
        ."<div class='ddContainer'style='float:left; clear:right;'> ".$ddUnderConstruction->show()."</div></div></div>";
break;
                case 'right':
  $constructeddd =  "<div id='".$ddName."'><div style='clear:both;overflow:auto;'>"."<div class='ddContainer'style='float:left;clear:left;'> ".$ddUnderConstruction->show()."</div>".
                        "<div class='ddLabelContainer' style='float:left;clear:right;'> ".$ddLabels->show()."</div></div></div>";
break;
                 }
      }
return $constructeddd;

}

private function buildWYSIWYGDropdownEntity()
{
//   $objElement = new radio('sex_radio');
$ddParameters = $this->objDBddEntity->listDropdownParameters($this->ddName);
$this->objDD = new dropdown($this->ddName);
  foreach($ddParameters as $thisDDParameter){
   //Store the values of the array in variables

   $ddValue = $thisDDParameter["ddoptionvalue"];
   $ddOptionLabel = $thisDDParameter["ddoptionlabel"];
   $defaultValue = $thisDDParameter["defaultvalue"];
   $ddLabel=$thisDDParameter["label"];
   $labelOrientation=$thisDDParameter["labelorientation"];



$this->objDD->addOption($ddValue,$ddOptionLabel);
        if ($defaultValue == TRUE)
  {
      $this->objDD->setSelected($ddValue);
  }
  }
      $this->objDD->multiple =false;
//$ddLabel="test";
//$labelOrientation="top";
      if (   $ddLabel==NULL)
      {
  return "<div id='".$this->ddName."'>".$this->objDD->show()."</div>";
      }
      else
      {
            $ddLabels = new label ($ddLabel,$this->ddName);
 switch ($labelOrientation) {
                case 'top':
return "<div id='".$this->ddName."'><div class='ddLabelContainer' style='clear:both;'> ".$ddLabels->show()."</div>"
        ."<div class='ddContainer'style='clear:left;'> ".$this->objDD->show()."</div></div>";
break;
                case 'bottom':
return "<div id='".$this->ddName."'><div class='ddContainer'style='clear:both;'> ".$this->objDD->show()."</div>".
                        "<div class='ddLabelContainer' style='clear:both;'> ".$ddLabels->show()."</div></div>";
break;
                case 'left':
return "<div id='".$this->ddName."'><div style='clear:both;overflow:auto;'>"."<div class='ddLabelContainer' style='float:left;clear:left;'> ".$ddLabels->show()."</div>"
        ."<div class='ddContainer'style='float:left; clear:right;'> ".$this->objDD->show()."</div></div></div>";
break;
                case 'right':
return "<div id='".$this->ddName."'><div style='clear:both;overflow:auto;'>"."<div class='ddContainer'style='float:left;clear:left;'> ".$this->objDD->show()."</div>".
                        "<div class='ddLabelContainer' style='float:left;clear:right;'> ".$ddLabels->show()."</div></div></div>";
break;
                 }
      }
}

public function showWYSIWYGDropdownEntity()
{
    return $this->buildWYSIWYGDropdownEntity();
}




}
?>
