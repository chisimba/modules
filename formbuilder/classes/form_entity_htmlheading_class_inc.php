<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_htmlheading extends form_entity_handler
{
    private $objHTMLHeading;

    private $headingName;
    private $headingValue;
    private $textAlignment;
    private $fontSize;
    
    protected $objDBHTMLHeadingEntity;

    public function  init()
    {

$this->loadClass('htmlheading','htmlelements');
$this->objDBHTMLHeadingEntity = $this->getObject('dbformbuilder_htmlheading_entity','formbuilder');

                }

    public function createFormElement($headingName,$heading,$fontSize,$textAlignment)
    {

         if ($this->objDBHTMLHeadingEntity->checkDuplicateHTMLheadingEntry($headingName,$heading) == TRUE)
         {

             $this->headingName = $headingName;
             $this->headingValue = $heading;
             $this->fontSize = $fontSize;
             $this->textAlignment = $textAlignment;
             $this->objDBHTMLHeadingEntity->insertSingle($headingName,$heading,$fontSize,$textAlignment);

        return TRUE;
         }
         else
         {
             return FALSE;
         }
         
    }
    public function getWYSIWYGHeadingName()
{
    return $this->headingName;
}
protected function getHeadingName($HTMLHeadingFormName)
{
  $HTMLHeadingParameters = $this->objDBHTMLHeadingEntity->listHTMLHeadingParameters($HTMLHeadingFormName);
$HTMLHeadingNameArray = array();
  foreach($HTMLHeadingParameters as $thisHTMLHeadingParameter){
   //Store the values of the array in variables

   //$checkboxName = $thisCheckboxParameter["checkboxname"];
  $headingName = $thisHTMLHeadingParameter["headingname"];
  $HTMLHeadingNameArray[]=$headingName;
}
return $HTMLHeadingNameArray;
}

    public function getWYSIWYGHTMLHeadingInsertForm($formName)
    {
        $WYSIWYGLabelInsertForm="<b>Text Heading HTML ID and Name Menu</b>";
        $WYSIWYGLabelInsertForm.="<div id='HeadingNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
        $WYSIWYGLabelInsertForm.= $this->buildInsertIdForm('Heading',$formName,"70")."";
                $WYSIWYGLabelInsertForm.= "</div>";
           $WYSIWYGLabelInsertForm.="<b>Text Heading Properties Menu</b>";
        $WYSIWYGLabelInsertForm.="<div id='HeadingNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
       $WYSIWYGLabelInsertForm.= $this->insertFontSizeForm()."<br><br>";
        $WYSIWYGLabelInsertForm.= $this->insertTextAlignmentType()."<br><br>";
          $WYSIWYGLabelInsertForm.= $this->insertTextForm('HTML Heading',2,68);
                          $WYSIWYGLabelInsertForm.= "</div>";
           return $WYSIWYGLabelInsertForm;
    }
protected function deleteHTMLHeadingEntity($formElementName)
{
    $deleteSuccess = $this->objDBHTMLHeadingEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}

protected function constructHTMLHeadingEntity($HTMLHeadingName)
{
  // return $checkboxName;
    $HTMLHeadingParameters = $this->objDBHTMLHeadingEntity->listHTMLHeadingParameters($HTMLHeadingName);

//return $checkboxParameters;
foreach($HTMLHeadingParameters as $thisHTMLHeadingParameter){
   //Store the values of the array in variables

   //$checkboxName = $thisCheckboxParameter["checkboxname"];
  $headingName = $thisHTMLHeadingParameter["headingname"];
  $headingText = $thisHTMLHeadingParameter["heading"];
      $headingSize = $thisHTMLHeadingParameter["size"];
      $textAlignment = $thisHTMLHeadingParameter["alignment"];

$HTMLHeadingUnderConstruction = new htmlheading($headingText, $headingSize);
$HTMLHeadingUnderConstruction->align = $textAlignment;
$currentConstructedHTMLHeading = $HTMLHeadingUnderConstruction->show();

 $constructedHTMLHeading .=$currentConstructedHTMLHeading;
}
 // return "checkbod";
  return  $constructedHTMLHeading;
}



private function buildWYSIWYGHTMLHeadingEntity()
{

$this->objHTMLHeading = new htmlheading($this->headingValue,  $this->fontSize);
$this->objHTMLHeading->align = $this->textAlignment;
return $this->objHTMLHeading->show();

}

public function showWYSIWYGHTMLHeadingEntity()
{
    return $this->buildWYSIWYGHTMLHeadingEntity();
}




}
?>
