<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dbinternals_class_inc
 *
 * @author monwabisi
 */
class dbinternals extends dbTable {

    var $altConfig;
    var $domDoc;

    //put your code here
    public function init() {
        parent::init('tbl_internals');
        $this->altConfig = $this->getObject('altconfig', 'config');
        $this->domDoc = new DOMDocument('utf-8');
    }

    /**
     * @access public
     * @return array List of internal rembers
     */
    public function getinternals() {
        return $this->getAll();
    }

    /**
     * Method to get the list of pennding requests
     * 
     * @access public
     * @param string $userId
     * @return array List of pending requests
     */
    public function getLeaveRequests($userId = NULL) {
        parent::init('tbl_requests');
        if (!empty($userId)) {
            $requests = $this->getAll(NULL, "WHERE id='{$userId}' AND status='pending'");
        } else {
            $requests = $this->getAll();
        }
        return $requests;
    }

    /**
     * @access public
     * @return array list of the leave types
     */
    public function getLeaveList() {
        parent::init('tbl_leaves');
        $list = $this->getAll();
        return $list;
    }

    /**
     * @access public
     * @return string Name of the leave
     */
    public function getLeaveName($id) {
        $leaveRow = $this->getRow('id', $id, 'tbl_leaves');
        return $leaveRow['name'];
    }

    /**
     * Method to return the leave ID using it's ID
     * 
     * @param string $leavename
     * @return The ID of the leave
     */
    public function getLeaveId($leavename) {
        $leaveRow = $this->getRow('name', $leavename, 'tbl_leaves');
        return $leaveRow['id'];
    }

    /**
     * Method to send the request to the database and to the module administrator
     * 
     * @access public
     * @param string $userID The database primary key for the user
     * @param string $leaveID The ID of the leave type the user applied for
     * @param date $startDate The date the user wishes ;to start leave
     * @param date $endDate The date the leave will expire
     * @param string $days The total number ofd days requested by the user
     * @return boolean TRUE if the values were successfuly inserted to the database
     */
    public function postRequest($userID, $leaveID, $startDate, $endDate,$days) {
        //create the holidays array

        $data = array(
            'id' =>'',
            'userid' => $userID,
            'leaveid' => $leaveID,
            'days' => $days,
            'status' => 'pending',
            'startdate' => $startDate,
            'enddate' => $endDate
        );
        return $this->insert($data, 'tbl_requests');
    }

    /**
     * Method to return the number of available days for the user
     * 
     * @acess public
     * @param string $leaveId The ID of the leave type the user applied for
     * @param string $userId The database primary key for the user
     * @return array The values retrieved from the database
     */
    public function getDaysLeft($leaveId, $userId) {
        $this->_tableName = 'tbl_leaverecords';
        $stateMent = "WHERE id = '{$leaveId}' AND userid = '{$userId}'";
        return $this->getAll($stateMent);
    }

    /**
     * Method to add the user to the leave mnagement database
     * 
     * @access public
     * @param string $userId The users primary key from the user's table in the system
     */
    public function addUser($userId) {
        if ($this->valueExists('id', $userId)) {
            //prevent duplication
            header('location:index.php?module=internals');
        } else {
            $values = array(
                'id' => $userId,
                'isinternalsadmin' => 'false'
            );
            //change the table
            $this->_tableName = 'tbl_internals';
            $this->insert($values);
            //change table
            $this->_tableName = "tbl_leaverecords";
            
        }
    }

    /**
     * 
     * @param type $userId

      public function getAvailabelDays($userId) {
      $xmlFile = $this->altConfig->getModulePath() . 'internals/sql/internals_leaves.xml';
      $actFile = file_get_contents($xmlFile);
      //            $xmlFile = "
      //<leaves>
      //    <leave>
      //        <id>01ann</id>
      //        <leavename>
      //            Annual
      //        </leavename>
      //        <daysdue>
      //            21
      //        </daysdue>
      //    </leave>
      //    <leave>
      //        <id>
      //            02sic
      //        </id>
      //        <leavename>
      //            Sick
      //        </leavename>
      //        <daysdue>
      //            3
      //        </daysdue>
      //    </leave>
      //</leaves>";
      $test = '';
      $this->domDoc->loadXML($actFile);
      $leaves = new SimpleXMLElement($actFile);
      //            $users = $recordsFile->getElementsByTagName('userid');
      foreach ($leaves->leave as $leave) {
      $test .= $leave;
      }
      $loopTimes = count($leaves);
      for ($index = 0; $index < $loopTimes; $index++) {
      echo "<br />" . $leaves->leave[$index]->id;
      $arrayValues = array(
      'id' => $leaves->leave[$index]->id,
      'name' => $leaves->leave[$index]->name,
      'numberofdays' => $leaves->leave[$index]->daysdue
      );
      $this->insert($arrayValues);
      }
      }

      /**
     * The function to update the request after approval or rejection
     * 
     * @acces public
     * @param $requestId The unique record Id
     * @return boolean True or false depending on the query result
     */
    public function updateRequest($requestId, $userId) {
        $this->update('status', 'pending');
    }

    /**
     * 
     * @access public
     * @param string $leaveName The name of the leave |ie: Annual or sick.....
     * @param interg $numberOfDays the maximum number of days available for the leave type
     * @return boolean TRUE|FALSE returns true if the values were successfully inserted to the database
     */
    public function addLeaveType($leaveName, $numberOfDays) {
        $fields = array(
            'id' => NULL,
            'name' => $leaveName,
            'numberofdays' => $numberOfDays
        );
        return $this->insert($fields, 'tbl_leaves');
    }

}

?>
