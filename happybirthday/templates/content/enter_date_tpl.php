<?php
/*
* @author Emmanuel Natalis
* @Software developer university of dar es salaam
* @copyright (c) 2008 GNU GPL
* @package happyBirthDay
* @version 1
*/
$this->objLangu=$this->getObject('language','language');
$this->deleteSuccess=$this->objLangu->languageText('mod_happybirthday_deletesuccess','happybirthday');
$this->notAvailable=$this->objLangu->languageText('mod_happybirthday_useravailable','happybirthday');
$this->happybirthday=$this->objLangu->languageText('mod_happybirthday_happybirthday','happybirthday');
if(isset($remove)) //Starting of the removal codes
{
 $obj=$this->getObject('dbhappybirthday','happybirthday');
 $status=$obj->deleteBirthdate();
 if($status=='deleted')
 {
  echo($this->deleteSuccess);
 } else
   if($status=='not_exist')
 {
   echo $this->notAvailable;
 }
  
} else

if(isset($view_users)) //Displaying users celebrating their birthdate today
{
  $obj=$this->getObject('dbhappybirthday','happybirthday');
  echo("<h1><font color='green'>".$this->happybirthday."</font></h1>");
  $obj->displayUser();
} else

//Starting of the insertion codes
{
//get the happybirthday object and instantiate it
$this->obj=$this->getObject('enterdate','happybirthday');
echo $this->obj->displayMsg();
//echo $this->obj->show();


//Get the CSS layout to make two column layout
$cssLayout = $this->newObject('csslayout', 'htmlelements');
//Add some text to the left column
$cssLayout->setLeftColumnContent("");

//Add the form to the middle (right in two column layout) area
$cssLayout->setMiddleColumnContent($this->obj->show());
echo $cssLayout->show();
}
  
?>
