<?php
//class used to read all schoolnames form a file and then populate dropdown list
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object generates data used to populate all dropdwon list 
* @package 
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class schoolnames extends object{ 

  protected $_objUser;
 
	public function init()
	{
	 try {
      //Load Classes
      $this->loadClass('dropdown', 'htmlelements');
      //$this->_objUser = & $this->newObject('user', 'security');
			$this->objLanguage = &$this->newObject('language', 'language');
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
/*------------------------------------------------------------------------------*/	
	public function readfiledata(){
    
    $searchlist  = new dropdown('searchlist');
    //$file_location  = "modules/marketingrecruitmentforum/resources/schoolnames.csv";
    
    //open file
    $file_to_read = @fopen("modules/marketingrecruitmentforum/resources/schoolnames.csv", "r") or die ("file does not exist or could not open file");
      
      while(!feof($file_to_read)) {
        //get all contents of the file
        $file_contents = fgetcsv($file_to_read);
       // sort($file_contents);
        $names[] = $file_contents[1];
       //store file contents in a dropdown list 
        foreach($names as $sessname){
        
           $searchlist->addOption($sessname,$sessname);
           
        }  
      }
        return $searchlist->show();
  }
    
/*------------------------------------------------------------------------------*/    
  public function readpostcodes(){
  
      $postcodelist = new dropdown('postcodelist');
      
      //open file 
      $postfile_to_read = @fopen("modules/marketingrecruitmentforum/resources/postcodes.csv", "r") or die ("file does not exist or could not open file");
    
      //get all contents of the file
      $post_contents  = fgetcsv($postfile_to_read);
      //$codes
      
      echo '<prv />';
      print_r($post_contents);  
  }

}
?>
