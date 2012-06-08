<?php
/**
 *
 * Database access for statusbar
 *
 * Database access for statusbar. This is a sample database model class
 * that you will need to edit in order for it to work.
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
 * @package   statusbar
 * @author    Kevin Cyster kcyster@gmail.com
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
* Database access for statusbar
*
* Database access for statusbar. This is a sample database model class
* that you will need to edit in order for it to work.
*
* @package   statusbar
* @author    Kevin Cyster kcyster@gmail.com
*
*/
class dbstatusbar_settings extends dbtable
{
    /**
    * 
    * @var string $table  String object property for holding the table object
    * @access public;
    */
    public $table;    

    /**
     * 
     * Variable to hold the PKId
     * 
     * @access public
     * @var string
     */
    public $PKId;

    /**
    *
    * Intialiser for the statusbar database connector
    * @access public
    * @return VOID
    */
    public function init()
    {
        //Set the parent table to our demo table
        parent::init('tbl_statusbar_settings');
        $this->table = 'tbl_statusbar_settings';
    }

    /**
     *
     * Get the statusbar settings.
     *
     * @access public
     * @param string $PKId The PKId of the user to get settings for
     * @return array The array of setting
     */
    public function getSettings()
    {
        return $this->fetchAll("WHERE user_id = '$this->PKId'");
    }
    
    /**
     *
     * Method to save the settings
     * 
     * @access public
     * @param string $orientation The orientation setting for the user
     * @param string $position The position setting for the user
     * @return VOID
     */
    public function saveSettings($orientation, $position, $display)
    {
        $this->delete('user_id', $this->PKId);
        
        $data = array();
        $data['user_id'] = $this->PKId;
        $data['param'] = 'orientation';
        $data['value'] = $orientation;
        $data['created_by'] = $this->PKId;
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->insert($data);

        $data = array();
        $data['user_id'] = $this->PKId;
        $data['param'] = 'position';
        $data['value'] = $position;
        $data['created_by'] = $this->PKId;
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->insert($data);

        $data = array();
        $data['user_id'] = $this->PKId;
        $data['param'] = 'display';
        $data['value'] = $display;
        $data['created_by'] = $this->PKId;
        $data['date_created'] = date('Y-m-d H:i:s');

        $this->insert($data);
    }
}
?>