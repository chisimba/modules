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


class modulelinks_assignment extends object
{

    public function init()
    {
    	$this->objAssignment = $this->getObject('dbassignment', 'assignment');
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
       
       	$assignments = $this->objAssignment->getAssignment($contextCode);	   
          $bigArr = array();
		
		if(count($assignments) > 1)
		{
		 //var_dump($assignments);
          foreach ($assignments as $assignment)
          {
                $newArr = array();    
              $newArr['menutext'] = $assignment['name'];
              $newArr['description'] =$assignment['description'];
              $newArr['itemid'] = $assignment['id'];
              $newArr['moduleid'] = 'assignment';
              $newArr['params'] = array('id'=>$assignment['id'],'action' => 'view');
              $bigArr[] = $newArr;
          }
          
          return $bigArr;
        } else {
			return FALSE;
		}
         
    }
    
}

?>
