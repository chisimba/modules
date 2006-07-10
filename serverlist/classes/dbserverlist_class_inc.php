<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
//if (!$GLOBALS['kewl_entry_point_run']) {
  //  die("You cannot view this page directly");
//}
// end security check
/**
* The class will generate a server name for a
* installation. An installation done will contact
* server list . The class generates the xml so that 
* it can be consumed using  REST 
* 
* @package media manager
* @category document manager
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Wesley  Nitsckie
* @example :
*/

class dbserverlist extends dbTable 
{
    
    /**
     * Constructor
     */
    public function init()
    {
        try {
            parent::init('tbl_serverlist');
        }
        
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }
    
    /**
     * Method generate the xml file from the 
     * list in the database table
     * 
     * @return xml
     * @param string name The name of the server
     * @author Wesley Nitsckie
     * @access public
     */
    public function generateXML()
    {
        try {
            return $this->getName();       
        }
        
        
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }
    
    /**
     * Method to insert a server into the list
     * 
     * @param string $name The name of the server
     * @return bool
     * @access public
     * @author Wesley Nitsckie
     */
    public function add($name)
    {
        //inserting into the serverlist table
        
        return $this->insert(array( 'servername' => $name));
        
    }
    
    /**
     * Method to generate a new server name 
     * It using the date to format the new generated
     * name 
     * 
     * @return string The new generated name
     * @access public
     * @author Wesley Nitsckie
     */
    public function getName()
    {
        try 
        {
            $today = getdate();
            
            $nn = "gen".$today['hours']."Srv".$today['minutes']."Nme".$today['seconds'];
            
            //first check if the name exists
            if ( !$this->nameExists ( $nn ) )
            {        
                
                //insert the name into the database
                $this->add($nn);
    
                return $nn;
                               
            } else {
                
                //class this function again to try and find a name
                return $this->getName();
            }
        
        }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
        
        
    }
    
    /**
     * Method to check if a name is in 
     * the list
     * 
     * @return bool
     * @param string 
     * @access public
     */
    public function nameExists($name)
    {
        
        return  $this->valueExists('servername', $name);
        
    }
}
?>