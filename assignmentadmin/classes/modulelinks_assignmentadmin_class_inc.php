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
    	$this->objAssignment = $this->getObject('dbassignmentadmin', 'assignmentadmin');
    }
    
    public function show()
    {
        
    }
    
    /**
     * 
     *Method to get a set of links for a context
     *@param string $contextCode
     * @return array
     */
    public function getContextLinks($contextCode)
    { 
       
       	$assignmentadmins = $this->objAssignmentadmin->getAssignmentadmin($contextCode);	   
          $bigArr = array();

          foreach ($assignmentadmins as $assignmentadmin)
          {
                $newArr = array();    
              $newArr['menutext'] = $assignmentadmin['userid'];
              $newArr['description'] = '';
              $newArr['itemid'] = $assignmentadmin['id'];
              $newArr['moduleid'] = 'assignmentadmin';
              $newArr['params'] = array('id'=>$assignmentadmin['id'],'action' => 'view');
              $bigArr[] = $newArr;
          }
          
          return $bigArr;
         
    }
    
}

?>