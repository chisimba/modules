<?php

/**
 * This module contains utilities for rendering elsi skin.
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *

 * @author
 * @copyright  2009 AVOIR
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
class cfe extends controller {


    function init() {
    //Instantiate the language object
        $this->objLanguage = $this->getObject('language', 'language');
    }


    public function dispatch($action) {
    /*
    * Convert the action into a method (alternative to
    * using case selections)
    */
        $method = $this->getMethod($action);
    /*
    * Return the template determined by the method resulting
    * from action
    */
        return $this->$method();
    }

    /**
     *
     * Method to convert the action parameter into the name of
     * a method of this class.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return string the name of the method
     *
     */
    function getMethod(& $action) {
        if ($this->validAction($action)) {
            return '__'.$action;
        }
        else {
            return '__home';
        }
    }

	/*function getMethod(& $action) {
     

		return '__'.$action;

 
        }*/

    /**
     *
     * Method to check if a given action is a valid method
     * of this class preceded by double underscore (__). If it __action
     * is not a valid method it returns FALSE, if it is a valid method
     * of this class it returns TRUE.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return boolean TRUE|FALSE
     *
     */
    function validAction(& $action) {
        if (method_exists($this, '__'.$action)) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * landing page. This will return list of essays
     * @return <type>
     */
    function __home() {
              return "home_tpl.php";
    }
	
   function __shortCourses() {
              return "short_courses_tpl.php";
    }


 function __preStartUp() {
              return "pre_start_up_tpl.php";
    }
function __startUp() {
              return "start_up_tpl.php";
    }
function __development() {
              return "development_tpl.php";
    }
function __growth() {
              return "growth_tpl.php";
    }
function __courseSchedule() {
              return "course_schedule_tpl.php";
    }
  function __createYourVenture() {
              return "create_your_venture_tpl.php";
    }
  function __findYourVenture() {
              return "find_your_venture_tpl.php";
    }
  function __startYourVenture() {
              return "start_your_venture_tpl.php";
    }
  function __planYourVenture() {
              return "plan_your_venture_tpl.php";
    }
  function __buildYourVenture() {
              return "build_your_venture_tpl.php";
    }

  function __growYourVenture() {
              return "grow_your_venture_tpl.php";
    }
  function __masterclass() {
              return "masterclass_tpl.php";
    }

function __support() {
              return "support_tpl.php";
    }
function __news() {
              return "support_news_tpl.php";
    }
function __mentoring() {
              return "support_mentoring_tpl.php";
    }
function __internship() {
              return "support_internship_tpl.php";
    }
function __peerSupport() {
              return "support_peer_support_tpl.php";
    }
function __bussinessClinic() {
              return "support_bussiness_clinic_tpl.php";
    }
function __events() {
              return "support_events_tpl.php";
    }
function __successStories() {
              return "support_success_stories_tpl.php";
    }

function __outreach() {
              return "outreach_tpl.php";
}
function __trainTheTrainer() {
              return "outreach_train_the_trainer_tpl.php";
    }
function __witsStudentEvents() {
              return "outreach_wits_student_events_tpl.php";
    }
function __gewEvents() {
              return "outreach_gew_events_tpl.php";
    }
function __TtTSchedule() {
              return "outreach_TtT_schedule_tpl.php";
}

function __capacityBuilding() {
              return "capacity_building_tpl.php";
    }
function __thought() {
              return "capacity_building_thought_tpl.php";
    }
function __advisor() {
              return "capacity_building_advisor_tpl.php";
    }
function __symposia() {
              return "capacity_building_symposia_tpl.php";
    }
function __thoughtSchedule() {
              return "capacity_building_thought_schedule_tpl.php";
    }
function __download() {
              return "capacity_building_download_tpl.php";
    }
function __symposiaSchedule() {
              return "capacity_building_symposia_schedule_tpl.php";
    }


function __donorsAndSponsors() {
              return "donors_and_sponsors_tpl.php";
    }
function __currentPartners() {
              return "donors_current_partners_tpl.php";
    }
function __enterpriseDevelopment() {
              return "donors_enterprise_development_tpl.php";
    }

function __contactUs() {
              return "contact_us_tpl.php";
    }

function __siteMap() {
              return "site_map_tpl.php";
    }
  

  function __research() {
              return "research_tpl.php";
    }
  


function __aboutCfe() {
              return "aboutCfe_tpl.php";
    }
    function requiresLogin(){
        return false;
    }
}
?>
