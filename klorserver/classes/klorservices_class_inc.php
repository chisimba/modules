<?

/**klorserver class used to interact with server and client
 *   
 * @author JameelAdam.
 * @version 
 * @package 
 */
//include the sql to XML serializer class
include_once("XML/sql2xml.php");
error_reporting('E_ALL & ~E_NOTICE');
class klorservices extends object
{
	/**
	 * Std init
	 */
	function init()
	{
	$this->setSession('var1', 'value1');
	$this->objConfig = &$this->getObject("config","config");
	//load app configuration
    	$this->objDBConfig=&$this->newObject('dbconfig','config');
	}

	/**
	* The first few functions are all inside the server.php:names are the same
	*/
	 function unserializer($list)
    {
        //$list = file_get_contents($list);
        include('XML/Unserializer.php');
        $unserializer = &new XML_Unserializer();
        $data = $unserializer->unserialize($list, false);
        $data = $unserializer->getUnserializedData();
        //print_r($data);
        return $data;
       
    }

	
	/**Method:file list, gets a list of files from server
	*  @param filelist is of type array	
	*  void function returns array of files 
	*/
	/**
	*User course rating
	*/
	function getRating(){ 
		$this->objFiles =& $this->newObject('dbcoursewarefile','klorserver');
		$file_arr = $this->objFiles->getFiles();
		$file_arr = $this->serializer2($file_arr);
		return $file_arr;
		}//end fnct

	/**Method:file list, gets a list of files from server
	*  @param filelist is of type array	
	*  void function returns array of files 
	*/
	function fileList(){
 		/*error_reporting(E_ALL ^ E_NOTICE);
		include_once("XML/sql2xml.php");
		//int xml class 
		$hit = $this->objDBConfig->dbConString();
		$sql2xmlclass = new xml_sql2xml($hit);
		//Build SQL
        	$sql2="Select * from tbl_coursefile";
		//Serailize XML and return it
		$xmlstring = $sql2xmlclass->getXML($sql2);
		return $xmlstring;*/
		$this->objFiles =& $this->newObject('dbcoursewarefile','klorserver');
		$fileList = $this->objFiles->getFiles(); 
		$fileList = $this->serializer($fileList);
		return $fileList;
		}

	
	
	
	/**Method send file , base64 encodes file 
	* prepare for send
	*/
	function sendfile($file){
		error_reporting(E_ALL ^ E_NOTICE);
		//$file[] = array() ;
		$this->objFiles =& $this->newObject('dbcoursewarefile','klorserver');
		$fileList = $this->objFiles->getFiles(); 
		
		foreach($fileList as $key=>$element){
		$file[] = $this->objConfig->siteRootPath().$element['path'];
		
		//list files
		}
		
		foreach($file as $key=>$element){	
			//file is now a string encoded
			//$filedec = base64_encode($file);
			
			}//weird
	
		}

	
	
	/**Method send file , base64 encodes file 
	* prepare for send
	*/
	function getfile(){
	//error_reporting(E_ALL ^ E_NOTICE);
	$this->objFiles =& $this->newObject('dbcoursewarefile','klorserver');
	$fileList = $this->objFiles->getFiles();
	//print_r($this->objFiles->getFiles());
	//die("STFU! getfiles class");
	return $newArray;
	}//end 


 function serializer($data)
    {   
	   include('XML/Serializer.php');
       $options = array(
           'indent'         => "\t",        // indent with tabs
           'linebreak'      => "\n",        // use UNIX line breaks
           'rootName'       => 'dbTables',   // root tag
           'defaultTagName' => 'table'       // tag for values with numeric keys
        );
        $serializer = new XML_Serializer();//$options);
		$xml = $serializer->serialize($data);
		$xml = $serializer->getSerializedData();
		//var_dump($xml);
		return $xml;
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
