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
     * @return array List of pending requests
     */
    public function getLeaveRequests($userId = NULL) {
        parent::init('tbl_requests');
        if (isset($userId)) {
            $requests = $this->getAll(NULL, "WHERE id='{$userId}' AND status='pending'");
        } else {
            $requests = $this->getAll();
        }
        return $requests;
    }

    public function getLeaveList() {
        parent::init('tbl_leaves');
        $list = $this->getAll();
        if (count($list) <= 0) {
        $xmlFile = $this->altConfig->getModulePath() . 'internals/sql/internals_leaves.xml';
        $actFile = file_get_contents($xmlFile);
        $this->domDoc->loadXML($actFile);
        $leaves = new SimpleXMLElement($actFile);
        $loopTimes = count($leaves);
        for($index = 0;$index< $loopTimes;$index++){
            $arrayValues = array(
                'id'=>$leaves->leave[$index]->id,
                'name'=>$leaves->leave[$index]->name,
                'numberofdays'=>$leaves->leave[$index]->daysdue
            );
            $this->insert($arrayValues);
        }
            return $list;
        } else {
            return $list;
        }
    }

    public function getLeaveName($id) {
        $leaveRow = $this->getRow('id', $id, 'tbl_leaves');
        return $leaveRow['name'];
    }

    public function getLeaveId($leavename) {
        $leaveRow = $this->getRow('name', $leavename, 'tbl_leaves');
        return $leaveRow['id'];
    }

    public function postRequest($userID, $leaveID, $startDate, $endDate) {
        $data = array(
            'id' => NULL,
            'userid' => $userID,
            'leaveid' => $leaveID,
            'days' => '10',
            'status' => 'pending',
            'startdate' => $startDate,
            'enddate' => $endDate
        );
        return $this->insert($data, 'tbl_requests');
    }

    public function addUser($userId) {
        $value = array(
            'id' => $userId,
            'isinternalsadmin' => 'false'
        );
        return $this->insert($value);
    }

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
        for($index = 0;$index< $loopTimes;$index++){
            echo "<br />".$leaves->leave[$index]->id;
            $arrayValues = array(
                'id'=>$leaves->leave[$index]->id,
                'name'=>$leaves->leave[$index]->name,
                'numberofdays'=>$leaves->leave[$index]->daysdue
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

    public function addLeaveType() {
        
    }

}

?>
