<?php
/**
 * This class gets information from the database 
 * 
 * PHP version 5
 * 
 * 
 * @category  Chisimba
 * @package   cfe
 * @author    David Wafula with minor modification by Thato Selebogo.
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

class viewer extends object {
   
   /**
    * Constructor
    */
    function init() {

        $this->loadClass("link", "htmlelements");
    }

    /**
     * Get the story from the database
     *
     * @access public.
     */
    function getStory($categoryName,$storyNumber) {

        $objCategories = $this->getObject("dbnewscategories", "news");
        $news = $this->getObject("dbnewsstories", "news");
        $categories = $objCategories->getCategories();

        $story = "No news has been set up";
        $link = new link($this->uri(array("action" => "featured")));
        foreach ($categories as $cat) {

            if ($cat['categoryname'] == $categoryName) {
                $id = $cat['id'];
                $stories = $news->getCategoryStories($id);
                $story = $stories[$storyNumber]['storytext'];
            }
        }
        return $story;
    }

    /**
     * Get the story from the database
     *
     * @access public.
     */
    function getRelatedLinks($categoryName,$storytitle) {

        $objCategories = $this->getObject("dbnewscategories", "news");
        $news = $this->getObject("dbnewsstories", "news");
        $categories = $objCategories->getCategories();

        $storycontent = "No news has been set up";
        $link = new link($this->uri(array("action" => "featured")));
        foreach ($categories as $cat) {

            if ($cat['categoryname'] == $categoryName) {
                $id = $cat['id'];
                $stories = $news->getCategoryStories($id);
               foreach($stories as $story){
                if($story['storytitle'] == $storytitle){
                 $storycontent = $story['storytext'];
                 break;               
             }
               }
               
            }
        }
        return $storycontent;
    }
}
?>
