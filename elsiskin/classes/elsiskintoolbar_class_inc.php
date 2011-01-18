<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class elsiskintoolbar extends object {

    /**
     * Constructor
     */
    public function init() {

        $this->loadClass('link', 'htmlelements');
        $this->objModules = $this->getObject('modules', 'modulecatalogue');
    }

	/**
     * Method to show the Toolbar
     * @return string $retstr containing all the toolbar links.
     */
	 
	 public function show() {
		 $menuOptions = array(
				array('action' => 'about', 'text' => 'About', 'actioncheck' => array(), 'module' => 'elsiskin', 'status' => 'loggedin'),
				array('action' => 'staff', 'text' => 'Staff', 'actioncheck' => array(), 'module' => 'elsiskin', 'status' => 'loggedin')//,
				//array('action'=>'logoff', 'text'=>'Logout', 'actioncheck'=>array(), 'module'=>'security', 'status'=>'loggedin')
			);
		$str = "";
		$count = 1;
		foreach ($menuOptions as $option) {
			
			if($this->getParam('action') == $option['action']) {
				$isDefault = TRUE;
			}
			
			if ($isDefault) {
				$usedDefault = TRUE;
			}
			
			// Add to Navigation
			$str .= $this->generateItem($option['action'], $option['module'], $option['text'], $isDefault, $count);
			$count++;
		}
		
		// Check whether Navigation has Current/Highlighted item
        // Invert Result for Home Link
        $usedDefault = $usedDefault ? FALSE : TRUE;
		
		// Add Home Link
        $home = $this->generateItem('home', '_default', 'Home', $usedDefault);
		
		 // Return Toolbar
        $retstr =  '
			<div class="org_nav">
			<!-- Start: Topnav -->
				<div id="Topnav">
					<ul id="tabnav">' . $home . $str . '
					</ul>
				</div>
			<!-- End: Topnav -->
			</div>
			<!-- End: .grid_4 -->';
			
		return $retstr;
	 }	
		
    /**
     * Method to show the Toolbar
     * @return string
     */
    public function show1() {
        $objUser = $this->getObject('user', 'security');
        $userIsLoggedIn = $objUser->isLoggedIn();

        $menuOptions = array(
            array('action' => 'login', 'text' => 'About', 'actioncheck' => array(), 'module' => 'elsiskin', 'status' => 'login'),
            array('action' => 'login', 'text' => 'Staff', 'actioncheck' => array(), 'module' => 'elsiskin', 'status' => 'login'),
            array('action' => 'login', 'text' => 'Software', 'actioncheck' => array(), 'module' => 'elsiskin', 'status' => 'login'),
            array('action' => 'login', 'text' => 'Research', 'actioncheck' => array(), 'module' => 'elsiskin', 'status' => 'login'),
            array('action' => 'login', 'text' => 'About', 'actioncheck' => array(), 'module' => 'elsiskin', 'status' => 'login'),
             array('action'=>'logoff', 'text'=>'Logout', 'actioncheck'=>array(), 'module'=>'security', 'status'=>'loggedin'),
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
        // Invert Result for Home Link
        $usedDefault = $usedDefault ? FALSE : TRUE;

        // Add Home Link
        $home = $this->generateItem(NULL, '_default', 'Home', $usedDefault);


        // Return Toolbar
        return '<div id="chromestyle"><ul>' . $home . $str . '</ul>';
    }

    private function generateItem($action='', $module='_default', $text, $isActive=FALSE, $count=NULL) {
        switch ($module) {
            case '_default' : $isRegistered = TRUE;
                break;
            default: $isRegistered = $this->objModules->checkIfRegistered($module);
                break;
        }
		$tabClass = "";
		if($count > 0) {
			$tabClass .= ' class="tab'.++$count.'"';
		}
		
		if($text == "Home") {
			$tabClass .= ' class="tab1"';	
		}

        if ($isRegistered) {
            $link = new link($this->uri(array('action' => $action), $module));
            $link->link = $text;

            $isActive = $isActive ? ' id="current"' : '';

            return '
						<li' . $isActive . $tabClass.'>' . $link->show() . '</li>';
        } else {
            return '';
        }
    }
	
	public function getContactLink() {
		$module = 'elsiskin';
		$action = 'contact';

		$isRegistered = $this->objModules->checkIfRegistered($module);
		if($isRegistered) {
			$link = new link($this->uri(array('action' => $action), $module));
			$link->link = 'Contact Us';
		}
		else {
			$link = "";
		}
		
		return $link->show();
	}

}
?>