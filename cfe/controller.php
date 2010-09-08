<?php

/**
 * This module contains utilities for rendering CfE skin. It is similar to the one used for the elsi skin.
 *
 * PHP version 5
 *

 * @author	Palesa Mokwena, Thato Selebogo, Mmbudzeni Vhengani
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
        $this->viewer=$this->getObject("viewer");
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


function __home() {
	      $categoryname="homepage";
              $this->setVarByRef("category",$categoryname);
              return "home_tpl.php";
    }	
function __shortCourses() {
             $categoryname="short courses";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
 function __preStartUp() {
              $categoryname="pre start up";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __startUp() {
              $categoryname="start up";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __development() {
              $categoryname="development";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __growth() {
              $categoryname="growth";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __courseSchedule() {
              $categoryname="course schedule for 2010";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
  function __createYourVenture() {
             $categoryname="create your venture";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
  function __findYourVenture() {
              $categoryname="find your venture";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
  function __startYourVenture() {
              $categoryname="start your venture";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
  function __planYourVenture() {
              $categoryname="plan your venture";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
  function __buildYourVenture() {
              $categoryname="build your venture";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }

  function __growYourVenture() {
              $categoryname="grow your venture";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
  function __masterclass() {
             $categoryname="masterclass";
             $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }

function __support() {
                $categoryname="support";
                $relatedlinks='right column';
                $this->setVarByRef("category",$categoryname);
                $this->setVarByRef("relatedlinks",$relatedlinks);
              return "showstory_tpl.php";
    }
function __news() {
              $categoryname="news";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __mentoring() {
              $categoryname="mentoring and coaching";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __internship() {
              $categoryname="internship";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __peerSupport() {
              $categoryname="peer support";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __bussinessClinic() {
              $categoryname="business clinic";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __events() {
              $categoryname="events";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __successStories() {
              $categoryname="success stories";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }

function __outreach() {
              $categoryname="outreach";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
}
function __trainTheTrainer() {
              $categoryname="train the trainer";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __witsStudentEvents() {
              $categoryname="wits students events";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __gewEvents() {
              $categoryname="GEW events";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __TtTSchedule() {
              $categoryname="TtT Schedule";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
}

function __capacityBuilding() {
              $categoryname="capacity building";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __thoughtLeaders() {
             $categoryname="thought and leaders";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __advisorAndConsultants() {
               $categoryname="advisors and consultants";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __symposia() {
               $categoryname="symposia";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __thoughtSchedule() {
               $categoryname="thought schedule";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __download() {
               $categoryname="download application";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __symposiaSchedule() {
               $categoryname="sympsia schedule 2010";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }


function __donorsAndSponsors() {
               $categoryname="donors and sponsors";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __currentPartners() {
              $categoryname="current partners";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
function __enterpriseDevelopment() {
               $categoryname="Enterprise development";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }

function __contactUs() {
               $categoryname="contact us";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }

function __siteMap() {
               $categoryname="sitemap";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }
  

function __research() {
               $categoryname="research";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __researchthemes() {
              $categoryname="research themes";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __researchinprogress() {
              $categoryname="research in progress";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __researchbriefs() {
              $categoryname="research briefs";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __workingpapers() {
              $categoryname="working papers";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __publications() {
              $categoryname="publications";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __conference() {
              $categoryname="conference";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __aboutCfe() {
               $categoryname="about cfe";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
    }

function __background() {
               $categoryname="background";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __visionandgoals() {
               $categoryname="vision and goals";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __governance() {
               $categoryname="governance";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

function __staffmembers() {
               $categoryname="staff members";
              $this->setVarByRef("category",$categoryname);
              return "showstory_tpl.php";
   }

    function requiresLogin(){
        return false;
    }
}
?>
