<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'chisimba_modules_handler_class_inc.php';

class form_module extends chisimba_modules_handler
{
  
private $objform;

    public function init()
    {
         $this->objform= $this->loadClass('form','htmlelements');

    }
    public function createNewObjectFromModule($name_of_form= 'messages' , $form_action=NULL)
    {
 return   $this->objForm = new form($name_of_form, $form_action);
}
public function EditModule()
{
}


    public function addObjectToForm($object_to_be_added)
    {
         return $this->objForm->addToForm($object_to_be_added);
    }
    public function showBuiltForm ()
    {
        return $this->objForm->show();
    }

}


?>