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
        //put your code here
        public function init() {
                parent::init('tbl_internals');
                $this->altConfig = $this->getObject('altconfig','config');
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
                return $list;
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
                    'userid'=>$userID,
                    'leaveid' => $leaveID,
                    'days'=>'10',
                    'status' => 'pending',
                    'startdate' => $startDate,
                    'enddate'=>$endDate
                );
                return $this->insert($data, 'tbl_requests');
        }
        
        public function addUser($userId){
                $value = array(
                    'id'=>$userId,
                    'isinternalsadmin'=>'false'
                );
                return $this->insert($value);
        }
        
        public function getAvailabelDays($userId)
        {
//            $recordsFile = new DOMDocument('utf-8');
//            $recordsFile->loadXML($source);
            $simpleResource = simplexml_load_file('./packages/internals/sql/records.xml');
//            $users = $recordsFile->getElementsByTagName('userid');
            return var_dump($simpleResource);
        }

                /**
         * The function to update the request after approval or rejection
         * 
         * @acces public
         * @param $requestId The unique record Id
         * @return boolean True or false depending on the query result
         */
        public function updateRequest($requestId,$userId){
                $this->update('status','pending');
        }
        
        public function addLeaveType(){
                
        }

}

?>
