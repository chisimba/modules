<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}


/**
* This object generates data used to populate dropdown list for schoolnames and postcodes 
* @package 
* @category sems
* @copyright 2005, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class schoolnames extends object{ 

/**
 * Standard int function
 * @param void
 * @return void  
 */ 
 
	public function init()
	{
	 try {
      
      $this->loadClass('dropdown', 'htmlelements');
      $this->objLanguage = &$this->newObject('language', 'language');
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
/*------------------------------------------------------------------------------*/
/**
 * Method to read schoolnames from a csv file and place into a session variable
 * @param string $file_to_read, contains the statement used to open the file in specified location and if file is not found display error msg
 * loop through file contents until end of file
 * @param string $file_contents contents, contains all info within the file ...using  fgetcsv to retrieve values from file and returns an array of info  
 * and add each value to a multi - dim array 
 * add array $names to a session variable
 * @return void  
 */	
	public function readfiledata(){
   
    $file_to_read = @fopen("modules/marketingrecruitmentforum/resources/schoolnames.csv", "r") or die ("file does not exist or could not open file");
      
      while(!feof($file_to_read)) {
        
        $file_contents = fgetcsv($file_to_read);
        $names[] = $file_contents[1];
        
      }
      
      $this->setSession('schoolnames',$names);
  }
    
/*------------------------------------------------------------------------------*/  
/**
 * Method to read postal codes from a csv file and place into a session variable
 * @param string $file_to_read, contains the statement used to open the file in specified location and if file is not found display error msg
 * loop through file contents until end of file
 * @param string $file_contents contents, contains all info within the file ...using  fgetcsv to retrieve values from file and returns an array of info  
 * and add each value to a multi - dim array 
 * add array $area to a session variable
 * @return void  
 */	  
  public function readareadata(){
    
    $file_to_read = @fopen("modules/marketingrecruitmentforum/resources/postcodes.csv", "r") or die ("file does not exist or could not open file");
    
          while(!feof($file_to_read)){
          
              $file_contents = fgetcsv($file_to_read);
              $area[] = $file_contents[3];
              
          }
        
          $this->setSession('area',$area); 
  }       

}
?>
