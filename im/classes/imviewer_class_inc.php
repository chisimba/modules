<?php
/**
 * 
 * Viewer class for rendering an array of messages to the browser
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
 * @package   helloforms
 * @author    Derke Keats dkeats@uwc.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: controller.php,v 1.4 2007-11-25 09:13:27 dkeats Exp $
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
* 
* Viewer class for rendering an array of messages to the browser
*
* @author Derek Keats
* @package IM
*
*/
class imviewer extends object
{
   
    /**
    * 
    * @var string $objLanguage String object property for holding the 
    * language object
    * @access public
    * 
    */
    public $objLanguage;

    /**
    * 
    * Constructor 

    * @access public
    * 
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objFeatureBox = $this->getObject('featurebox', 'navigation');
    }

    public function renderOutputForBrowser($msgs)
    {
        $objWashout = $this->getObject('washout', 'utilities');
        $ret ="";
        foreach($msgs as $msg)
        {

            // whip out a content featurebox and plak the messages in
            $from = explode('/', $msg['msgfrom']);
            $sentat = $this->objLanguage->languageText('mod_im_sentat', 'im');
            $fromuser = $this->objLanguage->languageText('mod_im_sentfrom', 'im');
            $ret .= '<div class="im_default">'
              . '<p class="im_source"><b>' . $fromuser."</b>: ".$from[0] 
              . ', &nbsp;&nbsp;<b>' . $sentat . '</b>: ' . $msg['datesent'] . '</p>'
              . '<p class="im_message">' . $objWashout->parseText(htmlentities($msg['msgbody'])) 
              . '</p></div>';

        }
        return $ret;
    }
}
?>
