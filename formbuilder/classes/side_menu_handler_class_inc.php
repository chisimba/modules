<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class side_menu_handler extends object
{


    public function init()
    {
        $this->loadClass('button','htmlelements');
    }
public function createLinks()
{
    

}
    private function buildSlideMenu()
    {
    $objHomeButton=new button('homeButton');
    $objHomeButton->setValue('Home');
    $objHomeButton->setCSS("homeButton");
$mngHomelink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'home'
   )));
    $objHomeButton->setOnClick("parent.location='$mngHomelink'");
    $mngHomeButton =     $objHomeButton->showDefault();



  $objListAllFormsButton=new button('listAllFormsButton');
  $objListAllFormsButton->setValue('List All Forms');
  $objListAllFormsButton->setCSS("listAllFormsButton");
  $objListAllFormsLink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'listAllForms',
   )));
  $objListAllFormsButton->setOnClick("parent.location='$objListAllFormsLink'");
  $mngListAllFormsButton =  $objListAllFormsButton->showDefault();

    $objCreateNewFormButton=new button('createNewFormButton');
    $objCreateNewFormButton->setValue('Create New Form');
    $objCreateNewFormButton->setCSS("createNewFormButton");
    $objCreateNewFormLink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'addFormParameters',
   )));
    $objCreateNewFormButton->setOnClick("parent.location='$objCreateNewFormLink'");
    $mngCreateNewFormButton =      $objCreateNewFormButton->showDefault();

        $objHelpButton=new button('helpButton');
        $objHelpButton->setValue('Help');
        $objHelpButton->setCSS("helpButton");
        $objHelpLink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'moduleHelp',
   )));
        $objHelpButton->setOnClick("parent.location='$objHelpLink'");
        $mngHelpButton =             $objHelpButton->showDefault();

$mngHomeButton= "<button class='homeButton' onclick=parent.location='$mngHomelink'>Home</button>";
$mngListAllFormsButton= "<button class='listAllFormsButton' onclick=parent.location='$objListAllFormsLink'>List All Constructed Forms</button>";
$mngCreateNewFormButton="<button class='createNewFormButton' onclick=parent.location='$objCreateNewFormLink'>Create New A Form</button>";
$mngHelpButton="<button class='helpButton' onclick=parent.location='$objHelpLink'>Help</button>";
$slideMenuUnderConstruction = $mngHomeButton
.$mngListAllFormsButton
.$mngCreateNewFormButton
.$mngHelpButton;

return $slideMenuUnderConstruction;
    }
    private function buildSideMenu()
    {


          $objHomeButton=new button('homeButton');
    $objHomeButton->setValue('Home');
    $objHomeButton->setCSS("homeButton");
$mngHomelink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'home'
   )));
    $objHomeButton->setOnClick("parent.location='$mngHomelink'");
    $mngHomeButton =     $objHomeButton->showDefault();



  $objListAllFormsButton=new button('listAllFormsButton');
  $objListAllFormsButton->setValue('List All Forms');
  $objListAllFormsButton->setCSS("listAllFormsButton");
  $objListAllFormsLink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'listAllForms',
   )));
  $objListAllFormsButton->setOnClick("parent.location='$objListAllFormsLink'");
  $mngListAllFormsButton =  $objListAllFormsButton->showDefault();

    $objCreateNewFormButton=new button('createNewFormButton');
    $objCreateNewFormButton->setValue('Create New Form');
    $objCreateNewFormButton->setCSS("createNewFormButton");
    $objCreateNewFormLink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'addFormParameters',
   )));
    $objCreateNewFormButton->setOnClick("parent.location='$objCreateNewFormLink'");
    $mngCreateNewFormButton =      $objCreateNewFormButton->showDefault();

        $objHelpButton=new button('helpButton');
        $objHelpButton->setValue('Help');
        $objHelpButton->setCSS("helpButton");
        $objHelpLink = html_entity_decode($this->uri(array(
    'module'=>'formbuilder',
    'action'=>'moduleHelp',
   )));
        $objHelpButton->setOnClick("parent.location='$objHelpLink'");
        $mngHelpButton =             $objHelpButton->showDefault();

        
        $mngHomeButton= "<button class='homeButton' onclick=parent.location='$mngHomelink'>Home</button>";
$mngListAllFormsButton= "<button class='listAllFormsButton' onclick=parent.location='$objListAllFormsLink'>List All Forms</button>";
$mngCreateNewFormButton="<button class='createNewFormButton' onclick=parent.location='$objCreateNewFormLink'>Create A New Form</button>";
$mngHelpButton="<button class='helpButton' onclick=parent.location='$objHelpLink'>Help</button>";
$sideMenuUnderConstruction = $mngHomeButton
."<br>".$mngListAllFormsButton
."<br>".$mngCreateNewFormButton
."<br>".$mngHelpButton;
return $sideMenuUnderConstruction;
    }

    public function showSideMenu()
{
return $this->buildSideMenu();
}
    public function showSlideMenu()
    {
return "<div id='mainMenu' style='float:left;'>".$this->buildSlideMenu()."</div>";
    }
}
?>
