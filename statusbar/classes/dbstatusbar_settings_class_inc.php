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
     * @param string $userId The id of the user to get settings for
     * @return array The array of setting
     */
    public function getSettings($userId)
    {
        $sql = "SELECT * FROM $this->table AS s";
        $sql .= " LEFT JOIN `tbl_statusbar_configs` AS c";
        $sql .= " ON s.config_id = c.id";
        $sql .= " WHERE s.user_id = '$userId'";
        
        return $this->getArray($sql);
    }
}
?>