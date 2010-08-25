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

    protected  $objDBtaEntity;
  
//textinput($name=null, $value=null, $type=null, $size=null)
    public function  init()
    {

        $this->loadClass('textarea','htmlelements');
      $this->loadClass('htmlarea','htmlelements');
      //   $this->loadClass('inputmasks', 'htmlelements');

        $this->taName=Null;
        $this->taFormName =NULL;
        $this->taRowSize=NULL;
        $this->taColumnSize=NULL;
        $this->taTextValue=NULL;
        $this->taSimpleorAdvancedChoice=NULL;
        $this->toolbarChoice =NULL;

        $this->objDBtaEntity = $this->getObject('dbformbuilder_textarea_entity','formbuilder');

                }

    public function createFormElement($textAreaFormName='',$textAreaName='')
    {
    $this->taFormName = $textAreaFormName;
    $this->taName= $textAreaName;
      
        
    }
public function getTextAreaName()
{
    return $this->taName;
}

public function insertTextAreaParameters($textareaformname,$textareaname,$textareavalue,$columnsize,$rowsize,$simpleoradvancedchoice,$toolbarchoice)
{

    if ($this->objDBtaEntity->checkDuplicateTextAreaEntry($textareaformname,$textareaname) == TRUE)
    {
        $this->objDBtaEntity->insertSingle($textareaformname,$textareaname,$textareavalue,$columnsize,$rowsize,$simpleoradvancedchoice,$toolbarchoice);

        $this->taName = $textareaname;
        $this->taRowSize=$rowsize;
        $this->taColumnSize=$columnsize;
        $this->taTextValue=$textareavalue;
        $this->taSimpleorAdvancedChoice=$simpleoradvancedchoice;
        $this->toolbarChoice=$toolbarchoice;

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
private function buildWYSIWYGTextAreaEntity()
{
    if ($this->taSimpleorAdvancedChoice == "textarea")
    {
        $this->objTextArea= new textarea($this->taName,$this->taTextValue,$this->taRowSize,$this->taColumnSize);
       return $this->objTextArea->show();

    }
 else {

  // $ha = new htmlarea("name","value",4,50,false);
  $this->objTextArea = $this->newObject('htmlarea','htmlelements');
  $this->objTextArea->setName($this->taName);
   //$ha->setColumns(100);
     // $ha->setRows(3);
     $this->objTextArea->setContent($this->taTextValue);
     $this->objTextArea->width =$this->convertTextAreaColumnSizeToHTMLColumnSize($this->taColumnSize);
     $this->objTextArea->height =$this->convertTextAreaRowSizeToHTMLRowSize($this->taRowSize);
       // return  $this->HTMLArea->setName($name_of_HTML_area);
       $this->objTextArea->toolbarSet= $this->toolbarChoice;
      //  return  $this->HTMLArea->setDefaultToolBarSet();
//$ha->setVersion("2.6.3");
//$ha->setFormsToolBar();
return $this->objTextArea->show();


    }


}

public function showWYSIWYGTextAreaEntity()
{
    return $this->buildWYSIWYGTextAreaEntity();
}




}
?>
