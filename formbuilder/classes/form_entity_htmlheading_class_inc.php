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
public function getHeadingName()
{
    return $this->headingName;
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
