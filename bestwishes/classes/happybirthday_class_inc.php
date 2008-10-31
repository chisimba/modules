<?php
/**
 * happybirthday class
 *
 * 
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *
 * @package  bestwishes
 * @author    Emmanuel Natalis  <matnatalis@udsm.ac.tz>
 * @University Computing center
 * @Dar es salaam university of Tanzania
 * @copyright 2008 Emmanuel Natalis
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
 * happybirthday class
 *
 * 
 *
 *
 * @package   bestwishes
 * @author    Emmanuel Natalis<matnatalis@udsm.ac.tz>
 * @copyright 2008 Emmanuel Natalis
 */
 class happybirthday extends object
 {
     public $fullName;
     public $objUser;
     public $siteRoot;
     public $objAltconfig;
     public $objLanguage;
     public $msg1;
     public $msg2;
     public $msg3;
     public $backBtnOnclick;
 
 
 function init()
  {
       $this->objLanguage=$this->getObject('language','language');
       $this->objUser= $this->getObject('user','security');
       $this->objAltconfig=$this->getObject('altconfig','config');
       
        $this->fullName=$this->objUser->fullname();
        $this->msg1=$this->objLanguage->languageText('mod_happybirthday_msg1','bestwishes');
        $this->msg2=$this->objLanguage->languageText('mod_happybirthday_msg2','bestwishes');
        $this->msg3=$this->objLanguage->languageText('mod_happybirthday_msg3','bestwishes');
        $this->backBtnOnclick="goBack()";

  }
  function goBack()
      {
          return "<script> function goBack() { window.location.href='".$this->objAltconfig->getsiteRoot()."?module=bestwishes'; }</script>";
      }
 
 function displayWelcMsg()
 {
 $msg="<br>".$this->msg1." <b><i> ".$this->fullName." </i></b>,<br>".$this->msg2."<br><br>";
return $msg;
  }
 private function loadElements()
  {
     $this->loadClass('form','htmlelements');
     $this->loadClass('label', 'htmlelements'); 
   }

   public function buildForm()
   {
      $this->loadElements();
     //Create the form
     //making the form action
      $formAct=$this->siteroot."?module=bestwishes&action=enterdate";
      $objForm = new form('comments',$formAct);
       //create label
       $titleLabel = new label($this->msg3,"title");
       $objForm->addToForm($titleLabel->show() . "<br />");

       //
       $this->cdate=$this->getObject('datepicker','htmlelements');
       $objForm->addToForm("<center>".$this->cdate->show() . "</center><br />");

                 //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
        $objButton = new button('save');
        // Set the button type to submit
        $objButton->setToSubmit();
         $objBack = new button('back');
          $objBack->setValue('Main menu');
          $objBack->setOnClick($this->backBtnOnclick);
        // with the word save
        $objButton->setValue(' Save ');
        $objForm->addToForm("<center>".$objButton->show());
        $objForm->addToForm($objBack->show()."</center>");
      
         

        return $objForm->show();
    }

}
 ?>
