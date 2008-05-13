<?php

/**
* File modulelinks extends object
*
* @author Yasser Buchana
* @copyright (c) 2007 UWC
* @version 0.1
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
    	//$objDbContext = $this->getObject('dbcontext', 'context');
 		//$this->contextCode = $objDbContext->getContextCode();
    }
    
     public function show()
    {
        // Link to Module itself - First Level
        $rootNode = new treenode (array('link'=>$this->uri(NULL, 'assignmentadmin'), 'text'=>'Assignment Management'));
        
        // Get Assignments - Second Level
        //$assignments = getContextLinks();
/*
        // Extra Check
        if (count($assignments) > 0) {
            
            // Array for References
            $nodesArray = array();
            
            // Loop through Podcasters - second level
            foreach ($assignments as $assignment)
            {
                // Create Node
                $node =& new treenode(array('link'=>$this->uri($assignment['params']), 'text'=>$assignment['name']));
                
                // Create Reference to Node
                $nodesArray['assignment'.$assignment['id']] =& $node;
                
                // Attach to Root Node
                $rootNode->addItem($node);
            }
            
        }
     */   
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
          $sql = "SELECT * FROM tbl_assignment WHERE context = '$contextCode' ORDER BY closing_date, name";
          $data = $this->getArray($sql);
             
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
