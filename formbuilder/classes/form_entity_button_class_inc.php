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
public function getButtonName()
{
    return $this->buttonName;
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
