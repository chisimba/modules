<?php
/**
*
* WTM error template
*
* This file provides a means to display action errors occuring in the WTM module
* 
* @category Chisimba
* @package wtm
* @author Yen-Hsiang Huang <wtm.jason@gmail.com>
* @copyright 2007 AVOIR
* @license http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
* @version CVS: $Id:$
* @link: http://avoir.uwc.ac.za 
*/

// security check
/**
* The $GLOBALS is an array used to control access to certain constants.
* Here it is used to check if the file is opening in engine, if not it
* stops the file from running.
*
* @global entry point $GLOBALS['kewl_entry_point_run']
* @name $kewl_entry_point_run
*/
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check


//Get the CSS layout to make two column layout
$cssLayout = $this->newObject('csslayout', 'htmlelements');
//Add some text to the left column
$cssLayout->setLeftColumnContent("Place holder text");
//get the editform object and instantiate it
$objEdit = $this->getObject('edit', 'wtm');
//Add the parsed string to the middle (right in two column layout) area
$cssLayout->setMiddleColumnContent($str);
echo $cssLayout->show();
?>
