<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'form_entity_handler_class_inc.php';
       $this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$this->appendArrayVar('headerParams', '<script type="text/javascript">jQuery.noConflict();</script>');
class form_entity_textinput extends form_entity_handler
{
    private $objTextInput;
    private $tiFormName;
    private $tiName;
    private $tiTextValue;
    private $tiType;
    private $tiSize;
    private $tiTextMask;

    protected  $objDBtiEntity;
  
//textinput($name=null, $value=null, $type=null, $size=null)
    public function  init()
    {

        $this->loadClass('textinput','htmlelements');
         $this->loadClass('inputmasks', 'htmlelements');

        $this->tiName=Null;
        $this->tiFormName =NULL;
        $this->tiTextValue=NULL;
        $this->tiType=NULL;
        $this->tiSize=NULL;
        $this->tiTextMask=NULL;

        $this->objDBtiEntity = $this->getObject('dbformbuilder_textinput_entity','formbuilder');
        //$this->tempWYSIWYGBoolDefaultSelected=FALSE;
                }

    public function createFormElement($textInputFormName='',$textInputName='')
    {
    $this->tiFormName = $textInputFormName;
    $this->tiName=$textInputName;
      
        
    }
public function getTextInputName()
{
    return $this->tiName;
}

public function insertTextInputParameters($textinputformname,$textinputname,$textvalue,$texttype,$textsize,$maskedinputchoice)
{

    if ($this->objDBtiEntity->checkDuplicateTextInputEntry($textinputformname,$textinputname) == TRUE)
    {
        $this->objDBtiEntity->insertSingle($textinputformname,$textinputname,$textvalue,$texttype,$textsize,$maskedinputchoice);

        $this->tiName = $textinputname;
        $this->tiTextValue=$textvalue;
        $this->tiType=$texttype;
        $this->tiSize=$textsize;
        $this->tiTextMask=$maskedinputchoice;

    return TRUE;
    }
 else {
        return FALSE;
    }
}

private function buildWYSIWYGTextInputEntity()
{
    $this->objTextInput = new textinput($this->tiName,  $this->tiTextValue, $this->tiType, $this->tiSize);
    if ($this->tiTextMask != "default")
    {
        $this->objTextInput->setCss($this->tiTextMask);
    }

$objInputMasks = $this->getObject('inputmasks', 'htmlelements');
return $objInputMasks->show().$this->objTextInput->show();

}

public function showWYSIWYGTextInputEntity()
{
    return $this->buildWYSIWYGTextInputEntity();
}




}
?>
