<?php
/**
 * bestwishesforms class
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
 * @package   bestwishes
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
    class bestwishesforms extends object
    {
    
        
        function init()
            {
                 $this->objLanguage=$this->getObject('language','language');
            }
   

   public function buildFormEnterEvent()
   {
      $this->loadClass('form','htmlelements');
      $this->loadClass('textinput', 'htmlelements'); 
      $this->loadClass('textarea', 'htmlelements'); 
      $this->loadClass('button', 'htmlelements'); 
      $this->loadClass('label', 'htmlelements'); 
      $formAct=$this->siteroot."?module=bestwishes&action=saveEvent";
      $objTitle=new label($this->objLanguage->languageText('mod_bestwishes_entermsgtitle','bestwishes'),1);
      $objDescription=new label($this->objLanguage->languageText('mod_bestwishes_eventdescription','bestwishes'),2);
      $objForm = new form('comments',$formAct);
      $objTextinput=new textinput('title');
      $objTextarea=new textarea('description');
      $objButton=new button('submit','submit');
      $objButton->setToSubmit();
       $objForm->addToForm("<center>".$objTitle->show()."</center><br>");
      $objForm->addToForm("<center>".$objTextinput->show()."</center><br>");
       $objForm->addToForm("<center>".$objDescription->show()."</center><br>");
      $objForm->addToForm("<center>".$objTextarea->show()."</center><br>");
      $objForm->addToForm("<center>".$objButton->show()."</center>");
      return $objForm->show();
    }
    }
?>
