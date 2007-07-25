<?
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

define("BY_URL", 0);
define("BY_ID", 1);

/**
*
* Class for manipulating YouTube API generated XML
*
* @author Derek Keats
* @category Chisimba
* @package youtube
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class youtubetpl extends object 
{
    
    /**
     * 
    * @var $objLanguage String object property for holding the 
    * language object
    * @access private
    * 
    */
    private $objLanguage;
    
    /**
    *
    * Standard init method  
    *
    */
    public function init()
    {
        //Get the language object 
        $this->objLanguage = $this->getObject('language', 'language');
        //Load the link builder and image classes since we use them 
        //  in more than one funciton
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('image', 'htmlelements');
    }
    
    /**
    * 
    * A method to show the requested videos as thumbnails
    * with next and previous links. The videos are formatted
    * into a table.
    * 
    */
    public function showVideos(& $apiXml)
    {
        // loop through each video in the list and display it
        $i = 0;
        $count = 0;
        $this->loadClass('htmltable', 'htmlelements');
        $outerTable = new htmltable();
        $table = new htmltable();
        $table->startRow();
        $table->width=399;
        $str="";
        //Get the total number of videos
        $total = $apiXml->video_list->total;
        //Get the number of hits per page
        $perPage = $this->getParam('hitsperpage', 24);
        $objLink = new link();
        $objImage = new image();
        $videoId = $this->getParam('videoid', 'none');
        //Get the video player so we can play the first or chosen video
        $this->objYtFilter = $this->getObject('parse4youtube', 'filters');
        if ($videoId == 'none') {
            $displayVideoUrl = $apiXml->video_list->video[0]->url;
            $videoId = $this->objYtFilter->getVideoCode($displayVideoUrl);
        }
        $vidPlayer = $this->getVideoPlayer($videoId);
        $vidPlayer .= "<br />" . $this->objLanguage->languageText("mod_youtube_chifilter", "youtube")
          . "<br />" . $this->getFilterLink($this->getYahooUrl($videoId));
        //Loop and insert the thumbnails with links 
        foreach($apiXml->video_list->video as $video) {
            //Keep the title short to not break the layout
            $title = htmlentities(substr($video->title, 0, 15)) . '...';
            //Add the link to a filter
            //$title .= $this->getFilterLink($video->url);
            //The thumbnaiil image for the current video
            $objImage->src = $video->thumbnail_url;
            //Set up the link URL
            $videoUrl = $video->url;
            $videoId = $this->objYtFilter->getVideoCode($videoUrl);
            
            $ytMethod = $this->getParam('ytmethod', 'by_tag');
            $ytIdentifier = $this->getParam('identifier', 'digitalfreedom');
            $action=$this->getParam('action', 'view');
            $arUri = array(
              'ytmethod'=>$ytMethod,
              'identifier'=>$ytIdentifier,
              'page'=>$this->getParam('page', 1),
              'action'=>$action,
              'videoid'=>$videoId);
            $objLink->href = $this->uri($arUri, 'youtube');
            ////////////working there
            
            //$objLink->href = htmlentities($video->url);
            //Make the link the image
            $objLink->link = $objImage->show();
            //Add the linked image to a table cell
            $table->addCell($objLink->show() . "<br />" . $title, "130");
            //Using two counters, one to keep track of rows, and one to track the totals
            $i++;
            $count++;
            $flag=FALSE;
            // only 3 videos per row
            if ($i == 3) {
                $i = 0;
                $table->endRow();
                //echo $count . "----" . $perPage . "<br />";
                if ($count != $perPage) {
                    $table->startRow();
                }
                $flag=TRUE;
            }
        }
        //If we have not closed the row then close it
        if ($flag==FALSE) {
            $table->endRow();
        }
        $str = $table->show();
        //Clean up the table memory
        unset($table);
        //Count the number of pages        
        if ($total > $perPage) {
            $pages = round($total/$perPage, 0);
        } else {
            $pages = 1;
        }
        $page = $this->getParam('page', 1);
        $nextPage = $this->getNextPageLink($page, $pages);
        $prevPage = $this->getPrevPageLink($page, $pages);
        $arRep=array(
          'PAGE'=>$page,
          'PAGES'=>$pages);
        $pageOf = $this->objLanguage->code2Txt("mod_youtube_pageofpages", "youtube", $arRep);
        $navTable = new htmltable();
        $navTable->width = 399;
        $navTable->startRow();
        $navTable->addCell($prevPage);
        $navTable->addCell($pageOf);
        $navTable->addCell($nextPage, NULL, "top", "right");
        $navTable->endRow();
        $navBar = $navTable->show();
        $str = $navBar . $str . $navBar;
        $outerTable->startRow();
        $outerTable->addCell($str);
        $outerTable->addCell($vidPlayer);
        $outerTable->endRow();
        return $outerTable->show();
    }
    
    /**
    * 
    * Method to get and format the Next page link
    * @return string The formatted next page link 
    */
    private function getNextPageLink(&$page, &$pages)
    {
        if ($page < $pages) {
            $nextPage = $page+1;
            $ytMethod = $this->getParam('ytmethod', 'by_tag');
            $ytIdentifier = $this->getParam('identifier', 'digitalfreedom');
            $action=$this->getParam('action', 'view');
            $arUri = array(
              'ytmethod'=>$ytMethod,
              'identifier'=>$ytIdentifier,
              'page'=>$nextPage,
              'action'=>$action);
            //Use the module we are in so any module can have a page of vids
            $curModule = $this->getParam('module', 'youtube');
            $objLink = new link();
            $objLink->href = $this->uri($arUri, $curModule);
            $objLink->link = $this->objLanguage->languageText("mod_youtube_nextpage", "youtube");
            return $objLink->show();
        } else {
            return $this->objLanguage->languageText("mod_youtube_onlastpage", "youtube");
        }
    }
    
    /**
    * 
    * Method to get and format the Previous page link
    * 
    * @param integer $page The page number
    * @param integer $pages The number of pages
    * @return string The formatted next page link 
    * 
    */
    private function getPrevPageLink(&$page, &$pages)
    {
        $prevPage = $page-1;
        if ($page > 1) {
            $prevPage = $page-1;
            $ytMethod = $this->getParam('ytmethod', 'by_tag');
            $ytIdentifier = $this->getParam('identifier', 'digitalfreedom');
            $action=$this->getParam('action', 'view');
            $arUri = array(
              'ytmethod'=>$ytMethod,
              'identifier'=>$ytIdentifier,
              'page'=>$prevPage,
              'action'=>$action);
            $objLink = new link();
            $objLink->href = $this->uri($arUri, 'youtube');
            $objLink->link = $this->objLanguage->languageText("mod_youtube_prevpage", "youtube");
            return $objLink->show();
        } else {
            return $this->objLanguage->languageText("mod_youtube_onfirstpage", "youtube");
        }
    }
    
    /**
    * 
    * Method to return a Filter plugin link for 
    * Chisimba from a Youtube video URL
    *  
    */
    private function getFilterLink($url)
    {
        return '[YOUTUBE]' . $url . '[/YOUTUBE]';
    }
    
    public function getTagSearchBox()
    {
        $this->loadClass('form','htmlelements');
        $objForm = new form('vidtag');
        $objForm->setAction($this->uri(array('ytmethod'=>'by_tag'),'youtube'));
        $this->loadClass('textinput','htmlelements');
        $identifier = new textinput('identifier');
        $objForm->setDisplayType(1);
        $objForm->addToForm($this->objLanguage->languageText("mod_youtube_dispbytag", "youtube") . "<br />");
        $objForm->addToForm($identifier);
        $this->loadClass('button','htmlelements');
        $btn = $this->objLanguage->languageText("mod_youtube_showvideos", "youtube");
        $objButton=new button('bytagbtn');
        $objButton->setToSubmit();
        $objButton->setValue($btn);
        $objForm->addToForm($objButton->show());
        return $objForm->show();
    }
    
    public function getUserSearchBox()
    {
        $this->loadClass('form','htmlelements');
        $objForm = new form('viduser');
        $objForm->setAction($this->uri(array('ytmethod'=>'by_user'),'youtube'));
        $this->loadClass('textinput','htmlelements');
        $identifier = new textinput('identifier');
        $objForm->setDisplayType(1);
        $objForm->addToForm($this->objLanguage->languageText("mod_youtube_dispbyuser", "youtube") . "<br />");
        $objForm->addToForm($identifier);
        $this->loadClass('button','htmlelements');
        $btn = $this->objLanguage->languageText("mod_youtube_showvideos", "youtube");
        $objButton=new button('byusrbtn');
        $objButton->setToSubmit();
        $objButton->setValue($btn);
        $objForm->addToForm($objButton->show());
        return $objForm->show();
    }
    
    
   public function showMethod()
   {
        //Get the method to use and default to by_tag
        $ytMethod = $this->getParam('ytmethod', 'by_tag');
        //Get the tag or user or other identifier and default to digitalfreedom
        $ytIdentifier = $this->getParam('identifier', 'digitalfreedom');
        switch ($ytMethod) {
                case "by_tag":
                    $ret = $this->objLanguage->languageText("mod_youtube_bytag", "youtube")
                      . ": " . $ytIdentifier;
                    break;
                    
                case "by_user":
                    $ret = $this->objLanguage->languageText("mod_youtube_byuser", "youtube")
                      . ": " . $ytIdentifier;
                    break;
                    
                case "by_playlist":
                    $ret = $this->objLanguage->languageText("mod_youtube_bypl", "youtube")
                      . ": " . $ytIdentifier;
                    break;
                    
                default:
                    $ret = $this->objLanguage->languageText("mod_youtube_unknownmethod", "youtube")
                      . ": " . $ytMethod;
                    break;
        }
       return $ret;
   } 
    
    public function getVideoPlayer($videoId)
    {
        //$this->objYtFilter = $this->getObject('parse4youtube', 'filters');
        return $this->objYtFilter->getVideoObject($videoId);
    }
    
    public function getYahooUrl(&$videoId)
    {
        return "http://www.youtube.com/watch?v=" . $videoId;
    }
    
}
?>
