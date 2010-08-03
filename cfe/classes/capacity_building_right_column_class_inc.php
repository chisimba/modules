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

class capacity_building_right_column extends object {

  /**
   * Constructor
   */
    public function init() {
	//Create objects of different classes
	$this->objNewsletter = $this->newObject('newsletter', 'cfe');
        $this->objLinks = $this->newObject('links', 'cfe');
    }

  /**
   * Method to show the links and the newsletter
   * @return string
   */
    public function show() {
        // Add all links on the right column
        $rlinks =   $this->objLinks->Link('capacityBuilding', 'Capacity building','cfe').
		    $this->objLinks->Link('thought', 'Thought Leaders','cfe').
                    $this->objLinks->Link('advisor', 'Advisor and consultants', 'cfe').
                    $this->objLinks->Link('symposia', 'Symposia', 'cfe').
                    $this->objLinks->Link('thoughtSchedule','Schedule for 2010', 'cfe').
		    $this->objLinks->Link('download','Download application', 'cfe').
		    $this->objLinks->Link('symposiaSchedule','Schedule for 2010', 'cfe');
              
                    

        $rightCol ='<div id="shortCoursesRight"><h3>  RELATED LINKS </h3></div>';

	$newsletter = $this->objNewsletter->show();

      	//return the links and the newsletter form
	return '<div id="menu">' . '<div id="shortCoursesRightCol">' . '<div class="shortbl">' . '<div class="shortbr">' . '<div class="shorttl">'. '<div class="shorttr">'. $rightCol. $rlinks . $str . '</div>'.'</div>'.'</div>'. '</div>' . '</div>'. '</div>' .$newsletter;

    }
       
}

?>
