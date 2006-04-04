<?php

/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object manages the media folders and files
* @package mmutils
* @category mmutils
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Wesley  Nitsckie
* @example :
*/

class mmutils extends object 
{
	
	
	/**
	 * @var string $rootMediaPath The root  path
	 * @access protected
	 */
	protected    $_rootMediaPath = '';
	
	
	/**
	 * @var object $_objConfig The config Object
	 * @access protected
	 */
	protected $_objConfig;
	
	/**
	 * @var array $_folders The Media Folders
	 * @access protected
	 */
	protected $_folders;
	
	
	
	/**
	 * Constructor
	 */
	public function init()
	{
		try {
			
			$this->_objConfig = & $this->newObject('config', 'config');			
			$this->_rootMediaPath = $this->_objConfig->contentBasePath().'media';	
			$this->_folders = array();
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	
	
	
	/**
	 * Method to remove a folder
	 * @param string foldername The name of the new folder	 
	 * @access public
	 * @return bool
	 * @version 0.1
	 * @author Wesley Nitsckie
	 */
	public function removeFolder($folderName)
	{
		try {
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
	}
	
	
	/**
	 * Method to get the list of folders
	 * @access public
	 * @return bool
	 * @version 0.1
	 * @author Wesley Nitsckie
	 */
	public function getFolders()
	{
		try 
		{	
			//find all the folders
			
			$this->_folders[] = array('foldername' => '/');
			$this->_recurseFolders($this->_rootMediaPath);
			return $this->_folders;
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	}
	
	/**
	 * Method to recurse all the folder in a given folder
	 * @param string $path The path of the folder to recurse
	 * @return void
	 * @access private 
	 * @author Wesley Nitsckie
	 * @version 0.1
	 */
	private function _recurseFolders($path)
	{
		try {
			$baseDir=$path;
		    $hndl=opendir($baseDir);
	
	       	while($file=readdir($hndl)) 
	       	{
	    		 $completepath=$baseDir.'/'.$file;
	            
	    		
	            if (!strcmp(".", $file))continue; // this may not the most efficient way to detect the . and .. entries
	            if (!strcmp("..", $file))continue; // but it is the easiest to understand
	            if (!strcmp("CVS", $file))continue; // ignore CVS folders
	            if (!strcmp("config", $file))continue; // do not display config folder
	            if (!strcmp("_vti_cnf", $file))continue;//ignore frontpage crap   		
	            
	            if(is_dir($completepath))
	            {
	            	$foldername = str_replace($this->_rootMediaPath, '',$completepath);
	            	//if ($foldername == '')
	            	//{
	            	//	$foldername = '\/';
	            	//}
	            	$this->_folders[] = array('foldername' => $foldername);
	            	$this->_recurseFolders($completepath);
	            }
	       	}
	     }catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
	       	
	}
	
	
	/**
	 * Method to get the files for a given folder
	 * @param  string $folder The current folder
	 * @access public
	 * @version 0.1
	 * @author Wesley Nitsckie
	 * @return array
	 */
	public function getFiles($folder = NULL)
	{
		try 
		{
			if($folder == NULL)
			{
				//display files on the root 
				return $this->_getFiles($this->_rootMediaPath);
			} else {
				//diplay files fom the folder
				return 	$this->_getFiles($this->_rootMediaPath.$folder);
			}
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
			
	}
	
	/**
	 * Method to loop the files for a given folder
	 * @param  string $folder The current folder
	 * @access public
	 * @version 0.1
	 * @author Wesley Nitsckie
	 * @return array
	 */
	private function _getFiles($folder = NULL)
	{
		try 
		{
			$baseDir=$folder;
		    $hndl=opendir($baseDir);
			$arrFiles = array();
	       	while($file=readdir($hndl)) 
	       	{
	       		$completepath=$baseDir.'/'.$file;
	    		if(is_file($completepath)) 
	    		{
	    			$path = str_replace($this->_objConfig->siteRootPath(),  $this->_objConfig->siteRoot(),$completepath);
	       			$arrFiles[] = array('path' => $path , 'name' => $file);
	    		 	
	    		}
	       	}		 
	       	
	       	return $arrFiles;
	    }catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
	    }
	}
	
	
	/**
	 * Method to upload files to the media directory
	 * @access public
	 * @version 0.1
	 * @author Wesley Nitsckie
	 * @return array
	 */
	public function upload()
	{
		try 
		{
			$objFileMan = $this->newObject('fileupload', 'utilities');
			$filename = $this->getParam('upload');
			$currentFolder = $this->getParam('folder');
		
			
			if($currentFolder == '')
			{
				$uploadFile = $this->_rootMediaPath.'/';
			} else {
				$uploadFile = $this->_rootMediaPath.$currentFolder.'/';
			}
			//die ($uploadFile);
			return $objFileMan->upload_file($uploadFile,null,true,0);
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
	    }
	}
	
	/**
	 * Method to create a new folder
	 * @access public
	 * @version 0.1
	 * @author Wesley Nitsckie
	 * @return array
	 */
	public function createFolder()
	{
		try 
		{
			$newFolder = $this->getParam('newfolder');
			$folder = $this->getParam('folder');
			$newDir = $this->_rootMediaPath.$folder.'/'.$newFolder;
			
			
			if(!is_dir($newDir))
			{ 
				return mkdir($newDir);
			}
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
	    }
	}

}