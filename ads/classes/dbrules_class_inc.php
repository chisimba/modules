<?php
/* 
 * Responsibl for insterting, updating and deleting rules table
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbrules extends dbtable{

    public function init()
    {
        parent::init('tbl_rules');  //super
        $this->table = 'tbl_rules';
        $this->objUser = $this->getObject ( 'user', 'security' );

    }

    public function addRules($data){
	if($this->exists($data['unit_name'],$data['userid'])){
	foreach($data as $key=>$value){
	$data[$key]=addslashes($data[$key]);
	}
	$sql="update ".$this->table." set b1='$data[b1]', b2='$data[b2]', b3a='$data[b3a]', b3b='$data[b3b]', b4a='$data[b4a]', b4b='$data[b4b]', b4c='$data[b4c]', b5a='$data[b5a]', b5b='$data[b5b]' where unit_name='$data[unit_name]' and userid='$data[userid]';";	
	$this->_execute($sql);
	}
	else{
        $this->insert($data);
	}
    }

    public function getRules($unit_name, $userid)
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
	if(count($this->getRules($unit_name,$userid))==0){
	$exists=0;}
	return $exists;
    }
	
   private function printd(){
	$logins=$this->getAll();
	echo 'Start: <br>';
	foreach($logins as $login){
	echo $login['b1'].' '.$login['b2'].' '.$login['b3a'].' '.$login['b3b'].' '.$login['b4a'].' '.$login['b4b'].' '.$login['b4c'].' '.$login['b5a'].' '.$login['5b'].'<br>';
	}
	echo 'End <br>';
	}
}
?>
