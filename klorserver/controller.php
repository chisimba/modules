<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
 * The klorserver  controller manages content on server repository,
 * sends files to remote machines and retrieves them from remote uploads
 * to local machine
 * this contains server and client 
 * the klorserver module
 * @author Jameel Adam 
 * @version $Id$
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package nextgen module klorserver 
*/
error_reporting(E_ALL & ~E_NOTICE);
class klorserver extends controller
{
   
    /**
    * Method to initialise the controller
    */
    function init(){
	error_reporting(E_ALL & ~E_NOTICE);
	/**
	* Setting up objects for the controller

	*/
	//$this->setSession('var1', 'value1');
	$this->setPageTemplate("null_page_tpl.php");
	$this->objUser= & $this->getObject('user','security');
	$this->objContext=& $this->getObject('dbcontext', 'context');
    $this->objLanguage=& $this->getObject('language', 'language');
    $this->objIcon=& $this->getObject('geticon','htmlelements');
    $this->objUser= & $this->getObject('user','security');
    $this->objConfig = & $this->newObject('config', 'config');
    $this->objFile=&$this->newObject('dbcoursewarefile', 'klorserver');
    $this->objDC =& $this->newObject('dublincore','klorserver');
	//wsdl function calls
    $this->objKlor =  &  $this->getObject('klorservices', 'klorserver');
	$this->objKlient =  &  $this->getObject('klorclients', 'klorclient');
	
 	}

	function dispatch()
    	{
	$action = $this->getParam('action');
	$this->CheckDirectories();	
    switch($action){ 

	/**Context Import Function #3
	* doAll method inserts all courseware into
	* MIT's crap aswell
	*/
	/**
	*author: Jameel Adam
	*@param param array xsd ,
	*@param listinsert array xsd ,
	*method that inserts listinsert and metadata into 
	*db table tbl_coursefile completion status=100%
	*/
	
	
	case 'filedrop':
		$myvar = $this->objKlor->fileList();		
		$this->objKlor->sendfile();
		$this->setVar('myvar',$myvar);
 		return "main_tpl.php";
	break;

	
	
	case 'listinsert':
		$listinsert = $this->getParam('listinsert',null);
		//print_r($listinsert);die('STFU');
		$listinsert = $this->objKlient->unserializer($listinsert);
		//print_r($listinsert);die('STFU');
		$myvar = array ('listinsert'=>$listinsert ,'testphrase'=>'rabbits');
		//$myvar = $listinsert;
		foreach($listinsert as $key=>$element){			
		$params[$key] = $element;				
		}
		//print_r($params);
		$myvar = $params;
		$this->objFile->insertFile($listinsert);		
		$this->setVar('myvar',$myvar);
 		return "main_tpl.php";
	break;

	/**
	*author: Jameel Adam
	*@param param array xsd ,
	*@param array xsd ,
	*method that inserts title metadata into 
	*db table tbl_coursefile 
	*from remote machine
	*/
	case 'insertmetadata':
		$insertmetadata = $this->getParam('insertmetadata',null);
		//unserialize metadata 
		$insertmetadata = $this->objKlient->unserializer($insertmetadata);
		$myvar =$insertmetadata;
		//print_r($insertmetadata); die('zwitter');
		$id = $insertmetadata['id'];
		//print_r($id); die('zwitter');
		$this->objDC->insertMetaData($id,$insertmetadata);		
		$this->setVar('myvar',$myvar);
 		return "main_tpl.php";
	break;

	/**
	*author: Jameel Adam
	*@param param array xsd ,
	*@param array xsd ,
	*method that inserts title metadata into 
	*db table tbl_coursefile 
	*from remote machine
	*/
	case 'getRating':
	$myvar = $this->objKlor->getRating();
	$this->setVar('myvar',$myvar);
 	return "main_tpl.php";
	break;

	/**
	*author: Jameel Adam
	*@param param array xsd ,
	*@param rating array xsd ,
	*method that inserts rating and metadata into 
	*db table tbl_coursefile completion status=100%
	*/
	case 'insertrating':
		$id = $this->getParam('id',null);
		$rating = $this->getParam('rating',null);
		$myvar = array ('id'=>$id,'rating'=>$rating ,'testphrase'=>'rabbits');
		$this->objFile->insertrating($id,$rating);		
		$this->setVar('myvar',$myvar);
 		return "main_tpl.php";
		break;


	/**wsdl method filelist
	*  @param $fileList type array of courses 
	*  @param return fileList 
	*/	
	//wsdl filelist 
		default:
			return "main_tpl.php";
		break;

		case 'getList':
		$myvar = $this->objKlor->getList();
		//$myvar = $this->objKlor->unserializer($myvar);
		$this->setVar('myvar',$myvar);
 		return "main_tpl.php";
		break;

		
		case 'fileList':
		$myvar = $this->objKlor->fileList();
		//$myvar = $this->objKlor->unserializer($myvar);
		$this->setVar('myvar',$myvar);
 		return "main_tpl.php";
		break;

		case 'getfile':
			/**First get upload template info
			* base encoded stream comes in and we decode it into 
			* a normal file 
			*/
			$myvar = $this->objFile->getRecords();
		
		//$myvar = $this->objKlor->getfile(); //returns files not pressent on those servers
		foreach($myvar as $item=>$key){
		$name = $key['name'];
		$path = $key['path'];
		$destination = $this->objConfig->contentBasePath().'klor/'.$name; // var.../usrfiles/

		$filearr['file'][] =  base64_encode($destination);
		$filearr['filename'][] = $name;
			
		}
		$myvar=$filearr;
		$this->setVar('myvar',$myvar);
 		return "main_tpl.php";
		break;	

		case 'serverinfo':
		return print_r($_SERVER);
		break;
		//---------> Servers 
		case 'client':		
			return "display_tpl.php";
		break;

		case 'local':		
			return "display_tpl.php";
		break;
		//---------Servers <-----

		case 'clientlist':	 
			return 'clientlist_tpl.php';
		break;	

		case 'add' :
			return 'add_tpl.php';	
		break;
		
		case 'clientupload':
			return "clientupload_tpl.php";
		
		/** 
		* Returns the upload template 
		*/	
		case 'upload':
			return 'upload_tpl.php';
		
		case 'uploadfile':
	    	if (is_uploaded_file($_FILES['file']['tmp_name'])){
		
			$title 			=	$this->getParam('title');
			$description 	= 	$this->getParam('description');
         	$version 		= 	$this->getParam('version');
			
			$name = $_FILES['file']['name'];       
        	$datatype = "zip file";
        	$size = $_FILES['file']['size'];
        	$tmp_name=$_FILES['file']['tmp_name'];
        	$type = $_FILES['file']['type'];
			$this->uploadFile($title,$description,$version,$name,$datatype,$size,$tmp_name,$type);
			
			}else{
			echo "Possible file upload attack:";
			echo "filename '". $_FILES['file']['tmp_name'] . "'.";
			}//end else
	
			return 'display_tpl.php';
		break;
		
	
		//local
		case 'deletefile':
			 $id=$this->getParam('id');
			 $this->objFile->deleteFile($id);
			return 'display_tpl.php';
		break;
			
		//default:
		//	return "menu_tpl.php";
		//break;
		}//end of switch
    }//end of dispatch
    /**
    * Method to upload a file
    * Gets its data from getParam()
	* Note: we upload zip files
    */
    function uploadFile($title,$description,$version,$name,$datatype,$size,$tmp_name,$type)
    {
	$objConfig =& $this->getObject('config', 'config');
	$siteRootPath = "http://localhost/nextgen/";
	$contentRoot = "usrfiles";
	$userId = $this->objUser->userId();
	$dir_link="http://localhost/nextgen/usrfiles/content/courseware/$name";
	$destination = $dir_link;
	/**
	*Inserting 
	*/    

	$condition = file_exists("{$siteRootPath}{$contentRoot}/content/courseware/");
	$userId=$this->objUser->userId();
	$params=array(
		'title' 		=>  $title,
		'type' 			=>  $type,
		'name' 			=>	$name,
		'size' 			=>  $size,
		'description' 	=>  $description,
		'version' 		=>  $size,
		'datatype' 		=>  $datatype,
		'path' 			=> $destination,
		'filedate' 		=> date('F j, Y, g:i a'),
		'category' 		=> "CoursewareUpload"
	);
	$filepath =$destination;
	/**
	*Inserting into coursefile database, with params 
	*/    
	$this->objFile->insertFile( $userId, $params, $filepath);
	/**
	*Check directories if they exist or not 
	*@param condition is boolean 'true:false', @param dir the working directory
	*/
	$dir = "/var/www/nextgen/usrfiles/content/courseware/";
	$this->checkupload($dir,$_FILES['file']['tmp_name'],$name,$up_flag='n',$lastname=null);
	}
	
	/**
	* Method to create and check directories,
	* 
	*/
	function CheckDirectories()
		{

		//"makes directory"
		$objConfig =& $this->getObject('config', 'config');
		$siteRootPath = $objConfig->siteRootPath();
		$contentRoot = $objConfig->contentRoot();
			if (!file_exists("{$siteRootPath}{$contentRoot}/content/courseware/")){
				mkdir("{$siteRootPath}{$contentRoot}/content/courseware/",0777);
				}else{ 
			}

		}//end of check dir

		/**
		*Method to insert records from context,
		*into the local server db 
		*Note:This occurs "Only Once" , the refresh method ? 
		*/
	function updateContextRecords(){
		$this->objDBFile = & $this->getObject("dbcoursewarefile","klorserver");
		$this->objContext =& $this->getObject('dbcontext', 'context');
		$sql="Select * from tbl_context";
		$data=$this->objContext->getArray($sql);
		//print_r($data);			
		$contentBasePath = $this->objConfig->contentPath().'content'.'/';
		$userId=$this->objUser->userId();
		foreach($data as $item){
			$contextCode = $item['contextCode'] ;
			$title       = $item['menutext'];			
			$description = $item['about'];
			$date        = $item['updated'];
			$zipname = "$contextCode".".zip";
			$linklocation = $contentBasePath."{$contextCode}"."/"."{$zipname}";

			
		$params = array(
			'title' 		=>  $title,
			'type' 			=>  ' ',
			'name' 			=>	$zipname,
			'size' 			=>  ' ',
			'description' 	=>  $description,
			'version' 		=>  ' ',
			'datatype' 		=>  ' ',
			'path' 			=> $linklocation,
			'filedate' 		=> $date,
			'category' 		=> "CoursewareUpload"
		);
		//insert into db
		//$this->objDBFile->insertFile($params);
			$this->objFile->insertFile( $userId, $params, $filepath=' ');
			}//end loop
		
		}//end function

	function checkupload($filedir,$source,$source_name,$up_flag,$lastname)
	{
	if (!file_exists($filedir)){
		mkdir($filedir,0777);
	}
	@chmod($filedir,0777);
		
		if (!$lastname){
			$lastname=$source_name;
		}
	if (file_exists("$filedir/$lastname")){
		if ($up_flag=="y"){
			@unlink($filedir/$lastname);
			@move_uploaded_file($source,"$filedir/$lastname");
			$what=1; 
		}
		else
		$what=1;
	}else{
		@move_uploaded_file($source,"$filedir/$lastname");
		$what=1;
	 }
	}//end function check upload

	function requiresLogin() // overides that in parent class
    {
       return FALSE;
    }


}//end class
?>
