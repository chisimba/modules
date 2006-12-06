<?php
class freemind extends controller 
{
    /**
    * $var string $action The action to performin dispatch
    */
    var $action;
	
	/**
	* @var $objDBContext
	*/
	var $objDBContext;
    
	/**
	*@var $objImport
	*/
	var $objImport;
	/**
    * Standard init method, create an instance of the language object
    */
    function init()
    { 
        // Create an instance of the language object
        $this->objLanguage = &$this->getObject('language', 'language');
        // Create an instance of the dir framework extension
        $this->objReadDir = $this->getObject('dir', 'files');
        // Create an instance of the config object
        //$this->objConfig = $this->getObject('config', 'config');
        // Retrieve the action parameter
        $this->action = $this->getParam("action", null);
		$this->objDBContext= & $this->getObject('dbcontext','context');
		$this->objImport= &$this->getObject('mapimport');
		$this->objConfig= &$this->getObject('altconfig','config');
        $this->objUser= &$this->getObject('user','security');
    } 
	
	function dispatch ()
    {
		   
        switch ($this->action) {
             case null:
                return $this->nextAction('list');
                
             case 'list':
                $this->setVar('ar', $this->objImport->getContextMaps());
                return 'list_tpl.php';
                break;
             case 'listcontentmaps':
                    $this->setVar('pageSuppressContainer', TRUE);
                    $this->setVar('pageSuppressBanner', TRUE);
                    $this->setVar('pageSuppressToolbar', TRUE);
                    $this->setVar('suppressFooter', TRUE);
                    $this->setVar('pageSuppressIM', TRUE);           
                 return 'listcontentmaps_tpl.php';
            case 'getcontentmap':
                 $this->setPageTemplate('null_page_tpl.php');                
            case 'viewmap':
               
			   /*
                    $this->setVar('pageSuppressContainer', TRUE);
                    $this->setVar('pageSuppressBanner', TRUE);
                    $this->setVar('pageSuppressToolbar', TRUE);
                    $this->setVar('suppressFooter', TRUE);
                    $this->setVar('pageSuppressIM', TRUE);           
				*/

                $this->setVar('map', $this->objImport->getMap());
                
                return 'viewmap_tpl.php';			
            // Added by J O'Connor 9 June 2006
            case 'view':
                $this->setVar('map', $this->getParam('path'));
                return 'viewmap_tpl.php';			
            case 'save':
				$this->save();				
				$this->nextAction('list');			
			case 'upload' :
				return 'upload_tpl.php';
			case 'savemap' :
                $this->setPageTemplate('null_page_tpl.php');
				$results =$this-> _insertMap();
                $this->setVar('myvar', $result);
				return 'result_tpl.php';
				break;
			case 'get_Map' :
                $this->setPageTemplate('null_page_tpl.php');
				$results =$this-> _get_Map();
                $this->setVar('myvar', base64_encode($result));
				return 'result_tpl.php';
				break;
			case 'getmaplist':
			case 'get_Map_List' :
                $this->setPageTemplate('null_page_tpl.php');
				$results =$this-> _get_Map_List();
                $this->setVar('myvar', $result);
				return 'result_tpl.php';
				break;
            case 'delete':
                $this->objImport->deleteMap( $this->getParam('id'));
                $this->nextAction('list');
                   
               
			default:
                die("Action unknown");
                break;
                
        } // end of switch
    } //end of dispatch

	/**
	*Method to save an
	*uploaded map
	*/
	function save(){
		$contextCode=$this->objDBContext->getContextCode();
		$this->_uploadMap($contextCode);				
	}
	
	/**
	*Method to save the uploaded
	*file to the content folder
	*@param string $contextCode: The Context Code
	*/
	function _uploadMap($contextCode){
		if (is_uploaded_file($_FILES['mapupload']['tmp_name'])) {
		//echo  $this->objConfig->contentBasePath().$contextCode."\maps\\";
			copy($_FILES['mapupload']['tmp_name'], $this->objConfig->getcontentBasePath().'/tmpmap.mm');
            //read the contents of the map into a string
            $filename = str_replace('\\', '/',$this->objConfig->getcontentBasePath().'/tmpmap.mm');
            $filesize = filesize($this->objConfig->getcontentBasePath().'/tmpmap.mm');
            $fp = fopen($filename, 'r');
            $title = $_FILES['mapupload']['name'];
            $map =  addslashes(fread($fp, $filesize));
            $contextCode = $this->objDBContext->getContextCode();
          //  print $contextCode. $title;
            //print_r($_FILES);
            return $this->objImport->insertMap($contextCode, $title, $map);
            //print $filename;
		} else {
			echo "Possible file upload attack. Filename: " . $_FILES['mapupload']['name'];
		}

	}
	/**
	*Method to delete a map
	*/
	function deleteMap(){
		$contextCode=$this->objDBContext->getContextCode();
		$filename=$this->getParam('filename');
		$this->_deleteMap($contextCode,$filename);
		//	$this->objDBFreemind->deleteMap($filename,$contextCode);
	}
	/**
	* Method to delete a map
	* folder
	* @param $contextCode string:The context Code
	*/
	
    function _deleteMap($contextCode,$filename){
		$filepath=$this->objConfig->getcontentBasePath().$contextCode."\\maps\\".$filename;
		
		if (file_exists($filepath)) {
			return unlink($filepath);		
		}else 
			return false;		
	}
	/**
	*Method to insert a map into the DataBase
	*@param none
	*/
	function _insertMap(){
	 $contextCode =$this->getParam('contextCode');
	 $title =$this->getParam('title');
	 $map=$this->getParam('map');
	 $result=$this->objImport->insertMap($contextCode,$title,$map);
	 return $result;
	}
	
	/**
	*Method to get maps from the DataBase
	*@param none
	*/
	
	function _get_Map_List(){
		
	$contextCode =$this->getParam('contextCode');
	$result=$this->objImport->getContextMaps($contextCode,true);	
		
	return base64_encode($result);	
	}
	
	/**
	*Method to get a map from the DataBase
	*@param none
	*/
	
	function _get_Map(){
		
	$contextCode =$this->getParam('contextCode');
	$mapid=$this->getParam('mapid');
	$result=$this->objImport->getMap($contextCode,$mapid);	
		
	return base64_encode($result);	
	}
	
    /**
    * Method to create the blog footer. It is here because it is used on a 
    * large number of pages
    */
    function putFooter()
    {
        $this->footerNav = &$this->newObject('layer', 'htmlelements');
        $this->footerNav->id = "blog-outside-footer"; 
        // Display the total number of blog entries
        $this->footerNav->str = "Freemind Mind mapper: Demonstration not translated. Please put ML text here";
        return $this->footerNav->addToLayer();
    } 
    
    
    
    /**
    * Method to not authenticate
    */
    function requiresLogin()
    {
        return FALSE;
    }
    
    
    
    
    
}//end class