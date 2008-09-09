<?php
/**
*
* Wrapper class for SimplePie. This wrapper was generated
* using the generate module of the Chisimba framework as
* developed by Derek Keats on his birthday in 2006. For 
* further information about the class being wrapped, see
* the SimplePie documentation.
*
*/
class spie extends object
{
    
    public $objSimplePieWrapper;
    public $objConfig;
    public $useProxy=FALSE;
    
    /**
    * 
    * Standard init method to initialize the class 
    * (SimplePie) being wrapped.
    *
    */
    public function init()
    {
        // Get the config object.
        $this->objConfig = $this->getObject('altconfig', 'config');
        //Include the class file to wrap 
        require_once($this->getResourcePath('simplepie.inc', "feed"));
        //Instantiate the class
        $this->objSimplePieWrapper = new SimplePie();
        // Set the cache location to usrfiles/feed/cache/
        $cacheLocation = $this->objConfig->getsiteRootPath() . "usrfiles/feed/cache/";
        $this->objSimplePieWrapper->set_cache_location($cacheLocation);

        
        //Check the proxy settings
        $this->checkProxy();
    }
        
    /**
    *
    * Method to extract the proxy settings from the chisimba settings
    * and set the useProxy to TRUE if settings found
    * 
    * @access private
    * @return TRUE
    *
    */
    private function checkProxy()
    {
        
        $proxy = $this->objConfig->getProxy();
        if ($proxy && $proxy !=="") {
            $this->useProxy=TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * This is the URL of the feed you want to parse.
     *
     * This allows you to enter the URL of the feed you want to parse, or the
     * website you want to try to use auto-discovery on. This takes priority
     * over any set raw data.
     *
     * You can set multiple feeds to mash together by passing an array instead
     * of a string for the $url. Remember that with each additional feed comes
     * additional processing and resources.
     *
     * @access public
     * @param mixed $url This is the URL (or array of URLs) that you want to parse.
     * @see SimplePie::set_raw_data()
     */
    public function setFeedUrl($url)
    {
        return $this->objSimplePieWrapper->set_feed_url($url);
    }
    
    /**
    * 
    * Get the feed provided by the given URL and hand off to
    * the display method indicated by $display. Valid methods
    * are:
    *   displayPlain (boring, title and description)
    *   displaySmart (try to figure out what feed it is and display accordingly)
    * 
    * @param string $display The method to use to render the output
    * @param string $url The URL of the feed
    * @return string The rendered feed
    * @access public
    * 
    */
    public function getFeed($url, $display="displayPlain")
    {
        if (!$this->useProxy) {
            $this->setFeedUrl($url);
        } else {
            // We are using a proxy so use the curl wrapper to return the string
            $objCurl = $this->getObject('curl', 'utilities');
            $rss = $objCurl->exec($link);
            $this->objSimplePieWrapper->set_raw_data($rss);
        }
        $this->objSimplePieWrapper->init();
        return $this->$display();
    }
    
    /**
    * 
    * Render the output as a plain display of Title and description
    * 
    * @return string the formatted output
    * @access private
    * 
    */
    private function displayPlain()
    {
        $title = $this->getTitle();
        if ($logo = $this->getImageUrl()) {
        $logo = '<img src="' . $logo
          . '" width="' . $this->getImageWidth() 
          . '" height="' . $this->getImageHeight() 
          . '" alt="' . $title . '" />';
        $ret='<div class="feed_render_title_forcewhite">'  
          . '<table><tr><td>' . $logo . '</td><td><h3 style="color:black;">&nbsp;&nbsp;' 
          . $title . '</h3></td></tr></table></div>';
        } else {
            $ret='<h3 class="feed_render_title">' . $title . '</h3><br />';
        }
        $counter=0;
        foreach ($this->objSimplePieWrapper->get_items() as $item) {
            $counter++;
            $ret .= '<div class="feed_render_top"></div>'
              . '<div class="feed_render_default">'
              . '<p class="feed_render_link"><a href="' . $item->get_permalink() . '">' . $item->get_title() . '</a></p>'
              . '<p class="feed_render_description">' . $item->get_description() . '</p>'
              . '<p class="feed_render_date">' .  $item->get_date('j F Y | g:i a') . '</p>'
              . '</div><div class="feed_render_bottom"></div>';
        }
        return $ret;
    }
    
    /**
     * 
     * Try to figure out what kind of feed we have and be a bit
     * smart about how it is rendered. It uses some criteria to 
     * identify known feed sources, and calls the appropriate method
     * to render them. It degrades to displayPlain if it does not
     * recognize the source.
     * 
     * @return string the formatted output
     * @access private
     * 
     */
    private function displaySmart()
    {
        $title = $this->getTitle();
        if ($this->isTwitterSearch($title)) {
            return $this->twitterSearch();
        } 
        $permaLink = $this->getPermalink();
        if ($this->isYouTube($permaLink)) {
            return $this->youTubeFeed();
        }
        if ($this->isSlideShare($permaLink)) {
            return $this->slideShareFeed();
        }
        // Degrade to the plain display so as not to fail when it cannot identify feed
        return $this->displayPlain();
    }
    
    /**
    * 
    * Process the results of a feed from a twitter search. This
    * avoids the title and description (which are the same in the 
    * feed) being duplicated, and caters for a bug with links in the 
    * title in the current version of SimplePie.
    * 
    * @return string The rendered feed
    * 
    */
    public function twitterSearch()
    {
        $title = $this->getTitle();
        $ret='<h3 class="feed_render_title">' . $title . '</h3><br />';
        $counter=0;
        foreach ($this->objSimplePieWrapper->get_items() as $item) {
            $counter++;
            $author = $item->get_author();
            $name = $author->get_name();
            $ln = $author->get_link();
            $nickAr = explode(" (", $name);
            $nick = "<a href=\"" . $ln . "\">" . $nickAr[0] . "</a>:&nbsp;&nbsp;";
            $description = $item->get_description();
            $info = $nick . " " . $description;
            $ret .= '<div class="feed_render_top"></div>'
              . '<div class="feed_render_default">'
              . '<p class="feed_render_description">' . $info 
              . '<br /><span class="feed_render_date">' 
              .  $item->get_date('j F Y | g:i a') 
              . '</span></p>'
              . '</div><div class="feed_render_bottom"></div>';
        }
        unset($author, $name, $ln, $nickAr, $nick, $description, $info);
        return $ret;
    }
    
    /**
    * 
    * Process the output of a YouTube feed. This avoids the title 
    * being repeated since it is already part of the description. It
    * also inserts the YouTube logo
    * 
    * @return string The rendered feed
    * 
    */
    public function youTubeFeed()
    {
        // YouTube has some really ugly feeds
        $permaLink = $this->getPermalink();
        if (!$this->isYoutTubeStandards($permaLink)) {
            $standardsFeed = FALSE;
        }
        $title = $this->getTitle();
        $logo = '<img src="' . $this->getImageUrl() 
          . '" width="' . $this->getImageWidth() 
          . '" height="' . $this->getImageHeight() 
          . '" alt="You Tube" />';
        //Need to hard code this or it looks ugly
        $ret='<div class="feed_render_title_forcewhite">'  
          . '<table><tr><td>' . $logo . '</td><td><h3 style="color:black;">&nbsp;&nbsp;' 
          . $title . '</h3></td></tr></table></div>';
        $counter=0;
        foreach ($this->objSimplePieWrapper->get_items() as $item) {
            $counter++;
            $description = $item->get_description();
            if (!$standardsFeed) {
                $title = $item->get_title();
                $ln = $item->get_link();
                $title = '<a href="' . $ln . '">'. $title . '</a>';
                $description = str_replace("align=\"right\"", "style=\"float:left; margin-left: 5px; margin-right: 20px;\"", $description);
                $description = $title . "<br />" . $description;
            }
            $ret .= '<div class="feed_render_top"></div>'
              . '<div class="feed_render_default">'
              . '<p class="feed_render_description">' . $description 
              . '<br /><span class="feed_render_date">' 
              .  $item->get_date('j F Y | g:i a') 
              . '</span></p>'
              . '</div><div class="feed_render_bottom"></div>';
        }
        unset($title, $description, $logo);
        return $ret;
        
    }
    
    /**
    * Process the output of a SlideShare feed
    * 
    * Slideshare embeds its whole layout in a CDATA tag and does not give
    * any control over layout. This sucks. It floats the thumbnail right, which looks very ugly.
    * Thus we take it and float it left
    * 
    * @return string The rendered feed
    * 
    */
    public function slideShareFeed()
    {
        $title = $this->getTitle();
        $logo = '<img src="' . $this->getImageUrl() 
          . '" width="' . $this->getImageWidth() 
          . '" height="' . $this->getImageHeight() 
          . '" alt="Slideshare" />';
        $ret='<div class="feed_render_title_forcewhite">'  
          . '<table><tr><td>' . $logo . '</td><td><h3>&nbsp;&nbsp;' 
          . $title . '</h3></td></tr></table></div>';
        $counter=0;
        foreach ($this->objSimplePieWrapper->get_items() as $item) {
            $counter++;
            $description = $item->get_description();
            $description = str_replace("float:right;", "float:left; margin-left: 5px; margin-right: 20px;", $description);
            $title = $item->get_title();
            $ln = $item->get_link();
            $ret .= '<div class="feed_render_top"></div>'
              . '<div class="feed_render_default">'
              . '<p class="feed_render_description"><a href="' 
              . $ln . '">' . $title . '</a><br />' . $description 
              . '<br /><span class="feed_render_date">' 
              .  $item->get_date('j F Y | g:i a') 
              . '</span></p>'
              . '</div><div class="feed_render_bottom"></div>';
        }
        return $ret;
    }
    
    
    
    
    
    // --------------- Methods for determining the search type
    
    /**
     * 
     * Method to determine if a feed is a twitter search feed
     * 
     * @param string Title The title from the feed
     * @return TRUE|FALSE
     * @access private
     * 
     */
    private function isTwitterSearch($title)
    {
        // Check for Twitter search results
        $twitterSearch = stripos($title, "Twitter Search");
        if (!$twitterSearch == FALSE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * 
     * Method to determine if a feed is a Youtube feed
     * 
     * @param string $permaLink The permaLink from the feed
     * @return TRUE|FALSE
     * @access private
     * 
     */
    private function isYouTube($permaLink)
    {
        $yt = stripos($permaLink, "youtube.com");
        if (!$yt == FALSE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    private function isYoutTubeStandards($permaLink)
    {
        $yt = stripos($permaLink, "standards");
        if (!$yt == FALSE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * 
     * Method to determine if a feed is a Slideshare feed
     * 
     * @param string $permaLink The permaLink from the feed
     * @return TRUE|FALSE
     * @access private
     * 
     */
    public function isSlideShare($permaLink)
    {
        $ss = stripos($permaLink, "slideshare.net");
        if (!$ss == FALSE) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    // --------------- End methods for determining search type
    
    
    /**
    *
    * Wrapper method for get_title in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_titlemethod.
    * 
    * Gets the title of the channel
    * 
    * @return String The feed title
    * @access Public
    *
    */
    public function getTitle()
    {
        return $this->objSimplePieWrapper->get_title();
    }
    
    /**
    *
    * Wrapper method for get_permalink in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_permalinkmethod.
    *
    * Gets the permalink of the channel
    * 
    * @return String The feed permalink
    * @access Public
    * 
    */
    public function getPermalink()
    {
        return $this->objSimplePieWrapper->get_permalink();
    }
    
    /**
    *
    * Wrapper method for get_image_title in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_titlemethod.
    * 
    * @return String The logo Title
    * @access Public
    *
    */
    public function getImageTitle()
    {
        return $this->objSimplePieWrapper->get_image_title();
    }

    /**
    *
    * Wrapper method for get_image_url in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_urlmethod.
    * 
    * @return String The Logo image URL
    * @access Public
    *
    */
    public function getImageUrl()
    {
        return $this->objSimplePieWrapper->get_image_url();
    }

    /**
    *
    * Wrapper method for get_image_link in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_linkmethod.
    * 
    * @return String The logo image Link
    * @access Public
    *
    */
    public function getImageLink()
    {
        return $this->objSimplePieWrapper->get_image_link();
    }

    /**
    *
    * Wrapper method for get_image_width in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_widthmethod.
    * 
    * @return String The logo image width
    * @access Public
    *
    */
    public function getImageWidth()
    {
        return $this->objSimplePieWrapper->get_image_width();
    }

    /**
    *
    * Wrapper method for get_image_height in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_heightmethod.
    *
    * @return String The logo image height
    * @access Public
    *
    */
    public function getImageHeight()
    {
        return $this->objSimplePieWrapper->get_image_height();
    }
    
    
    //--------------------- PLEASE NOTE --------------------------//
    /* 
     * I am working here. This is incomplete. Documentation will be inserted
     * as I update or add functionality
     * 
     * @Todo -- 
     *  1. Deal with proxy settings (can SimplePie handle it?)  
     *
     * 
     */
    
    
    
    /**
    *
    * Wrapper method for get_author in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_authormethod.
    * 
    * This returns the author of a feed identified by key
    * 
    * @access public
    *
    */
    public function getAuthor($key=0)
    {
        return $this->objSimplePieWrapper->get_author($key);
    }



    /**
    *
    * Wrapper method for get_description in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_descriptionmethod.
    *
    */
    public function get_description()
    {
        return $this->objSimplePieWrapper->get_description();
    }

    /**
    *
    * Wrapper method for get_copyright in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_copyrightmethod.
    *
    */
    public function get_copyright()
    {
        return $this->objSimplePieWrapper->get_copyright();
    }

    /**
    *
    * Wrapper method for get_language in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_languagemethod.
    *
    */
    public function get_language()
    {
        return $this->objSimplePieWrapper->get_language();
    }

    /**
    *
    * Wrapper method for get_latitude in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_latitudemethod.
    *
    */
    public function get_latitude()
    {
        return $this->objSimplePieWrapper->get_latitude();
    }

    /**
    *
    * Wrapper method for get_longitude in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_longitudemethod.
    *
    */
    public function get_longitude()
    {
        return $this->objSimplePieWrapper->get_longitude();
    }



    /**
    *
    * Wrapper method for get_item_quantity in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_item_quantitymethod.
    *
    */
    public function get_item_quantity($max)
    {
        return $this->objSimplePieWrapper->get_item_quantity($max);
    }

    /**
    *
    * Wrapper method for get_item in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_itemmethod.
    *
    */
    public function get_item($key)
    {
        return $this->objSimplePieWrapper->get_item($key);
    }

    /**
    *
    * Wrapper method for get_items in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_itemsmethod.
    *
    */
    public function get_items($start,$end)
    {
        return $this->objSimplePieWrapper->get_items($start,$end);
    }

    /**
    *
    * Wrapper method for sort_items in the SimplePie
    * class being wrapped. See that class for details of the 
    * sort_itemsmethod.
    *
    */
    public function sort_items($a,$b)
    {
        return $this->objSimplePieWrapper->sort_items($a,$b);
    }

    /**
    *
    * Wrapper method for merge_items in the SimplePie
    * class being wrapped. See that class for details of the 
    * merge_itemsmethod.
    *
    */
    public function merge_items($urls,$start,$end,$limit)
    {
        return $this->objSimplePieWrapper->merge_items($urls,$start,$end,$limit);
    }

}
?>