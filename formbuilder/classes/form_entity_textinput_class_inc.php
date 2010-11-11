<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';
     //  $this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
//$this->appendArrayVar('headerParams', '<script type="text/javascript">jQuery.noConflict();</script>');
class form_entity_textinput extends form_entity_handler
{
    private $objTextInput;
    private $tiFormName;
    private $tiName;
    private $tiTextValue;
    private $tiType;
    private $tiSize;
    private $tiTextMask;
private $tiLabel;
private $tiLabelLayout;
    protected  $objDBtiEntity;
  
//textinput($name=null, $value=null, $type=null, $size=null)
    public function  init()
    {

        $this->loadClass('textinput','htmlelements');
                $this->loadClass('label','htmlelements');
         $this->loadClass('inputmasks', 'htmlelements');

        $this->tiName=Null;
        $this->tiFormName =NULL;
        $this->tiTextValue=NULL;
        $this->tiType=NULL;
        $this->tiSize=NULL;
        $this->tiTextMask=NULL;
        $this->tiLabel =NULL;
        $this->tiLabelLayout=NULL;
        $this->objDBtiEntity = $this->getObject('dbformbuilder_textinput_entity','formbuilder');
        //$this->tempWYSIWYGBoolDefaultSelected=FALSE;
                }

    public function createFormElement($textInputFormName='',$textInputName='')
    {
    $this->tiFormName = $textInputFormName;
    $this->tiName=$textInputName;
      
        
    }
public function getWYSIWYGTextInputName()
{
    return $this->tiName;
}

protected function getTextInputName($textInputFormName)
{
    $tiParameters = $this->objDBtiEntity->listTextInputParameters($textInputFormName);
    $textInputArray = array();
foreach($tiParameters as $thistiParameter){
   //Store the values of the array in variables

   $textInputFormName = $thistiParameter["textinputname"];
   $textInputArray[]=$textInputFormName;
}
return $textInputArray;
}
    public function getWYSIWYGTextInputInsertForm($formName)
    {
      
        
       $WYSIWYGTextInputInsertForm="<b>Text Input HTML ID and Name Menu</b>";
    $WYSIWYGTextInputInsertForm.="<div id='textInputNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
        $WYSIWYGTextInputInsertForm.= $this->buildInsertIdForm('textinput',$formName,"70")."<br>";

       $WYSIWYGTextInputInsertForm.= $this->buildInsertFormElementNameForm('text input', "70");
         $WYSIWYGTextInputInsertForm.= "</div>";

                $WYSIWYGTextInputInsertForm.="<b>Text Input Label Menu</b>";
            $WYSIWYGTextInputInsertForm.="<div id='textInputLabelContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
 $WYSIWYGTextInputInsertForm.= $this->insertFormLabelOptions("text_input","labelOrientation");
          $WYSIWYGTextInputInsertForm.= "</div>";
          $WYSIWYGTextInputInsertForm.="<b>Text Input Size Menu</b>";
           $WYSIWYGTextInputInsertForm.="<div id='textInputSizeContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
           
           $WYSIWYGTextInputInsertForm .= $this->insertCharacterSizeForm()."";
                       $WYSIWYGTextInputInsertForm.= "</div>";
           $WYSIWYGTextInputInsertForm.="<b>Text Input Properties Menu</b>";
           $WYSIWYGTextInputInsertForm.="<div id='textInputSizeContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
       $WYSIWYGTextInputInsertForm.= $this->insertTextInputOptionsForm(2,68)."";
         $WYSIWYGTextInputInsertForm.= "</div>";
    //      $WYSIWYGTextInputInsertForm.= $this->insertTextForm('text input',2,68);
           return $WYSIWYGTextInputInsertForm;
    }

public function insertTextInputParameters($textinputformname,$textinputname,$textvalue,$texttype,$textsize,$maskedinputchoice,$formElementLabel,$formElementLabelLayout)
{

    if ($this->objDBtiEntity->checkDuplicateTextInputEntry($textinputformname,$textinputname) == TRUE)
    {
        $this->objDBtiEntity->insertSingle($textinputformname,$textinputname,$textvalue,$texttype,$textsize,$maskedinputchoice,$formElementLabel,$formElementLabelLayout);

        $this->tiName = $textinputname;
        $this->tiTextValue=$textvalue;
        $this->tiType=$texttype;
        $this->tiSize=$textsize;
        $this->tiTextMask=$maskedinputchoice;

        $this->tiLabel =$formElementLabel;
        $this->tiLabelLayout=$formElementLabelLayout;
    return TRUE;
    }
 else {
        return FALSE;
    }
}

protected function deleteTextInputEntity($formElementName)
{
    $deleteSuccess = $this->objDBtiEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}

protected function constructTextInputEntity($textInputName)
{
  // return $checkboxName;
    $tiParameters = $this->objDBtiEntity->listTextInputParameters($textInputName);

//return $checkboxParameters;
foreach($tiParameters as $thistiParameter){
   //Store the values of the array in variables

   $textInputFormName = $thistiParameter["textinputformname"];
  $textInputName = $thistiParameter["textinputname"];
  $textValue = $thistiParameter["textvalue"];
      $textType = $thistiParameter["texttype"];
      $textSize = $thistiParameter["textsize"];
      $maskedInputChoice = $thistiParameter["maskedinputchoice"];

      $textInputLabel = $thistiParameter["label"];
      $labelOrientation = $thistiParameter["labelorientation"];


      if ($textInputLabel == NULL)
      {
$tiUnderConstruction = new textinput($textInputName, $textValue, $textType, $textSize);
    if ($maskedInputChoice != "default")
    {
        $tiUnderConstruction->setCss($maskedInputChoice);
    }

    $inputMasksUnderConstruction = $this->getObject('inputmasks', 'htmlelements');

$currentConstructedti = "<div style='clear:both;>".$inputMasksUnderConstruction->show().$tiUnderConstruction->show()."</div>";
 $constructedti .= $currentConstructedti;
      }
 else {
  $textInputLabelUnderConstruction = new label ($textInputLabel, $textInputName);
     $tiUnderConstruction = new textinput($textInputName, $textValue, $textType, $textSize);
    if ($maskedInputChoice != "default")
    {
        $tiUnderConstruction->setCss($maskedInputChoice);
    }

    $inputMasksUnderConstruction = $this->getObject('inputmasks', 'htmlelements');

switch ($labelOrientation) {
                case 'top':
$currentConstructedti= $inputMasksUnderConstruction->show()."<div id='textInputLabelContainer' style='clear:both;'> ".$textInputLabelUnderConstruction->show()."</div>"
        ."<div id='textInputContainer'style='clear:left;'> ".$tiUnderConstruction->show()."</div>";
break;
                case 'bottom':
$currentConstructedti= $inputMasksUnderConstruction->show()."<div id='textInputContainer'style='clear:both;'> ".$tiUnderConstruction->show()."</div>".
                        "<div id='textInputLabelContainer' style='clear:both;'> ".$textInputLabelUnderConstruction->show()."</div>";
break;
                case 'left':
$currentConstructedti= "<div style='clear:both;overflow:auto;'>".$inputMasksUnderConstruction->show()."<div id='textInputLabelContainer' style='float:left;clear:left;'> ".$textInputLabelUnderConstruction->show()."</div>"
        ."<div id='textInputContainer'style='float:left; clear:right;'> ".$tiUnderConstruction->show()."</div></div>";
break;
                case 'right':
$currentConstructedti= "<div style='clear:both;overflow:auto;'>".$inputMasksUnderConstruction->show()."<div id='textInputContainer'style='float:left;clear:left;'> ".$tiUnderConstruction->show()."</div>".
                        "<div id='textInputLabelContainer' style='float:left;clear:right;'> ".$textInputLabelUnderConstruction->show()."</div></div>";
break;
                 }

 $constructedti .= $currentConstructedti;
      }

}

  return $constructedti;
}

private function buildWYSIWYGTextInputEntity()
{
  if ($this->tiLabel == NULL)
  {
    //  switch ($this->tiLabelLayout)
      $this->objTextInput = new textinput($this->tiName,  $this->tiTextValue, $this->tiType, $this->tiSize);
    if ($this->tiTextMask != "default")
    {
        $this->objTextInput->setCss($this->tiTextMask);
    }

$objInputMasks = $this->getObject('inputmasks', 'htmlelements');
return "<div style='clear:both;>".$objInputMasks->show().$this->objTextInput->show()."</div>";
 }
 else {
                                 $textInputLabel = new label ($this->tiLabel,  $this->tiName);
    $this->objTextInput = new textinput($this->tiName,  $this->tiTextValue, $this->tiType, $this->tiSize);
    if ($this->tiTextMask != "default")
    {
        $this->objTextInput->setCss($this->tiTextMask);
    }
$objInputMasks = $this->getObject('inputmasks', 'htmlelements');

     switch ($this->tiLabelLayout) {
                case 'top':
return $objInputMasks->show()."<div class='textInputLabelContainer' style='clear:both;'> ".$textInputLabel->show()."</div>"
        ."<div class='textInputContainer'style='clear:left;'> ".$this->objTextInput->show()."</div>";
break;
                case 'bottom':
return $objInputMasks->show()."<div class='textInputContainer'style='clear:both;'> ".$this->objTextInput->show()."</div>".
                        "<div class='textInputLabelContainer' style='clear:both;'> ".$textInputLabel->show()."</div>";
break;
                case 'left':
return "<div style='clear:both;overflow:auto;'>".$objInputMasks->show()."<div class='textInputLabelContainer' style='float:left;clear:left;'> ".$textInputLabel->show()."</div>"
        ."<div class='textInputContainer'style='float:left; clear:right;'> ".$this->objTextInput->show()."</div></div>";
break;
                case 'right':
return "<div style='clear:both;overflow:auto;'>".$objInputMasks->show()."<div class='textInputContainer'style='float:left;clear:left;'> ".$this->objTextInput->show()."</div>".
                        "<div class='textInputLabelContainer' style='float:left;clear:right;'> ".$textInputLabel->show()."</div></div>";
break;
                 }
 }
  

}

public function showWYSIWYGTextInputEntity()
{
    return $this->buildWYSIWYGTextInputEntity();
}




}
?>
