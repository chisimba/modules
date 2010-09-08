<?php

/**
 * navigation bar Class.
 *
 *
 * PHP version 5
 * @category  Chisimba
 * @package   cfe
 * @authors    Palesa Mokwena, Thato Selebogo, Mmbudzeni Vhengani
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

class navigationbar extends object {
   /**
    * Constructor
    */
    function init() {

        $this->objLink = $this->getObject("links");
        $this->objConfig = $this->getObject("altconfig", "config");
    }


  /**
   * A method to show the navigation bar
   * 
   * @access public
   */
    function show() {


        echo '<ul id="navigation_bar">';

        echo ' <li>';
        echo $this->objLink->Link('home', 'Home', 'cfe');
        echo '</li>';


        echo '<li><a href="' . $this->objConfig->getSiteRoot() . '?module=cfe&action=aboutCfe"';
        echo '
        onmouseover="mopen(\'m1\')" 
        onmouseout="mclosetime()">About us</a>
        <div id="m1" 
        onmouseover="mcancelclosetime()" 
        onmouseout="mclosetime()">
';

        echo $this->objLink->Link('background', 'Background', 'cfe') .
        $this->objLink->Link('visionandgoals', 'Vision and goals', 'cfe') .
        $this->objLink->Link('governance', 'Governance', 'cfe') .
        $this->objLink->Link('staffmembers', 'Gtaff members', 'cfe');
        echo '</div>
    </li>
';
        

          echo   '<li><a href="'.$this->objConfig->getSiteRoot().'?module=cfe&action=shortCourses"';
          echo 'onmouseover="mopen(\'m2\')"
          onmouseout="mclosetime()">Short courses</a>
          <div id="m2"
          onmouseover="mcancelclosetime()"
          onmouseout="mclosetime()">';

          echo $this->objLink->Link('preStartUP', 'Pre startup', 'cfe').
          $this->objLink->Link('startUP', 'Startup', 'cfe').
          $this->objLink->Link('development', 'Development', 'cfe').
          $this->objLink->Link('growth', 'Growth', 'cfe').
          $this->objLink->Link('courseSchedule', 'Course schedule for 2010', 'cfe');
          
        echo  '</div>
          </li>';

     echo '<li><a href="'.$this->objConfig->getSiteRoot().'?module=cfe&action=research"';
       echo  'onmouseover="mopen(\'m3\')"
          onmouseout="mclosetime()">Reseach</a>
          <div id="m3"
          onmouseover="mcancelclosetime()"
          onmouseout="mclosetime()">';
     echo $this->objLink->Link('researchthemes', 'Research themes', 'cfe').
          $this->objLink->Link('researchinprogress', 'Research in progress', 'cfe').
          $this->objLink->Link('researchbriefs', 'Research brief', 'cfe').
          $this->objLink->Link('workingpapers', 'Working papers', 'cfe').
          $this->objLink->Link('conference', 'Conference', 'cfe');
        
     echo' </div>
          </li>';

   echo '<li><a href="'.$this->objConfig->getSiteRoot().'?module=cfe&action=support"';
     echo 'onmouseover="mopen(\'m4\')"
          onmouseout="mclosetime()">Support</a>
          <div id="m4"
          onmouseover="mcancelclosetime()"
          onmouseout="mclosetime()">';
     echo $this->objLink->Link('news', 'News', 'cfe').
          $this->objLink->Link('mentoring', 'Mentoring and coaching', 'cfe').
          $this->objLink->Link('internship', 'Internship', 'cfe').
          $this->objLink->Link('peerSupport', 'Peer support', 'cfe');
        
     echo '</div>
          </li>';

   echo '<li><a href="'.$this->objConfig->getSiteRoot().'?module=cfe&action=outreach"';
     echo 'onmouseover="mopen(\'m5\')"
          onmouseout="mclosetime()">Outreach</a>
          <div id="m5"
          onmouseover="mcancelclosetime()"
          onmouseout="mclosetime()">';
     echo $this->objLink->Link('trainTheTrainer', 'Train the trainer', 'cfe').
          $this->objLink->Link('witsStudentEvents', 'Wits student events', 'cfe').
          $this->objLink->Link('gewEvents', 'GEW events', 'cfe');
       
     echo '</div>
          </li>';

     echo '<li><a href="'.$this->objConfig->getSiteRoot().'?module=cfe&action=capacityBuilding"';
     echo 'onmouseover="mopen(\'m6\')"
          onmouseout="mclosetime()">Capacity building</a>
          <div id="m6"
          onmouseover="mcancelclosetime()"
          onmouseout="mclosetime()">';
     echo $this->objLink->Link('thoughtLeaders', 'Thought leaders', 'cfe').
          $this->objLink->Link('advisorAndConsultants', 'Advisor and consultants', 'cfe').
          $this->objLink->Link('symposia', 'Symposia', 'cfe');
       
     echo '</div>
          </li>';

     echo '<li><a href="'.$this->objConfig->getSiteRoot().'?module=cfe&action=donorsAndSponsors"';
     echo 'onmouseover="mopen(\'m7\')"
          onmouseout="mclosetime()">Donors and sponsors</a>
          <div id="m7"
          onmouseover="mcancelclosetime()"
          onmouseout="mclosetime()">';
     echo $this->objLink->Link('currentPartners', 'Current partners', 'cfe').
          $this->objLink->Link('enterpriseDevelopment', 'Enterprise development', 'cfe');
          
     echo '</div>
          </li>';
     echo '<li>';
     echo $this->objLink->Link('contactUs', 'Contact us', 'cfe'); 
     echo '</li>';
     echo '<li>';
     echo $this->objLink->Link('siteMap', 'Site map', 'cfe');
     echo '</li>';

     echo '        </ul>
          <div style="clear:both"></div>

         
         ';
    }

}

?>
