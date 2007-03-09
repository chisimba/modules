<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* Class to display the cms content / pages using the correct layout
*
* @author Megan Watson
* @version 0.1
* @copyright (c) 2007 UWC
* @licence GNU GPL
* @package cms
*/

class cmslayouts extends object
{
    
    /**
    * Constructor function
    *
    * @access public
    */
    public function init()
    {
        try{
            $this->_objSections =$this->newObject('dbsections', 'cmsadmin');
            $this->_objContent =$this->newObject('dbcontent', 'cmsadmin');
            $this->_objFrontPage =$this->newObject('dbcontentfrontpage', 'cmsadmin');
                
            $this->objUser = $this->newObject('user', 'security');
            $this->objDate = $this->getObject('datetime', 'utilities');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objCCLicense = $this->getObject('displaylicense', 'creativecommons');
            
            $this->objRound = $this->newObject('roundcorners', 'htmlelements');
            $this->objHead = $this->newObject('htmlheading', 'htmlelements');

            $this->loadClass('href', 'htmlelements');

            $this->objLayer = $this->newObject('layer', 'htmlelements');
            $this->objIcon = $this->newObject('geticon', 'htmlelements');

            $this->loadClass('link', 'htmlelements');
            $this->loadClass('htmltable', 'htmlelements');
            
        }catch(Exception $e){
            throw customException($e->getMessage());
            exit();
        }
    }

        /**
         * Method to get the Front Page Content
         * in a ordered way. It should also conform to the
         * section template for the section that this page is in
         *
         * @return string The content to be displayed on the front page
         * @access public
         */
        public function getFrontPageContent($displayId=NULL)
        {   
            $lbRead = $this->objLanguage->languageText('phrase_readmore');
            $lbWritten = $this->objLanguage->languageText('phrase_writtenby');
            $arrFrontPages = $this->_objFrontPage->getFrontPages();

            $str = '';
            //set a counter for the records .. display on the first 2  the rest will be dsiplayed as links
            $cnt = 0 ;

            // Display the selected page above the others
            if(!empty($displayId) && !empty($arrFrontPages)) {
                foreach ($arrFrontPages as $frontPage) {
                    if($displayId == $frontPage['content_id']){
                        $str .= $this->showBody();
                        break;
                    }
                }
            }

            // Display the introductions of all front pages
            if (!empty($arrFrontPages)) {
                foreach ($arrFrontPages as $frontPage) {
                    //get the page content
                    $page = $this->_objContent->getContentPage($frontPage['content_id']);
                    
                    // Check it's not the page displayed at the top.
                    if(!empty($displayId) && $displayId == $frontPage['content_id']){
                        // do nothing
                    }else{
                        $pageStr = '';
                        $cnt++;
                        
                        // Page heading
                        $this->objHead->type = '2';
                        $this->objHead->str = $page['title'];
    
                        $pageStr = $this->objHead->show();
                        
                        // Read more link
                        $moreLink = $this->uri(array('displayId' => $frontPage['content_id'], 'sectionid' => $page['sectionid'], 'id' => $page['id']), 'cms');
                        //array('action' => 'showfulltext', 'sectionid' => $page['sectionid'], 'id' => $page['id']), 'cms');
                        $readMoreLink = new link($moreLink);
                        $readMoreLink->link = $lbRead.'...';
                        $readMoreLink->title = $page['title'];
                        $readMoreLink->cssClass = 'morelink';
                        
                        // Display the page title and introduction
                        $pageStr .= '<p><span class="date">'.$lbWritten.'&nbsp;'.$this->objUser->fullname($page['created_by']).'</span><br />';
                        if(isset($page['created']) && !empty($page['created'])){
                            $pageStr .= '<span class="date">'.$this->objDate->formatDate($page['created']).'</span>';
                        }
                        $pageStr .= '</p>';
                        $pageStr .= stripslashes($page['introtext']).'<br />'.$readMoreLink->show();
                        $pageStr .= '<p />';
                        
                        if(isset($page['modified']) && !empty($page['modified'])){
                            $pageStr .= '<p> <span class="date">Last updated : '.$this->objDate->formatDate($page['modified']).'</span></p>';
                        }
                       
                        $str .= $this->objRound->show($pageStr);
                    }
                }
            }

            return $str;
        }

    
        /**
         * Method to generate the content for a section
         *
         * @access public
         * @return string The content displayed when a section is selected
         */
        public function showSection($module = "cms")
        {
            $sectionId = $this->getParam('id');
            //get the section record
            $arrSection = $this->_objSections->getSection($sectionId);
            
            switch(strtolower($arrSection['layout'])){
                
                case 'previous':
                    return $this->_layoutPrevious($arrSection, $module);
                
                case 'page':
                    return $this->_layoutPage($arrSection, $module);
                
                case 'summaries':
                    return $this->_layoutSummaries($arrSection, $module);
                
                case 'columns':
                    return $this->_layoutColumns($arrSection, $module);
                
                case 'list':
                default:
                    return $this->_layoutList($arrSection, $module);
            }
        }

        /**
         * Method to generate the layout for a section
         * in 'Previous Layout'
         *
         * @param array $arrSection The Section record
         * @access private
         * @return string
         */
        function _layoutPrevious($arrSection, $module)
        {
            $pageId = $this->getParam('pageid', '');
            $orderType = $arrSection['ordertype'];
            $showIntro = $arrSection['showintroduction'];
            $showDate = $arrSection['showdate'];
            $description = $arrSection['description'];

            switch ($orderType) {

            case null:
            case 'pageorder':
                $filter = 'ORDER BY ordering';
                break;

            case 'pagedate_asc':
                $filter = 'ORDER BY created';
                break;

            case 'pagedate_desc':
                $filter = 'ORDER BY created DESC';
                break;

            case 'pagetitle_asc':
                $filter = 'ORDER BY title';
                break;

            case 'pagetitle_desc':
                $filter = 'ORDER BY title DESC';
                break;
            }
            
            $arrPages = $this->_objContent->getAll('WHERE sectionid = \''.$arrSection['id'].'\' AND published=1 AND trash=0 '.$filter);

            $cnt = 0;
            $returnStr = '';
            $strBody = '';
            $str = '';

            if ($pageId == '') {
                $pageId = $arrPages[0]['id'];
            }

            $foundPage = FALSE;

            if(!empty($arrPages)){
                $str = '<ul>';
                foreach ($arrPages as $page) {
                    if ($foundPage == TRUE) {
                        $link = new link($this->uri(array('action' => 'showsection', 'id' => $arrSection['id'], 'pageid' => $page['id'], 'sectionid' => $page['sectionid']), $module));
                        $link->link = $page['title'];
                        if($showDate) {
                            $str .= '<li>'. $this->objDate->formatDate($page['created']).' - '.$link->show() .'</li> ';
                        } else {
                            $str .= '<li>'. $link->show() .'</li> ';
                        }
                    }
    
                    if ($pageId == $page['id']) {
                        $this->objHead->str = $page['title'];
                        $this->objHead->type = 2;
                        $strBody = $this->objHead->show();
                        $strBody .= stripslashes($page['body']);
                        $foundPage = TRUE;
                        
                        $strBody = $this->objRound->show($strBody);
                        
                    }
                }
                $str .= '</ul>';
            }
            
            if($showIntro && !empty($description)) {
                $returnStr = '<hr /><em>'.$description.'</em><hr />';
            }
            
            $returnStr .= '<p>'.$strBody.'</p><p>'.$str.'</p>';
            return $returnStr;
        }

        /**
         * Method to generate the layout for a section
         * in 'Previous Layout'
         *
         * @param array $arrSection The Section record
         * @access private
         * @return string Content to be displayed
         */
        function _layoutSummaries($arrSection, $module)
        {
            $str = '';
            
            $lbWritten = $this->objLanguage->languageText('phrase_writtenby');
            $lbRead = $this->objLanguage->languageText('phrase_readmore');

            $orderType = $arrSection['ordertype'];
            $showIntro = $arrSection['showintroduction'];
            $showDate = $arrSection['showdate'];
            $description = $arrSection['description'];
            $sectionTitle = $arrSection['title'];

            //Add section title
            $this->objHead->type = 2;
            $this->objHead->str = $sectionTitle;
            $headStr = $this->objHead->show();
            
            //Check if section intro should be displayed and act accordingly
            if($showIntro && !empty($description)) {
              $headStr .= $description;
            }
            $str .= $this->objRound->show($headStr);

            switch ($orderType) {
            case 'pagedate_asc':
                $filter = 'ORDER BY created';
                break;

            case 'pagedate_desc':
                $filter = 'ORDER BY created DESC';
                break;

            case 'pagetitle_asc':
                $filter = 'ORDER BY title';
                break;

            case 'pagetitle_desc':
                $filter = 'ORDER BY title DESC';
                break;
                
            case 'pageorder':
            default:
                $filter = 'ORDER BY ordering';
                break;
            }
            $arrPages = $this->_objContent->getAll('WHERE sectionid = \''.$arrSection['id'].'\' AND published=1 AND trash=0 '.$filter);
                        
            if(!empty($arrPages)){
                foreach ($arrPages as $page) {
                    $pageStr = '';
                    
                    $this->objHead->type = '4';
                    $this->objHead->str = $page['title'];
                    $pageStr .= $this->objHead->show();
                   
                    $pageStr .= '<p>';
                    if (isset($page['created_by'])) {
                        $pageStr .= '<span class="minute">'.$lbWritten.'&nbsp;'.$this->objUser->fullname($page['created_by']).'</span>';
                    }
                    
                    if($showDate){
                        $creationDate = $this->objDate->formatDate($page['created']);
                        $pageStr .= '<span class="date">'.$creationDate.'</span>';
                    }
                    $pageStr .= '</p>';
                    
                    // introduction text
                    $pageStr .= '<p>'.stripslashes($page['introtext']);
    
                    // Read more link
                    $uri = $this->uri(array('action' => 'showfulltext', 'sectionid' => $arrSection['id'], 'id' => $page['id']), $module);
                    $readMoreLink = new link($uri);
                    $readMoreLink->link = $lbRead.'...';
                    $readMoreLink->title = $page['title'];
                    $readMoreLink->cssClass = 'morelink';
    
                    $pageStr .= '<br />'.$readMoreLink->show().'</p><hr />';
                    $str .= $pageStr;
                }
            }

            return $str;
        }

        /**
         * Method to generate the layout for a section
         * in 'Page Layout'
         *
         * @param array $arrSection The Section record
         * @access private
         * @return string Content to be displayed
         */
        function _layoutPage($arrSection, $module)
        {
            $pageId = $this->getParam('pageid', '');
            $showIntro = $arrSection['showintroduction'];
			$description = $arrSection['description'];
            $orderType = $arrSection['ordertype'];
            $showDate = $arrSection['showdate'];
			$imagesrc = $arrSection['link'];
			$introStr = null;
			
            switch ($orderType) {

                case 'pagedate_asc':
                    $filter = 'ORDER BY created';
                    break;

                case 'pagedate_desc':
                    $filter = 'ORDER BY created DESC';
                    break;

                case 'pagetitle_asc':
                    $filter = 'ORDER BY title';
                    break;

                case 'pagetitle_desc':
                    $filter = 'ORDER BY title DESC';
                    break;
                    
                case 'pageorder':
                default:
                    $filter = 'ORDER BY ordering';
                    break;
            }
            
            $arrPages = $this->_objContent->getAll('WHERE sectionid = \''.$arrSection['id'].'\' AND published=1 AND trash=0 '.$filter);

            $cnt = 0;
            $topStr = '';
            $str = '';

            if ($pageId == '') {
                if (count($arrPages)) {
                    $pageId = $arrPages[0]['id'];
                }
            }

            // Display the selected page
            // Display links to the other pages
            if(!empty($arrPages)){
            	 if(!empty($description)) {
            	 	 $introStr = null;
            	 	 $introStr .= '<p><hr /><span>'.$arrSection['title'].'&nbsp;'.'</span>';
					 $introStr .= '</p>';
            		 $introStr .=  '<em><span>'.$description.'</span>';
                     $introStr .= '<br /></em><hr />';
                	
            	 }
                foreach ($arrPages as $page) {
                    if ($pageId == $page['id']) {
                        $this->objHead->type = 2;
                        $this->objHead->str = $page['title'];
                        $pageStr = $this->objHead->show();
                        $pageStr .= stripslashes($page['body']);
                        
                        $topStr = $this->objRound->show($pageStr);
                        $str .= $page['title'].' | ';
                    } else {
                        $link = new link($this->uri(array('action' => 'showsection', 'pageid' => $page['id'], 'id' => $page['sectionid'], 'sectionid' => $page['sectionid']), $module));
                        $link->link = $page['title'];
                        $str .= $link->show() .' | ';
                    }
                }
            }

            // Remove the end pipe
            if (strlen($str) > 1) {
                $str = substr($str, 0, strlen($str) - 3);
            }

            return $introStr.'<p />'.$topStr.'<p>'.$str.'</p>';
        }

        /**
        * Method to display content in two columns
        *
        * @access private
        * @return string html
        */
        private function _layoutColumns($arrSection, $module)
        {
            return '';
        }

        /**
         * Method to generate the layout for a section
         * in 'List Layout'
         *
         * @param array $arrSection The Section record
         * @access private
         * @return string
         */
        function _layoutList($arrSection, $module)
        {
            $str = '';
            $introStr = '';
			$objFeatureBox = $this->newObject('featurebox', 'navigation');
			$objMindMap = $this->getObject('parse4mindmap', 'filters');
            $objMath = $this->getObject('parse4mathml', 'filters');
            $orderType = $arrSection['ordertype'];
            $showIntro = stripslashes($arrSection['showintroduction']);
            $showDate = $arrSection['showdate'];
            $description = stripslashes($arrSection['description']);
            $imagesrc = $arrSection['link'];
            $title = $arrSection['title'];

            switch ($orderType) {
    
                case 'pagedate_asc':
                    $filter = 'ORDER BY created';
                    break;
    
                case 'pagedate_desc':
                    $filter = 'ORDER BY created DESC';
                    break;
    
                case 'pagetitle_asc':
                    $filter = 'ORDER BY title';
                    break;
    
                case 'pagetitle_desc':
                    $filter = 'ORDER BY title DESC';
                    break;
                    
                case 'pageorder':
                default:
                    $filter = 'ORDER BY ordering';
                    break;
            }
            $arrPages = $this->_objContent->getAll('WHERE sectionid = \''.$arrSection['id'].'\' AND published=1 AND trash=0 '.$filter);

            if(!empty($arrPages)){
                $str .= '<ul>';
                foreach ($arrPages as $page) {
                    $link = new link ($this->uri(array('action' => 'showcontent', 'id' => $page['id'], 'sectionid' => $page['sectionid']), $module));
                    $link->link = $page['title'];
                    $str .= '<li>'.$this->objDate->formatDate($page['created']).' - '. $link->show() .'</li>';
                }
                $str .= '</ul>';
            }
            
            $this->objHead->str = $title;
            $this->objHead->type = 2;
            $introStr = $this->objHead->show();
            
            //parse the body stuff
            $objMindMap->parse($description);
            $objMath->parseAll($description);
            
            if($showIntro && !empty($description)){
            		 $introStr .= '<p><span>'.$description.'</span>';
                     $introStr .= '<br /></p>';
            }
            return $this->objRound->show($introStr).$str;
        }
        
        /**
         * Method to show  the body of a pages
         *
         * @access public
         * @return string The page content to be displayed
         */
        public function showBody()

        {
        	$objFeatureBox = $this->newObject('featurebox', 'navigation');
            $contentId = $this->getParam('id');
            $lbWritten = $this->objLanguage->languageText('phrase_writtenby');
            $page = $this->_objContent->getContentPage($contentId);
            $sectionId = $page['sectionid'];
            $section = $this->_objSections->getSection($sectionId);

            $objMindMap = $this->getObject('parse4mindmap', 'filters');
            $objMath = $this->getObject('parse4mathml', 'filters');
            		    
            //Build Footer Items
		     $bmurl = $this->uri(array(
                    'action' => 'showsection',
                    'module' => 'cms',
                    'sectionid' => $sectionId
                ));
             //pdf url
              $pdfurl = $this->uri(array(
                    'action' => 'makepdf',
                    'sectionid' => $sectionId,
                    'module' => 'cms',
                    'id' => $page['id']
                ));
                $bmurl = urlencode($bmurl);
                $bmlink = "http://www.addthis.com/bookmark.php?pub=&amp;url=".$bmurl."&amp;title=".urlencode(addslashes(htmlentities($page['title'])));
                $bmtext = '<img src="http://www.addme.com/images/button1-bm.gif" width="125" height="16" border="0" alt="'.$this->objLanguage->languageText("mod_cms_bookmarkarticle", "cms").'"/>';
                $bookmark = new href($bmlink, $bmtext, NULL);
                //do the cc licence part
                $cclic = $page['post_lic'];

                //get the lic that matches from the db
                $this->objCC = $this->getObject('displaylicense', 'creativecommons');
                if ($cclic == '') {
                	$cclic = 'copyright';
                }
                $iconList = $this->objCC->show($cclic);

                //table of non logged in options
                //Set the table name
                $tblnl = $this->newObject('htmltable', 'htmlelements');
                $tblnl->cellpadding = 3;
                $tblnl->width = "100%";
                $tblnl->align = "center";
                $tblnl->addCell($bookmark->show(),null,null,'left'); //bookmark link(s)
                $tblnl->addCell('<em class="date">'.$this->objLanguage->languageText("mod_cms_lastupdated", "cms").':' .$this->objDate->formatDate($page['modified']).'</em>',null,null,'left');               
                $tblnl->addCell($iconList); //cc licence
                    
            // Print & pdf icons
            $this->objIcon->setIcon('pdf', 'png', 'icons/cms/');
            $objLink = new link($this->uri(''));
            $objLink->link = $this->objIcon->show();
            $icons = $objLink->show();
            //and the mail to a friend icon
            $mtficon = $this->newObject('geticon', 'htmlelements');
            $mtficon->setIcon('filetypes/eml');
            $lblmtf = $this->objLanguage->languageText("mod_cms_mailtofriend", "cms");
            $mtficon->alt = $lblmtf;
            $mtficon->align = false;
            $mtfimg = $mtficon->show();

            $mtflink = new href($this->uri(array('action' => 'mail2friend', 'sectionid' => $page['sectionid'], 'id' => $page['id'])), $mtfimg, NULL);
         
            //Create heading

            $this->objHead->type = 2;
            $this->objHead->str = $page['title'];
           
            //Lets format the header information for the page
            $tblh = $this->newObject('htmltable', 'htmlelements');
            $tblh->cellpadding = 3;
            $tblh->width = "100%";
            $tblh->align = "center";
            $tblh->startRow();
            //PDF link into header
            $pdficon = $this->newObject('geticon', 'htmlelements');
            $pdficon->setIcon('filetypes/pdf');
            $lblView = $this->objLanguage->languageText("mod_cms_saveaspdf", "cms");
            $pdficon->alt = $lblView;
            $pdficon->align = false;
            $pdfimg = $pdficon->show();
			$pdflink = null;
            $pdflink = new href($pdfurl, $pdfimg, NULL);
			$tblh->addCell($this->objHead->show());
            $tblh->addCell($pdflink->show() . $mtflink->show(),null,null,'center'); //pdf icon
            $tblh->endRow();
            $strBody = null;
            $strBody .= '<p><span class="date">'.$lbWritten.'&nbsp;'.$this->objUser->fullname($page['created_by']).'</span>';

            $strBody .= '</p>';
            $strBody .=  '<em><span class="date">'.$this->objDate->formatDate($page['created']).'</span>';
            $strBody .= '<br /></em><hr />';
            //parse for mindmaps
            $page['body'] = $objMindMap->parse($page['body']);
            //parse for mathml as well
            $page['body'] = $objMath->parseAll($page['body']);
            $strBody .= stripslashes($page['body']);

             $strBody .= '<hr /><p />';
             
                         
            return $objFeatureBox->showContent($tblh->show(),$strBody ."<p /><center>".$tblnl->show() ."</center>");
          
          
        }
        
        /**
         * The method generates the interface for the send mail to a
         * friend funtionality
         *
         * @param array $m2fdata
         * @return form interface
         */
    	public function sendMail2FriendForm($m2fdata){
    	
    	$this->objUser = $this->getObject('user', 'security');
    	if($this->objUser->isLoggedIn())
		{
			$theuser = $this->objUser->fullName($this->objUser->userid());
		}
		else {
			$theuser = $this->objLanguage->languageText("mod_cms_word_anonymous", "cms");
		}
    	//start a form object
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $mform = new form('mail2friend', $this->uri(array(
            'action' => 'mail2friend', 'id' => $m2fdata['id']
        )));
        $mfieldset = $this->newObject('fieldset', 'htmlelements');
        //$mfieldset->setLegend($this->objLanguage->languageText('mod_blog_sendmail2friend', 'blog'));
        $mtable = $this->newObject('htmltable', 'htmlelements');
        $mtable->cellpadding = 3;
        $mtable->startHeaderRow();
        $mtable->addHeaderCell('');
        $mtable->addHeaderCell('');
        $mtable->endHeaderRow();
        //your name
        $mtable->startRow();
        $mynamelabel = new label($this->objLanguage->languageText('mod_cms_myname', 'cms') .':', 'input_myname');
        $myname = new textinput('sendername');
        $myname->size = '80%';
        $myname->setValue($theuser);
        $mtable->addCell($mynamelabel->show());
        $mtable->addCell($myname->show());
        $mtable->endRow();

        //Friend(s) email addresses
        $mtable->startRow();
        $femaillabel = new label($this->objLanguage->languageText('mod_cms_emailadd', 'cms') .':', 'input_femail');
        $emailadd = new textinput('emailadd');
        $emailadd->size = '80%';
        if(isset($m2fdata['user']))
        {
        	$emailadd->setValue($m2fdata['user']);
        }
        $mtable->addCell($femaillabel->show());
        $mtable->addCell($emailadd->show());
        $mtable->endRow();
        //message for friends (optional)
        $mtable->startRow();
        $fmsglabel = new label($this->objLanguage->languageText('mod_cms_emailmsg', 'cms') .':', 'input_femailmsg');
        $msg = new textarea('msg','',4,68);
        $mtable->addCell($fmsglabel->show());
        $mtable->addCell($msg->show());
        $mtable->endRow();

        //add a rule
        $mform->addRule('emailadd', $this->objLanguage->languageText("mod_cms_phrase_emailreq", "cms") , 'required');
        $mfieldset->addContent($mtable->show());
        $mform->addToForm($mfieldset->show());
        $this->objMButton = new button($this->objLanguage->languageText('mod_cms_word_sendmail', 'cms'));
        $this->objMButton->setValue($this->objLanguage->languageText('mod_cms_word_sendmail', 'cms'));
        $this->objMButton->setToSubmit();
        $mform->addToForm($this->objMButton->show());
        $mform = $mform->show();

        //bust out a featurebox for consistency
        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_cms_sendmail2friend", "cms") , $mform);
        return $ret;

    }
    
    /**
     * Method to build and create the feeds options box
     *
     * @author Paul Scott
     * @param integer $userid
     * @param bool $featurebox
     * @return string
     */
    public function showFeeds($pageid, $featurebox = FALSE, $showOrHide = 'none')
    {
        $this->objUser = $this->getObject('user', 'security');
        
        $leftCol = NULL;
        if($featurebox == FALSE)
        {
            $leftCol .= "<em>" . $this->objLanguage->languageText("mod_cms_feedheader", "cms") . "</em><br />";
        }
        
        //RSS2.0
        $rss2 = $this->getObject('geticon', 'htmlelements');
        $rss2->setIcon('rss', 'gif', 'icons/filetypes');
        $link = new href($this->uri(array('action' => 'feed', 'format' => 'rss2', 'pageid' => $pageid)), $this->objLanguage->languageText("mod_cms_word_rss2", "cms"));
        $leftCol .= $rss2->show() . $link->show() . "<br />";

        //RSS0.91
        $rss091 = $this->getObject('geticon', 'htmlelements');
        $rss091->setIcon('rss', 'gif', 'icons/filetypes');
        $link = new href($this->uri(array('action' => 'feed', 'format' => 'rss091', 'pageid' => $pageid)),$this->objLanguage->languageText("mod_cms_word_rss091", "cms"));
        $leftCol .= $rss091->show() . $link->show() . "<br />";

        //RSS1.0
        $rss1 = $this->getObject('geticon', 'htmlelements');
        $rss1->setIcon('rss', 'gif', 'icons/filetypes');
        $link = new href($this->uri(array('action' => 'feed', 'format' => 'rss1', 'pageid' => $pageid)),$this->objLanguage->languageText("mod_cms_word_rss1", "cms"));
        $leftCol .= $rss1->show() . $link->show() . "<br />";

        //PIE
        $pie = $this->getObject('geticon', 'htmlelements');
        $pie->setIcon('rss', 'gif', 'icons/filetypes');
        $link = new href($this->uri(array('action' => 'feed', 'format' => 'pie', 'pageid' => $pageid)),$this->objLanguage->languageText("mod_cms_word_pie", "cms"));
        $leftCol .= $pie->show() . $link->show() . "<br />";

        //MBOX
        $mbox = $this->getObject('geticon', 'htmlelements');
        $mbox->setIcon('rss', 'gif', 'icons/filetypes');
        $link = new href($this->uri(array('action' => 'feed', 'format' => 'mbox', 'pageid' => $pageid)),$this->objLanguage->languageText("mod_cms_word_mbox", "cms"));
        $leftCol .= $mbox->show() . $link->show() . "<br />";

        //OPML
        $opml = $this->getObject('geticon', 'htmlelements');
        $opml->setIcon('rss', 'gif', 'icons/filetypes');
        $link = new href($this->uri(array('action' => 'feed', 'format' => 'opml', 'pageid' => $pageid)),$this->objLanguage->languageText("mod_cms_word_opml", "cms"));
        $leftCol .= $opml->show() . $link->show() . "<br />";

        //ATOM
        $atom = $this->getObject('geticon', 'htmlelements');
        $atom->setIcon('rss', 'gif', 'icons/filetypes');
        $link = new href($this->uri(array('action' => 'feed', 'format' => 'atom', 'pageid' => $pageid)),$this->objLanguage->languageText("mod_cms_word_atom", "cms"));
        $leftCol .= $atom->show() . $link->show() . "<br />";

        //Plain HTML
        $html = $this->getObject('geticon', 'htmlelements');
        $html->setIcon('rss', 'gif', 'icons/filetypes');
        $link = new href($this->uri(array('action' => 'feed', 'format' => 'html', 'pageid' => $pageid)),$this->objLanguage->languageText("mod_cms_word_html", "cms"));
        $leftCol .= $html->show() . $link->show() . "<br />";
        
        $this->setVar('pageSuppressXML',true);
        $icon = $this->getObject('geticon', 'htmlelements');
        $icon->setIcon('up');
        $scripts = '<script src="core_modules/htmlelements/resources/script.aculos.us/lib/prototype.js" type="text/javascript"></script>
                      <script src="core_modules/htmlelements/resources/script.aculos.us/src/scriptaculous.js" type="text/javascript"></script>
                      <script src="core_modules/htmlelements/resources/script.aculos.us/src/unittest.js" type="text/javascript"></script>';
        $this->appendArrayVar('headerParams',$scripts);
        $str = "<a href=\"#\" onclick=\"Effect.SlideUp('feedmenu',{queue:{scope:'myscope', position:'end', limit: 1}});\">".$icon->show()."</a>";
        $icon->setIcon('down');
        $str .="<a href=\"#\" onclick=\"Effect.SlideDown('feedmenu',{queue:{scope:'myscope', position:'end', limit: 1}});\">".$icon->show()."</a>";

        $str .='<div id="feedmenu"  style="width:170px;overflow: hidden;display:'.$showOrHide.';"> ';
        $str .= $leftCol;
        $str .= '</div>';


        if($featurebox == FALSE){
            return $str;
        }else {
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_cms_feedheader","cms"), $str);
            return $ret;
        }


    }
        
    /**
     * Method to output a rss feeds box
     *
     * @param string $url
     * @param string $name
     * @return string
     */
    public function rssBox($url, $name)
    {
    	$objFeatureBox = $this->getObject('featurebox', 'navigation');
        $objRss = $this->getObject('rssreader', 'feed');
        $objRss->parseRss($url);
        $head = $this->objLanguage->languageText("mod_cms_word_headlinesfrom", "cmsadmin");
        $head .= " " . $name;
        $content = "<ul>\n";
        foreach ($objRss->getRssItems() as $item)
        {
        	if(!isset($item['link']))
        	{
        		$item['link'] = NULL;
        	}
    		@$content .= "<li><a href=\"" . htmlentities($item['link']) . "\">" . htmlentities($item['title']) . "</a></li>\n";
		}
		$content .=  "</ul>\n";
		return $objFeatureBox->show($head, $content);
    }

    public function rssRefresh($rssurl, $name, $feedid)
    {
    	$objFeatureBox = $this->getObject('featurebox', 'navigation');
    	$objRss = $this->getObject('rssreader', 'feed');
    	$this->objConfig = $this->getObject('altconfig', 'config');

    	//get the proxy info if set
    	$objProxy = $this->getObject('proxyparser', 'utilities');
    	$proxyArr = $objProxy->getProxy();

    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $rssurl);
    	//curl_setopt($ch, CURLOPT_HEADER, 1);
    	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	if(!empty($proxyArr) && $proxyArr['proxy_protocol'] != '')
    	{
    		curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'].":".$proxyArr['proxy_port']);
    		curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'].":".$proxyArr['proxy_pass']);
    	}
    	$rsscache = curl_exec($ch);
    	curl_close($ch);
    	//var_dump($rsscache);
    	//put in a timestamp
    	$addtime = time();
    	$addarr = array('url' => $rssurl, 'rsstime' => $addtime);

    	//write the file down for caching
    	$path = $this->objConfig->getContentBasePath() . "/cms/rsscache/";
    	$rsstime = time();
    	if(!file_exists($path))
    	{

    		mkdir($path);
    		chmod($path, 0777);
    		$filename = $path . $this->objUser->userId() . "_" . $rsstime . ".xml";
    		if(!file_exists($filename))
    		{
    			touch($filename);

    		}
    		$handle = fopen($filename, 'wb');
    		fwrite($handle, $rsscache);
    	}
    	else {
    		$filename = $path . $this->objUser->userId() . "_" . $rsstime . ".xml";
    		$handle = fopen($filename, 'wb');
    		fwrite($handle, $rsscache);
    	}
    	//update the db
    	$addarr = array('url' => htmlentities($rssurl), 'rsscache' => $filename, 'rsstime' => $addtime);
    	//print_r($addarr);
    	$this->objDbBlog->updateRss($addarr, $feedid);

    	$objRss->parseRss($rsscache);
    	$head = $this->objLanguage->languageText("mod_cms_word_headlinesfrom", "cmsadmin");
    	$head .= " " . $name;
    	$content = "<ul>\n";
    	foreach ($objRss->getRssItems() as $item)
    	{
    		if(!isset($item['link']))
    		{
    			$item['link'] = NULL;
    		}
    		@$content .= "<li><a href=\"" . htmlentities($item['link']) . "\">" . htmlentities($item['title']) . "</a></li>\n";
    	}
    	$content .=  "</ul>\n";
    	return $objFeatureBox->show($head, $content);

    }

    public function rssEditor($featurebox = FALSE, $rdata = NULL)
    {
    	//print_r($rdata);
    	$this->loadClass('href', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');

    	$this->objUser = $this->getObject('user', 'security');
    	if($rdata == NULL)
    	{
        	$rssform = new form('addrss', $this->uri(array(
            	'action' => 'addrss'
        	)));
    	}
    	else {
    		$rdata = $rdata[0];
    		$rssform = new form('addrss', $this->uri(array(
            	'action' => 'rssedit', 'mode' => 'edit', 'id' => $rdata['id']
        	)));
    	}
        //add rules
        $rssform->addRule('rssurl', $this->objLanguage->languageText("mod_cms_phrase_rssurlreq", "cmsadmin") , 'required');
        $rssform->addRule('name', $this->objLanguage->languageText("mod_cms_phrase_rssnamereq", "cmsadmin") , 'required');
        //start a fieldset
        $rssfieldset = $this->getObject('fieldset', 'htmlelements');
        $rssadd = $this->newObject('htmltable', 'htmlelements');
        $rssadd->cellpadding = 3;

        //url textfield
        $rssadd->startRow();
        $rssurllabel = new label($this->objLanguage->languageText('mod_cms_rssurl', 'cmsadmin') .':', 'input_rssuser');
        $rssurl = new textinput('rssurl');
        if(isset($rdata['url']))
        {
        $rssurl->setValue($rdata['url']);
       // $rssurl->setValue('url');

		}
        $rssadd->addCell($rssurllabel->show());
        $rssadd->addCell($rssurl->show());
        $rssadd->endRow();

        //name
        $rssadd->startRow();
        $rssnamelabel = new label($this->objLanguage->languageText('mod_cms_rssname', 'cmsadmin') .':', 'input_rssname');
        $rssname = new textinput('name');
        if(isset($rdata['name']))
        {
        	$rssname->setValue($rdata['name']);
        }
        $rssadd->addCell($rssnamelabel->show());
        $rssadd->addCell($rssname->show());
        $rssadd->endRow();

        //description
        $rssadd->startRow();
        $rssdesclabel = new label($this->objLanguage->languageText('mod_cms_rssdesc', 'cmsadmin') .':', 'input_rssname');
        $rssdesc = new textarea('description');
        if(isset($rdata['description']))
        {
          	//var_dump($rdata['description']);
        	$rssdesc->setValue($rdata['description']);
        }
        $rssadd->addCell($rssdesclabel->show());
        $rssadd->addCell($rssdesc->show());
        $rssadd->endRow();

        //end off the form and add the buttons
        $this->objRssButton = &new button($this->objLanguage->languageText('word_save', 'system'));
        $this->objRssButton->setValue($this->objLanguage->languageText('word_save', 'system'));
        $this->objRssButton->setToSubmit();
        $rssfieldset->addContent($rssadd->show());
        $rssform->addToForm($rssfieldset->show());
        $rssform->addToForm($this->objRssButton->show());
        $rssform = $rssform->show();

        //ok now the table with the edit/delete for each rss feed
        $efeeds = $this->objRss->getUserRss($this->objUser->userId());
        $ftable = $this->newObject('htmltable', 'htmlelements');
        $ftable->cellpadding = 3;
        //$ftable->border = 1;
        //set up the header row
        $ftable->startHeaderRow();
        $ftable->addHeaderCell($this->objLanguage->languageText("mod_cms_fhead_name", "cmsadmin"));
        $ftable->addHeaderCell($this->objLanguage->languageText("mod_cms_fhead_description", "cmsadmin"));
        $ftable->addHeaderCell('');
        $ftable->endHeaderRow();

        //set up the rows and display
        if (!empty($efeeds)) {
            foreach($efeeds as $rows) {
                $ftable->startRow();
                $feedlink = new href($rows['url'], $rows['name']);
                $ftable->addCell($feedlink->show());
                //$ftable->addCell(htmlentities($rows['name']));
                $ftable->addCell(($rows['description']));
                $this->objIcon = &$this->getObject('geticon', 'htmlelements');
                $edIcon = $this->objIcon->getEditIcon($this->uri(array(
                    'action' => 'addrss',
                    'mode' => 'edit',
                    'id' => $rows['id'],
                    //'url' => $rows['url'],
                    //'description' => $rows['description'],
                    'module' => 'cmsadmin'
                )));
                $delIcon = $this->objIcon->getDeleteIconWithConfirm($rows['id'], array(
                    'module' => 'cmsadmin',
                    'action' => 'deleterss',
                    'id' => $rows['id']
                ) , 'cmsadmin');
                $ftable->addCell($edIcon.$delIcon);
                $ftable->endRow();
            }
            //$ftable = $ftable->show();
        }
      
            return $rssform . $ftable->show();

    }
        
}
?>