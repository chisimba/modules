<?
/**
* Block for the right menu 
*
* @package etd
* @author Megan Watson
* @version 0.1
* @copyright (c) UWC 2006
*/

class block_rightmenu extends object
{
    /**
    * @var the block title
    */
    public $title;
    
    /**
    * Constructor for the class
    */
    public function init()
    {
        $this->objLanguage =& $this->getObject('language','language');
        
        //Set the title
        $this->title = $this->objLanguage->languageText('word_menu');
    }
    
    /**
    * The display method for the block
    */
    public function show()
	{
        $browse = $this->objLanguage->languageText('word_browse');
        $collections = $this->objLanguage->languageText('word_collections');
        $authors = $this->objLanguage->languageText('word_authors');
        $titles = $this->objLanguage->languageText('word_titles');
        
	    // Browse menu items
        $list = '<b>'.$browse.':</b><ul>';
        
        $objLink = new link($this->uri(array('action'=>'browse_collection')));
        $objLink->link = $collections;
        $list .= '<li style="padding-bottom: 5px;">'.$objLink->show().'</li>';
        
        $objLink = new link($this->uri(array('action'=>'browse_author')));
        $objLink->link = $authors;
        $list .= '<li style="padding-bottom: 5px;">'.$objLink->show().'</li>';
        
        $objLink = new link($this->uri(array('action'=>'browse_title')));
        $objLink->link = $titles;
        $list .= '<li style="padding-bottom: 5px;">'.$objLink->show().'</li>';
        
        $list .= '</ul>';

        return $list;
    }
}