<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end of security

class wits_studenttoolbar extends object {

/**
 * Constructor
 */
    public function init() {

        $this->loadClass('link', 'htmlelements');
        $this->objModules = $this->getObject('modules', 'modulecatalogue');
        $this->objDbSysconfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->storyparser=$this->getObject('storyparser');
    }

    /**
     * Method to show the Toolbar
     * @return string
     */
    public function show() {
        $objUser = $this->getObject('user', 'security');
        $userIsLoggedIn = $objUser->isLoggedIn();
        $howcreated =$this->storyparser->howCurrentUserWasCreated();// $objUser->howCreated($objUser->userId());

        $menuOptions = array();

        $topcatid=$this->objDbSysconfig->getValue('TOP_NAV_CATEGORY','wits_student');
        $topnavs=$this->storyparser->getStoryByCategory($topcatid);

        if($objUser->isAdmin()) {
            $menuOptions[]= array('action'=>NULL, 'text'=>'Enrolment Rollover (Postgraduate)', 'actioncheck'=>array(), 'module'=>'witsstudentrollover', 'status'=>'loggedin');
            $menuOptions[]= array('action'=>NULL, 'text'=>'Appllication Status', 'actioncheck'=>array(), 'module'=>'witsapplicationstatus', 'status'=>'loggedin');
            $menuOptions[]= array('action'=>NULL, 'text'=>'Apply Online', 'actioncheck'=>array(), 'module'=>'witsstudentonlineapplication', 'status'=>'loggedin');
        }else {
            if($howcreated == 'LDAP' || $howcreated == 'ldap' ) {
                $menuOptions[]= array('action'=>NULL, 'text'=>'Enrolment Rollover (Postgraduate)', 'actioncheck'=>array(), 'module'=>'witsstudentrollover', 'status'=>'loggedin');
            }else {
                $menuOptions[]= array('action'=>NULL, 'text'=>'Appllication Status', 'actioncheck'=>array(), 'module'=>'witsapplicationstatus', 'status'=>'loggedin');
                $menuOptions[]= array('action'=>NULL, 'text'=>'Apply Online', 'actioncheck'=>array(), 'module'=>'witsstudentonlineapplication', 'status'=>'loggedin');
            }

        }
        $menuOptions[]= array('action'=>NULL, 'text'=>'Admin', 'actioncheck'=>array(), 'module'=>'toolbar', 'status'=>'admin');
        $menuOptions[]= array('action'=>NULL, 'text'=>'My details', 'actioncheck'=>array(), 'module'=>'userdetails', 'status'=>'loggedin');

        $menuOptions[]= array('action'=>'logoff', 'text'=>'Logout', 'actioncheck'=>array(), 'module'=>'security', 'status'=>'loggedin');



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
                $str .= $this->generateItem($option['action'], $option['module'], $option['text'], $isDefault,$option['storyid']);
            }
        }

        // Check whether Navigation has Current/Highlighted item
        // Invert Result for Home Link
        $usedDefault = $usedDefault ? FALSE: TRUE;

        // Add Home Link
        $home = $this->generateItem(NULL, '_default', 'Home', $usedDefault);


        // Return Toolbar
        return '<div class="chromestyle"><ul>'.$home.$str.'</ul></div>';


    }

    private function generateItem($action='', $module='wits_student', $text, $isActive=FALSE,$storyid='') {
        switch ($module) {
            case '_default' : $isRegistered = TRUE; break;
            default: $isRegistered = $this->objModules->checkIfRegistered($module); break;
        }

        if ($isRegistered) {
            $link = new link ($this->uri(array('action'=>$action,'storyid'=>$storyid), $module));
            $link->link = $text;

            $isActive = $isActive ? ' id="current"' : '';

            return '<li'.$isActive.'>'.$link->show().'</li>';
        } else {
            return '';
        }
    }


}
    ?>