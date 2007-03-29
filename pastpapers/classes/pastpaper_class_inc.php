<?php 


/*
 * A class to interact with tbl_pastpaper table
 *  
*/

class pastpaper extends dbTable{

public function init(){
parent::init('tbl_pastpapers');
$this->table = 'tbl_pastpapers';

//instance of the language items
$this->objLanguage = & $this->getObject('language','language');

}

/*
* Function to upload the pastpapers
* $filename - name of the file that has been uploaded
*/

public function uploadfile(){
$this->objConfig = & $this->getObject('altconfig','config');
//$contextCode = $this->_objDBContext->getContextCode();
 if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
   $file_name = $_FILES['filename']['name'];   
   $tmp = $_FILES['filename']['tmp_name'];
   $size = $_FILES['filename']['size'];     
   
   $folderpath = $this->objConfig->getModulePath().'pastpapers/papers/'.$file_name;   
  
   $path = str_replace('\\', '/',$folderpath);
   
   //check extensions
    $path_info = pathinfo($file_name);
    $extn = $path_info['extension'];
	
    switch($extn){ 
			case "doc":
			case "pdf":
			case "txt":					
			 $res= move_uploaded_file($tmp,$path);
			break;
		
		}//closing the switch	
	 if($res){

        return true;
  }
  else return false;	
}//closing if not uploaded




}


/*
* Function that adds the past paper details to the database
* @param - $file_name - name of the file
* @param - $examyear Year the paper was sat for
*/
function savepaper($file_name,$examyear,$topic){
$this->_objDBContext = $this->getObject('dbcontext','context');
$this->objUser = $this->getObject('user','security');

   $uploadDate = date('Y-m-d H:i:s');
   $contextCode = $this->_objDBContext->getContextCode();   
    $fields = array( 'contextcode' => $contextCode,
                        'userid' => $this->objUser->userId(),
						'topic'=>$topic,
                        'filename' => $file_name,
                        'dateuploaded' => $uploadDate,
                        'hasanswers' => 0,
                        'examyear' =>$examyear
                        ); 
		   $ret = $this->insert($fields);
		   return $ret;
}


/*
* Function to get all the available pastpapers for the course
* @param $contextcode 
*/
public function getpapersforcontext($contextcode){
$sql = "select * from ".$this->table." where contextcode ='$contextcode'";
$ar = $this->getArray($sql);
if($ar ){ return $ar;}

 else return false;


}


/*
* Funcion to didplay a list of all the papaers that are in the conext outside the one user is in
*/
public function getpapers($contextcode){
$sql = "select * from ".$this->table." where contextcode !='$contextcode'";
$ar = $this->getArray($sql);
if($ar ){ return $ar;}

 else return false;



}
/*
* Function to get the details of the paper
*/
public function getPaperDetails($paperid){
$this->_objDBContext = & $this->getObject('dbcontext','context');

$sql = "select * from ".$this->table." where id ='$paperid'";
$ar = $this->getArray($sql);
if($ar ){ return $this->_objDBContext->getTitle($this->_objDBContext->getContextCode())."&nbsp;(&nbsp;".$ar[0]['examyear']."&nbsp;".$ar[0]['topic'].")";}

 else return false;


}



}//closing the class


?>