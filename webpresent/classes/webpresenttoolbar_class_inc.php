<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end of security

class webpresenttoolbar extends object
{
    
    public function init()
    {
        $this->loadClass('link', 'htmlelements');
    }
    
    public function show()
    {
        

        $menuOptions = array(
            array('action'=>'upload', 'text'=>'Upload', 'check'=>array('upload')),
            array('action'=>'search', 'text'=>'Search', 'check'=>array('search')),
        );
        
        $usedDefault = FALSE;
        $str = '';
        
        $firstOne = TRUE;
        
        foreach ($menuOptions as $option)
        {
            $isDefault = in_array($this->getParam('action'), $option['check']);
            
            if ($isDefault) {
                $usedDefault = TRUE;
            }
            
            $str .= $this->generateItem($option['action'], $option['text'], $isDefault);
            $firstOne = FALSE;
        }
        
        $usedDefault = $usedDefault ? FALSE: TRUE;
        
        $home = $this->generateItem(NULL, 'Home', $usedDefault);
        
        $objUser = $this->getObject('user', 'security');
        
        if ($objUser->isLoggedIn()) {
            $login = $this->generateItem('logoff', 'Logout', FALSE, FALSE, 'security');
        } else {
            $login = $this->generateItem('login', 'Login');
        }
        
        return '<div id="modernbricksmenu"><ul>'.$home.$str.$login.'</ul><div id="modernbricksmenuline">&nbsp;</div>';


    }
    
    private function generateItem($action='', $text, $isActive=FALSE, $firstOne=FALSE, $module='webpresent')
    {
        $link = new link ($this->uri(array('action'=>$action), $module));
        $link->link = $text;
        
        $isActive = $isActive ? ' id="current"' : '';
        $firstOne = $firstOne ? '  style="margin-left: 1px"' : '';
        
        return '<li'.$isActive.$firstOne.'>'.$link->show().'</li>';
    }
    

}
?>