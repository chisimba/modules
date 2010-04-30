<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'chisimba_modules_handler_class_inc.php';

class button_module extends chisimba_modules_handler
{

private $objButton;

    public function init()
    {
         $this->objButton= $this->loadClass('button','htmlelements');

    }

    public function createNewObjectFromModule($name_of_button= 'NoName' , $Id_value=NULL, $on_click=NULL)
    {
 return   $this->objButton = new button($name_of_button, $Id_value, $on_click);
}
public function EditModule()
{
}
public function buttonSetToSubmit()
{
    return $this->objButton->setToSubmit();
}

public function setButtonLabel($label_of_button)
{
      return $this->objButton->setValue($label_of_button);
}
public function showButton()
{
 return $this->objButton->show();
}


}


?>