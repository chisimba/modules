<?php

/**
* Class to Generate Custom Menu for the Sanord Skin
* @author Tohir Solomons
*/
class sanordmenu extends object
{
    /**
    * @var array $menuItems List of Menu Items/Modules to be built into the system
    */
    public $menuItems;
    
    /**
    * Constructor
    */
    public function init()
    {
        // List of Menu Items
        $this->menuItems = array(
            //array('title'=>'Home', 'module'=>'cms'),
            array('title'=>'Members', 'module'=>'cms'),
        );
        
        $this->objUser = $this->getObject('user', 'security');
        $this->objConfig = $this->getObject('altconfig', 'config');
    }
    
    /**
    * Method to build and display the menu
    */
    public function show()
    {
        // Start Tags
        $str = '<div id="sanordmenu"><ul>';
        
        // SHow link to login
        if (!$this->objUser->isLoggedIn()) {
            $objIcon = $this->newObject('geticon', 'htmlelements');
            $objIcon->setIcon('loginlink');
            $objIcon->alt = 'Login';
            $objIcon->title = 'Login';
            $objIcon->align = 'top';
            
            $link = new link ($this->uri(array('action'=>'error', 'message'=>'needlogin'), 'security'));
            $link->link = $objIcon->show();
            $str .=  '<div style="float:right;">'.$link->show().'</div>';
        }
        
        // Add Home Link
        $str .= $this->prepareModuleItem('Home', '_default');
        
        // Build Items into menu
        foreach ($this->menuItems as $menuItem)
        {
            $str .= $this->prepareModuleItem($menuItem['title'], $menuItem['module']);
        }
        
        
        // Add Logout link if user is logged in
        if ($this->objUser->isLoggedIn()) {
            $link = $this->uri(array('action'=>'logoff'), 'security');
            $str .= $this->prepareItem('Logout', $link);
        }
        
        // End Tags
        $str .= '</ul></div>';
        
        return $str;
    }
    
    /**
    * Method to build a module item into a link
    * @param string $title Title of the Item
    * @param string $module Name of the Module
    */
    private function prepareModuleItem($title, $module)
    {
        // Get Current Module from URL
        $currentModule = $this->getParam('module');
        $isCurrent = FALSE;
        
        // Check if Current Module
        if ($module == $currentModule) {
            $isCurrent = TRUE; 
        }
        
        // Check if Home Module
        if ($currentModule == '' && $module == '_default') {
            $isCurrent = TRUE;
        }
        
        if (!$this->objUser->isLoggedIn() && $currentModule == $this->objConfig->getPrelogin() && $module == '_default') {
            $isCurrent = TRUE;
        }
        
        if ($this->objUser->isLoggedIn() && $currentModule == $this->objConfig->getdefaultModuleName() && $module == '_default') {
            $isCurrent = TRUE;
        }
        
        // Create Link
        $link = $this->uri(NULL, $module);
        
        // Build Menu Item
        return $this->prepareItem($title, $link, $isCurrent);
    }
    
    /**
    * Method to Build a Menu Item
    * @param string $title Title of the Item
    * @param string $link Link of the Item
    * @param boolean $isCurrent Flag to set item as current
    */ 
    private function prepareItem($title, $link, $isCurrent=FALSE)
    {
        $str = '<li';
        
        if ($isCurrent) {
            $str .= ' id="current"';
        }
        
        $str .= '><a href="'.$link.'" title="'.$title.'">'.$title.'</a></li>';
        
        return $str;
    }
    
    
}

?>