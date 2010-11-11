<?php

class home_page_handler extends object
{

   public function init()
   {
       $this->loadClass('htmlheading','htmlelements');
              $this->loadClass('link','htmlelements');
   }

   private function buildHomePage()
   {
     $homePageHeading =new  htmlheading("Form Builder", 2);
         $homePageUnderConstruction= $homePageHeading->show();

        $myFile = "packages/".$this->moduleName."/resources/text files/home.txt";


        $fh = fopen($myFile, 'r');


        $theData = fread($fh, filesize($myFile));

        fclose($fh);
         $homePageUnderConstruction .=$theData;
 $QuickStartHeading = new htmlheading("Quick Start Links", 3);
         $homePageUnderConstruction .= $QuickStartHeading->show();

   $createNewFormlink = new link($this->uri(array(
    'module'=>$this->moduleName,
    'action'=>'addFormParameters'
   )));
   $createNewFormlink->link = "Create a New Form";

   $homePageUnderConstruction .= $createNewFormlink->show()."<br>";
       
   $listAllFormslink = new link($this->uri(array(
    'module'=>$this->moduleName,
    'action'=>'listAllForms'
   )));
   $listAllFormslink->link = "List All Constructed Forms";
$homePageUnderConstruction .= $listAllFormslink->show()."<br>";

   //$homePageUnderConstruction .= $createNewFormlink->show()."<br>";

   $helpLink = new link($this->uri(array(
    'module'=>$this->moduleName,
    'action'=>'moduleHelp'
   )));
$helpLink->link = "Module Help and Tutorials";
$homePageUnderConstruction .= $helpLink->show()."<br>";
         return         $homePageUnderConstruction;
   }
public function showHomePage()
{
 return $this->buildHomePage();
}

}

?>
