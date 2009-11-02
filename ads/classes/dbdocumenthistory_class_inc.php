<?php
class dbdocumenthistory extends dbtable{
    var $tablename = "tbl_ads_documenthistory";

    public function init(){
        parent::init($this->tablename);
    }

    public function saveHistory($courseid, $phase, $forwadedto) {
        $objUser = $this->getObject ( 'user', 'security' );
        $data = array('courseid'=>$courseid, 'phase'=>$phase, 'forwardedto'=>$forwadedto, 'forwardedby'=>$objUser->userId(),'dateforwarded'=>strftime('%Y-%m-%d %H:%M:%S',mktime()));
        $this->insert($data);
    }

    public function getData($courseid) {
        $objUser = $this->getObject ( 'user', 'security' );
        $filter = "where courseid = '".$courseid."'";
        $data = $this->getAll($filter);

        // history grid data
        $count = 1;
        $membercount = count($data);
        foreach($data as $data){
            if($data['phase']== 0){
                    $data['phase'] = 'Proposal Phase';
                }
                elseif($data['phase']== 1){
                    $data['phase'] = 'APO Comment';
                }
                elseif($data['phase']== 2){
                    $data['phase'] = 'Faculty subcommittee approval';
                }
                elseif($data['phase']== 3){
                    $data['phase'] = 'Faculty board approval';
                }
            $date = date_create($data['dateforwarded']);
            $hisData.="['".$data['phase']."','".date_format($date, 'd/m/Y h:m:s')."','".$objUser->fullname($data['forwardedto'])."','".$objUser->fullname($data['forwardedby'])."']";

            if($count < $membercount){
                $hisData.=",";
            }
            $count++;
        }

        return $hisData;
    }
}
?>