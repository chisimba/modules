<?php
// security check - must be included in all scripts
if ( !$GLOBALS['kewl_entry_point_run'] ) {
    die( "You cannot view this page directly" );
}

/**
 * Class fullprofile containing all display/output functions of the fullprofile module
 *
 * @author Warren Windvogel <warren.windvogel@wits.ac.za>
 * @copyright Wits University 2010
 * @license http://opensource.org/licenses/lgpl-2.1.php
 * @package fullprofile
 *
 */
class fpdisplay extends object
{
   /** @var object $objLanguage: The language class of the language module
    * @access private
    */
    private $objLanguage;

   /** @var object $objUser: The user class of the buddies module
    * @access public
    */
   public $objUser;

   /** @var object $objFuncs: The funcs class of the fullprofile module
    * @access public
    */
   public $objFuncs;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        // system classes
        $this->objLanguage = $this->getObject('language','language');
        $this->objUser = $this->getObject('user','security');
	$this->objFuncs = $this->getObject('fpfuncs', 'fullprofile');
        $this->objDbContext = $this->getObject('dbcontext', 'context');
        $this->objDbFoaf = $this->getObject('dbfoaf', 'foaf');
    }

    /**
     * Method to show a users complete profile
     *
     * @param string $userId The users id
     * @return string $html The html to display the users complete profile
     */
    public function showFullProfile($userId)
    {
        //Create the html holder
        $html = "";
        //Load the multi tabbed box class
        $this->loadClass('multitabbedbox', 'htmlelements');
        //Create the tabbed box
        $tabbedBox = new multitabbedbox("1500", "1000");
        //Get the users affiliations content
        $userDetailsHtml = $this->showUserDetails($userId);
        $tabbedBox->addTab(array('name'=>'Details', 'content'=>$userDetailsHtml, 'default'=>TRUE));

        //Get the activty content
        $activityHtml = $this->showUserActivity($userId);
        //Add the content to the activity tab
        $tabbedBox->addTab(array('name'=>'Activity', 'content'=>$activityHtml, 'default'=>FALSE));
        //Get the users affiliations content
        $affiliationHtml = $this->showUserAffiliations($userId);
        $tabbedBox->addTab(array('name'=>'Affiliations', 'content'=>$affiliationHtml, 'default'=>FALSE));
        //Add tabbed box to the output string
        $html .= $tabbedBox->show();

        
        return $html;
    }
    
    /**
     * Method to display a users activity stream
     *
     * @param string $userId The users id
     * @return string $html The html displaying the users activity
     */

    public function showUserActivity($userId)
    {
        $html = "";
        //Create the page title
        $title = $this->getObject('htmlheading', 'htmlelements');
        $title->type = '2';
        $title->str = $this->objLanguage->languageText('mod_fullprofile_siteactivity', 'fullprofile');

        $html .= $title->show();
        //Place the listin a div
        $html .= '<div id="activitystream" class="activitystream">';
        //Get the users activity
        $userActivity = $this->objFuncs->getActivity($userId);
        //Display the activity
        if(is_array($userActivity) && count($userActivity)>0){
            foreach($userActivity as $ua){
                $dateTime = date("F j, Y, g:i a", strtotime($ua['createdon']));
                $title = $ua['title'];
                $contextCode = $ua['contextcode'];
                if(is_null($contextCode)){
                    $html .= '<ul>'.$dateTime.'&nbsp;&nbsp;'.'-'.'&nbsp;&nbsp;'.$title.'</ul>';
                } else {
                    $html .= '<ul>'.$dateTime.'&nbsp;&nbsp;'.'-'.'&nbsp;&nbsp;'.$title.'&nbsp;&nbsp;'.'-'.'&nbsp;&nbsp;'.$this->objDbContext->getTitle($contextCode).'</ul>';
                }
            }
        } else {
            $html .= '<span class="subdued">No activities logged</span>';
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * Method to display a users affiliations
     *
     * @param string $userId The users id
     * @return string $html The html displaying the users affiliations
     */
    public function showUserAffiliations($userId)
    {
        $html = "";

        //Create the page title
        $title = $this->getObject('htmlheading', 'htmlelements');
        $title->type = '2';
        $title->str = $this->objLanguage->languageText('mod_fullprofile_affiliations', 'fullprofile');

        $html .= $title->show();
        //Place the list in a div
        $html .= '<div id="affiliations" class="affiliations">';

        //Get the users triples
        $userTriples = $this->objFuncs->getTriples($userId);

        if(is_array($userTriples) && count($userTriples)>0){
            foreach($userTriples as $trip){
                //Convert triple into readable string
                $tripleString = $this->objFuncs->tripleToString($trip);
                $html .= '<ul>'.$tripleString.'</ul>';
            }
        }

        $html .= '</div>';

        return $html;
    }
    /**
     * Method to display a users foaf details
     *
     * @param string $userId The users id
     * @return string $html The html displaying the users details
     */
    public function showUserDetails($userId)
    {
        $html = "";
        //Create detail header
        $title = $this->getObject('htmlheading', 'htmlelements');
        $title->type = '2';
        $title->str = $this->objLanguage->languageText('mod_fullprofile_details', 'fullprofile');
        //Add title to the output string

        $html .= '<div id="userdetails" class="userdetails">';
        //Get the users details
        $userDetails = $this->objDbFoaf->getRecordSet($userId, 'tbl_users');

        //Create table to hold user details
        $table = $this->getObject('htmltable', 'htmlelements');

        $table->startRow();
        $table->addCell($title->show(), NULL, 'colspan="2"');
        $table->endRow();

        $table->startRow();
        $table->addCell('&nbsp;');
        $table->addCell('&nbsp;');
        $table->endRow();

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('word_name', 'system', 'Name').':');
        $table->addCell($userDetails[0]['title'].'&nbsp;'.$userDetails[0]['firstname'].'&nbsp;'.$userDetails[0]['surname']);
        $table->endRow();

        $table->startRow();
        $table->addCell('&nbsp;');
        $table->addCell('&nbsp;');
        $table->endRow();

        if(!is_null($userDetails[0]['emailaddress'])){
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_email', 'system', 'Email').':');
            $table->addCell('<a href="mailto:'.$userDetails[0]['emailaddress'].'">'.$userDetails[0]['emailaddress'].'</a>');
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->endRow();
        }
        if(!is_null($userDetails[0]['cellnumber'])){
            $table->startRow();
            $table->addCell($this->objLanguage->languageText('word_number', 'system', 'Number').':');
            $table->addCell($userDetails[0]['cellnumber']);
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->endRow();
        }

        //Add the users interests
        $title->str = $this->objLanguage->languageText('mod_foaf_interests', 'foaf');
        //get the users interests
        $userInterests = $this->objDbFoaf->getInterests($userId);
        if(is_array($userInterests) && count($userInterests)>0){

            $table->startRow();
            $table->addCell($title->show(), null, 'top', null, null, 'colspan="2"', '0');
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->endRow();

            foreach($userInterests as $interest){
                $table->startRow();
                $table->addCell('<a href="'.$interest['interesturl'].'">'.$interest['interesturl'].'</a>', null, 'top', null, null, 'colspan="2"', '0');
                $table->endRow();

                $table->startRow();
                $table->addCell('&nbsp;');
                $table->addCell('&nbsp;');
                $table->endRow();

            }
        }
        //Add the users depictions
        $title->str = $this->objLanguage->languageText('mod_foaf_depictions', 'foaf');
        //get the users depictions
        $userDepictions = $this->objDbFoaf->getDepictions($userId);
        if(is_array($userDepictions) && count($userDepictions)>0){

            $table->startRow();
            $table->addCell($title->show(), null, 'top', null, null, 'colspan="2"', '0');
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->endRow();

            foreach($userDepictions as $depictions){
                $table->startRow();
                $table->addCell('<a target="_blank" href="'.$depictions['depictionurl'].'"><img src="'.$depictions['depictionurl'].'" width="90" height="90" /></a>');
                $table->addCell('<a href="'.$depictions['depictionurl'].'">'.$depictions['depictionurl'].'</a>');
                $table->endRow();

                $table->startRow();
                $table->addCell('&nbsp;');
                $table->addCell('&nbsp;');
                $table->endRow();

            }
        }

        //Add the users pages
        $title->str = $this->objLanguage->languageText('mod_foaf_pages', 'foaf');
        //get the users pages
        $userPages = $this->objDbFoaf->getPgs($userId);
        if(is_array($userPages) && count($userPages)>0){

            $table->startRow();
            $table->addCell($title->show(), null, 'top', null, null, 'colspan="2"', '0');
            $table->endRow();

            $table->startRow();
            $table->addCell('&nbsp;');
            $table->addCell('&nbsp;');
            $table->endRow();

            foreach($userPages as $pages){
                $table->startRow();
                $table->addCell('Title:');
                $table->addCell($pages['title']);
                $table->endRow();
                $table->startRow();
                $table->addCell('Page:');
                $table->addCell('<a href="'.$pages['page'].'">'.$pages['page'].'</a>');
                $table->endRow();
                $table->startRow();
                $table->addCell('Description:');
                $table->addCell($pages['description']);
                $table->endRow();

                $table->startRow();
                $table->addCell('&nbsp;');
                $table->addCell('&nbsp;');
                $table->endRow();

            }
        }

        $html .= $table->show();

        $html .= '</div>';
        
        return $html;
    }
}
?>