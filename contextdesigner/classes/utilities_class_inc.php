<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
 * The utilities class holds a set of method that the context designer or any other 
 * method can use.
 * 
 * @author Wesley Nitsckie
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package contextdesigner
 **/

class utilities extends object
{

    /**
     * The Standard Constructor
     * 
     */
    public function init()
    {
//        $this->_objLanguage=& $this->newObject('language', 'language');
          $this->_objDBContext= $this->getObject('dbcontext','context');
          $this->_objIcon=& $this->getObject('geticon','htmlelements');
          $this->objLink = & $this->newObject('link', 'htmlelements');
//        $this->_objUser= & $this->newObject('user','security');
//        $this->_objDBContextModules=&$this->newObject('dbcontextmodules','context');
//        $this->_objUtils =  & $this->newObject('utilities', 'context');
//        $this->_objModule = & $this->newObject('modules', 'modulecatalogue');

         $this->_objIcon->setIcon('down');
         $this->downArrow = $this->_objIcon->show();
         $this->_objIcon->setIcon('up');
         $this->upArrow =  $this->_objIcon->show();
               
         $this->_objDBContextDesigner = & $this->getObject('dbcontextdesigner','contextdesigner');
         $this->maxOrder = $this->_objDBContextDesigner->getLastOrderPosition( $this->_objDBContext->getContextCode()) - 1;
       //  print  $this->maxOrder;
    }
    
    
    /**
     * Method to go through all the modules 
     * registered to the current context and get the
     * list of links that the module has made available
     * 
     * @return boolean|array
     * @access public
     * @package null
     * @author Wesley Nitsckie
     */
    public function getModuleLinks($moduleId)
    {
        try{
            $objConfig = $this->getObject('altconfig','config');
           if(file_exists($objConfig->getModulePath().$moduleId.'/classes/modulelinks_'.$moduleId.'_class_inc.php'))
           {
                $objModuleLink = $this->newObject('modulelinks_'.$moduleId, $moduleId);
                if(method_exists($objModuleLink,'getContextLinks'))
                {
                    return $objModuleLink->getContextLinks( $this->_objDBContext->getContextCode());
                }
           } else {
               return FALSE;
           }
        }                        
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }
    
    /**
    * Method to check if a plugin has links available
    * for the context designer to consume
    *
    * @return boolean
    * @access public 
    * @param string $moduleId
    */
    public function hasLinks($moduleId)
    {
		
	}
    
    /**
     * Method to returnn the up and down arrow icons for a link record
     * so that the order can be changed appropriately
     * 
     * @param integer $order The position of the current record
     * @return string
     * @access public
     */
    public function getOrderIcons($id, $order)
    {
      
       
        //first record only shows down arrow
        
        if($order > 1 && $order < $this->maxOrder)
        {
            
            $this->objLink->href = $this->uri(array('action' => 'moveup', 'id' => $id));
            $this->objLink->link = $this->upArrow;
            $str = $this->objLink->show();
            
            $this->objLink->href = $this->uri(array('action' => 'movedown', 'id' => $id));
            $this->objLink->link = $this->downArrow;
            $str .= $this->objLink->show();
            
            return $str;
        } 
        elseif ($order == 1)
        {
            
            $this->objLink->href = $this->uri(array('action' => 'movedown', 'id' => $id));
            $this->objLink->link = $this->downArrow;
            
           
            $str = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;          '.$this->objLink->show();
            
            return $str;
        }
        elseif ($order == $this->maxOrder)
        {
            $this->objLink->href = $this->uri(array('action' => 'moveup', 'id' => $id));
            $this->objLink->link = $this->upArrow;
            $str = $this->objLink->show();
            return $str;
        }
        
    }
}
?>