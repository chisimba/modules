<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class dbuserextra extends dbtable {
    private $tablename;
    function init() {
        parent::init('tbl_users');
        $this->tablename='tbl_users';
    }

    /**
     * we get puid because we need it to add a user to a group
     * @param <type> $userid
     * @return <type>
     */
    function getUserPuid($userid) {

        $sql=
                "select puid  from tbl_users where userid = '$userid'";
        $rows=$this->getArray($sql);
        $row=$rows[0];
        return $row['puid'];
    }
}

?>
