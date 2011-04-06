<?php

/**
 * controller class
 *
 * 
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
 *
 * @package   wishes
 * @author    Emmanuel Natalis  <matnatalis@udsm.ac.tz>
 * @University Computing center
 * @Dar es salaam university of Tanzania
 * @copyright 2008 Emmanuel Natalis
 */
// security check - must be included in all scripts
if (!
        /**
         * Description for $GLOBALS
         * @global entry point $GLOBALS['kewl_entry_point_run']
         * @name   $kewl_entry_point_run
         */
        $GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
 * controller class
 *
 * 
 *
 *
 * @package   wishes
 * @author    Emmanuel Natalis<matnatalis@udsm.ac.tz>
 * @copyright 2008 Emmanuel Natalis
 */
class bestwishes extends controller {

    public function init() {

    }

    public function dispatch() {
        $action = $_GET['action'];
        switch ($action) {
            case bdenterdate:
                return 'happybirthdayenterdate_tlp.php';
                break;
            case enterdate:
                $birthdate = $_POST['calendardate'];
                $objHappybirthday = $this->getObject('dbbestwishes', 'bestwishes');
                $report = $objHappybirthday->insertBirthdate($birthdate);
                $this->setVar('report', $report);
                return 'bestwishesreport_tlp.php';
                break;
            case bdremovedate:
                $objHappybirthday = $this->getObject('dbbestwishes', 'bestwishes');
                $report = $objHappybirthday->removeBirthdate();
                $this->setVar('report', $report);
                return 'bestwishesreport_tlp.php';
                break;
            case viewbdusers:
                return 'happybirthdayviewusers_tpl.php';
                break;
            case enterevent:
                return 'enterevent_tpl.php';
                break;
            case viewevents:
                return 'viewevents_tpl.php';
            case saveEvent:

                $objHappybirthday = $this->getObject('dbbestwishes', 'bestwishes');
                $title = $_POST['title'];
                $description = $_POST['description'];
                $report = $objHappybirthday->saveEvents($title, $description);
                $this->setVar('report', $report);
                return 'bestwishesreport_tlp.php';
                break;
            default:
                return 'main_menu_tlp.php';
        }
    }

    public function requiresLogin() {
        return TRUE;
    }

}
?>