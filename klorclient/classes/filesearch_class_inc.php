<?php

/**klorclient class used to interact with server and client , to search files in directories
 *   
 * @author JameelAdam.
 * @version 0.01
 * @package kng-filesearch
 */
class filesearch extends object
{
	/**
	 * Std init
	 */
	function init()
	{
	//error_reporting(E_ALL & ~E_NOTICE);
	//$this->setSession('var1', 'value1');
	$this->objConfig = &$this->getObject("config","config");
	}
	/**
      	GLOB_MARK - Adds a slash to each item returned
      	GLOB_NOSORT - Return files as they appear in the directory (no sorting)
      	GLOB_NOCHECK - Return the search pattern if no files matching it were found
      	GLOB_NOESCAPE - Backslashes do not quote metacharacters
      	GLOB_BRACE - Expands {a,b,c} to match 'a', 'b', or 'c'
      	GLOB_ONLYDIR - Return only directory entries which match the pattern
      	GLOB_ERR - Stop on read errors (like unreadable directories), by default errors are ignored
      	GLOB_ERR was added in PHP 5.1 
      	**/



	/** This function searches thru directory , finds all the files
	* 	spits out and array list containing items found , note directories aswell
	*	main method....
	*/
	function search($dir,$wildcard,$args=NULL){
        	//arguments for glob 
		$args = GLOB_ONLYDIR;
		//concatenateing the direct path
		$searchvalue = $dir.$wildcard;
		//print_r($dir.$wildcard); //directory unit test 1) 
		$list = $this->glob($searchvalue,$args); //item list array
   	$this->check_dir($list);
	//return $list;
	}
	
	/** Glob function okay! 
	*	same like command line , using wildcard, 'ls function <unix>' 'dir<dos>'
	*/	
	function glob($searchvalue,$args){
			$list = glob($searchvalue,$args);
	return $list;
	}
	
	/** Search or Scan for files of type html ...
	*	Method check directory
	*	Author : Jameel Adam (intern dev.)
	*	@param : returns $	
	*/
	function check_dir($list){
		foreach($list as $key=>$element){
		//print_r($element.'<br>');
		//check directory for *.html's		
		$this->findfile($element);
		}
	}

	//using glob to extract infomation
	//scans directory for files 
	function findfile($element){
		//level 2 glob --- subdirectories
		 $list = $this->glob($element.'/*',null);
		//globbing this list.....
		
		//print_r($this->glob($element.'/*.htm*',null));die('findfiles');
		/* coursedirect ---> is files ?TRUE ----> is direct ?TRUE ----> */

	}	

}//end class
?>
