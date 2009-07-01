<?php
/**
 * Methods which intergrates the Turnitin API
 * into the Chisimba framework
 * 
 * This module requires a valid Turnitin account/license which can 
 * purhase at http://www.turnitin.com
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
 * @category  Chisimba
 * @package   turnitin
 * @author    Wesley Nitsckie
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check


/**
 * Class to supply an easy API for use from this module or even other modules.
 * @author Wesley Nitsckie
 * @package turnitin
 */
class forms extends object
{
	
	public function init()
	{
		
		$this->objLanguage = $this->getObject ( 'language', 'language' );
	}
	
	public function addAssignmentForm()
	{
		/*
		try {
            $this->loadClass ( 'form', 'htmlelements' );
            $this->loadClass ( 'textinput', 'htmlelements' );
            $this->loadClass ( 'textarea', 'htmlelements' );
            $this->loadClass ( 'button', 'htmlelements' );
            //$this->loadClass('htmlarea', 'htmlelements');
            $this->loadClass ( 'dropdown', 'htmlelements' );
            $this->loadClass ( 'label', 'htmlelements' );
           // $objCaptcha = $this->getObject ( 'captcha', 'utilities' );
        } catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }
        
         $cform = new form ( 'massmsg');
		$cform->action = $this->uri ( array ('action' => 'massmessage' ,'module' => 'das') ) ;
        $cfieldset = $this->getObject ( 'fieldset', 'htmlelements' );
        $ctbl = $this->newObject ( 'htmltable', 'htmlelements' );
        $ctbl->cellpadding = 5;

        //textarea for the message
        $commlabel = new label ( $this->objLanguage->languageText ( 'mod_im_message', 'im' ) . ':', 'input_comminput' );
        $ctbl->startRow ();
        $ctbl->addCell ( $commlabel->show () );
        $ctbl->endRow ();
        $ctbl->startRow ();
        
        $ctbl->endRow ();

        //end off the form and add the buttons
        $this->objCButton = &new button ( $this->objLanguage->languageText ( 'mod_im_send', 'im' ) );
        $this->objCButton->setValue ( $this->objLanguage->languageText ( 'mod_im_send', 'im' ) );
        $this->objCButton->setToSubmit ();

        $cfieldset->addContent ( $ctbl->show () );
        $cform->addToForm ( $cfieldset->show () );
        $cform->addToForm ( $this->objCButton->show () );

        return $cform;
        */
		return "add assignment form";
	}
	
}