<?php
/* 
 * Responsibl for insterting, updating and deleting overview table
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dboverview extends dbtable{

    public function init()
    {
        parent::init('tbl_overview');  //super
        $this->table = 'tbl_overview';
        $this->objUser = $this->getObject ( 'user', 'security' );

    }

    public function addOverview($org,$data){
	if($this->exists($org,$data['userid'])){
	foreach($data as $key=>$value){
	$data[$key]=addslashes($data[$key]);
	}
	$sql="update ".$this->table." set unit_type='$data[unit_type]', motiv='$data[motiv]', qual='$data[qual]', unit_type2='$data[unit_type2]', unit_name='$data[unit_name]' where unit_name='$org' and userid='$data[userid]';";	
	$this->_execute($sql);
	}
	else{
        $this->insert($data);
	}
    }

    public function getOverview($unit_name, $userid)
    {
	$sql ="select * from ".$this->table." where unit_name='$unit_name' and userid='$userid';";
        $arr=$this->getArray($sql);

	if(count($arr)==0){
	return array();}
	else{
	return $arr[0];}
    }
    
    public function exists($unit_name, $userid)
    {
	$exists=1;
	if(count($this->getOverview($unit_name,$userid))==0){
	$exists=0;}
	return $exists;
    }
	
   private function printd(){
	$logins=$this->getAll();
	echo 'Start: <br>';
	foreach($logins as $login){
	echo $login['unit_type'].' '.$login['motiv'].' '.$login['qual'].' '.$login['unit_type2'].' '.$login['unit_name'].'<br>';
	}
	echo 'End <br>';
	}
}
?>
