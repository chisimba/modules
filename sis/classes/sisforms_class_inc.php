<?php
/**
 * SIS forms class
 *
 * Forms for the SIS system
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
 * @package   sis
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2008 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: $
 * @link      http://avoir.uwc.ac.za
 */
// security check - must be included in all scripts
if (! /**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
// end security check


/**
 * SIS Forms class
 *
 * Forms for the SIS system
 *
 * @category  Chisimba
 * @package   sis
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2008 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 */
class sisforms extends object {
    public $objLanguage;
    public $objUser;
    public $sysConfig;
    public $objFMPro;
    public $required;

    /**
	 * Standard init function to __construct the class
	 *
	 * @param void
	 * @return void
	 * @access public
	 */
	public function init()
	{
		try {
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objUser =  $this->getObject("user", "security");
			$this->sysConfig = $this->getObject('dbsysconfig', 'sysconfig');

			// Load up the HTML elemnts we may need
			$this->loadClass('form', 'htmlelements');
			$this->loadClass('textinput', 'htmlelements');
			$this->loadClass('textarea', 'htmlelements');
			$this->loadClass('button', 'htmlelements');
			//$this->loadClass('htmlarea', 'htmlelements');
			$this->loadClass('dropdown', 'htmlelements');
			$this->loadClass('label', 'htmlelements');
			$objCaptcha = $this->getObject('captcha', 'utilities');
			$this->required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
    	}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}

	}

	public function profileForm( $recid, $featurebox = FALSE ) {
	    $prform = new form('updateprofile', $this->uri(array('module' => 'sis', 'action' => 'updateprofile', 'recid' => $recid)));
		$prfieldset = $this->getObject('fieldset', 'htmlelements');
		$prfieldset->setLegend($this->objLanguage->languageText('mod_sis_updateprofile', 'sis'));

		$prtbl = $this->newObject('htmltable', 'htmlelements');
		$prtbl->cellpadding = 3;

		//start the inputs
		//textinput for last name (required)
		$ln = new textinput('lastname');
		$lnlabel = new label($this->objLanguage->languageText("mod_sis_lastname", "sis") . ':', 'comm_input_ln');

		// First name
		$fn = new textinput('firstname');
		$fnlabel = new label($this->objLanguage->languageText("mod_sis_firstname", "sis") . ':', 'comm_input_fn');

		// Middle name
		$mn = new textinput('firstname');
		$mnlabel = new label($this->objLanguage->languageText("mod_sis_middlename", "sis") . ':', 'comm_input_mn');

		$prtbl->startRow();
		$prtbl->addCell($lnlabel->show());
		$prtbl->addCell($mnlabel->show());
		$prtbl->addCell($fnlabel->show());
		$prtbl->endRow();
		$prtbl->startRow();
		$prtbl->addCell($ln->show().$this->required);
		$prtbl->addCell($mn->show()); // not required
		$prtbl->addCell($fn->show().$this->required);
		$prtbl->endRow();



	    //end off the form and add the buttons
		$this->objPrButton = new button($this->objLanguage->languageText('word_save', 'system'));
		$this->objPrButton->setValue($this->objLanguage->languageText('word_save', 'system'));
		$this->objPrButton->setToSubmit();

		$prfieldset->addContent($prtbl->show());
		$prform->addToForm($prfieldset->show());
		$prform->addToForm($this->objPrButton->show());

		// return the form for display
		if($featurebox == TRUE)
		{
			$objFeaturebox = $this->getObject('featurebox', 'navigation');
			return $objFeaturebox->showContent($this->objLanguage->languageText("mod_sis_prformheader", "sis"), $prform->show());
		}
		else {
			return $prform->show();
		}
	}


}
?>