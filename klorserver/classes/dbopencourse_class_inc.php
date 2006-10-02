<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 
// end security check
/**
 * The class export that manages 
 * the export of static content 
 * @package export
 * @category context
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @version
 * @author  
 * The process for export static content is:
 * Get the content for the context that your are in
 * 
 */
//include('lib/curlit.php');
class dbcourseware extends object 
{
    /**
	* @var object $objDBContentNodes
	*/
    var $objDBContentNodes;
    
    /**
    * @var object $objDBContext
    */
    var $objDBContext;
    
    /**
    * @var object $objConfig
    */
    var $objConfig;
   
    
    /**
    * @var string $staticFolder
    */
    var $staticFolder;
    
    /**
    * @var string $contextCode
    */
    var $contextCode;
    
    function init()
    {
		parent::init('tbl_zipfiles');
		$this->objDBCourseware = & $this->newObject('dbcourseware','opencourseware');
        $this->objDBContentNodes = & $this->newObject('dbcontentnodes','context');
        $this->objDBContext = &$this->newObject('dbcontext','context');
        $this->objConfig = & $this->newObject('config','config');
        $this->contextCode = $this->objDBContext->getContextCode();
    }


/** Meta data about the zip files
*
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
    * Method to export static content
    * @return null
    * @access public
    */
    function exportStaticContent($contextCode)
    {
		$sql="Select * from tbl_context WHERE contextCode='$contextCode'";
		$data=$this->objContext->getArray($sql);
		$data[0]["contextId"]=

		//$contextid = $this->getParam('contextid');        
        //$contextCode = $this->getParam('contextcode');      
        //$contextCode = $this->objDBContext->getContextCode();
        
        $objConfig = & $this->newObject('config','config');
        $dircreate = & $this->newObject('dircreate','utilities');
         //check if the course folder exist
         if(!is_dir($objConfig->siteRootPath().'usrfiles')){
             $dircreate->makeFolder('usrfiles',$objConfig->siteRootPath());
         }
         if(!is_dir($objConfig->siteRootPath().'usrfiles/content')){
             $dircreate->makeFolder('content',$objConfig->siteRootPath().'usrfiles/');
         }
         if(!is_dir($objConfig->siteRootPath().'usrfiles/content/'.$contextCode)){
             $dircreate->makeFolder($contextCode,$objConfig->siteRootPath().'usrfiles/content/');
         }
         
        //create 'staticcontent' folder
        $dircreate->makeFolder('staticcontent',$objConfig->siteRootPath().'usrfiles/content/'.$contextCode.'/');
        
        //create 'staticcontent/courseCode' folder      
        $staticFolder = $objConfig->siteRootPath().'usrfiles/content/'.$contextCode.'/staticcontent/';
        
        $this->staticFolder = $staticFolder;
        $this->archive = $objConfig->siteRootPath().'usrfiles/content/'.$contextCode.'/'.$contextCode.'.zip';
        
        //create 'images' folder
        $dircreate->makeFolder('assets',$staticFolder);
        $imagesFolder = $staticFolder.'/assets';        
        
        
        $rootnodeid=$this->objDBContext->getRootNodeId($contextid);        
        $this->objDBContentNodes->resetTable();
        $nodesArr=$this->objDBContentNodes->getAll("WHERE tbl_context_parentnodes_id='$rootnodeid' ORDER BY sortindex");
       
       //create nodes
        $this->createNodes($nodesArr,$rootnodeid);
        //copy images to images folder
        $this->copyImages($imagesFolder,$rootnodeid);
        
        //copy the stylesheet
        $objSkin = & $this->newObject('skin','skin');
        copy($objSkin->getSkinLocation().'/kewl_css.php',$this->staticFolder.'/kewl.css');       

        //copy banners
        copy($objSkin->getSkinLocation().'banners/smallbanner.jpg',$this->staticFolder.'/smallbanner.jpg');

        //copy nav buttons
        copy($objSkin->getSkinLocation().'icons/prev.gif',$this->staticFolder.'/prev.gif');
        copy($objSkin->getSkinLocation().'icons/next.gif',$this->staticFolder.'/next.gif');
        print $objSkin->getSkinLocation();
        //create 'treeimages' folder
        $dircreate->makeFolder('treeimages',$staticFolder);
        $this->copyTreeImages($staticFolder,$objSkin->getSkinLocation());
        
        //copy the TreeMenu.js File
         copy($objConfig->siteRootPath().'modules/tree/resources/TreeMenu.js', $staticFolder.'/TreeMenu.js');

         //zip the static folder
         $this->zipFolder();
         
    }
    
     /**
    * Method to create the html file with its content
    * @param string $rootNodeId The Id of the parentNode
    * @param array $nodesArr The array of nodes
    */   
    function createNodes($nodesArr,$rootNodeId)
    {
        $objDublinCore = & $this->newObject('dublincore','dublincoremetadata');
	    $objMathML =& $this->getObject('parse4mathml','filters');
        
        foreach ( $nodesArr as $list)
        {

            $str = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><html>
                <head>
                 <link rel="stylesheet" type="text/css" href="kewl.css">
                 <title>'.$this->objDBContentNodes->getMenuText($list['id']).'</title>
                 '. $objDublinCore->getMetadataTags($list['id']).'
                 </head>
                 <body>
                    <div id="container">      
                       	<div id="top">
                    		<img src="smallbanner.jpg" alt="banner">
                         </div>
                         
                         <div id="leftnav" >        
                             <script type="text/javascript" src="TreeMenu.js"></script>
                             <script type="text/javascript" src="treedata.js"></script>                  
                    	     
                    	 </div>    
                    	 
                    	 <div id="content">
                    	 '.$objMathML->parseAll(
                            $this->replaceImages(
						 		$this->objDBContentNodes->getBody($list['id'])
							)
						 ).'
                    	 <p><p><p>
                    	 '.$this->_getNavButtons($list['prev_Node'], $list['next_Node']).'
                    	 </div>      
                        <div id="footer">
                    	</div>
                    </div>
        	       ';
            $str.='</body></html>';            
            
            //create html file
            $this->_createFile($list['id'],$str);
        }
        
        $this->createIndexFile($nodesArr);
        $this->createTreeFile($nodesArr);
        //$this->createFile('header.html', '<image src="smallbanner.jpg" borger="0">');
        $this->createFile('content.html', 'hello');
    }    
    
    /**
    * Method to create the tree file
    * @param array $nodesArr The array of nodes that is needed to create links
    * @return boolean
    */
    function createTreeFile($nodesArr)
    {       
        $objTree = & $this->newObject('contenttree','tree');        
        
        $fp=@fopen($this->staticFolder.'/treedata.js',"wb");
        if ($fp==FALSE)
        {
            return FALSE; // if we can't write to the specified location, we don't try.
        }
        fwrite($fp,$this->_removeScriptTags($objTree->getStaticTree($nodesArr)));           
        fclose($fp);
        return TRUE; 
    }
    
    
    /**
    * Method to create the index file
    * @param array $nodesArr The array of nodes that is needed to create links
    * @return boolean
    */
    function createIndexFile($nodesArr)
    {
        $objTree = & $this->newObject('contenttree','tree');
        
        $str = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><html>
                <head>
                 <link rel="stylesheet" type="text/css" href="kewl.css">
                 <title>'. $this->objDBContext->getTitle($this->getParam('contextCode')).'</title>
                 
                 </head>
                 <body>
                    <div id="container">      
                       	<div id="top">
                    		<img src="smallbanner.jpg" alt="banner">
                         </div>
                         
                         <div id="leftnav" style="height:80%">        
                             <script type="text/javascript" src="TreeMenu.js"></script>
                             <script type="text/javascript" src="treedata.js"></script>                  
                    	     
                    	 </div>    
                    	 
                    	 <div id="content" style="height:80%">
                    	 '.$this->objDBContext->getField('about').'
                    	 </div>      
                        <div id="footer">
                    	</div>
                    </div>
        	       ';
         $str.='</body></html>';
         $this->createFile('index.html', $str);        
    }
    
    /**
    * Method to create a textfile 
    * @param string $fileName The name of the file
    * @param string $content The content of the text file
    * @return boolean
    */    
    function createFile($fileName, $content=NULL){
        $str = $content;
        $fp=@fopen($this->staticFolder.'/'.$fileName,"wb");
        if ($fp==FALSE)
        {
            return FALSE; // if we can't write to the specified location, we don't try.
        }
        fwrite($fp,$str);
        fclose($fp);
        return TRUE; 
    }
    
     /**
    * Method to create the file on the file system 
    * @param string $name The name of the file
    * @param string $folder The path to the files
    */   
    function _createFile($name,$content)
    {
        $fp=@fopen($this->staticFolder.'/'.$name.'.html',"w");
        if ($fp==FALSE)
        {
            return FALSE; // if we can't write to the specified location, we don't try.
        }
        fwrite($fp,$content);
        fclose($fp);
        return TRUE; 
    }
    
    /**
    * Method to copy all the images to the images folder
    * @param string $rootNodeId The Id of the parentNode
    * @param string $folder The path to the files
    */   
    function copyImages($folder,$rootNodeId)
    {
        $this->objDBContentNodes->changeTable('tbl_context_file');
        $filelist = $this->objDBContentNodes->getAll("WHERE tbl_context_parentnodes_id = '$rootNodeId'");
        $this->objDBContentNodes->resetTable();
        foreach ($filelist  as $files)
        {
            $this->writeFile($files,$folder);
        }
    }
    
    /**
    * Method to copy the tree images
    * @param string $staticFolder The working folder that the static content is exported to
    * @return null
    */
    function copyTreeImages($staticFolder,$skinFolder)
    {
        //print $skinFolder;
        //die;
        $path = $skinFolder.'treeimages/imagesAlt2';
      //  $path = $this->objConfig->
        $hndl=opendir($path);
		while($SrcPathFile=readdir($hndl))
		{	
			$fileArr=pathinfo($SrcPathFile);
			if (!strcmp(".", $SrcPathFile))continue; // this may not the most efficient way to detect the . and .. entries
			if (!strcmp("..", $SrcPathFile))continue;			
            if (!strcmp("cvs", strtolower($SrcPathFile)))continue;			
			//echo 'Images  ---- '.$path.'/'.$SrcPathFile.'<br>';
			if(is_file($path.'/'.$SrcPathFile)){
                if(! copy($path.'/'.$SrcPathFile, $staticFolder.'/treeimages/'.$SrcPathFile))
                {
                    print $staticFolder.'/treeimages/<br>';
                }
            }				
		}
    }
    
    /**
    * Method to copy the file from the blob to the file system
    * @param array $arr The file information
    * @param string $location The path to the files
    */
    function writeFile($arr, $location)
    {
        $name=$arr['name'];      
        $this->objDBContentNodes->changeTable('tbl_context_filedata');
        $data = $this->objDBContentNodes->getAll("WHERE tbl_context_file_id  = '".$arr['id']."' ORDER BY segment");
        $fp=@fopen($location.'/'.$name,"wb");
        if ($fp==FALSE)
        {
            return FALSE; // if we can't write to the specified location, we don't try.
        }
        
        foreach ($data as $line)
        {           
            fwrite($fp,$line['filedata']);
        }
       
        fclose($fp);
        return TRUE; 
        $this->objDBContentNodes->resetTable();
    
    }
    
    /**
    * Method to write the CSS file
    */
    function writeCSS()
    {
		
		$url = 'http://'.$_SERVER['SERVER_NAME'].$this->objConfig->siteRoot().'skins/'.$this->objConfig->defaultSkin().'/kewl_css.php';  
		$objProxy = & $this->newObject('proxy', 'utilities');
			$arrProxy = $objProxy->getProxy();
			$proxy = 'http://'.$arrProxy['proxyserver'].':'.$arrProxy['proxyport'];
			$proxyUserPass = $arrProxy['proxyusername'].':'.$arrProxy['proxypassword'];
		//$url = 'http://kng.nettelafrica.org/skins/nettel/kewl_css.php';
		$ch = curl_init();
		
		if (!$_SERVER['SERVER_NAME'] == 'localhost') {
			$objProxy = & $this->newObject('proxy', 'utilities');
			$arrProxy = $objProxy->getProxy();
			$proxy = 'http://'.$arrProxy['proxyserver'].':'.$arrProxy['proxyport'];
			$proxyUserPass = $arrProxy['proxyusername'].':'.$arrProxy['proxypassword'];
				
			curl_setopt ($ch, CURLOPT_PROXY, $proxy); 
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyUserPass);
		}
		curl_setopt($ch, CURLOPT_URL,$url);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		$return = curl_exec($ch);
		
		
	  //$return = curlit($url, $proxy, $proxyUserPass);
	  $contents = $return;
	 
      $fp = fopen($this->staticFolder.'/kewl.css','w');
      fwrite($fp,$contents);
      fclose($fp);      
    
    }
    
    /**
    * Method to remove to the script tags
    * @param string $str
    * @return string
    */
    function _removeScriptTags($str)
    {
        $str = str_replace('<script language="javascript" type="text/javascript">', "", $str);
        $str = str_replace('</script>', "", $str);
        
        return $str;   
    }
    
    /**
    * Method to add the navigation buttons
    * @param string $prevId The previous Id
    * @param string $nextId The Next Id
    * @return string 
    */
    function _getNavButtons($prevId = NULL, $nextId = NULL)
    {
        $objStr = & $this->newObject('contenttree', 'tree');
        $str = '';
        
        if(!$prevId == NULL)   
        {
            $title = $this->objDBContentNodes->getField('title',$prevId);
            $str = '<a href="'.$prevId.'.html"><img alt="Previous Page:'.$title.'" src="prev.gif" border="0">'.
                $objStr->shortenString($title).'</a>';
        }
     
        if(!$nextId == NULL)   
        {
            $title = $this->objDBContentNodes->getField('title',$nextId);
            $str .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$nextId.'.html">'.
                $objStr->shortenString($title).
                    '<img alt="Next Page:'.$title.'" src="next.gif" border="0"></a>';
        }
        
        return $str;
    }
    
    /**
    * Method to zip the static folder
    */
    function zipFolder()
    {
        $objWZip = $this->newObject('wzip', 'utilities');
        $objWZip->addArchive($this->staticFolder, $this->archive, $this->staticFolder);
    }
    
     /**
    * Method to replace the image tags with 
    * ones pointing to static content
    * @param string $body
    * @return string 
    */
    function replaceImages($body = NULL)
    {
        
        //return if no body
		//$body =  stripslashes($body);
         if($body == NULL)
         {
            return $body;
         } 
         else
        {
                $body =stripslashes($body);
             //find '/contextdownload&amp;id=' in the body

            $str = preg_match_all('/contextdownload&amp;id=\w*/',$body, $matches);


            //check for matches
            if(array_key_exists(0, $matches))
            {

                if(array_key_exists(0, $matches[0]))
                {
                    //loop through matches
                    foreach($matches[0] as $m)
                    {
                        //get the id from the match
                        $id = str_replace('contextdownload&amp;id=','',$m);

                        //get the filename from the matched id
                        $objFile = & $this->newObject('dbfile', 'context');
                        $line = $objFile->getRow('id',$id);
                        $imgName = $line['name'];

                        //create the replacement string
                        $replace = array('src="assets/'.$imgName.'"');
                        //create the search pattern

                        //replace all the found strings

                        $pattern = array("'src=\"http:[a-zA-Z0-9._\/]+\/index.php\?module=contextview(\&|\&amp;)action=contextdownload(\&|\&amp;)id=".$id."'");

                        $body = preg_replace ($pattern, $replace,$body);

                        $pattern = array("'src=\"[a-zA-Z0-9._\/]+\/index.php\?module=contextview(\&|\&amp;)action=contextdownload(\&|\&amp;)id=".$id."'");

                        $body = preg_replace ($pattern, $replace,$body);

                    }
                }
            }

            //images on the file system
            $pattern = array("'src=\"http:\/\/[a-zA-Z0-9_.\/]+\/'");
            $replace = array("src=\"assets/");
            $body = preg_replace ($pattern, $replace, $body);


            return  $body;
}

}
}
?>
