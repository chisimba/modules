
<?php
/**
 /* This class contains the content that goes into the left column of the short courses page.
 * 
 * PHP version 5
 * 
 * 
 * @category  Chisimba
 * @package   cfe
 * @author    JCSE <JCSE>
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


class short_courses_content extends object
{

   /**
    * Description for public
    * @var    unknown
    * @access public 
    */
    public $content;
   /**
    * Constructor
    */
	public function init()
    	{
		//Create objects            	
		$this->loadClass('link', 'htmlelements');
         	$this->objModules = $this->getObject('modules', 'modulecatalogue');
		$this->objLinks = $this->newObject('links', 'cfe');
		     
        }
    
    /**
     * This method contains the content that goes to the left column of build your venture page.
     * @return string
     * @access private.
     */
	private function buildContent()
	{
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
   //             $str .= $this->generateItem($option['action'], $option['module'], $option['text'], $isDefault);
            }
        }

        // Check whether Navigation has Current/Highlighted item
        // Invert Result for Home Link
        $usedDefault = $usedDefault ? FALSE : TRUE;

	

        $linking =  'createYourVenture';
	$texting = 'create your venture';
	$more = '...Click here for more';
	$linkFindYourVenture =  'findYourVenture';
	$findYourVenture = 'Find your venture';
	$linkStartYourVenture =  'startYourVenture';
	$startYourVenture = 'Start your venture';
	$linkPlanYourVenture =  'planYourVenture';
	$planYourVenture = 'Plan your venture';
	$linkBuildYourVenture =  'buildYourVenture';
	$buildYourVenture = 'Build your venture';
	$linkGrowYourVenture =  'growYourVenture';
	$growYourVenture = 'Grow your venture';
	$linkMasterclass=  'masterclass';
	$masterclass=  'Masterclass';
	$linkCourseSchedule =  'createYourVenture';
	$courseSchedule=  'Course schedule for 2010';
	$mod = 'cfe';
	
//$this->objLinks->linkModule = 'createYourVenture';
//$this->objLinks->texting = 'Course schedule for 2010';
	$content = '<div id="shortCoursesh3"><h3> SHORT COURSES </h3>
<div id="shortCoursesh4"><h4>Courses</h4></div></div>
			
<div id="shortCoursesContent"><h4>Pre start up</h4></div>'. '<div id = "shortCoursesContent">' . $this->objLinks->Link($linking, $texting, $mod) .
'<p><b>So you want to be an entrepreneur?</b> Starting your own business can be an exhilarating experience, but also one fraught with difficulty and frustration. Developing the right kind of skills before you create a business as well as knowing where to go for help once you have, can make the difference between start-up and shut-down.</p>' . '<div id = "more">' .  $this->objLinks->Link($linking, $more, $mod) . '</div>' .'<p></p>' . $this->objLinks->Link($linkFindYourVenture, $findYourVenture, $mod) . '<p>finding your venture</p>' . '<div id = "more">'. $this->objLinks->Link($linkFindYourVenture, $more, $mod) . '</div>' .
			
'<h4>Startup</h4>' . $this->objLinks->Link($linkStartYourVenture, $startYourVenture, $mod) . '<p><b>Exactly how do you start a business?</b> Sometimes, small businesses are started in a hurry, and the entrepreneur hasn’t always considered every aspect of setting up the new enterprise. In this situation, learning skills you can use right away, with other entrepreneurs who really understand what you’re going through, can help clarify your thinking and get you moving forward in the right direction.  </p>' . '<div id = "more">' . $this->objLinks->Link($linkStartYourVenture, $more, $mod) . '</div>' . '<p></p>' . $this->objLinks->Link($linkPlanYourVenture, $planYourVenture, $mod). '<p><b>Struggling to put together a business plan? </b>It is often said that the process of business planning is much more important than the plan itself because the process gives you new insights into your business’s future direction and operations. But knowing how to start a plan and finding time to finish it can be difficult when you are already dealing with the day-to-day challenges of running a business.</p>' . '<div id = "more">' . $this->objLinks->Link($linkStartYourVenture, $more, $mod) . '</div>' .

'<h4>Development</h4>' .$this->objLinks->Link($linkBuildYourVenture, $buildYourVenture, $mod) . '<p><b>Did you think you would be further ahead by now?</b> Your business might be up and running, but you probably find yourself dealing with challenges you never imagined when you started. Now is the time to sharpen your skills and refresh your thinking so that when times get tough, you will be ready to take your business from shaky start-up to seriously sustainable></p>' . '<div id = "more">' . $this->objLinks->Link($linkStartYourVenture, $more, $mod) . '</div>' .

'<h4>Growth</h4>' . $this->objLinks->Link($linkGrowYourVenture, $growYourVenture, $mod) . '<p><b>Are you adequately prepared for growth? </b>For many companies, the time of greatest risk is when the founding CEO seeks to grow or scale the business. All too often, the founder’s ambition outstrips the capabilities and resources available to the developing venture. In this situation growth is not only constrained, but the very existence of the enterprise is threatened. Can you be certain your growth plans won’t be the death of your business?</p>' . '<div id = "more">' . $this->objLinks->Link($linkStartYourVenture, $more, $mod). '</div>' . '<p></p>' .  $this->objLinks->Link($linkMasterclass, $masterclass, $mod). '<p><b>Struggling to put together a business plan?</b> It is often said that the process of business planning is much more important than the plan itself because the process gives</p>' . '<div id = "more">' .  $this->objLinks->Link($linkMasterclass, $more, $mod) . '</div>' . '<p></p>' .

$this->objLinks->Link('createYourVenture', 'Course schedule for 2010', $mod) . '<p>This are causes scheduled for 2010</p>' . '</div>';


	return $content;
    
		
}
    /**
     * A method to show the content in the left column of short courses page.
     * 
     * @return string $result The rendered object in HTML code
     * @access public
     */

	public function show()
	{
		return  $this->buildContent();
	}
        
}
?>
