<?php
/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 
// end security check
/**
* Class to access the  Tables 
* @package dbfile
* @category context
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version 
* @author  Jameel Adam 
* @example :
*/

/** Nobody 
*	Nobody
*/
include_once("XML/sql2xml.php");

 class dbcoursewarefile extends dbTable{
    /**
	 * @var object objDBContext;
	 */
	 var $objDBContext;
     
     /**
	 * @var object objDBData;
	 */
	 var $objDBFileData;
	 /** 
	 * Constructor
	 *
	 */
     	function init(){
		//sql table_
		$this->setSession('var1', 'value1');
         parent::init('tbl_klor_coursefile');
		 $this->objDBConfig=&$this->newObject('dbconfig','config');
		 $this->objImport=&$this->newObject('import','contextadmin');
       }

	/**
	* Course Search
	*/
	
	function search($value)
	{
		$stmt = "WHERE tbl_klor_coursefile.title LIKE '$value%' OR tbl_klor_coursefile.name LIKE '$value%' OR  tbl_klor_coursefile.description LIKE '$value%'" ;
		return $this->getAll($stmt);
	}//end funct


	
	/**
	*Course Rating
	*/

	function insertrating($id,$rating){
	//var_dump($rating);
	$this->update('id', $id, array('rating'=>$rating));
	}//end 


	 /**
	 * Method to get the list of files,from sql database
	 */

 	function getRecords(){
		 $ret = $this->getAll();
		 return $ret;
	 }

	 function getFiles(){
		  $ret = $this->getAll();
		  return $ret;
	 }
     
  /**
     * Method to delete a file and the file data
     * @param string $id The Id of the file
     */
     function deleteFile($id){
         $row = $this->getRow('id', $id);
         $path = $row['path'];
         if ($path != '') {
             $result = @unlink($path);
         }
         return $this->delete('id', $id);
     }
     
        
     function deleteAll(){
     	
     	//return $this->delete('id', $id);
     	$sql = "DELETE * FROM tbl_klor_coursefile";
        $ret = $this->_execute($sql);
        return $ret;
     }
       
     
     
  /**
     * Method to save a file 
     * @param string completePath The path to the file
     * @param string $fileId The File Id
     * @param string $parentId The Parent Id
     * @return boolean
     */
     function insertFile($params){
	/**Import Funtion for context 
	*@param void
	*/
	$bittorrentlocation = $params['bittorrentlocation'];
	$file64 = $params['file'];		
	$userId = $params['userId'];
	$destination =   $params['destination'];
	$title = $params['title'];
	$filepath =   $params['destination'];
   $path =$params['path'];
	$name = $params['name'];  
   	$link = $params['link'];
	//$this->objImport->doAll($title,$name,$path);
	$description = $params['description'];
       $version = $params['version'];
       $datatype = $params['datatype'];
       $size = $params['size'];
	$license = $params['license'];
	//$params['filedate'];
        $category =$params['category'];
        $fileId = $this->insert(array(
			'file'=>$file64,
			'bittorrentlocation'=>$bittorrentlocation,
			'link'=>$link,
			'userId' => $userId,
			'title' => $title,
			'name' => $name,			
			'description' => $description,
			'version'=>$version,
			'size' => $size,
			'datatype' => $datatype,
			'path' => $path,
			'filedate' => date('F j, Y, g:i a'),
			'category' => $category,
			'license' => $license,
			'rating' => 1
		
        		));
        
    }//end

	function fetchAllRecords($tblname)
		{
		
		
		$sql = "SELECT * FROM  tbl_".$tblname." ";
		//Serailize XML and return it
		
		return $this->getAll();
		}
		

 
  	/**
     * Method to update a file
     * @return boolean
     */
     function updateFile($id, $params){
        $this->update('id', $id, $params);
     }
     
 }
 ?>
