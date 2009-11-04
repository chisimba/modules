<?php
class dbconfigurations extends dbtable {
    var $tablename = "tbl_ads_dbconfigurations";

    public function init() {
        parent::init($this->tablename);        
    }

    public function saveEmailConfig($emailOption, $id){
        // check if current user has configurations saved.
        $data = $this->getRow('userid', $id);
        $numRows = count($data);
        $data = array('emailOption' => $emailOption, 'userid'=>$id);

        if($numRows == 0) {
            $update = $this->insert($data);
        }
        else {
            $update = $this->update('userid', $id, $data);
        }
        
        echo $emailOption." succesful";
    }
}
?>
