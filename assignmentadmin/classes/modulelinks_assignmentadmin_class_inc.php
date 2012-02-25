<?php

/**
 *
 * Assignments
 *
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
 * @package   assignment2
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: modulelinks_assignmentadmin_class_inc.php 11340 2008-11-05 17:47:06Z davidwaf $
 * @link      http://avoir.uwc.ac.za
 */


/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts

if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}


class modulelinks_assignmentadmin extends object
{

    public function init()
    {
    	$this->loadClass('treenode','tree');
    	//$this->objAssignment = $this->getObject('dbassignmentadmin', 'assignmentadmin');
    	$this->objAssignment = $this->getObject('dbassignment', 'assignment');
    	$this->objContext = $this->getObject('dbcontext','context');
 		if($this->objContext->isInContext()){
            $this->contextCode=$this->objContext->getContextCode();
            $this->context=$this->objContext->getTitle();
        }
    }
    
     public function show()
    {
        // Link to Module itself - First Level
        $rootNode = new treenode (array('link'=>$this->uri(NULL, 'assignmentadmin'), 'text'=>'Assignment Management'));
        $assignments = array();
        // Get Assignments - Second Level
        //$assignments = getContextLinks($this->contextCode);
        $contextCode = $this->contextCode;
        $sql = "SELECT * FROM tbl_assignment WHERE context = '$contextCode' ORDER BY name";
        $assignments = $this->objAssignment->getArray($sql);

        // Extra Check
        if (count($assignments) > 0) {
            
            // Array for References
            $nodesArray = array();
            
            // Loop through Podcasters - second level
            foreach ($assignments as $assignment)
            {
                // Create Node
                $node =& new treenode(array('link'=>$this->uri(array('id'=>$assignment['id'],'action' => 'view')), 'text'=>$assignment['name']));
                
                // Create Reference to Node
                $nodesArray['assignment'.$assignment['id']] =& $node;
                
                // Attach to Root Node
                $rootNode->addItem($node);
            }
            
        }
       
        // Return Root Node
        return $rootNode;
    }
    
    
    /**
     * 
     *Method to get a set of links for a context
     *@param string $contextCode
     * @return array
     */
    public function getContextLinks($contextCode)
    { 
          $sql = "SELECT * FROM tbl_assignment WHERE context = '$contextCode' ORDER BY name";
          $data = $this->objAssignment->getArray($sql);
             
          $bigArr = array();
	
	      foreach ($data as $assignmentadmin)
	      {
	          $newArr = array();    
	          $newArr['name'] = $assignmentadmin['name'];
	          $newArr['description'] = $assignmentadmin['description'];
	          $newArr['itemid'] = $assignmentadmin['id'];
	          $newArr['moduleid'] = 'assignmentadmin';
	          $newArr['params'] = array('id'=>$assignmentadmin['id'],'action' => 'view');
	          $bigArr[] = $newArr;
	      }
	      
	      return $bigArr;
         
    }
    
}

?>
