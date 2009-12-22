<?php
class dbuserstbl extends dbtable {
/**
 * Assign the table name in dbtable to be the table specified below
 */
    public function init() {
        parent::init("tbl_userstable");
        $this->objUser     = $this->getObject("user","security");
    }


    public function addUser($data) {
        return $this->insert($data);
    }

	function userExists($userid){
		return $this->valueExists('userid',$userid);
	}

}
?>
