<?php

/**klorclient class used to interact with server and client
 *   
 * @author JameelAdam.
 * @version 
 * @package 
 */
class klorclients extends object
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
	* The first few functions are all inside the server.php:names are the same
	*/

	function remoteaccess($remoteaddress,$method){
	//require_once('lib/nusoap/nusoap.php');
	//'http://'.$_SERVER['HTTP_HOST'].$this->objConfig->siteRoot().'modules/klorserver/server.php?wsdl'
	$client = new soapClient($remoteaddress);
	$err = $client->getError();
	if ($err){
	// Display the error
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	// At this point, you know the call that follows will fail
	}
	$fileList = $this->unserializer($client->call($method));
	foreach($fileList as $key=>$element){			
	foreach($fileList[$key] as $item=>$value){
	$file_array[] = $value;
	}
		//var_dump($key); //<--- dumps the keys
		//var_dump($element);// <--- dumps the elements
		//var_dump($value);
		//$this->writefile($key,$element);
	}
	//var_dump($file_array);die("filelist");
	return $file_array;
	}//end function 

	
	function glob($dir){
	//error_reporting('E_ALL & ~E_NOTICE');
    $shit = glob($dir.'*.zip');
    return $shit;
	}

	//----------------TEST NEW

	/**method to insert data into the server db
	* rating is my first step so...we will test this first
	* 
	*/
	function listinsert($listinsert){
	//error_reporting('E_ALL & ~E_NOTICE');
		//print_r($listinsert);die();
	
		/**
		*get a list of all the files all the files
		*/
		require_once('lib/nusoap/nusoap.php');
		/**
		*Enabling the client 
		*/
	//print	$this->objConfig->siteRoot(); 
	
	 $client = new soapclient('http://'.$_SERVER['HTTP_HOST'].$this->objConfig->siteRoot().'modules/klorserver/server.php?wsdl',true);
	// Check for an error
	
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
		// At this point, you know the call that follows will fail
	}
	//$client->call('listinsert',array('listinsert'=>$listinsert));
	//data is already serialized
	//so we can parse it into our server
	//print_r($listinsert);die('STFU');
	return $client->call('listinsert',array('listinsert'=>$listinsert));

	
		
	//the data is processed on the other side.....	
	}



	function insertmetadata($insertmetadata){
	/**
	*get a list of all the files all the files
	*/
	require_once('lib/nusoap/nusoap.php');
	/**
	*Enabling the client 
	*/
	  $url1 = str_replace($this->objConfig->siteRoot().'index.php','',$_SERVER[SCRIPT_FILENAME]);
	 $url1 = str_replace('/var/www','',$url1);
	 $path_clean = $url1;
     	 //$url = "http://".$_SERVER['HTTP_HOST'].$path_clean.$this->objConfig->siteRoot().'nextgen/modules/klorserver/server.php?wsdl',true);
	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].$this->objConfig->siteRoot().'modules/klorserver/server.php?wsdl',true);
	// Check for an error
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
		// At this point, you know the call that follows will fail
	}
	//data is already serialized
	//print_r($params);
	//so we can parse it into our server
	return $client->call('insertmetadata',array('insertmetadata'=>$insertmetadata));
	//print $shit."  Controller and klients" ;
	//return $shit;
	//the data is processed on the other side.....	
	}




	function Rating(){
	/**
		*get a list of all the files all the files
		*/
		require_once('lib/nusoap/nusoap.php');
		/**
		*Enabling the client 
		*/
	 $url1 = str_replace($this->objConfig->siteRoot().'index.php','',$_SERVER[SCRIPT_FILENAME]);
	 $url1 = str_replace('/var/www','',$url1);
	 $path_clean = $url1;
     	 //$url = "http://".$_SERVER['HTTP_HOST'].$path_clean.$this->objConfig->siteRoot().'nextgen/modules/klorserver/server.php?wsdl',true);	
	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].$this->objConfig->siteRoot().'modules/klorserver/server.php?wsdl',true);
		// Check for an error
		$err = $client->getError();
		if ($err) {
			// Display the error
			echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
			// At this point, you know the call that follows will fail
		}
	//data is already serialized
	//print_r($params);
	//so we can parse it into our server
	//xml string
	$data = $client->call('getRating');
	//print $shit."  Controller and klients" ;
	//return $shit;
	//the data is processed on the other side.....	
	return $data;
	}

	/**method to insert data into the server db
	* rating is my first step so...we will test this first
	* 
	*/
	function inserrrating($params){
	/**
	*get a list of all the files all the files
	*/
	require_once('lib/nusoap/nusoap.php');
	/**
	*Enabling the client 
	*/
	  $url1 = str_replace($this->objConfig->siteRoot().'index.php','',$_SERVER[SCRIPT_FILENAME]);
	 $url1 = str_replace('/var/www','',$url1);
	 $path_clean = $url1;
     	 //$url = "http://".$_SERVER['HTTP_HOST'].$path_clean.$this->objConfig->siteRoot().'nextgen/modules/klorserver/server.php?wsdl',true);	
	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].$this->objConfig->siteRoot().'modules/klorserver/server.php?wsdl',true);
	// Check for an error
	$err = $client->getError();
	if ($err) {
	// Display the error
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	// At this point, you know the call that follows will fail
	}
	//data is already serialized
	//print_r($params);
	//so we can parse it into our server
	$client->call('insertrating',$params);
	//print $shit."  Controller and klients" ;
	//return $shit;
	//the data is processed on the other side.....	
	}

	/**Method:file list, gets a list of files from server
		*  @param filelist is of type array	
		*  void function returns array of files 
		*/
		function getfile(){
	
		/**
		*get a list of all the files all the files
		*/
		require_once('lib/nusoap/nusoap.php');
		/**
		*Enabling the client 
		*/
	
     	 //$url = "http://".$_SERVER['HTTP_HOST'].$path_clean.$this->objConfig->siteRoot().'nextgen/modules/klorserver/server.php?wsdl',true);	
	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].$this->objConfig->siteRoot().'modules/klorserver/server.php?wsdl',true);
		// Check for an error
		$err = $client->getError();
		if ($err) {
			// Display the error
			echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
			// At this point, you know the call that follows will fail
		}
		//unserialized send a file ,requires a file 
		$newArray = $client->call('getfile');
		//var_dump($newArray); die('die!');
		//$newArray = $this->unserializer($newArray);
		//echo '<pre>';
		//var_dump($newArray); die('die!');
		/**
		* array has filename as key and baseencode64 as element..
		*/
		//keys extract function
		//foreach($newArray as $key=>$element){			
			//var_dump($key); //<--- dumps the keys 
			//die('worms');
			//var_dump($element);// <--- dumps the elements
			//var_dump($value);
			//$this->writefile($key,$element);
			//}
		return $newArray;
		}
	
	//----------------TEST END
	
	/**Method:file list, gets a list of files from server
	*  @param filelist is of type array	
	*  void function returns array of files 
	*/
	function getList(){
	//error_reporting(E_ALL & ~E_NOTICE);
	/**
	*get a list of all the files all the files
	*/
	require_once('lib/nusoap/nusoap.php');
	/**
	*Enabling the client 
	*/
	 $url1 = str_replace($this->objConfig->siteRoot().'index.php','',$_SERVER[SCRIPT_FILENAME]);
	 $url1 = str_replace('/var/www','',$url1);
	 $path_clean = $url1;
     	 //$url = "http://".$_SERVER['HTTP_HOST'].$path_clean.$this->objConfig->siteRoot().'nextgen/modules/klorserver/server.php?wsdl',true);
	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].$this->objConfig->siteRoot().'modules/klorserver/server.php?wsdl',true);
	// Check for an error
	$err = $client->getError();
	if ($err){
		// Display the error
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
		// At this point, you know the call that follows will fail
	}
		  //unserialized send a file ,requires a file 
		$fileList = $client->call('getList');
		$fileList = $this->unserializer($fileList);
			foreach($fileList as $key=>$element){			
			//count($fileList[$key]);
			foreach($fileList[$key] as $item=>$value){
			$file_array[] = $value;
			}
			//var_dump($key); //<--- dumps the keys
			//var_dump($element);// <--- dumps the elements
			//var_dump($value);
			//$this->writefile($key,$element);
			}
		
		return $file_array;
		
		
		}

	/**Method:file list, gets a list of files from server
	*  @param filelist is of type array	
	*  void function returns array of files 
	*/
	function fileList(){
	/**
	*get a list of all the files all the files
	*/
	require_once('lib/nusoap/nusoap.php');
	/**
	*Enabling the client 
	*/
	 $url1 = str_replace($this->objConfig->siteRoot().'index.php','',$_SERVER[SCRIPT_FILENAME]);
	 $url1 = str_replace('/var/www','',$url1);
	 $path_clean = $url1;
     	// $url = "http://".$_SERVER['HTTP_HOST'].$path_clean.$this->objConfig->siteRoot().'nextgen/modules/klorserver/server.php?wsdl',true);
	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].$this->objConfig->siteRoot().'modules/klorserver/server.php?wsdl',true);
	// Check for an error
	$err = $client->getError();
	if ($err){
		// Display the error
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
		// At this point, you know the call that follows will fail
	}
		  //unserialized send a file ,requires a file 
		$fileList = $this->unserializer($client->call('fileList'));
		//var_dump($fileList);
		//printing the array 
			foreach($fileList as $key=>$element){			
			//count($fileList[$key]);
			foreach($fileList[$key] as $item=>$value){
			$file_array[] = $value;
			}
			//var_dump($key); //<--- dumps the keys
			//var_dump($element);// <--- dumps the elements
			//var_dump($value);
			//$this->writefile($key,$element);
			}
		//var_dump($file_array);die("filelist");
		return $file_array;
		
		}


	/**
	* The first few functions are all inside the server.php:names are the same
	*/
	/**
	* The first few functions are all inside the server.php:names are the same
	*/
	 function unserializer($list)
    {
        //$list = file_get_contents($list);
       	require_once('XML/Unserializer.php');
        $unserializer = &new XML_Unserializer();
        $data = $unserializer->unserialize($list, false);
        $data = $unserializer->getUnserializedData();
        //print_r($data);
        return $data;
       
	}

	 function unserializer2($list)
    {
        //$list = file_get_contents($list);
       	require_once('XML/Unserializer.php');
        $unserializer = &new XML_Unserializer();
        $data = $unserializer->unserialize($list, false);
        $data = $unserializer->getUnserializedData();
        //print_r($data);
        return $data;
       
	}

	function writefile($name,$file)
	{	
	$file = base64_decode($file);
	$filename = $name; //'test.zip';
	$handle = fopen($this->objConfig->contentBasePath().$filename, 'w');
        // Write $file to our opened file.
        $result = fwrite($handle, $file);
        fclose($handle);
		return $result;	
	}
    
	function makeDir($filename){
	if(!file_exists($this->objConfig->contentBasePath().'/content/courseware/downloads/'.$filename))
		{
		mkdir($this->objConfig->contentBasePath().'/content/courseware/downloads/',0777);
		}else{
		
			//	chmod($this->objConfig->contentBasePath().'/content/courseware/downloads/',0777);
		}
	}

	function bargraph($percentage){
	$this->objIcon=&$this->newObject('geticon','htmlelements');
	//draws rating graph
	//$percentage=100;
        $this->objIcon->setIcon('bar_left_green');
 		$barIcon=$this->objIcon->show();
        for($i=1;$i<=$percentage;$i++){
        $this->objIcon->setIcon('bar_green');
        $barIcon.=$this->objIcon->show();
        }
        $this->objIcon->setIcon('bar_right_green');
        $barIcon.=$this->objIcon->show();
 		return $barIcon;
	}

	function getRating(){ 
	/**
	*get a list of all the files all the files
	*/
	require_once('lib/nusoap/nusoap.php');
	/**
	*Enabling the client 
	*/
	 $url1 = str_replace($this->objConfig->siteRoot().'index.php','',$_SERVER[SCRIPT_FILENAME]);
	 $url1 = str_replace('/var/www','',$url1);
	 $path_clean = $url1;
     	 //$url = "http://".$_SERVER['HTTP_HOST'].$path_clean.$this->objConfig->siteRoot().'nextgen/modules/klorserver/server.php?wsdl',true);
	$client = new soapclient('http://'.$_SERVER['HTTP_HOST'].'modules/klorserver/server.php?wsdl',true);
	// Check for an error
	$err = $client->getError();
	if ($err){
		// Display the error
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
		// At this point, you know the call that follows will fail
	}
	//unserialized send a file ,requires a file 
		$fileList = $client->call('getRating');
		 $serializer = new XML_Serializer();//$options);
 		 $xml = $serializer->serialize($fileList);
		  $xml = $serializer->getSerializedData();
 		return $xml;
	}//end fnct
	

	//percentiles dont delete
	/*$this->objKlor=  &  $this->getObject('klorclients', 'klorclient');
	$file_arr = $this->objKlor->fileList();
	foreach($file_arr as $key=>$element){			
	$file_array[] = $element;// <--- dumps the elements
	//var_dump($element);
	}
	//calc the total rating
	foreach($file_array as $item){
		$total = $total + $item['rating'];
	}
	foreach($file_array as $key){
	    $percent[$key['id']] = $key['rating']/$total;
	}	
	//var_dump($percent);
	return $percent;
	*/
	//-----------




}//end class
?>
