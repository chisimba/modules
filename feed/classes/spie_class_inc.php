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
    
    /**
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
    
    
    public function getFeed($url)
    {
        $this->setFeedUrl($url);
        $this->objSimplePieWrapper->init();
        $title = $this->getTitle();
        $ret="<h3>$title</h3><br />";
        foreach ($this->objSimplePieWrapper->get_items() as $item) {
            $ret .= '<div class="feed_render_top" />'
              . '<div class="feed_render_default">'
              . '<p class="feed_render_link"><a href="' . $item->get_permalink() . '">' . $item->get_title() . '</a></p>'
              . '<p class="feed_render_description>' . $item->get_description() . '</p>'
              . '<p class="feed_render_date">' .  $item->get_date('j F Y | g:i a') . '</small></p>'
              . '<div class="feed_render_bottonm" />';
        }
        return $ret;
    }
    
    
    /**
    *
    * Wrapper method for get_title in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_titlemethod.
    * 
    * Gets the title of the channel
    *
    */
    public function getTitle()
    {
        return $this->objSimplePieWrapper->get_title();
    }
    /**
    *
    * Wrapper method for get_author in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_authormethod.
    *
    */
    public function getAuthor($key=0)
    {
        return $this->objSimplePieWrapper->get_author($key);
    }

    /**
    *
    * Wrapper method for get_permalink in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_permalinkmethod.
    *
    */
    public function getPermalink()
    {
        return $this->objSimplePieWrapper->get_permalink();
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
    * Wrapper method for get_image_title in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_titlemethod.
    *
    */
    public function get_image_title()
    {
        return $this->objSimplePieWrapper->get_image_title();
    }

    /**
    *
    * Wrapper method for get_image_url in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_urlmethod.
    *
    */
    public function get_image_url()
    {
        return $this->objSimplePieWrapper->get_image_url();
    }

    /**
    *
    * Wrapper method for get_image_link in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_linkmethod.
    *
    */
    public function get_image_link()
    {
        return $this->objSimplePieWrapper->get_image_link();
    }

    /**
    *
    * Wrapper method for get_image_width in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_widthmethod.
    *
    */
    public function get_image_width()
    {
        return $this->objSimplePieWrapper->get_image_width();
    }

    /**
    *
    * Wrapper method for get_image_height in the SimplePie
    * class being wrapped. See that class for details of the 
    * get_image_heightmethod.
    *
    */
    public function get_image_height()
    {
        return $this->objSimplePieWrapper->get_image_height();
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