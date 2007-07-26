<?php
// security check - must be included in all scripts
if ( !$GLOBALS['kewl_entry_point_run'] ) {
    die( "You cannot view this page directly" );
} 
// end security check
/**
 * The context postlogin controls the information 
 * of courses that a user is registered to and the tools
 * that goes courses
 * 
 * @author Wesley Nitsckie
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package context
 */

class utils extends object 
{
    
    /**
     * The constructor
     */
    public function init()
    {
       
          $this->_objContextModules = $this->newObject('dbcontextmodules', 'context');
	      $this->_objLanguage = $this->newObject('language', 'language');
	      $this->_objUser = $this->newObject('user', 'security');
	      $this->_objModules = $this->newObject('modules', 'modulecatalogue');
	      $this->_objDBContext = $this->newObject('dbcontext', 'context');
    }
    
    /**
     * Method to get the widgets
     * 
     */
    public function getWidgets()
    {
        
        
    }
    
    /**
     * Method to get the context for this user
     * 
     */
    public function getContexts($userId)
    {
        
        
    }
    
    /**
	   * Method to get the users context that he
	   * is registered to
	   * @return array
	   * @access public
	   */
	  public function getContextList()
	  {
	  	 try
	  	 {
		  	$objGroups = & $this->getObject('managegroups', 'contextgroups');
		  	$contextCodes = $objGroups->usercontextcodes($this->_objUser->userId());
		  	$objMM = & $this->getObject('mmutils', 'mediamanager');
		  	
		  	$arr = array();
		  	foreach ($contextCodes as $code)
		  	{
		  		$arr[] = $this->_objDBContext->getRow('contextcode',$code); 
		  		
		  	}
		  	
		  	
		  	
		  	//print_r($arr);
		  	return $arr;
	  	 }
	  	catch (Exception $e) {
    		echo customException::cleanUp('Caught exception: '.$e->getMessage());
    		exit();
    	}
	  }
	  
	  /**
	   * Method to get the users context that he
	   * is registered to
	   * @return array
	   * @access public
	   */
	  public function getOtherContextList($myCourses, $filter = NULL)
	  {
	  	try{
		  	//$objGroups = & $this->newObject('managegroups', 'contextgroups');
		    $objMM = $this->getObject('mmutils', 'mediamanager');
		  	$arr = array();
		  	if($filter)
		  	{
		  		if($filter == 'listall')
		  		{
		  			$filter = '';
		  		} 
		  		else 
		  		{
		  			$filter = "  AND title LIKE '".$filter."%' ";
		  		}
		  	}
		  	//get all public courses
		  	$publicCourses = $this->_objDBContext->getAll( "WHERE status = 'Published' OR status = '' ".$filter."  ORDER BY title ");
		  	//print_r($publicCourses);
		  	
		  	foreach($publicCourses as $pCourse)
		  	{
		  		if(!$objMM->deep_in_array($pCourse['contextcode'], $myCourses))
		  		{
		  			$arr[] = $this->_objDBContext->getRow('contextcode',$pCourse['contextcode']); 
		  		}
		  		
		  	}
		  
		  	
		  	return $arr;//$objGroups->usercontextcodes($this->_objUser->userId());
	  	}
	  	catch (Exception $e) {
    		echo customException::cleanUp('Caught exception: '.$e->getMessage());
    		exit();
    	}
	  }
	  
	  /**
	   * Method to get a filter list to filter the courses
	   * @param array $courseList the list of courses
	   * @return string
	   * @access public
	   */
	  public function getFilterList($courseList)
	  {
	  	
	  	try {
	  		$objAlphabet=& $this->getObject('alphabet','navigation');
	  		$linkarray=array('filter'=>'LETTER');
			$url=$this->uri($linkarray,'contextpostlogin');
	  		$str = $objAlphabet->putAlpha($url);
	  		return $str;
	  		
	  	}
	  	catch (Exception $e) {
    		echo customException::cleanUp('Caught exception: '.$e->getMessage());
    		exit();
    	}
	  }
	  
	  
	  /**
	   * Method to get the left widgets
	   * @return string
	   * @access public
	   */
	  public function getLeftContent()
	  {
	  	//Put a block to test the blocks module
		$objBlocks = & $this->getObject('blocks', 'blocks');
		//$userMenu  = &$this->newObject('postloginmenu','toolbar');
		$leftSideColumn = $this->getUserPic();//$userMenu->show();;
		//Add loginhistory block
		
        		if($this->_objDBContext->isInContext())
        {
            $objContextUtils = & $this->getObject('utilities','context');
            $cm = $objContextUtils->getHiddenContextMenu('home','none');
        } else {
            $cm = '';
        }
		$leftSideColumn .= $cm;
		
		$leftSideColumn .= $objBlocks->showBlock('latest', 'blog');
		
		$leftSideColumn .= $objBlocks->showBlock('loginstats', 'context');
		
        $leftSideColumn .= $objBlocks->showBlock('calendar', 'eventscalendar');

		$leftSideColumn .= $objBlocks->showBlock('latestpodcast', 'podcast');

		$leftSideColumn .= $objBlocks->showBlock('contextchat', 'messaging');

	
		/*
		$leftSideColumn .= $objBlocks->showBlock('loginstats', 'context');
		//Add guestbook block
		$leftSideColumn .= $objBlocks->showBlock('guestinput', 'guestbook');
		//Add latest search block
		$leftSideColumn .= $objBlocks->showBlock('lastsearch', 'websearch');
		//Add the whatsnew block
		$leftSideColumn .= $objBlocks->showBlock('whatsnew', 'whatsnew');
		//Add random quote block
		$leftSideColumn .= $objBlocks->showBlock('rquote', 'quotes');
		$leftSideColumn .= $objBlocks->showBlock('today_weather','weather');
		*/
	      return $leftSideColumn;
	  }
	   
	  /**
	   * Method to get the user images
	   * 
	   */
	  public function getUserPic()
	  {
	  	$objUserPic =& $this->getObject('imageupload', 'useradmin');
	  	$objGoups = $this->getObject('groupusersdb', 'groupadmin');
	  	$groupsArr = $objGoups->getUserGroups($this->_objUser->userId());
	  	//var_dump($groupsArr);
	  	$objBox = & $this->newObject('featurebox', 'navigation');
	  	$str = '<p align="center"><img src="'.$objUserPic->userpicture($this->_objUser->userId() ).'" alt="'.$this->_objUser->fullName().'" /></p>';
	  	$str .= $this->getUserRole();
	  	
	  	return $objBox->show($this->_objUser->fullName(), $str,'userbox');
	  }
	  
	  /**
	   * Method get the User's Current Role
	   * 
	   * @return string
	   * @access public
	   */
	  public function getUserRole()
	  {
	       
	      
	      $objGroup = & $this->getObject('groupusersdb', 'groupadmin');
	      $objGroupName = & $this->getObject('groupadminmodel', 'groupadmin');
	      $arr = $objGroup->getUserRoles($this->_objUser->PKId());
	     // var_dump($arr);
   	   $str = '';
	      $str1 = '';
	      $str2 = '';
	      $heading1 = '';
	      $heading = '';
	       foreach ($arr as $group)
	      {
	          if(strpos($objGroupName->getFullPath($group['group_id']),'/'))
	          {
	              $strArr = explode('/',$objGroupName->getFullPath($group['group_id'])) ;
	              $heading1 = $this->_objDBContext->getMenuText($strArr[0]);
	              // this variable stores the lecturer or student // - '. $strArr[1];
	              $Usertype = $strArr[1];
	              if($Usertype=="Lecturers")
	              {
	              $str .= '<br/>'. $heading1 ;
					  }
					  elseif($Usertype=="Students")
					  {
					  $str2 .= '<br/>'. $heading1 ;
					  }	
	          } else {
	              $heading = $objGroupName->getFullPath($group['group_id']);
	          }
	          
	      }
	      $str1 .= '<br/>'. $heading ;
	      
	      $data = $this->_objLanguage->code2Txt('phrase_mysitegroups').':  <span class="highlight">'.$str1.'</span>';
	      if(strlen($str) > 0)
	      {
         $data1 = $this->_objLanguage->code2Txt('mod_contextpostlogin_mycourseslect', 'contextpostlogin').' <span class="highlight">'.$str.'</span>';
         }
         else
         {
         $data1 = "";
			}
         
         if(strlen($str2) > 0)
         {
         $data2 = $this->_objLanguage->code2Txt('mod_contextpostlogin_mycoursesstudent', 'contextpostlogin').' <span class="highlight">'.$str2.'</span>';
         }
         else
         {
         $data2 = "";
         }
			         
         
         $fullresult = $data."<br/>".$data1."<br/>".$data2;
         return $fullresult;
	  }
	  
	 
	  
	  /**
	   * Method to get the right widgets
	   * @return string
	   * @access public
	   */
	  public function getRightContent()
	  {
	     $rightSideColumn = "";
	     $objBlocks = $this->newObject('blocks', 'blocks');
	     
	    
	     
		//Add the getting help block
		$rightSideColumn .= $objBlocks->showBlock('dictionary', 'dictionary');
		//Add the latest in blog as a a block
		//$rightSideColumn .= $objBlocks->showBlock('latest', 'blog');
		//Add the latest in blog as a a block
		//$rightSideColumn .= $objBlocks->showBlock('latestpodcast', 'podcast');
		//Add a block for chat
		//$rightSideColumn .= $objBlocks->showBlock('chat', 'chat');
		//Add a block for the google api search
		$rightSideColumn .= $objBlocks->showBlock('google', 'websearch');
		//Put the google scholar google search
		$rightSideColumn .= $objBlocks->showBlock('scholarg', 'websearch');
		//Put a wikipedia search
		$rightSideColumn .= $objBlocks->showBlock('wikipedia', 'websearch');
		//Put a dictionary lookup
			$rightSideColumn .= $objBlocks->showBlock('randomphoto', 'photogallery');
		
		
		return $rightSideColumn;
	  } 
	  
	  
	  public function getStories()
	  {
			// Add postlogin stories
	        if($this->_objModules->checkIfRegistered('stories')){
	            $objStories = $this->getObject('sitestories','stories');
	            return $objStories->fetchCategory('postlogin');
	        }	
			else 
			{
				return '';
			}
			  
		}
	  
	  /**
	   * Method to get the Lectures for a course
	   * @param string $contextCode The context code
	   * @return array
	   * @access public
	   */
	  public function getContextLecturers($contextCode)
	  {
	  		$objLeaf = $this->newObject('groupadminmodel', 'groupadmin');
	  		$leafId = $objLeaf->getLeafId(array($contextCode,'Lecturers'));
	  		
	  		$arr = $objLeaf->getGroupUsers($leafId);
	  		
	  		return $arr;
	  		
	  }
	  
	  /**
	   * Method to get a plugins for a context 
	   * @param string $contextCode The Context Code
	   * @return string 
	   * @access public
	   * 
	   */
	  public function getPlugins($contextCode)
	  {
	  	$str = '';
	  	$arr = $this->_objContextModules->getContextModules($contextCode);
	  	$objIcon = & $this->newObject('geticon', 'htmlelements');
	  	$objLink = & $this->newObject('link', 'htmlelements');
	  	$objModule = & $this->newObject('modules', 'modulecatalogue');
	  	if(is_array($arr))
	  	{
	  		foreach($arr as $plugin)
	  		{
	  			
	  			$modInfo =$objModule->getModuleInfo($plugin['moduleid']);
	  			
	  			$objIcon->setModuleIcon($plugin['moduleid']);
	  			$objIcon->alt = $this->_objDBContext->getTitle($contextCode). ' : '.$modInfo['name'];
	  			
	  			$objLink->href = $this->uri(array ('action' => 'gotomodule', 'moduleid' => $plugin['moduleid'], 'contextcode' => $contextCode), 'context');
	  			$objLink->link = $objIcon->show();
	  			$str .= $objLink->show().'   ';
	  		}
	  		
	  		return $str;
	  	} else {
	  		return '';
	  	}
	  	
	  }
	  
	 
}	
?>