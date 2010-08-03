<?php
/**
 * This class contains the links on the right column of the short courses page in CfE website
 * 
 * PHP version 5
 * 
 * @category  Chisimba
 * @package   cfe
 * @author    JCSE <JCSE>
 */

// security check - must be included in all scripts
if (!/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class short_courses_right_column_content extends object {

    /**
     * Constructor
     */
    public function init() {
	//Create objects of different classes
        $this->loadClass('link', 'htmlelements');
        $this->objModules = $this->getObject('modules', 'modulecatalogue');
	$this->menuDropDown = $this->newObject('dropdown', 'htmlelements');
    }

    /**
     * Method to show the links
     * @return string
     */
    public function show() {
        $objUser = $this->getObject('user', 'security');
        $userIsLoggedIn = $objUser->isLoggedIn();

        $menuOptions = array(
            array('action' => 'logoff', 'text' => 'Logout', 'actioncheck' => array(), 'module' => 'security', 'status' => 'loggedin'),
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

        // Add all links
        $tbar = $tbar = $this->generateItem('shortCourses', '_default', 'Short courses', $usedDefault).
                $this->generateItem('createYourVenture', '_default', 'Create your venture', $usedDefault).
                
                $this->generateItem('findYourVenture', '_default', 'Find your venture', $usedDefault).
		$this->generateItem('startYourVenture', '_default', 'Start your venture', $usedDefault).
		$this->generateItem('planYourVenture', '_default', 'Plan your venture', $usedDefault).
		$this->generateItem('buildYourVenture', '_default', ' Build your venture', $usedDefault).
		$this->generateItem('growYourVenture', '_default', 'Grow your venture', $usedDefault).
		$this->generateItem('masterclass', '_default', 'Masterclass', $usedDefault);


       
	
	//$rightCol ='<div id="shortCoursesRight"><h3> <img src = "http://localhost/framework/app/packages/cfe/resources/bullet.png" height = "10px" /> RELATED LINKS </h3></div>';
	//$rightCol = '<div id="aboutCFE"><div id="left"><div id="subhead"><div class="txt"><img src = "http://localhost/framework/app/packages/cfe/resources/subhead.gif"/></div></div>';
$rightCol ='<div id="shortCoursesRight"><h3>  RELATED LINKS </h3></div>';
	$newsletter = '<div id="newsLetter"><table>
					   <tr>
					   <td>
					   
					   </td>
					   <td>
					
					   <form action="http://wbs.pl.privatelabel.co.za/pls/cms/mail.subscribe" name="fb_form" method="post"> 
					   <input type="text" name="p_firstname" value="Name"  maxlength="500" height="5px"/>
			      		   <input type="text" name="p_email"  maxlength="500" value="Email address" /><br>
					   <a href="http://www.google.co.za"><img src = "http://146.141.208.61/chisimba2/packages/cfe/resources/latest_newsletter.jpeg"   							id="latestNewButton"/></a>
					   <input type="image" src = "http://146.141.208.61/chisimba2/packages/cfe/resources/signup.jpeg" id="signButton"><br>
                   			   </form>
					   </td>
					   </tr>
					   </table></div>';  
	$gewpicture = '<a href = "http://www.gmail.com"><img src = "http://146.141.208.61/chisimba2/packages/cfe/resources/GEW.jpeg"></a>';

      	//return the links and the newsletter form
	 // Return Toolbar
        return '<div id="menu">' . '<div id="shortCoursesRightCol">' . '<div class="shortbl">' . '<div class="shortbr">' . '<div class="shorttl">'. '<div class="shorttr">'. $rightCol. $tbar . $str . '</div>'.'</div>'.'</div>'. '</div>' . '</div>'. '</div>' .$newsletter;
     
    }
    /**
     * This method generate items for linkks
     * @return string.
     * @access private.
     */
    private function generateItem($action='', $module='webpresent', $text, $isActive=FALSE) {
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

            return $link->show();
        } else {
            return '';
        }
    
    }
}

?>
