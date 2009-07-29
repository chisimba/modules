<?php
class dbgift extends dbtable
{
    public function init() {
        parent::init("tbl_gifttable");
    }
		
    public function addInfo($donor,$recipient,$giftname,$description,$value,$listed) {
        $data=array("donor"=>$donor,"recipient"=>$recipient,"giftname"=>$giftname,"description"=>$description,"value"=>$value,"listed"=>$listed);
        $this->insert($data);
    }

    public function updateInfo($donor,$recipient,$giftname,$description,$value,$listed,$id) {
        $data=array("donor"=>$donor,"recipient"=>$recipient,"giftname"=>$giftname,"description"=>$description,"value"=>$value,"listed"=>$listed);
		
        $result = $this->update('id',$id,$data);
    }
	
    public function getInfo($qry) {
        $data=$this->getArray($qry);
        return $data;
    }
}
?>
