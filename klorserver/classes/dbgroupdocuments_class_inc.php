<?php
/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 
// end security check
/**
* Class to access the groupdocument Tables 
* @package dbfile
* @category context
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version 
* @author  Jameel Adam 
* @example :
*/

 class dbgroupdocuments extends dbTable{
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
		//sql table_groupdocuments
         parent::init('tbl_groupdocuments');
       }
	 
	 /**
	 * Method to get the list of files
	 * @param string  $contextCode The context Code
	 * @return array
	 */

 	function getRecords(){
		 $ret = $this->getAll();
		 
		 return $ret;
	 }

	 function getFiles($userId, $category=NULL, $orderBy='filedate DESC'){
		 $where = "WHERE userId='$userId'";
         $order = "ORDER BY $orderBy";
		 $ret = $this->getAll($where.' '.$order);
		 
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
  /**
     * Method to save a file 
     * @param string completePath The path to the file
     * @param string $fileId The File Id
     * @param string $parentId The Parent Id
     * @return boolean
     */
     function insertFile( $userId, $params, $filepath){
        $title = $params['title'];
        $name = $params['name'];       
        $description = $params['description'];
        $version = $params['version'];
        $datatype = $params['datatype'];
        $size = $params['size'];
	     
        
	 //$params['filedate'];
        $path =$params['path'];
        $category =$params['category'];
        $fileId = $this->insert(array(
			'userId' => $userId,
			'title' => $title,
			'name' => $name,			
			'description' => $description,
			'version'=>$version,
			'size' => $size,
			'datatype' => $datatype,
			'path' => $path,
			'filedate' => date('F j, Y, g:i a'),
			'category' => $category
        		));
        
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