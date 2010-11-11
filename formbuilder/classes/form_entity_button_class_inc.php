<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_button extends form_entity_handler
{
    private $objButton;
    private $buttonFormName;
    private $buttonName;
    private $buttonLabel;
    private $isSetOrResetChoice;
    private $objDBbuttonEntity;

    public function  init()
    {
$this->loadClass('button','htmlelements');
 $this->objDBbuttonEntity = $this->getObject('dbformbuilder_button_entity','formbuilder');

 $this->buttonFormName = NULL;
 $this->buttonName = NULL;
 $this->buttonLabel = NULL;
 $this->isSetOrResetChoice= NULL;
                }

    public function createFormElement($buttonFormName,$buttonName,$buttonLabel,$isSetToResetOrSubmit)
    {

         if ($this->objDBbuttonEntity->checkDuplicateButtonEntry($buttonFormName,$buttonName) == TRUE)
         {

             $this->buttonFormName = $buttonFormName;
             $this->buttonName = $buttonName;
             $this->buttonLabel = $buttonLabel;
             $this->isSetOrResetChoice = $isSetToResetOrSubmit;
        $this->objDBbuttonEntity->insertSingle($buttonFormName,$buttonName,$buttonLabel,$isSetToResetOrSubmit);
        return TRUE;
         }
         else
         {
             return FALSE;
         }
         
    }
public function getWYSIWYGButtonName()
{
    return $this->buttonName;
}
    public function getWYSIWYGButtonInsertForm($formName)
    {
        $WYSIWYGButtonInsertForm="<b>Button HTML ID and Name Menu</b>";
        $WYSIWYGButtonInsertForm.="<div id='labelNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
        $WYSIWYGButtonInsertForm.= $this->buildInsertIdForm('button',$formName,"70")."<br>";
       $WYSIWYGButtonInsertForm.= $this->buildInsertFormElementNameForm('button', "70")."<br>";
               $WYSIWYGButtonInsertForm.="</div>";
               $WYSIWYGButtonInsertForm.="<b>Button Properties Menu</b>";
        $WYSIWYGButtonInsertForm.="<div id='labelNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> " ;
       $WYSIWYGButtonInsertForm.= $this->insertButtonParametersForm()."<br>";
               $WYSIWYGButtonInsertForm.="</div>";
           return $WYSIWYGButtonInsertForm;
    }
protected function deleteButtonEntity($formElementName)
{
    $deleteSuccess = $this->objDBbuttonEntity->deleteFormElement($formElementName);
    return $deleteSuccess;
}


protected function constructButtonEntity($buttonFormName)
{
  // return $checkboxName;
    $buttonParameters = $this->objDBbuttonEntity->listButtonParameters($buttonFormName);

//return $checkboxParameters;
foreach($buttonParameters as $thisbuttonParameter){
   //Store the values of the array in variables

   //$checkboxName = $thisCheckboxParameter["checkboxname"];
  //$buttonFormName = $thisbuttonParameter["buttonformname"];
  $buttonName = $thisbuttonParameter["buttonname"];
      $buttonLabel = $thisbuttonParameter["buttonlabel"];
  $isSetToResetOrSubmit = $thisbuttonParameter["issettoresetorsubmit"];

$buttonUnderConstuction= new button($buttonName);
$buttonUnderConstuction->setValue($buttonLabel);
if ($isSetToResetOrSubmit == "reset")
{
  $buttonUnderConstuction->setToReset();
}
else
{
   $buttonUnderConstuction->setToSubmit();
}
$currentConstructedButton = $buttonUnderConstuction->showDefault();
 $constructedButton .=$currentConstructedButton;
}

return $constructedButton;
}

private function buildWYSIWYGButtonEntity()
{

     $this->objButton=new button($this->buttonName);
             $this->objButton->setValue($this->buttonLabel);
             

             if ($this->isSetOrResetChoice == "submit")
             {
                $this->objButton->setToSubmit();  //If you want to make the button a submit button
             }
             else
             {
                $this->objButton->setToReset();
             }
 return $this->objButton->show();


}

public function showWYSIWYGButtonEntity()
{
    return $this->buildWYSIWYGButtonEntity();
}




}
?>
