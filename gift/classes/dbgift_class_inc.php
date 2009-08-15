<?php
class dbgift extends dbtable
{
    public function init() {
        parent::init("tbl_gifttable");
    }
		
    public function addInfo($donor,$recipient,$giftname,$description,$value,$listed) {
        $data=array("donor"=>$donor,"recipient"=>$recipient,"giftname"=>$giftname,"description"=>$description,"value"=>$value,"listed"=>$listed);
        $result = $this->insert($data);
        return $result;
    }

    public function updateInfo($donor,$recipient,$giftname,$description,$value,$listed,$id) {
        $data=array("donor"=>$donor,"recipient"=>$recipient,"giftname"=>$giftname,"description"=>$description,"value"=>$value,"listed"=>$listed);
		
        $result = $this->update('id',$id,$data);
        return $result;
    }
	
    public function getInfo($qry) {
        $data=$this->getArray($qry);
        return $data;
    }

    public function archive($id) {
        $listed = !$this->_getListedValue($id);
        $data['listed'] = $listed;
        $result = $this->update('id',$id,$data);
        return $result;
    }

    private function _getListedValue($id) {
        $result = $this->getRow('id',$id);
        return $result['listed'];
    }
}
?>
