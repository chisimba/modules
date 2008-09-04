<?php
/**
 * Presence IM dbtable derived class
 * 
 * Class to interact with the database for the popularity contest module
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
 * @category  chisimba
 * @package   im
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2008 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: $
 * @link      http://avoir.uwc.ac.za
 * @see       api
 */
class dbimpresence extends dbTable 
{
    
    /**
     * Constructor
     *
     */
    public function init()
    {
        parent::init('tbl_im_presence');
    }
    
    
    public function updatePresence($userarr)
    {
    	// check if user exists in msg table
    	$status = $this->userExists($userarr['from']);
    	if($status == FALSE)
    	{
    		$times = $this->now();
    		$insarr['datesent'] = $times;
    		$insarr['person'] = $userarr['from'];
    		$insarr['status'] = $userarr['status'];
    		$insarr['show'] = $userarr['show'];
    		
    		$this->addRecord($insarr);
    	}
    	else {
    		// update the presence info for this user
    		
    	}
    }
    
    /**
     * Private method to insert a record to the popularity contest table as a log.
     * 
     * This method takes the IP and module_name and inserts the record with a timestamp for temporal analysis. 
     *
     * @param array $recarr
     * @return string $id
     */
    private function addRecord($pl)
    {
    	$times = $this->now();
    	$recarr['datesent'] = $times;
    	$recarr['msgtype'] = $pl['type'];
    	$recarr['msgfrom'] = $pl['from'];
    	$recarr['msgbody'] = $pl['body'];
    	// Check for empty messages
    	if($recarr['msgbody'] == "")
    	{
    		return;
    	}
    	else {
    		return $this->insert($recarr, 'tbl_im');
    	}
    }

    public function userExists($user)
    {
    	$count = $this->getRecordCount("WHERE msgfrom = '$user'");	
    	if($count > 0)
    	{
    		return TRUE;
    	}
    	else {
    		return FALSE;
    	}
    }
    
}
?>