<?php
/**
 *
 * Database access for Khan Academy Importer
 *
 * Database access for Khan Academy Importer. This is a sample database model class
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
 * @package   kaimporter
 * @author    Derek Keats derek@dkeats.com
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
* Database access for Khan Academy Importer
*
* Database access for Khan Academy Importer. This is a sample database model class
* that you will need to edit in order for it to work.
*
* @package   kaimporter
* @author    Derek Keats derek@dkeats.com
*
*/
class dbkaimporter extends dbtable
{

    /**
    *
    * Intialiser for the kaimporter database connector
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        //Set the parent table to our demo table
        parent::init('tbl_contextcontent_chapters');
    }

    /**
     *
     * Get the text of the init_overview that we have in the sample database.
     *
     * @return string The text of the init_overview
     * @access public
     *
     */
    public function getChapters($contextCode)
    {
        $sql = 'SELECT
  tbl_contextcontent_chaptercontent.chaptertitle,
  tbl_contextcontent_chaptercontent.chapterid,
  tbl_contextcontent_chaptercontext.chapterid,
  tbl_contextcontent_chaptercontext.contextcode
FROM
  tbl_contextcontent_chaptercontent,
  tbl_contextcontent_chaptercontext
WHERE
    tbl_contextcontent_chaptercontent.chapterid =  tbl_contextcontent_chaptercontext.chapterid
AND
  tbl_contextcontent_chaptercontext.contextcode = \'' . $contextCode . '\'';
        $results = $this->getArray($sql);
        if (count($results) == 0) {
            return FALSE;
        } else {
            return $results;
        }

    }

}
?>