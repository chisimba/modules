<?php
/**
*
* WTM list-all events template
*
* This file provides a means to display all the WTM module's events in the 
* buildings database.
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
//$refID = $this->getParam('refID');
if (!empty($refID))
{
	//echo $refID;
	//exit;
	//Instantiate the view object
	$objViewEvents = $this->getObject('viewevents', 'wtm');
	echo $objViewEvents->show();
}
else
{
	echo "it's empty";
	exit;
};
?>
