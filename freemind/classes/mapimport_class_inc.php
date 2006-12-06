<?php
/**
* Class to store freemind maps
* @copyright 2004, University of the Western Cape 
* @license GNU GPL
* @author Prince Mbekwa
* @package FREEMIND
**/
//include sql2xml
//include_once("XML/sql2xml.php");

class mapimport extends dbTable
{

    /**
    * @var object $objDBContext
    */
    var $objDBContext;
    
     /**
    * @var object $objConfig
    */
    var $objConfig;


/**
 *Initialize method
*/
	function init(){
		parent::init('tbl_freemind');
        $this->objDBContext =  & $this->newObject('dbcontext', 'context');
        $this->objConfig= &$this->getObject('config','config');
	}
    
    /** 
    * Method to insert a map into the database
    * @param string $contextCode The context Code
    * @param string $title The title of the map
    * @param string $map The map
    * @return bool 
    */
	function insertMap($contextCode, $title, $map)
    {    
        parent::init('tbl_freemind');
		$result = $this->insert(array('contextCode'=>$contextCode,
                                    'title'=> $title,
                                    'map'=>$map,
                                    'dateCreated'=> date("Y-m-d H:i:s") ));
       // $this->dumpMaps();
        return $result; 
	}
    
    /**
    * Get a list of maps
    * @param string $contextCode The Context Code
    * @return array 
    */
    function getContextMaps($contextCode = NULL, $returnAsXml = FALSE)
    {
        if($contextCode == NULL)
        {
            if($this->objDBContext->isInContext())
            {
                $contextCode = $this->objDBContext->getContextCode();
             }
             else
             {
                 return FALSE;
             }
            
        } 
        if($returnAsXml == TRUE)
		{
		    $sql = "SELECT * FROM tbl_freemind WHERE contextCode = '".$contextCode."'"; 

			$sql2xmlclass = new xml_sql2xml($this->objDBConfig->dbConString());
   			$options =array(tagNameRow  =>'tbl_freemind',
                    tagNameResult =>'n2');
    		$sql2xmlclass->SetOptions($options);
    		$xmlstring = $sql2xmlclass->getxml($sql);
   			
    		 base64_encode($xmlstring);
    		 print_r($xmlstring);
    		 exit;
		}
        return $this->getAll('WHERE contextCode = "'. $contextCode.'"');    
    
    
    }
    
    /**
    * Method to dump a map on the server
    */
    function dumpMaps(){
        $mapList = $this->getContextMaps();
        foreach($mapList as $list){
            $this->_dumpMap($list['id']);
        
        }
    
    }
    
    /**
    * Create a file for the map and insert the body
    */
    function _dumpMap($id){
        $realPath = $this->objConfig->contentBasePath().'/content/'.$this->objDBContext->getContextCode().'/maps/map_'.$id.'.mm';
        $row = $this->getRow('id', $id);
        $mapBody = stripcslashes($row['map']);
        $fp = fopen ($realPath, 'w+');
        fwrite($fp, $mapBody);
        fclose($fp);
    }
    
    /**
    * MEthod to delete a map
    * @param string $id The Id
    */
    function deleteMap($id)
    {
        $this->_deleteMap($id);
        $this->delete('id', $id);
    }
    
    function _deleteMap($id)
    {
        unlink($this->objConfig->contentBasePath().'/content/'.$this->objDBContext->getContextCode().'/maps/map_'.$id.'.mm');
        return TRUE;
    }
    
	
	/**
    * Method to get the map string
    * @return string
    */
    function getMap($mapId = NULL, $contextCode = NULL)
    {
        $this->dumpMaps();        
		if($mapId == NULL)
		{
			$mapId = $this->getParam('id');
		}
	
		if($contextCode == NULL)
		{
			$contextCode = $this->objDBContext->getContextCode();
		}
		
        $mapFile = 'http://'.$_SERVER['SERVER_NAME'].$this->objConfig->siteRoot().'usrfiles/content/'.$contextCode.'/maps/map_'.$mapId.'.mm';              
        return $mapFile;
       
    }
}
?>