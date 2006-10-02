<?php
error_reporting(E_ALL & ~E_NOTICE);
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
 * @author Jameel Adam i
 * @version $Id$
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package nextgen module klorserver 
*/
include('XML/Serializer.php');
//require_once 'File/Bittorrent/Encode.php';
//require_once 'File/Bittorrent/Decode.php';
//require_once 'File/Bittorrent/MakeTorrent.php';

//upload_max_filesize = 2M

class klorclient extends controller
{
   	var $remote;
	var $maxSize;
    /**
    * Method to initialise the controller
    */
    function init(){
	/**
	* Setting up objects for the controller
	*/
	error_reporting(E_ALL & ~E_NOTICE);
    ini_set("upload_max_filesize", "500m");
	//ini_set("upload_max_filesize",15000000);	
	$this->maxSize=15000000; 
	//$this->setPageTemplate('null_page_tpl.php');
	$this->setLayoutTemplate('klor_layout_tpl.php');
	$this->objConfig= &$this->newObject('altconfig','config');
	//$this->objPrep = &$this->getObject('prepdata','mirrordb');
	$this->objContext=& $this->getObject('dbcontext', 'context');
	$this->objLanguage=& $this->getObject('language', 'language');
	$this->objIcon=& $this->getObject('geticon','htmlelements');
	$this->objUser= & $this->getObject('user','security');
	$this->objLink=&$this->newObject('link','htmlelements');
	$this->objConfig = & $this->newObject('config', 'config');
	$this->objFile=&$this->newObject('dbcoursewarefile', 'klorserver');
	$this->objDC =& $this->newObject('dublincore','klorserver');
	//$this->objUnserial =& $this->newObject('sql2xml2','webservices');
	$this->objZip =& $this->newObject('zip','utilities');
	$this->objHelp=& $this->getObject('help','help');
	//wsdl function calls
	$this->objWzip =  &  $this->getObject('wzip', 'utilities');
	//$this->objKlor=  &  $this->getObject('klorclients', 'klorclient');
	$this->objServer=  &  $this->getObject('klorservices', 'klorserver');
	$this->objUploader = & $this->newObject('upload','files');
	$this->objfilesearch =  &  $this->getObject('filesearch', 'klorclient');
	//$this->objProxy = & $this->newObject('proxy','utilities');
	//setting a session
	$this->setSession('var1', 'value1');
	
	}

	function requiresLogin()
    {
    return False;
	}
	
	function dispatch()
    {
		//$action = $this->getParam('action');
		//$this->CheckDirectories();	

    switch($action){
    
    
    case 'exportcontext':
    $this->objContextExport = & $this->newObject('export', 'contextadmin');
	$contextArr = $this->objContextExport->exportBackupAll();
	
	foreach($contextArr as $item){
	//print $item['path'];
	//print $item['title'];
	$file = $_SERVER['http_host'].$item['path'];
	$listinsert=array(
		'bittorrentlocation'	=>	'' ,
		'file'					=>  $_SERVER['http_host'].$item['path'],   
		'userId'				=>  '',
		'destination'			=>  '',
		'link'					=>  $file,
		'title' 				=>  $item['title'],
		'type' 					=>  'zip',
		'name' 					=>  $item['title'],
		'size' 					=>  '',
		'description' 			=>  $item['menutext'],
		'version' 				=>  '',
		'datatype' 				=>  'zip',
		'path' 					=>  $item['path'],
		'filedate' 				=>  '',
		'category' 				=>  ''
		);
		//print_r($listinsert);die; 
		$listinsert = $this->serializer($listinsert);
		$this->objKlor->listinsert($listinsert);//this is into klorserver db
		
    }
    return "display_tpl.php";
    break;
    
    
    case 'remote':
 /*echo "<br>".$this->objConfig->siteRootPath();
   echo "<br>".$this->objConfig->siteRoot();
  echo "<br>".$this->objConfig->contentPath();
  echo "<br>".$this->objConfig->contentBasePath();
   echo "<br>".$this->objConfig->templatePath();
    echo "<br>".$this->objConfig->contentRoot();
  die;*/
  
   
	return "remote_tpl.php";
		
		case 'remoteconfirm':
		
		//GET FORM PARAMS	
		$address = $this->getParam('remoteaddress');
		$method = $this->getParam('remotemethod');
		$httpaddress = $this->getParam('httpaddress');
		$remotelist = $this->objKlor->remoteaccess($address,$method);
		//echo "<pre>";
		//print_r($remotelist);
		//die;
		$locallist = $this->objKlor->fileList();
		
		//filter array list by name
		$remotearr = array(); 
		$localarr = array(); 
		
		foreach($remotelist as $item=>$key)
		{
		$remotearr[] = $key['name'];
		}
		
		foreach($locallist as $element=>$value)
		{
		$localarr[] = $value['name'];
		}
		//echo 'diff list'.'<br>';
		//this is a list of file names not in our respository
		$insertlist = array_diff($remotearr,$localarr);
		if($insertlist==Null){
			return "remoterror_tpl.php";
		break;
		}
		/*
		echo 'remote list'.'<br>';
		print_r($remotearr);
		echo 'local list'.'<br>';
		print_r($localarr);
		die;
		*/
		$i=0;
		foreach($remotelist as $item=>$key)
		{
		if($insertlist[$i++]==$key['name']){
		
		$listinsert=array(
		'bittorrentlocation'			=>  $key['bittorrentlocation'] ,
		'file'					=>  $key['file'],   
		'userId'				=>  $key['userId'],
		'destination'				=>  $key['destination'],
		'link'					=>  $httpaddress.$key['link'],
		'title' 				=>  $key['title'],
		'type' 					=>  $key['type'],
		'name' 				=>  $key['name'],
		'size' 					=>  $key['size'],
		'description' 				=>  $key['description'],
		'version' 				=>  $key['version'],
		'datatype' 				=>  $key['datatype'],
		'path' 					=>  $key['path'],
		'filedate' 				=>  $key['filedate'],
		'category' 				=>  $key['category']
		);
		//print_r($listinsert);die; 
		$listinsert = $this->serializer($listinsert);
		$this->objKlor->listinsert($listinsert);//this is into klorserver db
		}
		}
		
		
		
		return "display_tpl.php";
	break;	
		

    
    case 'license':
   	//return "license_tpl.php";
	break;    

	case 'filedrop':
    //print	$this->objConfig->siteRoot();
	break;    

	
   	case 'filesearch':
		error_reporting(E_ALL & ~E_NOTICE);
   		$dir = $this->objConfig->contentBasePath().'klor/';
   		$list = $this->objfilesearch->search($dir,$wildcard='*.*');
  		$objDirTree = & $this->newObject('dirtree', 'tree');
		$this->setVar('tree', $objDirTree->biuldTree($dir));
		return 'dirtree_tpl.php';
   	break;

	case 'search':
	//echo "<pre>".$this->objConfig->contentBasePath();
	$search = $this->getParam('search',null);
	$mode = 'fileList';
	$fileList = $this->objFile->search($search);
	$this->setVar('fileList',$fileList);
	$this->setVar('mode',$mode);
	return 'clientlist_tpl.php';
	break;

	case 'display':
		return 'display_tpl.php';
	break;


	case 'fetch':
	$fileList = $this->objKlor->fileList();
	foreach($fileList as $item){
	$file  = $item['file'];
	$name  = $item['name'];
	$this->objKlor->writefile($name,$file);
	//*******
	$dir1 = $this->objConfig->contentBasePath();
	$dir = $dir1.$_FILES['file']['name'];
	$new =  $dir;
	$file = $new; //new is the file location in file sys..
	$res=-1;
	$destination = $this->objConfig->contentBasePath().'klor/'.'course-'.$_FILES['file']['name'];	
	@chmod($destination,0777);
    $this->makeDirect($destination);
	// create the unzip command
	$unzipCmd='unzip -o '.$file.' -x -d '.$destination;
       	//execute the command
       	$UnusedArrayResult=exec ($unzipCmd,$UnusedArrayResult,$res);   
	//********
	$myvar = $fileList;
	$this->setVar('myvar',$myvar);
	return 'progress_tpl.php';		
	}
	break;	

		case 'upload':
		if(!$this->objUser->isAdmin())
		{
	//	echo "You need to be admin to upload!";
	//	die;
			return 'error_tpl.php';
		
		}else{
			return 'upload_tpl.php';
		}
 		
 		
		case 'uploadfile':
			
		if (is_uploaded_file($_FILES['file']['tmp_name'])){
		$announce 	='';//$this->getParam('announce',null);
 		$comment 	='';//$this->getParam('comment',null);
 		$title 		=	$this->getParam('title');
		$description 	= 	$this->getParam('description');
       		$version 	= 	$this->getParam('version');
		$name = $_FILES['file']['name'];       
		$title 		=	$this->getParam('title');
		$description 	= 	$this->getParam('description');
        	$version 	= 	$this->getParam('version');
		$name = $_FILES['file']['name'];       
        	$license = $_FILES['license']['name']; 
		$datatype = "zip file";
       		$size = $_FILES['file']['size'];
       		$tmp_name=$_FILES['file']['tmp_name'];
		$tmp_license=$_FILES['license']['tmp_name'];
       		$type = $_FILES['file']['type'];
		$this->uploadFile($announce,$comment,$title,$description,$version,$name,$datatype,$size,$tmp_name,$type,$license,$tmp_license);
		//return	$this->nextAction('display',null);
		}else{
		$this->uploadFile($announce,$comment,$title,$description,$version,$name,$datatype,$size,$tmp_name,$type,$license,$tmp_license);
		//echo "Possible file upload attack:";
		//echo "filename '".$_FILES['file']['name'] . "'.";
		}//end else
		//return	$this->nextAction('display',null);
		return 'display_tpl.php';
		
		break;
		/**Methods: Upload file into filesystem, Insert into Database,
		*	    attached metadata, unzip file contents , design preview 4 OCW
		* */
		//Swithch from local repository to remote
		/**
		*Rating for courseware
		*/
		case 'rating':
		
		$id = $this->getParam('id');
		$name = $this->getParam('name');
		$this->setVar('id',$id);
		$this->setVar('name',$name);
		return "rating_tpl.php";
		break;			

		/**
		*Rating for courseware
		*/
		case 'ratingconfirm':
		$id = $this->getParam('id');
		$rating = $this->getParam('rating');
		$this->setVar('id',$id);
		$this->setVar('rating',$rating);
		$params = array('id'=>$id,'rating'=>$rating);
		$this->objKlor->inserrrating($params);
		return "display_tpl.php";
		break;			
    	/**Method to print a bargraph and overview of a course
		*This method is sensitive so calculations are done outside the class
		*author Jameel Adam , 
		*@param xml data , changed into array , 
		*recursing thru the array to extract the ratings so a calculation
		*/
		case 'overview':
		//error_reporting(E_ALL & ~E_NOTICE);
		$id = $this->getParam('id');
		$title =  $this->getParam('title');
		$this->setVar('title',$title);
		$this->setVar('id',$id);
		$percent1 = $this->objKlor->Rating();
		$percent1 = $this->unserializer($percent1);
		foreach($percent1 as $key=>$element){			
		$file_array[] = $element;// <--- dumps the elements
		}
		foreach($file_array as $item){
		foreach($item as $thing){
		$total = $total + $thing['rating'];
		}//end for
		foreach($item as $key){
		$percent[$key['id']] = $key['rating']/$total;
		}//end for
		}//end outer
		foreach($percent as $key=>$element){
		if($key=$id){
		$per = $element*100;
		$per =substr($per,0,5);
		$this->setVarByRef('per',$per);
		}else{
		$per = 0;
		$this->setVarByRef('per',$per);
		}//end for
		}//end for
		return "overview_tpl.php";
		break;

		case 'metadata':
		if(!$this->objUser->isAdmin())
		{
	//	echo "You need to be admin to upload!";
	//	die;
			return 'error_tpl.php';
		
		}else{
		$id = $this->getParam('id');
		$this->setVar('id',$id);
		return "add_tpl.php";
		}
		break;



		/*$deleted = $db_value;
		
		if($deleted == '1')
			fdsfsfxg
		else
			fvdfxg
		*/



		
		case 'metadataconfirm':
		//insertinto db-dc
		$id = $this->getParam('id');
		$this->setVar('id',$id);
		$title = $this->getParam('title');
        	$subject = $this->getParam('subject');
        	$description = $this->getParam('description');
        	$source = $this->getParam('source');
        	$sourceurl = $this->getParam('sourceurl');
        	$type = $this->getParam('type');
        	$relationship = $this->getParam('relationship');
        	$coverage = $this->getParam('coverage');
        	$creator =$this->objUser->fullname();
        	$publisher = $this->getParam('publisher');
        	$rights = $this->getParam('rights');
        	$date = date("Y-m-d H:i:s");
        	$format = $this->getParam('format');
        	$identifier = $this->getParam('identifier');
        	$language = $this->getParam('language');
        	$audience = $this->getParam('audience');
		$params = array(
        'id'=>$id,
		'dc_title' => $title,
        'dc_subject' => $subject,
        'dc_description' => $description,
        'dc_source' => $source,
        'dc_sourceurl' => $sourceurl,
        'dc_type' => $type,
        'dc_relationship' => $relationship,
        'dc_coverage' => $coverage,
        'dc_creator' =>$this->objUser->fullname(),
        'dc_publisher' => $publisher,
        'dc_rights' => $rights,
        'dc_date' => date("Y-m-d H:i:s"),
        'dc_format' => $format,
        'dc_identifier' => $identifier,
        'dc_language' => $language,
        'dc_audience' => $audience
       	);	
		//visible to the client side	
		$params = $this->serializer($params);
		//send meta data to server
		$this->objKlor->insertmetadata($params);		
		//dump into remote server side
		return "overview_tpl.php";
		break;

		case 'download':
		$this->nextAction('fileList',null);	
		break;

		/**wsdl method filelist
		*  @param $fileList type array of courses 
		*  @param return fileList 
		*/	
		//wsdl filelist 
		case 'fileList':
		//$fileList = $this->fileList();
		$fileList = $this->objKlor->fileList();
		//echo "<pre>";
		//var_dump($fileList);
		$mode = 'fileList';
		$this->setVar('mode',$mode);
		$this->setVar('fileList',$fileList);
		return 'clientlist_tpl.php';
		break;

		case 'getfile':
		/**First get upload template info
		* base encoded stream comes in and we decode it into 
		* a normal file 
		*/
		$getfile = $this->objKlor->getfile(); //returns files not pressent on those servers
		print_r($getfile[0]['file']);
		break;	


		case 'serverinfo':
		return print_r($_SERVER);
		break;
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
		//local
		case 'deleteall':
			 // $this->objFile->deleteAll();
		return 'display_tpl.php';
		break;
		case 'deletefile':
			 $id=$this->getParam('id');
			 $this->objFile->deleteFile($id);
			return 'display_tpl.php';
		break;
		default:
				
			return "display_tpl.php";
		break;
		}//end of switch
    }//end of dispatch
 
   
   	
   	 function makeTorrent($filepath,$filelocation,$announce,$comment,$name)
	{
		/*echo $filepath;
		//---------------
 		$filelocation = $this->objConfig->contentBasePath().'/klor/bittorrent/';
 		if(!is_dir($filelocation)){
 		mkdir($filelocation,0777);
 		@chmod($filelocation,0777);
 		}else{
 		//print 'no dir';
 		} 
 		@chmod($filelocation,0777);
 		//----------------
 		*/
 		// Build the torrent
 		$MakeTorrent = new File_Bittorrent_MakeTorrent($filepath);
 		$MakeTorrent->setAnnounce($announce);
 		$MakeTorrent->setComment($comment);
 		$MakeTorrent->setPieceLength(256);
 		$torrentdata = $MakeTorrent->buildTorrent();
 		touch($filelocation.$name.'.torrent');
 		@chmod($filelocation.$name.'.torrent',0777);
 		$this->fwritehelper($filelocation.$name.'.torrent',$torrentdata);
 	}
	
	function fwritehelper($filename,$somecontent)
	{
	
		// Let's make sure the file exists and is writable first.
		if (is_writable($filename)) {
	   	// In our example we're opening $filename in append mode.
   		// The file pointer is at the bottom of the file hence
   		// that's where $somecontent will go when we fwrite() it.
   		if (!$handle = fopen($filename, 'a')) {
         echo "Cannot open file ($filename)";
         exit;
   		}

   		// Write $somecontent to our opened file.
   		if (fwrite($handle, $somecontent) === FALSE) {
       	echo "Cannot write to file ($filename)";
       	exit;
   		}
  
   		//echo "Success, wrote ($somecontent) to file ($filename)";
  
   	fclose($handle);

		} else {
   		echo "The file $filename is not writable";
		}
	////return False;
	//return 'display_tpl.php';
	}//end fnct
   
   
	function serializer($data)
	{   
	$serializer = new XML_Serializer();//$options);
 	$xml = $serializer->serialize($data);
  	$xml = $serializer->getSerializedData();
 	//var_dump($xml);
 	return $xml;
 	 }

	function fileList(){
	$fileList = $this->objKlor->fileList();
	return $fileList;
	}
	
	/**
    * Method to upload a file
    * Gets its data from getParam()
	* Note: we upload zip files
    */
    function uploadFile($announce,$comment,$title,$description,$version,$name,$datatype,$size,$tmp_name,$type,$license,$tmp_license)
    {
	//echo $name;
	$objConfig =& $this->getObject('config', 'config');
	//$name_server = $_SERVER[HTTP_HOST];
	//$siteRootPath = $name_server."/nextgen/";
	//$contentRoot = "usrfiles";
	$userId = $this->objUser->userId();
	$url = str_replace('index.php','',$_SERVER[SCRIPT_FILENAME]);
	$url = str_replace('/var/www','',$url);
	$url1 = str_replace('index.php','',$_SERVER[SCRIPT_FILENAME]);
	$url1 = str_replace('/var/www','',$url1);
	$url = $url . "usrfiles/klor/zip/".$name;
	$dir_link=$url;
	$destination = $dir_link;
	$dir1_link= $url1."usrfiles/klor/courses/course-".$name;
	$licenseappendfilepath = $url1."usrfiles/klor/".$name;
	/**
	*Inserting 
	*/    
	$condition = file_exists($this->objConfig->contentBasePath());
	$userId=$this->objUser->userId();
	$filepath =$destination;
	/**
	*Check directories if they exist or not 
	*@param condition is boolean 'true:false', @param dir the working directory
	*/
	$new_path = $this->objConfig->contentBasePath().'klor/zip/'.$_FILES['file']['name'];
	$filepath = 'usrfiles/klor/zip/'.$_FILES['file']['name'];
	//******
	//license
	$new_license_path = $this->objConfig->contentBasePath().'klor/license/'.$_FILES['license']['name'];
	$license_path = 'usrfiles/klor/zip/'.$_FILES['license']['name'];
	//copy file into filesystem
	@chmod(move_uploaded_file($_FILES['license']['tmp_name'],$new_license_path),0777);
	@chmod($new_license_path,0777);
	//******
	/**
	* Make Torrent Method
	*/
	
	//copy file into filesystem
	@chmod(move_uploaded_file($_FILES['file']['tmp_name'],$new_path),0777);
	@chmod($new_path,0777);
	$this->makeTorrent($new_path,$file,$announce,$comment,$name);
	
	/**
	*Method to extract files
	*/
	//------------Unzip----
	$file = $new_path; //new is the file location in file sys..
	$res=-1;
	$destination = $this->objConfig->contentBasePath().'klor/courses/course-'.$_FILES['file']['name'];
	$this->makeDirect($destination);
	@chmod($destination,0777);
	// create the unzip command
	
	$unzipCmd='unzip -o '.$file.' -x -d '.$destination;
    $UnusedArrayResult=exec ($unzipCmd,$UnusedArrayResult,$res);   
	//echo $UnusedArrayResult;die;
	//-------------End Unzip
	//$unzipCmd='zip -o '.$file.' -x -d '.$destination;
 	//copy('license/gpl.txt',$destination.'/gpl.txt');
	//copy('license/license.txt',$destination.'/license.txt');
    //execute the command
    
	
	/**
	*Inserting into coursefile database, with params 
	*/    
	$userId = $this->objUser->userId();
	//echo "<pre>";
	//print_r($_SERVER['HTTP_HOST']);
	//$this->objConfig->contentPath()
	$link_hard = $this->objConfig->siteRoot().$this->objConfig->contentPath().'klor/zip/'.$_FILES['file']['name'];
	$bittorrentlocation = $this->objConfig->siteRoot().$this->objConfig->contentPath().'klor/bittorrent/'.$_FILES['file']['name'].'.torrent';
	//echo $link_hard;
	$listinsert=array(
		'bittorrentlocation'	=>	$bittorrentlocation ,
		'file'			=>  "http://".$_SERVER['HTTP_HOST'].$link_hard,   //  base64_encode($filepath) <----file encoded,
		'userId'		=>  $userId,
		'destination'		=>  $destination,
		'link'			=>  "http://".$_SERVER['HTTP_HOST'].$dir1_link,
		'title' 		=>  $title,
		'type' 			=>  $type,
		'name' 		=>  $name,
		'size' 			=>  $size,
		'description' 		=>  $description,
		'version' 		=>  $size,
		'datatype' 		=>  $datatype,
		'path' 			=>  $filepath,
		'filedate' 		=>  date('F j, Y, g:i a'),
		'category' 		=> "CoursewareUpload",
		'license' 		=> "http://".$_SERVER['HTTP_HOST'].$this->objConfig->siteRoot().$license_path
	);
	//echo '<pre>';
	//print_r($listinsert);die; 
	$listinsert = $this->serializer($listinsert);
		
	//print_r($listinsert);die; 
	$this->objKlor->listinsert($listinsert);//this is into klorserver db
	
	}

	function makeDirect($dir)
	{
		@chmod(mkdir($this->objConfig->contentBasePath().'klor/',0777),0777);
		@chmod(mkdir($this->objConfig->contentBasePath().'klor/bittorrent/',0777),0777);
		@chmod(mkdir($this->objConfig->contentBasePath().'klor/courses',0777),0777);
		@chmod(mkdir($this->objConfig->contentBasePath().'klor/zip',0777),0777);
		@chmod(mkdir($this->objConfig->contentBasePath().'klor/license',0777),0777);
		@chmod(mkdir($dir,0777),0777);
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
		}//end of check dir


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
	}else
		$what=1;
	}else{
		@move_uploaded_file($source,"$filedir/$lastname");
		$what=1;
	 }
	}//end function check upload


	function doUnzip($file)
    {
        $path =$this->objConfig->contentBasePath(); //$this->objConfig->siteRootPath();//.
        $file = $this->objConfig->contentBasePath().$file;
        $objUnzip = & $this->newObject('wzip','utilities');
        $objUnzip->unzip($file, $path);
        return TRUE;
    }


	function Unzip($file)
	{
	$destination 	= $this->objConfig->contentBasePath().'/courseview';
	$res=-1;
  	// create the unzip command
   	$unzipCmd='unzip -o '.$file.' -x -d '.$destination;
 	//execute the command
	$UnusedArrayResult=exec ($unzipCmd,$UnusedArrayResult,$res);     
	}

     function unserializer($list)
    {
        require_once('XML/Unserializer.php');
        $unserializer = &new XML_Unserializer();
        $data = $unserializer->unserialize($list, false);
        $data = $unserializer->getUnserializedData();
        //print_r($data);
        return $data;
    }	

	/*Method:glob allows directory browsing
	*	@author www.php.net:glob 
	*	@author Jameel Adam 
	*	@param $dir:string,  Returns:string
	*/
	function glob($dir){	
		foreach (glob("*.zip") as $filename) {
		   echo "$filename size " . filesize($filename) . "\n".'<br>';
		}
	}

	function serializer2($data)
    {   
	   include('XML/Serializer.php');
       		$serializer = new XML_Serializer();//$options);
		$xml = $serializer->serialize($data);
		$xml = $serializer->getSerializedData();
		//var_dump($xml);
		return $xml;
    }

}//end class
?>
