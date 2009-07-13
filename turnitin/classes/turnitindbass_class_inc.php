<?php
/**
 * 
 *
 * Class to interact with the database for the Turnitin Assignments 
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
 * @package   turnitin
 * @author    Wesley Nitsckie <wesleynitsckie@gmail.com>
 * @copyright 2008 Wesley Nitsckie
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: $
 * @link      http://avoir.uwc.ac.za
 */
class turnitindbass extends dbTable
{

    /**
     * Constructor
     *
     */
    public function init()
    {
        parent::init('tbl_turnitin_assignments');
        
    }
    
    /**
     * Method to add an assigment detail
     *
     * @param string $contextcode
     * @param array $params
     * @return boolean
     */
    
    public function addAssignment($contextcode, $params)
    {
    	$recarr['title'] = $params['assignmenttitle'];
    	$recarr['contextcode'] = $contextcode;
    	$recarr['duedate'] = $params['assignmentdatedue'];
    	$recarr['instructions'] = $params['assignmentinstruct'];
    	
    	if($recarr['title'] == "")
        {
            return false;
        }
        else {
            return $this->insert($recarr, 'tbl_turnitin_assignments');
        }
    }
    
    public function getAssignments($contextCode)
    {
    	$recs = $this->getAll("WHERE contextcode='$contextCode' ORDER BY duedate");
    	if(count($recs) > 0)
    	{
    		return $recs;
    	} else {
    		return false;
    	}
    }
    
    
}