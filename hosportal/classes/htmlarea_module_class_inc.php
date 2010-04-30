<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'chisimba_modules_handler_class_inc.php';

class htmlarea_module extends chisimba_modules_handler
{

private $HTMLArea;
//$objConfirm = &$this->newObject('confirm', 'utilities');
//   //Set object to confirm and the path for the confirm implementation and confirm text
//   $objConfirm->setConfirm($iconDelete = $this->objIcon->showIcon() , $this->uri(array(
//    'module' => 'hosportal',
//    'action' => 'delete',
//    'id' => $id
//    )) , $this->objouputtext->insertTextFromConfigFile("mod_hosportal_suredelete"));

    public function init()
    {
        // $this->objform= $this->loadClass('form','htmlelements');
//          $this->$objForm = new form($name_of_form, $form_action);
       // $this->objLanguage = $this->getObject('language','language');
        //$this->objIcon = $this->getObject('geticon','htmlelements');
    }
 //    public function setIcon($name, $type = 'gif', $iconfolder='icons/')

    //$iconDelete->align = false
//       $iconEdSelect = $this->getObject('geticon','htmlelements');
//   $iconEdSelect->setIcon('edit');
//   $iconEdSelect->alt = "Edit Comment";
    public function createNewObjectFromModule($name_of_class = 'htmlarea',$name_of_module = 'htmlelements')
    {
        return $this->HTMLArea = $this->newObject($name_of_class, $name_of_module);
       // return $this->objConfirm = &$this->newObject($name_of_class, $name_of_module);
    }





// return   $this->objForm = new form($name_of_form, $form_action);
public function setInputVariableForHTMLArea($name_of_input_variable)
            {
        
    return  $this->HTMLArea->setContent($name_of_input_variable);

            }

            public function setHTMLAreaName($name_of_HTML_area)
            {
                return  $this->HTMLArea->setName($name_of_HTML_area);

            }
            public function setToolBarType()
            {

                return  $this->HTMLArea->setDefaultToolBarSet();
            }
public function showHTMLArea()
        {
       return $this->HTMLArea->showFCKEditor();
        }

public function EditModule()
{
}





}


?>

