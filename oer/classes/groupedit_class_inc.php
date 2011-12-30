<?php
/**
 *
 * Group editor functionality for OER module
 *
 * Group editor functionality for OER module provides for the creation of the
 * group editor form, which is used by the class block_groupedit_class_inc.php
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
 * @package   oer
 * @author    Derek Keats derek@dkeats.com
 * @author    David Wafula
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
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
 * Group editor functionality for OER module
 *
 * Group editor functionality for OER module provides for the creation of the
 * group editor form, which is used by the class block_groupedit_class_inc.php
*
* @package   oer
* @author    Derek Keats derek@dkeats.com
*
*/
class groupedit extends object
{

    private $userstring;
    private $group;
    private $linkedInstitution;

public function xxinit(){}

    /**
    *
    * Intialiser for the oerfixer database connector
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // Configure the userstring
        $this->userstring = $this->getParam('userstring', NULL);
        if($this->userstring !== NULL) {
            $this->userstring = base64_decode($this->userstring);
            $this->userstring = explode(',', $this->userstring);
        }
        // Load all the required HTML classes from HTMLElements module
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('fieldset','htmlelements');
        //Get Group details
        $this->objDbGroups = $this->getObject('dbgroups');
        $this->group=$this->objDbGroups->getGroupInfo($this->getParam('id', NULL));
        //$this->linkedInstitution=$this->objDbGroups->getLinkedInstitution($this->getParam('id'));
    }

    public function show()
    {
        return "WORKING HERE";
    }

    /**
     *
     * Make a heading for the form
     *
     * @return string The text of the heading
     * @access private
     *
     */
    private function makeHeading()
    {
        // setup and show heading
        $header = new htmlheading();
        $header->type = 1;
        $header->str = $this->group[0]['name'].":"."Profile";  //objLang @ToDo
        return $header->show();
    }

}
?>