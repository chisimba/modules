<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class oertoolbar extends object {

    /**
     * Constructor
     */
    public function init() {

        $this->loadClass('link', 'htmlelements');
        $this->objModules = $this->getObject('modules', 'modulecatalogue');
        $this->objLanguage = $this->getObject('language', 'language');
    }

    /**
     * Method to show the Toolbar
     * @return string
     */
    public function show() {
        $objUser = $this->getObject('user', 'security');
        $userIsLoggedIn = $objUser->isLoggedIn();

        $menuOptions = array(
            array('action' => 'home', 'text' => $this->objLanguage->languageText('mod_oer_products','oer'), 'actioncheck' => array(), 'module' => 'oer', 'status' => 'both'),
            array('action' => '1b', 'text' => $this->objLanguage->languageText('mod_oer_adaptations','oer'), 'actioncheck' => array(), 'module' => 'oer', 'status' => 'both'),
            array('action' => 'viewgroups', 'text' => $this->objLanguage->languageText('mod_oer_groups','oer'), 'actioncheck' => array(), 'module' => 'oer', 'status' => 'both'),
            array('action' => 'viewreports', 'text' => $this->objLanguage->languageText('mod_oer_reporting','oer'), 'actioncheck' => array(), 'module' => 'oer', 'status' => 'both'),
            array('action' => 'about', 'text' => $this->objLanguage->languageText('mod_oer_about','oer'), 'actioncheck' => array(), 'module' => 'oer', 'status' => 'both'),
            array('action' => 'contacts', 'text' => $this->objLanguage->languageText('mod_oer_contacts','oer'), 'actioncheck' => array(), 'module' => 'oer', 'status' => 'both'),
        );

        $usedDefault = FALSE;
        $str = '';

        foreach ($menuOptions as $option) {
            // First Step, Check whether item will be added to menu
            // 1) Check Items to be Added whether user is logged in or not
            if ($option['status'] == 'both') {
                $okToAdd = TRUE;

                // 2) Check Items to be added only if user is not logged in
            } else if ($option['status'] == 'login' && !$userIsLoggedIn) {
                $okToAdd = TRUE;

                // 3) Check Items to be added only if user IS logged in
            } else if ($option['status'] == 'loggedin' && $userIsLoggedIn) {
                $okToAdd = TRUE;

                // 4) Check if User is Admin
            } else if ($option['status'] == 'admin' && $objUser->isAdmin() && $userIsLoggedIn) {
                $okToAdd = TRUE;
            } else {
                $okToAdd = FALSE; // ELSE FALSE
            }

            // IF Ok To Add
            if ($okToAdd) {

                // Do a check if current action matches possible actions
                if (count($option['actioncheck']) == 0) {
                    $actionCheck = TRUE; // No Actions, set TRUE, to enable all actions and fo module check
                } else {
                    $actionCheck = in_array($this->getParam('action'), $option['actioncheck']);
                }

                // Check whether Module of Link Matches Current Module
                $moduleCheck = ($this->getParam('module') == $option['module']) ? TRUE : FALSE;

                // If Module And Action Matches, item will be set as current action
                $isDefault = ($actionCheck && $moduleCheck) ? TRUE : FALSE;

                if ($isDefault) {
                    $usedDefault = TRUE;
                }

                // Add to Navigation
                $str .= $this->generateItem($option['action'], $option['module'], $option['text'], $isDefault);
            }
        }

        // Check whether Navigation has Current/Highlighted item
       
        $usedDefault = $usedDefault ? FALSE : TRUE;
        
        if($objUser->isAdmin()){
             $str .= $this->generateItem('', 'toolbar', $this->objLanguage->languageText('mod_oer_admin','oer'), FALSE);
        }

        // Return Toolbar
        return '<div id="modernbricksmenu"><ul>' . $str . '</ul></div>';
        
    }

    private function generateItem($action='', $module='oer', $text, $isActive=FALSE) {
        switch ($module) {
            case '_default' : $isRegistered = TRUE;
                break;
            default: $isRegistered = $this->objModules->checkIfRegistered($module);
                break;
        }

        if ($isRegistered) {
            $link = new link($this->uri(array('action' => $action), $module));
            $link->link = $text;

            $isActive = $isActive ? ' id="current"' : '';

            return '<li' . $isActive . '>' . $link->show() . '</li>';
        } else {
            return '';
        }
    }

}

?>