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
    * @var bool $hideMenu Boolean value determining whether to hide the menu block on the right side
    */
    private $hideMenu = FALSE;
    
    /**
    * @var bool $hideSearch Boolean value determining whether to hide the search block on the right side
    */
    private $hideSearch = FALSE;
        
    /**
    * @var bool $hideHelp Boolean value determining whether to hide the help block on the right side
    */
    private $hideHelp = FALSE;
        
    /**
    * Constructor method
    */
    public function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objCountry =& $this->getObject('languagecode', 'language');
        $this->objConfig =& $this->getObject('dbsysconfig', 'sysconfig');

        $this->objTable =& $this->newObject('htmltable', 'htmlelements');
        $this->objHead =& $this->newObject('htmlheading', 'htmlelements');
        $this->objLayer =& $this->newObject('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->objBlocks = & $this->newObject('blocks', 'blocks');
    }

    /**
    * Method to get the contents for the right column
    *
    * @access public
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
    * @access private
    * @return string html
    */
    private function getBrowseMenu()
    {
        $str = '';
        if(!$this->hideMenu){
            $str = $this->objBlocks->showBlock('rightmenu', 'etd');
        }
        //if($manager){
            $str .= $this->objBlocks->showBlock('managemenu', 'etd');
        //}
        if(!$this->hideSearch){
            $str .= $this->objBlocks->showBlock('searchetd', 'etd');
        }
        if(!$this->hideHelp){
            $str .= $this->objBlocks->showBlock('etdhelp', 'etd');
        }
        return $str;
    }
    
    /**
    * Method to set the content for the right side menu
    *
    * @access public
    * @param string $str The right side content
    * @param bool $append Flag to determine whether the new side content is appended to the standand content
    * @return
    */
    public function setRightSide($str, $append = FALSE)
    {
        if($append){
            $this->rightContent = $this->getBrowseMenu();
        }
        $this->rightContent .= $str;
    }

    /**
    * Method to set the blocks on the right menu to hide or display
    *
    * @access public
    * @param bool $menu Determines whether to display the browse menu block
    * @param bool $search Determines whether to display the search block
    * @param bool $help Determines whether to display the help block
    * @return
    */
    public function setRightBlocks($menu = FALSE, $search = FALSE, $help = FALSE)
    {
        $this->hideMenu = $menu;
        $this->hideSearch = $search;
        $this->hideHelp = $help;
    }
    
    /**
    * Method to create the dropdown of countries with additional entries for areas covering several countries
    *
    * @access public
    * @param string $selected The current selection
    * @return string html
    */
    public function getCountriesDropdown($selected = 'South Africa')
    {
//        $objDrop = new dropdown('country');
//        $objDrop->addFromDB($this->objCountries->getAll(' order by name'), 'printable_name', 'printable_name', $selected);
        
        return $this->objCountry->country();//$objDrop->show();
    }

    /**
    * Method to create a dropdown list of years using the configurable variable for the start date
    *
    * @access public
    * @param string $name The element name
    * @param string $select The selected year
    * @return string html
    */
    public function getYearSelect($name, $select = '')
    {
        $start = $this->objConfig->getValue('ARCHIVE_START_YEAR', 'etd');

        $objDrop = new dropdown($name);

        for($i=$start; $i <= date('Y'); $i++){
            $objDrop->addOption($i, $i);
        }
        $objDrop->setSelected($select);
        return $objDrop->show();
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