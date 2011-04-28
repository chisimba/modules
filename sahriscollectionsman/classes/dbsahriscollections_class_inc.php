<?php
/**
 *
 * Database access for SAHRIS collections
 *
 * Database access for Image Vault. It allow access to the table
 *  which contains a list of posted images for the gallery.
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
 * @package   sahriscollections
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2011 AVOIR
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
 * Database access for SAHRIS collections
 *
 * Database access for Simple gallery. It allow access to the table
 *  which contains a list of posted images for the gallery.
 *
 * @package   sahriscollections
 * @author    Paul Scott <pscott@uwc.ac.za>
 *
 */
class dbsahriscollections extends dbtable
{

    /**
     *
     * Intialiser for the simpleblog database connector
     * @access public
     * @return VOID
     *
     */
    public function init()
    {
        //Set the parent table to our default table
        parent::init('tbl_sahriscollections_collections');
    }     
     
     /**
      * Method to insert a collection to the collections table
      *
      * @param  integer $userid
      * @param  array   $insarr
      * @return string id
      */
    public function insertCollection($insarr)
    {   
        $this->changeTable('tbl_sahriscollections_collections');
        $insarr['datecreated'] = $this->now();
        $collid = $this->insert($insarr);
        return $collid;
    }
    
    public function getCollectionNames() {
        $this->changeTable('tbl_sahriscollections_collections');
        return $this->getAll();
    }
    
    public function insertRecord($insarr) {
        $this->changeTable('tbl_sahriscollections_items');
        $insarr['datecreated'] = $this->now();
        return $this->insert($insarr);
    }
    
    private function changeTable($table) {
        parent::init($table);
    }
    
}
?>
