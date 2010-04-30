<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'chisimba_modules_handler_class_inc.php';

class dropdown_module extends chisimba_modules_handler
{

private $objDropDownMenu;

    public function init()
    {
        $this->objDropDownMenu = $this->getObject('dropdown', 'htmlelements');
         //$this->objButton= $this->loadClass('button','htmlelements');

    }

    public function createNewObjectFromModule($name_of_drop_down= 'noOfMessagesDropDown')
    {

    return $this->objDropDownMenu=&new dropdown('noOfMessagesDropDown');
}
public function EditModule()
{
}

public function insertOptionIntoDropDown($name_of_option,$label_for_option)
        {
return $this->objDropDownMenu->addOption($name_of_option,$label_for_option);
        }

public function setDefaultOptionForDropDown($default_option)
        {
return $this->objDropDownMenu->setSelected($default_option);
        }
        
public function showBuildDropDownMenu()
{
    return $this->objDropDownMenu->show();
}


}


?>