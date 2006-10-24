<?php
/**
* etdtools class extends object
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* etdtools class
* @author Megan Watson
* @author Jonathan Abrahams
* @copyright (c) 2004 UWC
* @version 0.1
*/

class etdtools extends object
{
    /**
    * @var string $rightContent String containing the content for the right side menu.
    */
    private $rightContent = '';
    
    /**
    * Constructor method
    */
    public function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        $this->objLanguage =& $this->getObject('language', 'language');

        $this->objTable =& $this->newObject('htmltable', 'htmlelements');
        $this->objHead =& $this->newObject('htmlheading', 'htmlelements');
        $this->objLayer =& $this->newObject('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->objBlocks = & $this->newObject('blocks', 'blocks');
    }

    /**
    * Method to get the contents for the right column
    *
    * @return string html
    */
    public function getRightSide()
    {
        if(isset($this->rightContent) && !empty($this->rightContent)){
            return $this->rightContent;
        }
        
        $str = $this->getBrowseMenu();
        return $str;
    }
    
    /**
    * Method to display the menu for browsing and searching the repository.
    *
    * @return string html
    */
    private function getBrowseMenu()
    {
        $str = $this->objBlocks->showBlock('rightmenu', 'etd');
//        $str .= $this->objBlocks->showBlock('searchetd', 'etd');
        $str .= $this->objBlocks->showBlock('etdhelp', 'etd');
        return $str;
    }
    
    /**
    * Method to set the content for the right side menu
    *
    * @return
    */
    public function setRightSide($str, $append = FALSE)
    {
        if($append){
            $this->rightContent = $this->getBrowseMenu();
        }
        $this->rightContent .= $str;
    }


/* ** Methods below from KINKY version - unused as of yet ** */

    /**
    * Method to create the Left Side Menu.
    * @param string $accessLevel The level of access a user has.
    */
    function getLeftMenu($accessLevel)
    {
        $home = $this->objLanguage->languageText('mod_etd_etdhome');
        $browse = $this->objLanguage->languageText('mod_etd_browse');
        $collections = $this->objLanguage->languageText('mod_etd_collections');
        $authors = $this->objLanguage->languageText('mod_etd_authors');
        $titles = $this->objLanguage->languageText('mod_etd_titles');
        $manageColl = $this->objLanguage->languageText('mod_etd_managecollections');
        $managedETD = $this->objLanguage->languageText('mod_etd_manageetd');
        $configETD = $this->objLanguage->languageText('mod_etd_configureetd');

        $user = $this->objUser->fullName();

        $str = '';

        $this->objTable->init();
        $this->objTable->cellpadding = 5;

        // User name
        $this->objHead->str = $user;
        $this->objHead->type = 3;
        $this->objTable->addRow(array('', $this->objHead->show(), ''));

        // ETD home link
        $objLink = new link($this->uri(''));
        $objLink->link = $home;
        $this->objTable->addRow(array('', $objLink->show(), ''));

        // Browse label
        $list = '<b>'.$browse.':</b><ul>';
        $objLink = new link($this->uri(array('action'=>'browse_collection')));
        $objLink->link = $collections;
        $list .= '<li>'.$objLink->show().'</li>';
        $objLink = new link($this->uri(array('action'=>'browse_author')));
        $objLink->link = $authors;
        $list .= '<li>'.$objLink->show().'</li>';
        $objLink = new link($this->uri(array('action'=>'browse_title')));
        $objLink->link = $titles;
        $list .= '<li>'.$objLink->show().'</li>';
        $list .= '</ul>';
        $this->objTable->addRow(array('', $list, ''));

        if($accessLevel >= 5){
            // Manage collections link
            $objLink = new link($this->uri(array('action'=>'managecollections')));
            $objLink->link = $manageColl;
            $this->objTable->addRow(array('', $objLink->show(), ''));

            // Manage ETDs link
            $objLink = new link($this->uri(array('action'=>'manageetd')));
            $objLink->link = $managedETD;
            $this->objTable->addRow(array('', $objLink->show(), ''));

            // Configure ETD link
            $objLink = new link($this->uri(array('action'=>'configureetd')));
            $objLink->link = $configETD;
            $this->objTable->addRow(array('', $objLink->show(), ''));
        }

        $str .= $this->objTable->show();

        return $str;
    }

    /**
    * Method to create the Right Side Menu.
    *
    function getRightMenu()
    {
        $intro = $this->objLanguage->languageText('mod_etd_intro');
        $more = $this->objLanguage->languageText('mod_etd_more');
        $intro = substr($intro, 0, 212);

        $objLink = new link('javascript:void(0)');
        $objLink->extra = "onclick=\"javascript:window.open('" .$this->uri(array('action'=>'showinfo'))."', 'etdinfo', 'width=450, height=300, scrollbars=1')\"";
        $objLink->link = $more.'...';
        $intro .= '<br>['.$objLink->show().']';

        $str = '';
        $str .= $this->objBlocks->showBlock('searchetd', 'etd');

        $str .= '<p>'.$intro.'</p>';
        return $str;
    }

    /**
    * Method to create the Right Side Menu for the search template.
    */
    function getSearchRightMenu()
    {
        $str = '';
        return $str;
    }
}
?>