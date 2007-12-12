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
    * The calling module displaying the tabbed box of contexts
    * @access private
    * @var string $module
    */
    private $module = 'contextpostlogin';

    /**
     * The constructor
     */
    public function init()
    {

          $this->_objContextModules = $this->getObject('dbcontextmodules', 'context');
          $this->_objLanguage = $this->getObject('language', 'language');
          $this->_objUser = $this->getObject('user', 'security');
          $this->_objModules = $this->getObject('modules', 'modulecatalogue');
          $this->_objDBContext = $this->getObject('dbcontext', 'context');
          $this->_objDBContextUtils = $this->getObject('utilities', 'context');
          $this->objHelp= $this->getObject('help','help');
          // Set module to current by default
          $this->module = 'contextpostlogin';
    }

    /**
    * Method to set the calling module
    *
    * @access public
    * @param string $moduleid The calling module id
    * @return void
    */
    public function setModule($moduleid = 'contextpostlogin')
    {
        $this->module = $moduleid;
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
		

		//if the user is an administrator of the site then show him all the courses
		
	  	if ($this->_objUser->isAdmin())
 		{
			$newContexts = array();
            $contexts = $this->_objDBContext->getAll();
            
            if (count($contexts) > 0) {
                foreach ($contexts as $context)
                {
                    if ($context['archive'] != '1') {
                        if ($context['archive'] == 0) {
                            $newContexts[] = $context;
                        }
                    }
                };
            }
            return  $newContexts;
        
		} else {
            $arr = array();
            
            $objGroups =  $this->newObject('managegroups', 'contextgroups');
            $contextCodes = $objGroups->usercontextcodes($this->_objUser->userId());
            
            foreach ($contextCodes as $code)
            {
                
                $context = $this->_objDBContext->getRow('contextcode',$code);
                
                if ($context != FALSE) {
                    if ($context['archive'] == 0) {
                        $arr[] = $context;
                    }
                }
            };
          
            return $arr;
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
              //$objGroups = $this->getObject('managegroups', 'contextgroups');
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
              $publicCourses = $this->_objDBContext->getAll( "WHERE access != 'Private' AND status = 'Published' OR status = '' ".$filter."  ORDER BY title ");
              //print_r($publicCourses);

              foreach($publicCourses as $pCourse)
              {
                  if(!$objMM->deep_in_array($pCourse['contextcode'], $myCourses))
                  {
                      if ($pCourse['archive'] == 0) {
                        $arr[] = $this->_objDBContext->getRow('contextcode',$pCourse['contextcode']);
                      }
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
              $objAlphabet= $this->getObject('alphabet','navigation');
              $linkarray=array('filter'=>'LETTER');
            $url=$this->uri($linkarray, $this->module);
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
        $objBlocks = $this->getObject('blocks', 'blocks');
        //$userMenu  = $this->getObject('postloginmenu','toolbar');
        $leftSideColumn = $this->getUserPic();//$userMenu->show();;
        //Add loginhistory block

                if($this->_objDBContext->isInContext())
        {
            $objContextUtils = $this->getObject('utilities','context');
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
          $objUserPic = $this->getObject('imageupload', 'useradmin');
          $objGoups = $this->getObject('groupusersdb', 'groupadmin');
          $groupsArr = $objGoups->getUserGroups($this->_objUser->userId());
          //var_dump($groupsArr);
          $objBox = $this->newObject('featurebox', 'navigation');
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


          $objGroup = $this->getObject('groupusersdb', 'groupadmin');
          $objGroupName = $this->getObject('groupadminmodel', 'groupadmin');
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
         $objBlocks = $this->getObject('blocks', 'blocks');



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
              $objLeaf = $this->getObject('groupadminmodel', 'groupadmin');
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
          $objIcon = $this->newObject('geticon', 'htmlelements');
          $objLink = $this->newObject('link', 'htmlelements');
          $objModule = $this->newObject('modules', 'modulecatalogue');
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

    /**
    * Method to display a tabbed box with the list of contexts for the current user and the public contexts
    *
    * @access public
    * @param string $filter
    * @return string html
    */
    public function showBox($filter)
    {
        $contextList = $this->getContextList();
        $otherCourses = $this->getOtherContextList($contextList, $filter);
        $filter = $this->getFilterList($contextList);


        $tabBox = $this->newObject('tabpane', 'htmlelements');
        $featureBox = $this->newObject('featurebox', 'navigation');
        $objLink = $this->newObject('link', 'htmlelements');
        $icon = $this->newObject('geticon', 'htmlelements');
        $table = $this->newObject('htmltable', 'htmlelements');
        $domtt = $this->newObject('domtt', 'htmlelements');
        $objContextGroups = $this->getObject('onlinecount', 'contextgroups');

        $str = '';
        $other = '';
        $lects = '';
        $config = '';
        //registered courses
        //var_dump($contextList);

        if (count($contextList) > 0)
        {
            foreach ($contextList as $context)
            {

                $lecturers = $this->getContextLecturers($context['contextcode']);
                $lects = '';
                if(is_array($lecturers) && count($lecturers) > 0)
                {
                    $c = 0;
                    foreach($lecturers as $lecturer)
                    {
                        $c++;
                        $lects .= $this->_objUser->fullname($lecturer['userid']);
                        $lects .= ($c < count($lecturers)) ? ', ' : '';


                    }
                } else {
                    $lects = $this->_objLanguage->code2Txt('mod_contextpostlogin_nolectforcourse', 'contextpostlogin'); //'No Instructor for this course';
                }

                $content = '<span class="caption">'.$this->_objLanguage->code2Txt('word_lecturers').' : '.$lects.'</span>';// Instructors
                $content .= '<p>'.stripslashes($context['about']).'</p>';
                $content .= '<p>'.$this->getPlugins($context['contextcode']).'</p>';

                $contextCode = $context['contextcode'];


                if($this->_objDBContext->getContextCode() == $context['contextcode'])
                {
                    $objLink->href = $this->uri(array('action' => 'leavecontext','contextCode'=>$contextCode), 'context');
                    $icon->setIcon('leavecourse');
                    $icon->alt = $this->_objLanguage->code2Txt('phrase_leavecourse'); //'Leave Course';
                    $objLink->link = $icon->show();
                } else {
                    $objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$contextCode), 'context');
                    $icon->setIcon('entercourse');
                    $icon->alt = $this->_objLanguage->code2Txt('phrase_entercourse'); //'Enter Course';
                    $objLink->link =$icon->show();
                }
                $title = $objLink->show();
                $objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$contextCode), 'context');
                $objLink->link = $context['contextcode'] .' - '.$context['title'].'   ';
                $title = $objLink->show().$title;
                $str .= $featureBox->show($title, $content ).'<hr />';
            }
        } else {
            $str .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">'.
            $this->_objLanguage->code2Txt('mod_contextpostlogin_notassocanycourses', 'contextpostlogin').'</div>';//You are not associated with any courses
        }


        //public courses
        $other = $featureBox->show($this->_objLanguage->code2Txt('phrase_browsecourses'), $filter); //'Browse Courses'

        if(count($otherCourses) > 0)
        {
            //set the headings
            $table->width = '98%';
            $table->startHeaderRow();
            $table->addHeaderCell('Code');
            $table->addHeaderCell('Title');
            $table->addHeaderCell('Details');
            $table->addHeaderCell('&nbsp;');
            $table->endHeaderRow();

            $rowcount = 0;

            //loop through the context
            foreach($otherCourses as $context)
            {
                //set the odd and even rows
                $oddOrEven = ($rowcount == 0) ? "even" : "odd";

                //get the lecturers
                $lecturers = $this->getContextLecturers($context['contextcode']);

                //reset the $lects
                $lects = '';

                //check if there are lecturers
                if(is_array($lecturers))
                {
                    //get their names
                    $c = 0;
                    foreach($lecturers as $lecturer)
                    {
                        $c++;
                        $lects .= $this->_objUser->fullname($lecturer['userid']);
                        $lects .= ($c < count($lecturers)) ? ', ' : '';


                    }
                } else {
                    $lects = $this->_objLanguage->code2Txt('mod_contextpostlogin_nolectforcourse', 'contextpostlogin'); //'No Instructor for this course';
                }

                $content = '<span class="caption">'.$this->_objLanguage->code2Txt('word_lecturers').' : '.$lects.'</span>';
                $content .= '<p>'.$context['about'].'</p>';
                $content .= '<p>'.$this->getPlugins($context['contextcode']).'</p>';


                //link to join the context
                $objLink->href = $this->uri(array('action' => 'joincontext','contextCode'=>$context['contextcode']), 'context');
                $icon->setIcon('leavecourse');
                $icon->alt = $this->_objLanguage->code2Txt('phrase_entercourse').' '.$context['title'];
                $objLink->link = $icon->show();


                //check if this user can join this context before showing the link
            if($this->_objDBContextUtils->canJoin($context['contextcode']))
                {
                    $config = $objLink->show();
                } else {
                    $icon->setIcon('failed','png');
                    $config = $icon->show();
                }

                //setup the information icon
                $icon->setIcon('info');
                $icon->alt = '';

                //formulate the message for the mouseover
                $mes = '';
                $mes .= ($context['access'] != '') ?  $this->_objLanguage->code2Txt('word_access').' : <span class="highlight">'.$context['access'].'</span>' : '' ;
                $mes .= ($context['startdate'] != '') ? '<br/>'.$this->_objLanguage->code2Txt('phrase_startdate').' : <span class="highlight">'.$context['startdate'].'</span>'  : '';
                $mes .= ($context['finishdate'] != '') ? '<br/>'.$this->_objLanguage->code2Txt('phrase_finishdate').' : <span class="highlight">'.$context['finishdate'].'</span>'  : '';
                $mes .= ($lects != '') ? '<br/>'.$this->_objLanguage->code2Txt('word_lecturers').' : <span class="highlight">'.$lects.'</span>'  : '';
                $scnt = $objContextGroups->getUserCount($context['contextcode']);
                $mes .= ($scnt > 0) ? '<br />'.$this->_objLanguage->code2Txt('mod_contextpostlogin_numregstudents', 'contextpostlogin').' : <span class="highlight">'.$scnt.'</span>' : '';
                $mes = htmlentities($mes);

                $info = $domtt->show(htmlentities($context['title']),$mes,$icon->show());
                $tableRow = array();

                $tableRow[] = $context['contextcode'];
                $tableRow[] = $context['title'];
                $tableRow[] = $info;
                $tableRow[] = $config;

                $table->addRow($tableRow, $oddOrEven);
                 $rowcount = ($rowcount == 0) ? 1 : 0;
                //$other .= $featureBox->show($context['contextcode'] .' - '.$context['title'].'   '.$objLink->show(), $content ).'<hr />';
            }

            $other .='<hr />'.$featureBox->show('Courses', $table->show() );
        }else {

            $other .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">'.
            $this->_objLanguage->code2Txt('mod_contextpostlogin_nopublicopencourses', 'contextpostlogin').'</div>';
        }

        $tabBox->addTab(array('name'=> $this->_objLanguage->code2Txt('phrase_mycourses'),'content' => $str));

        $tabBox->addTab(array('name'=> $this->_objLanguage->code2Txt('phrase_othercourses'),'content' => $other));
        return $tabBox->show();

        echo ($this->objHelp->show('help','help'));
    }

}
?>