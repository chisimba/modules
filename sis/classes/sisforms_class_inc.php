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
    public function init() {
        try {
            $this->objLanguage = $this->getObject ( 'language', 'language' );
            $this->objUser = $this->getObject ( "user", "security" );
            $this->sysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );

            // Load up the HTML elemnts we may need
            $this->loadClass ( 'form', 'htmlelements' );
            $this->loadClass ( 'textinput', 'htmlelements' );
            $this->loadClass ( 'textarea', 'htmlelements' );
            $this->loadClass ( 'button', 'htmlelements' );
            $this->loadClass ( 'checkbox', 'htmlelements' );
            $this->loadClass ( 'dropdown', 'htmlelements' );
            $this->loadClass ( 'label', 'htmlelements' );
            $objCaptcha = $this->getObject ( 'captcha', 'utilities' );
            $this->required = '<span class="warning"> * ' . $this->objLanguage->languageText ( 'word_required', 'system', 'Required' ) . '</span>';
        } catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }

    }

    public function profileForm($recid, $featurebox = FALSE) {
        $prform = new form ( 'updateprofile', $this->uri ( array ('module' => 'sis', 'action' => 'updateprofile', 'recid' => $recid ) ) );
        $prfieldset = $this->getObject ( 'fieldset', 'htmlelements' );
        $prfieldset->setLegend ( $this->objLanguage->languageText ( 'mod_sis_updateprofile', 'sis' ) );

        $prtbl = $this->newObject ( 'htmltable', 'htmlelements' );
        $prtbl->cellpadding = 3;

        //start the inputs
        $personalfieldset = $this->newObject ( 'fieldset', 'htmlelements' );
        $personalfieldset->setLegend ( $this->objLanguage->languageText ( 'mod_sis_personaldetails', 'sis' ) );

        // textinput for last name (required)
        $ln = new textinput ( 'lastname' );
        $lnlabel = new label ( $this->objLanguage->languageText ( "mod_sis_lastname", "sis" ) . ':', 'comm_input_ln' );

        // First name
        $fn = new textinput ( 'firstname' );
        $fnlabel = new label ( $this->objLanguage->languageText ( "mod_sis_firstname", "sis" ) . ':', 'comm_input_fn' );

        // Middle name
        $mn = new textinput ( 'firstname' );
        $mnlabel = new label ( $this->objLanguage->languageText ( "mod_sis_middlename", "sis" ) . ':', 'comm_input_mn' );

        // Occupation
        $oc = new textinput ( 'occupation' );
        $oclabel = new label ( $this->objLanguage->languageText ( "mod_sis_occupation", "sis" ) . ':', 'comm_input_oc' );

        // Employer
        $em = new textinput ( 'employer' );
        $emlabel = new label ( $this->objLanguage->languageText ( "mod_sis_employer", "sis" ) . ':', 'comm_input_em' );

        // Username (required)
        $un = new textinput ( 'username' );
        $unlabel = new label ( $this->objLanguage->languageText ( "mod_sis_username", "sis" ) . ':', 'comm_input_un' );

        // Nested table for the personal details fieldset
        $perstbl = $this->newObject ( 'htmltable', 'htmlelements' );
        $perstbl->cellpadding = 3;
        $perstbl->startRow ();
        $perstbl->addCell ( $lnlabel->show () );
        $perstbl->addCell ( $mnlabel->show () );
        $perstbl->addCell ( $fnlabel->show () );
        $perstbl->endRow ();
        $perstbl->startRow ();
        $perstbl->addCell ( $ln->show () . $this->required );
        $perstbl->addCell ( $mn->show () ); // not required
        $perstbl->addCell ( $fn->show () . $this->required );
        $perstbl->endRow ();
        $perstbl->startRow ();
        $perstbl->addCell ( $oclabel->show () );
        $perstbl->addCell ( $emlabel->show () );
        $perstbl->addCell ( $unlabel->show () );
        $perstbl->endRow ();
        $perstbl->startRow ();
        $perstbl->addCell ( $oc->show () );
        $perstbl->addCell ( $em->show () );
        $perstbl->addCell ( $un->show () . $this->required );
        $perstbl->endRow ();

        // add the table to the fieldset
        $personalfieldset->addContent ( $perstbl->show () );

        $prtbl->startRow ();
        $prtbl->addCell ( $personalfieldset->show () );
        $prtbl->endRow ();

        // Address fields (all required!)
        $addfieldset = $this->newObject ( 'fieldset', 'htmlelements' );
        $addfieldset->setLegend ( $this->objLanguage->languageText ( 'mod_sis_address', 'sis' ) );

        $str = new textinput ( 'street' );
        $strlabel = new label ( $this->objLanguage->languageText ( "mod_sis_street", "sis" ) . ':', 'comm_input_str' );

        // City
        $city = new textinput ( 'city' );
        $citylabel = new label ( $this->objLanguage->languageText ( "mod_sis_city", "sis" ) . ':', 'comm_input_city' );

        // State
        $state = new textinput ( 'state' );
        $statelabel = new label ( $this->objLanguage->languageText ( "mod_sis_state", "sis" ) . ':', 'comm_input_state' );
        $state->maxlength = 2;
        $state->size = 2;

        // Zip
        $zip = new textinput ( 'zip' );
        $ziplabel = new label ( $this->objLanguage->languageText ( "mod_sis_zip", "sis" ) . ':', 'comm_input_zip' );
        $state->maxlength = 5;

        $prtbl->startRow ();
        // we need to make a nested table here
        $adtbl = $this->newObject ( 'htmltable', 'htmlelements' );
        $adtbl->cellpadding = 3;

        $adtbl->startRow ();
        $adtbl->addCell ( $strlabel->show () );
        $adtbl->addCell ( $str->show () . $this->required );
        $adtbl->endRow ();

        $adtbl->startRow ();
        $adtbl->addCell ( $citylabel->show () );
        $adtbl->addCell ( $city->show () . $this->required );
        $adtbl->endRow ();

        $adtbl->startRow ();
        $adtbl->addCell ( $statelabel->show () );
        $adtbl->addCell ( $state->show () . $this->required );
        $adtbl->endRow ();

        $adtbl->startRow ();
        $adtbl->addCell ( $ziplabel->show () );
        $adtbl->addCell ( $zip->show () . $this->required );
        $adtbl->endRow ();

        // stick the address content into the address fieldset
        $addfieldset->addContent ( $adtbl->show () );
        //$prform->addToForm($addfieldset->show());
        // add the address table info
        $prtbl->addCell ( $addfieldset->show () );
        $prtbl->endRow ();

        // Now we need the email address info also (required)
        $email = new textinput ( 'email' );
        $emaillabel = new label ( $this->objLanguage->languageText ( "mod_sis_emailaddress", "sis" ) . ':', 'comm_input_email' );
        $primail = new checkbox ( 'emailpriv' );
        $primaillabel = new label ( $this->objLanguage->languageText ( "mod_sis_private", "sis" ) . ':', 'comm_check_email' );

        // add the email stuff to the next cell
        $mailfieldset = $this->newObject ( 'fieldset', 'htmlelements' );
        $mailfieldset->setLegend ( $this->objLanguage->languageText ( 'mod_sis_email', 'sis' ) );
        // stick the email address content into the mail fieldset
        // nested table for the email info
        $mtbl = $this->newObject ( 'htmltable', 'htmlelements' );
        $mtbl->cellpadding = 3;
        $mtbl->startRow ();
        $mtbl->addCell ( $emaillabel->show () );
        $mtbl->addCell ( $email->show () . $this->required );
        $mtbl->endRow ();
        $mtbl->startRow ();
        $mtbl->addCell ( $primaillabel->show () );
        $mtbl->addCell ( $primail->show () );
        $mtbl->endRow ();

        $mailfieldset->addContent ( $mtbl->show () );

        // add to the parent table
        $prtbl->startRow ();
        $prtbl->addCell ( $mailfieldset->show () );
        $prtbl->endRow ();

        // Now we need the phone info (required)
        // home phone (required)
        $hphone = new textinput ( 'hphone' );
        $hphonelabel = new label ( $this->objLanguage->languageText ( "mod_sis_homephone", "sis" ) . ':', 'comm_input_hphone' );

        // cell phone
        $cellpriv = new checkbox ( 'cellpriv' );
        $cellprivlabel = new label ( $this->objLanguage->languageText ( "mod_sis_private", "sis" ) . ':', 'comm_check_cell' );
        $cphone = new textinput ( 'cphone' );
        $cphonelabel = new label ( $this->objLanguage->languageText ( "mod_sis_cellphone", "sis" ) . ':', 'comm_input_cphone' );

        // work phone (required)
        $wphone = new textinput ( 'wphone' );
        $wphonelabel = new label ( $this->objLanguage->languageText ( "mod_sis_workphone", "sis" ) . ':', 'comm_input_wphone' );

        // add the phone fieldset
        $phonefieldset = $this->newObject ( 'fieldset', 'htmlelements' );
        $phonefieldset->setLegend ( $this->objLanguage->languageText ( 'mod_sis_phonedetails', 'sis' ) );

        // nested table for the phone info
        $phtbl = $this->newObject ( 'htmltable', 'htmlelements' );
        $phtbl->cellpadding = 3;

        $phtbl->startRow ();
        $phtbl->addCell ( $hphonelabel->show () );
        $phtbl->addCell ( $hphone->show () . $this->required );
        $phtbl->endRow ();
        $phtbl->startRow ();
        $phtbl->addCell ( $wphonelabel->show () );
        $phtbl->addCell ( $wphone->show () . $this->required );
        $phtbl->endRow ();
        $phtbl->startRow ();
        $phtbl->addCell ( $cphonelabel->show () );
        $phtbl->addCell ( $cphone->show () . " " . $cellprivlabel->show () . " " . $cellpriv->show () );
        $phtbl->endRow ();

        // stick the phone content into the table
        $phonefieldset->addContent ( $phtbl->show () );

        $prtbl->startRow ();
        $prtbl->addCell ( $phonefieldset->show () );
        $prtbl->endRow ();

        //end off the form and add the buttons
        $this->objPrButton = new button ( $this->objLanguage->languageText ( 'word_save', 'system' ) );
        $this->objPrButton->setValue ( $this->objLanguage->languageText ( 'word_save', 'system' ) );
        $this->objPrButton->setToSubmit ();

        $prfieldset->addContent ( $prtbl->show () );
        $prform->addToForm ( $prfieldset->show () );
        $prform->addToForm ( $this->objPrButton->show () );

        // return the form for display
        if ($featurebox == TRUE) {
            $objFeaturebox = $this->getObject ( 'featurebox', 'navigation' );
            return $objFeaturebox->showContent ( $this->objLanguage->languageText ( "mod_sis_prformheader", "sis" ), $prform->show () );
        } else {
            return $prform->show ();
        }
    }

}
?>