<?php

/**
 * Class to provier reusable events management logic to the podcaster module
 *
 * This class takes functionality for viewing and creates reusable methods
 * based on it so that the code can be reused in different templates
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
 * @category  Chisimba
 * @package   podcaster
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright 2011 Wits and AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: viewer_class_inc.php 14266 2009-08-09 16:00:00Z davidwaf $
 * @link      http://chisimba.com
 */
// security check - must be included in all scripts
if (!
        /**
         * Description for $GLOBALS
         * @global string $GLOBALS['kewl_entry_point_run']
         * @name   $kewl_entry_point_run
         */
        $GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 *
 * Class for reusable events management logic
 * podcaster template
 *
 * @author Paul Mungai
 * @category Chisimba
 * @package podcaster
 * @copyright AVOIR
 * @licence GNU/GPL
 *
 */
class eventutils extends object {

    /**
     *
     * @var $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;
    /**
     *
     * @var $objUser String object property for holding the
     * user object
     * @access public
     *
     */
    public $objUser;
    /**
     *
     * @var $objConfig String object property for holding the
     * configuration object
     * @access public
     *
     */
    public $objConfig;
    /**
     * 
     * @var $_objGroupAdmin Object for GroupAdmin class
     * @access public
     */
    public $_objGroupAdmin;
    /**
     *
     * @var Object  $_objManageGroups for the Class ManageGroups
     */
    public $_objManageGroups;
    /**
     *
     * @var object  objGroupsOps for Group Ops class
     */
    public $objGroupsOps;

    /**
     *
     * Standard init method
     *
     */
    public function init() {
        // Instantiate the language object.
        $this->objLanguage = $this->getObject('language', 'language');
        // Instantiate the user object.
        $this->objUser = $this->getObject("user", "security");
        // Instantiate the config object
        $this->objConfig = $this->getObject('altconfig', 'config');

        //$this->_objGAModel = $this->newObject('gamodel', 'groupadmin');
        $this->_objGroupAdmin = $this->newObject('groupadminmodel', 'groupadmin');
        $this->_objManageGroups = &$this->newObject('managegroups', 'contextgroups');
        //TEMPORARY Check if class groupops exists
        if (file_exists($this->objConfig->getsiteRootPath() . "core_modules/groupadmin/classes/groupops_class_inc.php")) {
            $this->objGroupsOps = $this->getObject('groupops', 'groupadmin');
        }
        //$this->objGroupUsers = $this->getObject('groupusersdb', 'groupadmin');
    }

    /**
     * Method to get the child id with a specified name
     */
    function getchildId($parentid, $groupName) {
        $thisgroupId = $this->_objGroupAdmin->getChildren($parentid);
        //Get the id for the child that corresponds to $groupName
        foreach ($thisgroupId as $item) {
            $mygroupName = $item['name'];
            if ($mygroupName == $groupName) {
                $groupId = $item['id'];
            }
        }
        return $groupId;
    }

    /**
     * Method to create more groups for a user
     * @param string The user id.
     * @param string The Title of a new context.
     */
    function addGroups($title) {
        // user Pk id
        $userPid = $this->objUser->PKId($this->objUser->userId());
        $usergroupId = $this->_objGroupAdmin->getId($userPid);
        // Add subgroup
        $newGroupId = $this->_objGroupAdmin->addGroup($title, $userPid . ' ' . $title, $usergroupId);
        // then add them as subGroups of the parent Group.
        $data = array(
            'group_id' => $usergroupId,
            'subgroup_id' => $newGroupId
        );
        $newSubGroupId = $this->objLuAdmin->perm->assignSubGroup($data);
        // Add groupMembers
        //$this->addGroupMembers();
        $groupId = $this->_objGroupAdmin->addGroupUser($newGroupId, $this->objUser->userId());
        // Now create the ACLS
        $this->_objManageGroups->createAcls($userPid, $title);
    }

    /**
     * Method to get the user groups. Renders output in a table with manage links(Add/edit)
     * @return string
     */
    public function getUserGroups() {
        //load classes
        $objLanguage = &$this->getObject('language', 'language');
        $icon = &$this->newObject('geticon', 'htmlelements');
        $table = &$this->newObject('htmltable', 'htmlelements');
        $linkstable = &$this->newObject('htmltable', 'htmlelements');
        $objGroups = &$this->newObject('managegroups', 'contextgroups');
        $mngfeatureBox = &$this->newObject('featurebox', 'navigation');

        $table->width = '40%';
        $linkstable->width = '40%';
        $str = '';
        //Add Group Link
        $iconAdd = $this->getObject('geticon', 'htmlelements');
        $iconAdd->setIcon('add');
        $iconAdd->title = $objLanguage->languageText("mod_podcaster_addevent", 'podcaster', 'Add event');
        $iconAdd->alt = $objLanguage->languageText("mod_podcaster_addevent", 'podcaster', 'Add event');
        $addlink = new link($this->uri(array(
                            'module' => 'podcaster',
                            'action' => 'add_event'
                        )));
        $addlink->link = $objLanguage->languageText("mod_podcaster_addevent", 'podcaster', 'Add event');
        $objLink = &$this->getObject('link', 'htmlelements');
        $objLink->link($this->uri(array(
                    'module' => 'podcaster',
                    'action' => 'add_event'
                )));
        $objLink->link = $iconAdd->show();
        $mylinkAdd = $objLink->show();
        $addlink->link = 'Add Group';
        $linkAdd = $addlink->show();
        $linkstableRow = array(
            '<hr/>' . $linkAdd . ' ' . $mylinkAdd
        );
        $linkstable->addRow($linkstableRow);
        //Get group members
        //Get group id
        $userPid = $this->objUser->PKId($this->objUser->userId());
        $this->setVarByRef('userPid', $this->userPid);
        //get the descendents.
        if (class_exists('groupops', false)) {
            $usergroupId = $this->_objGroupAdmin->getId($userPid);
            $usersubgroups = $this->_objGroupAdmin->getSubgroups($usergroupId);
            //Check if empty
            if (!empty($usersubgroups)) {
                foreach ($usersubgroups as $subgroup) {
                    // The member list of this group
                    $myGroupId = array();
                    foreach (array_keys($subgroup) as $myGrpId) {
                        $myGroupId[] = $myGrpId;
                    }
                }
            }
            $fields = array(
                'firstName',
                'surname',
                'tbl_users.id'
            );
            //Check if empty
            if (!empty($usersubgroups)) {
                foreach ($myGroupId as $groupId) {
                    $membersList = $this->_objGroupAdmin->getGroupUsers($groupId, $fields);
                    $groupName = $this->_objGroupAdmin->getName($groupId);
                    $groupName = explode("^", $groupName);
                    if (count($groupName) == 2) {
                        $groupName = $groupName[1];
                        foreach ($membersList as $users) {
                            if ($users) {
                                $fullName = $users['firstname'] . " " . $users['surname'];
                                $userPKId = $users['id'];
                                $tableRow = array(
                                    $fullName
                                );
                                $table->addRow($tableRow);
                            } else {
                                $tableRow = array(
                                    '<div align="left" style="font-size:small;font-weight:bold;color:#sCCCCCC;font-family: Helvetica, sans-serif;">' . $this->objLanguage->languageText('mod_podcaster_manage', 'podcaster', 'Manage') . '</div>'
                                );
                                $table->addRow($tableRow);
                            }
                        }
                        //Add Users
                        $iconManage = $this->getObject('geticon', 'htmlelements');
                        $iconManage->setIcon('add_icon');
                        $iconManage->alt = $objLanguage->languageText("mod_podcaster_add", 'podcaster', 'Add') . ' / ' . $objLanguage->languageText("mod_podcaster_edit", 'podcaster', 'Edit') . ' ' . $groupName;
                        $iconManage->title = $objLanguage->languageText("mod_podcaster_add", 'podcaster', 'Add') . ' / ' . $objLanguage->languageText("mod_podcaster_edit", 'podcaster', 'Edit') . ' ' . $groupName;
                        $mnglink = new link($this->uri(array(
                                            'module' => 'podcaster',
                                            'action' => 'viewevents',
                                            'id' => $groupId
                                        )));
                        //	    		$mnglink->link = $objLanguage->languageText('mod_podcaster_manage', 'podcaster', 'Manage').' '.$subgroup['name'].' '.$iconManage->show();
                        $mnglink->link = $iconManage->show();
                        $linkManage = $mnglink->show();
                        //Manage Events
                        $iconShare = $this->getObject('geticon', 'htmlelements');
                        $iconShare->setIcon('fileshare');
                        $iconShare->alt = $objLanguage->languageText("mod_podcaster_configure", 'podcaster', 'Configure') . ' ' . $groupName . ' ' . $this->objLanguage->code2Txt("mod_podcaster_view", 'podcaster', 'View');
                        $iconShare->title = $objLanguage->languageText("mod_podcaster_configure", 'podcaster', 'Configure') . ' ' . $groupName . ' ' . $this->objLanguage->code2Txt("mod_podcaster_view", 'podcaster', 'View');
                        $mnglink = new link($this->uri(array(
                                            'module' => 'podcaster',
                                            'action' => 'manage_event',
                                            'id' => $groupId
                                        )));
                        $mnglink->link = $iconShare->show();
                        $linkMng = $mnglink->show();
                        $tableRow = array(
                            '<hr/>' . $linkManage . '   ' . $linkMng
                        );
                        $table->addRow($tableRow);
                        $textinput = new textinput("groupname", $groupName);
                        $str.= $mngfeatureBox->show($groupName, $table->show());
                        $table = &$this->newObject('htmltable', 'htmlelements');
                        $managelink = new link();
                    }
                } //end foreach
            }
        }
        $str.= $mngfeatureBox->show(NULL, $linkstable->show());
        return $str;
        unset($users);
    }

    /**
     * Method to show the list of users in an event
     */
    private function eventsHome($group) {
        // Generate an array of users in the event, and send it to page template
        $this->prepareEventUsersArray();
        // Default Values for Search
        $searchFor = $this->getSession('searchfor', '');
        $this->setVar('searchfor', $searchFor);
        $field = $this->getSession('field', 'firstName');
        $course = $this->getSession('course', 'course');
        //$group=$this->getSession('group','group');
        $this->setVar('field', $field);
        $this->setVar('course', $course);
        $this->setVar('group', $group);
        $confirmation = $this->getSession('showconfirmation', FALSE);
        $this->setVar('showconfirmation', $confirmation);
        //$this->setSession('showconfirmation', FALSE);
        //Ehb-added-begin
        $currentContextCode = $this->_objDBContext->getContextCode();
        $where = "where contextCode<>" . "'" . $currentContextCode . "'";
        $data = $this->_objDBContext->getAll($where);
        $this->setVarByRef('data', $data);
        //Ehb-added-End
        return 'eventhome_tpl.php';
    }

    /**
     * Method to Prepare a List of Users in a Context sorted by lecturer, student, guest
     * The results are sent to the template
     */
    private function prepareEventUsersArray() {
        // Get Context Code
        $contextCode = $this->_objDBContext->getContextCode();
        $filter = " ORDER BY surname ";
        // Guests
        //$gid=$this->_objGroupAdmin->getLeafId(array($contextCode,'Guest'));
        $groupId = $this->getSession('groupId');
        $guests = $this->_objGroupAdmin->getGroupUsers($groupId, array(
                    'userid',
                    'firstName',
                    'surname',
                    'title',
                    'emailAddress',
                    'country',
                    'sex',
                    'staffnumber'
                        ), $filter);
        $guestsArray = array();
        if (count($guests) > 0) {
            foreach ($guests as $guest) {
                $guestsArray[] = $guest['userid'];
            }
        }
        // Send to Template
        $this->setVarByRef('guests', $guestsArray);
        $this->setVarByRef('guestDetails', $guests);
    }

    /**
     * Method to search for Users
     * This function sets them as a session and then redirects to the results
     */
    private function searchForUsers() {
        $searchFor = $this->getParam('search');
        $this->setSession('searchfor', $searchFor);
        $field = $this->getParam('field');
        $this->setSession('field', $field);
        //Ehb-added-begin
        $course = $this->getParam('course');
        $this->setSession('course', $course);
        $group = $this->getParam('group');
        $this->setSession('group', $group);
        //Ehb-added-End
        $order = $this->getParam('order');
        $this->setSession('order', $order);
        $numResults = $this->getParam('results');
        $this->setSession('numresults', $numResults);
        return $this->nextAction('viewsearchresults');
    }

    /**
     * Method to Show the Results for a Search
     * @param int $page - Page of Results to show
     */
    private function getResults($page = 1) {
        $searchFor = $this->getSession('searchfor', '');
        $field = $this->getSession('field', 'firstName');
        //Ehb-added-begin
        $course = $this->getSession('course', 'course');
        $group = $this->getSession('group', 'group');
        //Ehb-added-End
        $order = $this->getSession('order', 'firstName');
        $numResults = $this->getSession('numresults', 20);
        $groupId = $this->getSession('groupId', $groupId);
        $this->setVar('searchfor', $searchFor);
        $this->setVar('field', $field);
        $this->setVar('order', $order);
        $this->setVar('numresults', $numResults);
        //Ehb-added-begin
        $this->setVar('course', $course);
        $this->setVar('group', $group);
        //Ehb-added-End
        // Prevent Corruption of Page Value - Negative Values
        if ($page < 1) {
            $page = 1;
        }
        $currentContextCode = $this->_objDBContext->getContextCode();
        $results = $this->objContextUsers->searchUsers($searchFor, $field, $order, $numResults, ($page - 1), $course, $group);
        $this->setVarByRef('results', $results);
        $countResults = $this->objContextUsers->countResults();
        $this->setVarByRef('countResults', $countResults);
        $this->setVarByRef('page', $page);
        $paging = $this->objContextUsers->generatePaging($searchFor, $field, $order, $numResults, ($page - 1));
        $this->setVarByRef('paging', $paging);
        $contextCode = $this->_objDBContext->getContextCode();
        $this->setVarByRef('contextCode', $contextCode);
        //Ehb-added-begin
        $currentContextCode = $this->_objDBContext->getContextCode();
        $where = "where contextCode<>" . "'" . $currentContextCode . "'";
        $data = $this->_objDBContext->getAll($where);
        $this->setVarByRef('data', $data);
        //Ehb-added-End
        // Get Users into Arrays
        $this->prepareContextUsersArray();
        return 'searchresults_tpl.php';
    }

}

?>