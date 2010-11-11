<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';
       $this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$this->appendArrayVar('headerParams', '<script type="text/javascript">jQuery.noConflict();</script>');

class form_entity_textarea extends form_entity_handler
{
    private $objTextArea;
    //private $objHTMLArea;
    private $taFormName;
    private $taName;
    private $taTextValue;
    private $taColumnSize;
    private $taRowSize;
    private $taSimpleorAdvancedChoice;
    private $toolbarChoice;
    private $taLabel;
    private $labelOrientation;

    protected  $objDBtaEntity;
  
//textinput($name=null, $value=null, $type=null, $size=null)
    public function  init()
    {

        $this->loadClass('textarea','htmlelements');
      $this->loadClass('htmlarea','htmlelements');
        $this->loadClass('label', 'htmlelements');

        $this->taName=Null;
        $this->taFormName =NULL;
        $this->taRowSize=NULL;
        $this->taColumnSize=NULL;
        $this->taTextValue=NULL;
        $this->taSimpleorAdvancedChoice=NULL;
        $this->toolbarChoice =NULL;
        $this->taLabel=NULL;
        $this->labelOrientation;

        $this->objDBtaEntity = $this->getObject('dbformbuilder_textarea_entity','formbuilder');

                }

    public function createFormElement($textAreaFormName='',$textAreaName='')
    {
    $this->taFormName = $textAreaFormName;
    $this->taName= $textAreaName;
      
        
    }
public function getWYSIWYGTextAreaName()
{
    return $this->taName;
}
protected function getTextAreaName($textAreaFormName)
{
       $taParameters = $this->objDBtaEntity->listTextAreaParameters($textAreaFormName);
    $textAreaNameArray = array();
//return $checkboxParameters;
foreach($taParameters as $thistaParameter){
   //Store the values of the array in variables

   //$textareaFormName = $thistaParameter["textareaformname"];
  $textareaName = $thistaParameter["textareaname"];
  $textAreaNameArray[]=$textareaName;
}

return $textAreaNameArray;
}
    public function getWYSIWYGTextAreaInsertForm($formName)
    {
               $WYSIWYGTextInputInsertForm="<div id='ALL'>";
        $WYSIWYGTextInputInsertForm.="<div id='simpleTAForm'>";

         $WYSIWYGTextInputInsertForm.="<b>Text Area HTML ID and Name Menu</b>";
      $WYSIWYGTextInputInsertForm.="<div id='textAreaNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
        $WYSIWYGTextInputInsertForm.= $this->buildInsertIdForm('textarea',$formName,"70")."<br>";
       $WYSIWYGTextInputInsertForm.= $this->buildInsertFormElementNameForm('text area', "70")."<br>";
                $WYSIWYGTextInputInsertForm.= "</div>";
   //     $WYSIWYGTextInputInsertForm.= "</div>";

      $WYSIWYGTextInputInsertForm.="<b>Text Area Label Menu</b>";
            $WYSIWYGTextInputInsertForm.="<div id='textAreaLabelContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
 $WYSIWYGTextInputInsertForm.= $this->insertFormLabelOptions("text_input","labelOrientationSimple");
          $WYSIWYGTextInputInsertForm.= "</div>";

                $WYSIWYGTextInputInsertForm.="<b>Text Area Size Menu</b>";
            $WYSIWYGTextInputInsertForm.="<div id='textAreaSizeMenuContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
              $WYSIWYGTextInputInsertForm .= $this->insertTextAreaSizeParameters();
                              $WYSIWYGTextInputInsertForm.= "</div>";

                              $WYSIWYGTextInputInsertForm.="<b>Text Area Properties Menu</b>";
           $WYSIWYGTextInputInsertForm.="<div id='textAreaPropertiesContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
            $WYSIWYGTextInputInsertForm.= $this->insertTextForm('text area',2,68);
                                          $WYSIWYGTextInputInsertForm.= "</div>";
                                            $WYSIWYGTextInputInsertForm.= "</div>";
             // $WYSIWYGTextInputInsertForm.="</div>";
                //$WYSIWYGTextInputInsertForm.="</div>";
                          $WYSIWYGTextInputInsertForm.="<div id='advancedTAForm'>";
                           $WYSIWYGTextInputInsertForm.="<b>Text Area HTML ID and Name Menu</b>";
      $WYSIWYGTextInputInsertForm.="<div id='textAreaNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
                      $WYSIWYGTextInputInsertForm.= $this->buildInsertIdForm('textarea',$formName,"70")."<br>";
       $WYSIWYGTextInputInsertForm.= $this->buildInsertFormElementNameForm('text area', "70")."<br>";
                       $WYSIWYGTextInputInsertForm.= "</div>";
                            //  $WYSIWYGTextInputInsertForm.= "</div>";
//                                              $WYSIWYGTextInputInsertForm.= "</div>";
                             $WYSIWYGTextInputInsertForm.="<b>Text Area Label Menu</b>";
            $WYSIWYGTextInputInsertForm.="<div id='textAreaLabelContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
 $WYSIWYGTextInputInsertForm.= $this->insertFormLabelOptions("text_input","labelOrientationAdvanced");
          $WYSIWYGTextInputInsertForm.= "</div>";
                        $WYSIWYGTextInputInsertForm.="<b>Text Area Size Menu</b>";
            $WYSIWYGTextInputInsertForm.="<div id='textAreaSizeMenuContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
       $WYSIWYGTextInputInsertForm .= $this->insertTextAreaSizeParameters();
                     $WYSIWYGTextInputInsertForm.="</div>";
                      $WYSIWYGTextInputInsertForm.="<b>Text Area Properties Menu</b>";
           $WYSIWYGTextInputInsertForm.="<div id='textAreaPropertiesContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
                       $WYSIWYGTextInputInsertForm.= $this->insertToolbarChoiceTextAreaOptions();
              $WYSIWYGTextInputInsertForm.= $this->insertTextForm('text area',2,68);
                                                        $WYSIWYGTextInputInsertForm.= "</div>";
              $WYSIWYGTextInputInsertForm.= "</div>";
                         //$WYSIWYGTextInputInsertForm.= "</div>";
           return $WYSIWYGTextInputInsertForm;
    }
public function insertTextAreaParameters($textareaformname,$textareaname,$textareavalue,$columnsize,$rowsize,$simpleoradvancedchoice,$toolbarchoice,$formElementLabel,$labelLayout)
{

    if ($this->objDBtaEntity->checkDuplicateTextAreaEntry($textareaformname,$textareaname) == TRUE)
    {
        $this->objDBtaEntity->insertSingle($textareaformname,$textareaname,$textareavalue,$columnsize,$rowsize,$simpleoradvancedchoice,$toolbarchoice,$formElementLabel,$labelLayout);

        $this->taName = $textareaname;
        $this->taRowSize=$rowsize;
        $this->taColumnSize=$columnsize;
        $this->taTextValue=$textareavalue;
        $this->taSimpleorAdvancedChoice=$simpleoradvancedchoice;
        $this->toolbarChoice=$toolbarchoice;
        $this->taLabel=$formElementLabel;
        $this->labelOrientation=$labelLayout;
    return TRUE;
    }
 else {
        return FALSE;
    }
}

private function convertTextAreaRowSizeToHTMLRowSize($rowSize)
{
    return 130 + $rowSize."px";
}

private function convertTextAreaColumnSizeToHTMLColumnSize($columnSize)
{
    return 0.7*$columnSize."%";
}

protected function deleteTextAreaEntity($formElementName)
{
    $deleteSuccess = $this->objDBtaEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}

protected function constructTextAreaEntity($textareaName)
{
  // return $checkboxName;
    $taParameters = $this->objDBtaEntity->listTextAreaParameters($textareaName);

//return $checkboxParameters;
foreach($taParameters as $thistaParameter){
   //Store the values of the array in variables

   $textareaFormName = $thistaParameter["textareaformname"];
  $textareaName = $thistaParameter["textareaname"];
      $textareaValue = $thistaParameter["textareavalue"];
      $columnSize = $thistaParameter["columnsize"];
      $rowSize = $thistaParameter["rowsize"];
            $simpleOrAdvancedChoice = $thistaParameter["simpleoradvancedchoice"];
                  $toolbarChoice = $thistaParameter["toolbarchoice"];
                  $textareaLabel= $thistaParameter["label"];
                  $labelLayout = $thistaParameter["labelorientation"];
if ($textareaLabel == NULL)
{
    if ($simpleOrAdvancedChoice == "textarea")
    {
$taUnderConstruction= new textarea($textareaName,$textareaValue,$rowSize,$columnSize);
    $currentConstructedta = $taUnderConstruction->show();

    }
 else {
$taUnderConstruction = $this->newObject('htmlarea','htmlelements');
$taUnderConstruction->setName($textareaName);
$taUnderConstruction->setContent($textareaValue);
$taUnderConstruction->width = $this->convertTextAreaColumnSizeToHTMLColumnSize($columnSize);
$taUnderConstruction->height =$this->convertTextAreaRowSizeToHTMLRowSize($rowSize);
$taUnderConstruction->toolbarSet= $toolbarChoice;
    $currentConstructedta = $taUnderConstruction->show();
    }


 $constructedta .= $currentConstructedta;
}
 else {
  $textAreaLabel = new label ($textareaLabel, $textareaName);
     if ($simpleOrAdvancedChoice == "textarea")
    {
$taUnderConstruction= new textarea($textareaName,$textareaValue,$rowSize,$columnSize);
    $currentConstructedta = $taUnderConstruction->show();
     switch ($labelLayout) {
                case 'top':
$currentConstructedta = "<div class='textAreaLabelContainer' style='clear:both;'> ".$textAreaLabel->show()."</div>"
        ."<div class='textAreaContainer'style='clear:left;'> ".$taUnderConstruction->show()."</div>";
break;
                case 'bottom':
$currentConstructedta = "<div class='textAreaContainer'style='clear:both;'> ".$taUnderConstruction->show()."</div>".
                        "<div class='textAreaLabelContainer' style='clear:both;'> ".$textAreaLabel->show()."</div>";
break;
                case 'left':
$currentConstructedta ="<div style='clear:both;overflow:auto;'>"."<div class='textAreaLabelContainer' style='float:left;clear:left;'> ".$textAreaLabel->show()."</div>"
        ."<div class='textAreaContainer'style='float:left; clear:right;'> ".$taUnderConstruction->show()."</div></div>";
break;
                case 'right':
$currentConstructedta = "<div style='clear:both;overflow:auto;'>"."<div class='textAreaContainer'style='float:left;clear:left;'> ".$taUnderConstruction->show()."</div>".
                        "<div class='textAreaLabelContainer' style='float:left;clear:right;'> ".$textAreaLabel->show()."</div></div>";
break;
                 }
       $constructedta .= $currentConstructedta;
    }
 else {

     $taUnderConstruction = $this->newObject('htmlarea','htmlelements');
$taUnderConstruction->setName($textareaName);
$taUnderConstruction->setContent($textareaValue);
$taUnderConstruction->width = $this->convertTextAreaColumnSizeToHTMLColumnSize($columnSize);
$taUnderConstruction->height =$this->convertTextAreaRowSizeToHTMLRowSize($rowSize);
$taUnderConstruction->toolbarSet= $toolbarChoice;
//    $currentConstructedta = $taUnderConstruction->show();/


     switch ($labelLayout) {
                case 'top':
$currentConstructedta = "<div class='textAreaLabelContainer' style='clear:both;'> ".$textAreaLabel->show()."</div>"
        ."<div class='textAreaContainer'style='clear:left;'> ".$taUnderConstruction->show()."</div>";
break;
                case 'bottom':
$currentConstructedta = "<div class='textAreaContainer'style='clear:both;'> ".$taUnderConstruction->show()."</div>".
                        "<div class='textAreaLabelContainer' style='clear:both;'> ".$textAreaLabel->show()."</div>";
break;
                case 'left':
$currentConstructedta ="<div style='clear:both;overflow:auto;'>"."<div class='textAreaLabelContainer' style='float:left;clear:left;'> ".$textAreaLabel->show()."</div>"
        ."<div class='textAreaContainer'style='float:left; clear:right;'> ".$taUnderConstruction->show()."</div></div>";
break;
                case 'right':
$currentConstructedta = "<div style='clear:both;overflow:auto;'>"."<div class='textAreaContainer'style='float:left;clear:left;'> ".$taUnderConstruction->show()."</div>".
                        "<div class='textAreaLabelContainer' style='float:left;clear:right;'> ".$textAreaLabel->show()."</div></div>";
break;
                 }
                  $constructedta .= $currentConstructedta;
    }
}
}


  return $constructedta;
}

private function buildWYSIWYGTextAreaEntity()
{
   if ($this->taLabel==NULL)
   {
    if ($this->taSimpleorAdvancedChoice == "textarea")
    {
        $this->objTextArea= new textarea($this->taName,$this->taTextValue,$this->taRowSize,$this->taColumnSize);
       return $this->objTextArea->show();
    }
 else {
  $this->objTextArea = $this->newObject('htmlarea','htmlelements');
  $this->objTextArea->setName($this->taName);
     $this->objTextArea->setContent($this->taTextValue);
     $this->objTextArea->width =$this->convertTextAreaColumnSizeToHTMLColumnSize($this->taColumnSize);
     $this->objTextArea->height =$this->convertTextAreaRowSizeToHTMLRowSize($this->taRowSize);
       $this->objTextArea->toolbarSet= $this->toolbarChoice;
return $this->objTextArea->show();
    }
   }
 else {
        $textAreaLabel = new label ($this->taLabel, $this->taName);
        if ($this->taSimpleorAdvancedChoice == "textarea")
    {
        $this->objTextArea= new textarea($this->taName,$this->taTextValue,$this->taRowSize,$this->taColumnSize);
    
             switch ($this->labelOrientation) {
                case 'top':
return "<div class='textAreaLabelContainer' style='clear:both;'> ".$textAreaLabel->show()."</div>"
        ."<div class='textAreaContainer'style='clear:left;'> ".$this->objTextArea->show()."</div>";
break;
                case 'bottom':
return "<div class='textAreaContainer'style='clear:both;'> ".$this->objTextArea->show()."</div>".
                        "<div class='textAreaLabelContainer' style='clear:both;'> ".$textAreaLabel->show()."</div>";
break;
                case 'left':
return "<div style='clear:both;overflow:auto;'>"."<div class='textAreaLabelContainer' style='float:left;clear:left;'> ".$textAreaLabel->show()."</div>"
        ."<div class='textAreaContainer'style='float:left; clear:right;'> ".$this->objTextArea->show()."</div></div>";
break;
                case 'right':
return "<div style='clear:both;overflow:auto;'>"."<div class='textAreaContainer'style='float:left;clear:left;'> ".$this->objTextArea->show()."</div>".
                        "<div class='textAreaLabelContainer' style='float:left;clear:right;'> ".$textAreaLabel->show()."</div></div>";
break;
                 }
    }
 else {
      $textAreaLabel = new label ($this->taLabel, $this->taName);
  $this->objTextArea = $this->newObject('htmlarea','htmlelements');
  $this->objTextArea->setName($this->taName);
     $this->objTextArea->setContent($this->taTextValue);
     $this->objTextArea->width =$this->convertTextAreaColumnSizeToHTMLColumnSize($this->taColumnSize);
     $this->objTextArea->height =$this->convertTextAreaRowSizeToHTMLRowSize($this->taRowSize);
       $this->objTextArea->toolbarSet= $this->toolbarChoice;

       
   switch ($this->labelOrientation) {
                case 'top':
return "<div class='textAreaLabelContainer' style='clear:both;'> ".$textAreaLabel->show()."</div>"
        ."<div class='textAreaContainer'style='clear:left;'> ".$this->objTextArea->show()."</div>";
break;
                case 'bottom':
return "<div class='textAreaContainer'style='clear:both;'> ".$this->objTextArea->show()."</div>".
                        "<div class='textAreaLabelContainer' style='clear:both;'> ".$textAreaLabel->show()."</div>";
break;
                case 'left':
return "<div style='clear:both;overflow:auto;'>"."<div class='textAreaLabelContainer' style='float:left;clear:left;'> ".$textAreaLabel->show()."</div>"
        ."<div class='textAreaContainer'style='float:left; clear:right;'> ".$this->objTextArea->show()."</div></div>";
break;
                case 'right':
return "<div style='clear:both;overflow:auto;'>"."<div class='textAreaContainer'style='float:left;clear:left;'> ".$this->objTextArea->show()."</div>".
                        "<div class='textAreaLabelContainer' style='float:left;clear:right;'> ".$textAreaLabel->show()."</div></div>";
break;
                 }
    }

   }
}

public function showWYSIWYGTextAreaEntity()
{
    return $this->buildWYSIWYGTextAreaEntity();
}




}
?>
