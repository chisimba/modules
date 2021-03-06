<?php

/**
 * Methods to track activities in the Chisimba framework
 * into the Chisimba framework
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
 * @package   activitystreamer
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 AVOIR
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
 * Methods to track activities in the Chisimba framework
 * into the Chisimba framework
 * 
 * @author Paul Scott <pscott@uwc.ac.za>
 */


class activitystreamsentry extends object 
{
    public $id = "";
    public $title = "";
    public $author;
    public $objects = array();
    public $verbs = array();
    public $published;
		
    function __construct() {
        $this->published = time();
    }
		
    function addObject(activitystreamsobject $object) {
        $this->objects[] = $object;
    }
    function addVerb($verb) {
        $this->verbs[] = $verb;
    }
    
    function setAuthor(activitystreamsauthor $author) {
        $this->author = $author;
    }
		
    function __toString() {
        $string = '';
        $string .=  "\n<entry>";
        $published = date('c',$this->published);
        $string .=  <<< END
        <id>{$this->id}</id>
        <title type="text"><![CDATA[{$this->title}]]></title>
        <published>{$published}</published>
END;
        if ($this->author instanceof activitystreamsauthor) $string .=  $this->author;
        if (sizeof($this->verbs)) foreach($this->verbs as $verb)
            $string .=  "\n<activity:verb>{$verb}</activity:verb>";
        if (sizeof($this->objects)) foreach($this->objects as $object)
            if ($object instanceof activitystreamsbbject) $string .=  $object;
        $string .=  "\n</entry>";
	
        return $string;
     }
}
?>
