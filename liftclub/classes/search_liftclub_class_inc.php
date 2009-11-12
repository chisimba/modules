<?php
/* ----------- data class extends dbTable for tbl_email------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for liftclub general functions
 * @author Paul Mungai
 * @copyright 2009 University of the Western Cape
 */
class search_liftclub extends dbTable
{
    /**
     * Constructor method to define the table
     */
    function init() 
    {
        $this->objUser = &$this->getObject('user', 'security');
        $this->objDBCities = $this->getObject('dbliftclub_cities', 'liftclub');
        $this->objDBOrigin = $this->getObject('dbliftclub_origin', 'liftclub');
        $this->objDBDestiny = $this->getObject('dbliftclub_destiny', 'liftclub');
        $this->objDBDetails = $this->getObject('dbliftclub_details', 'liftclub');
    }
    function getLifts($userneed, $start, $limit, $params=null) 
    {	
									$where = "";

									if(is_array($params['search'])){
										$max = count($params['search']);
		        //$max=3;
										$cnt = 0;
										foreach($params['search'] as $field){
											/*$cnt++;
											$where .= $field.' LIKE "'.$params['query'].'%"';											*/
											if($field=='orisuburb'){
												$cnt++;
											 $where .= ' ori.suburb LIKE "%'.$params['query'].'%" ';
						     }
						     if($field=='desuburb'){
												$cnt++;
											 $where .= ' des.suburb LIKE "%'.$params['query'].'%" ';
						     }
						     if($field=='needtype'){
												$cnt++;
											 $where .= ' det.needtype LIKE "%'.$params['query'].'%" ';
						     }
						     if($field=='userneed'){
												$cnt++;
											 $where .= ' det.userneed LIKE "%'.$params['query'].'%" ';
						     }
											if($cnt < $max){
												$where .= " OR ";
											}
										}					
										$where = ' AND '.$where;		
									}
         if(!empty($start) || $start>=0 && !empty($limit)){
           $sqlsearch = "select det.id as detid, ori.id as orid, des.id as desid, det.userid as detuserid, det.times, det.additionalinfo, det.specialoffer, det.emailnotifications, det.userneed, det.needtype, det.daterequired, det.createdormodified, det.monday, det.tuesday, det.wednesday, det.thursday, det.friday, det.saturday, det.sunday, ori.userid as oriuserid, ori.street as oristreet, ori.suburb as orisuburb, des.userid as desuserid, des.street as destreet, des.suburb as desuburb from tbl_liftclub_details as det, tbl_liftclub_origin as ori, tbl_liftclub_destiny as des where det.userid=ori.userid AND ori.userid=des.userid AND des.userid=det.userid AND det.userneed='".$userneed."'".$where." LIMIT ".$start." , ".$limit;
           $lifts = $this->objDBDetails->getArray($sqlsearch);
		         return $lifts;
         }else{
           $sqlsearch = "select det.id as detid, ori.id as orid, des.id as desid, det.userid as detuserid, det.times, det.additionalinfo, det.specialoffer, det.emailnotifications, det.userneed, det.needtype, det.daterequired, det.createdormodified, det.monday, det.tuesday, det.wednesday, det.thursday, det.friday, det.saturday, det.sunday, ori.userid as oriuserid, ori.street as oristreet, ori.suburb as orisuburb, des.userid as desuserid, des.street as destreet, des.suburb as desuburb from tbl_liftclub_details as det, tbl_liftclub_origin as ori, tbl_liftclub_destiny as des where det.userid=ori.userid AND ori.userid=des.userid AND des.userid=det.userid AND det.userneed='".$userneed."'".$where;
           $lifts = $this->objDBDetails->getArray($sqlsearch);
           return $lifts;           
         }
    }
 
    function jsonLiftSearch($userneed, $start, $limit) 
    {
								$params["start"] = ($this->getParam("start")) ? $this->getParam("start") : null;
								$params["limit"] = ($this->getParam("limit")) ? $this->getParam("limit") : null;
								$params["search"] = ($this->getParam("fields")) ? json_decode(stripslashes($this->getParam("fields"))) : null;
								$params["query"] = ($this->getParam("query")) ? $this->getParam("query") : null;
								$params["sort"] = ($this->getParam("sort")) ? $this->getParam("sort") : null;

        $myLifts = $this->getLifts($userneed, $start, $limit, $params);
       	$liftCount = ( count ( $myLifts ) );
        $str = '{"liftcount":"'.$liftCount.'","searchedlifts":[';
        $searchArray = array();
        foreach($myLifts as $thisLift){
          $infoArray = array();
          $infoArray['detid'] = $thisLift['detid'];
          $infoArray['orid'] = $thisLift['orid'];          
          $infoArray['desid'] = $thisLift['desid'];
          $infoArray['detuserid'] = $thisLift['detuserid'];
          $infoArray['times'] = $thisLift['times'];
          $infoArray['additionalinfo'] = $thisLift['additionalinfo'];
          $infoArray['specialoffer'] = $thisLift['specialoffer'];
          $infoArray['emailnotifications'] = $thisLift['emailnotifications'];
          $infoArray['userneed'] = $thisLift['userneed'];
          $infoArray['needtype'] = $thisLift['needtype'];
          $infoArray['daterequired'] = $thisLift['daterequired'];
          $infoArray['createdormodified'] = $thisLift['createdormodified'];
          //Store days selected in a string
          $strdays = "";
          if($thisLift['monday']=='Y')
          $strdays .= "Monday";
          if($thisLift['tuesday']=='Y'){
           if($strdays==""){
            $strdays .= "Tuesday";
           }else{
            $strdays .= ", Tuesday";
           }
          }
          if($thisLift['wednesday']=='Y'){
           if($strdays==""){
            $strdays .= "Wednesday";
           }else{
            $strdays .= ", Wednesday";
           }
          }
          if($thisLift['thursday']=='Y'){
           if($strdays==""){
            $strdays .= "Thursday";
           }else{
            $strdays .= ", Thursday";
           }
          }          
          if($thisLift['friday']=='Y'){
           if($strdays==""){
            $strdays .= "Friday";
           }else{
            $strdays .= ", Friday";
           }
          }          
          if($thisLift['saturday']=='Y'){
           if($strdays==""){
            $strdays .= "Saturday";
           }else{
            $strdays .= ", Saturday";
           }
          }
          if($thisLift['sunday']=='Y'){
           if($strdays==""){
            $strdays .= "Sunday";
           }else{
            $strdays .= ", Sunday";
           }
          }
          $infoArray['monday'] = $thisLift['monday'];
          $infoArray['tuesday'] = $thisLift['tuesday'];
          $infoArray['wednesday'] = $thisLift['wednesday'];
          $infoArray['thursday'] = $thisLift['thursday'];
          $infoArray['friday'] = $thisLift['friday'];
          $infoArray['saturday'] = $thisLift['saturday'];
          $infoArray['sunday'] = $thisLift['sunday'];
          $infoArray['selectedays'] = $strdays;         
          $infoArray['oriuserid'] = $thisLift['oriuserid'];
          $infoArray['oristreet'] = $thisLift['oristreet'];
          $infoArray['orisuburb'] = $thisLift['orisuburb'];
          $infoArray['desuserid'] = $thisLift['desuserid'];
          $infoArray['destreet'] = $thisLift['destreet'];
          $infoArray['desuburb'] = $thisLift['desuburb'];
          $searchArray[] = $infoArray;
        }
        return json_encode(array('liftcount' => $liftCount, 'searchresults' =>  $searchArray));
    }
    function getMessages($start, $limit, $params=NULL, $read=NULL, $trash=NULL) {
         $thisuser = $this->objUser->userId();
									$where = "";
									if(is_array($params['search'])){
										$max = count($params['search']);
		        //$max=3;
										$cnt = 0;
										foreach($params['search'] as $field){
						     if($field=='messagetitle'){
												$cnt++;
											 $where .= " messagetitle LIKE '%".$params['query']."%' ";
						     }
						     if($field=='timesent'){
												$cnt++;
											 $where .= " timesent LIKE '%".$params['query']."%' ";
						     }
											if($field=='sender'){												
												$senderuserid = $this->objUser->getUserId($params['query']);
												if($senderuserid !== FALSE){
											  $cnt++;
											  $where .= " userid = '".$senderuserid."' ";
											 }
						     }
											if($cnt < $max){
												$where .= ' OR ';
											}
										}					
										$where = ' AND '.$where;		
									}
         if(!empty($start) || $start>=0 && !empty($limit)){
           if(!empty($read)){
            $sqlsearch = "SELECT * FROM `tbl_liftclub_messages` WHERE `markasread`='".$read."' AND `markasdeleted` !='1' AND `recipentuserid`='".$thisuser."'".$where." LIMIT ".$start." , ".$limit;
           }elseif(!empty($trash)){
            $sqlsearch = "SELECT * FROM `tbl_liftclub_messages` WHERE `markasdeleted`='".$trash."' AND `recipentuserid`='".$thisuser."'".$where." LIMIT ".$start." , ".$limit;
           }else{
            $sqlsearch = "SELECT * FROM `tbl_liftclub_messages` WHERE `recipentuserid`='".$thisuser."'".$where." LIMIT ".$start." , ".$limit;
           }
           $messages = $this->objDBDetails->getArray($sqlsearch);
		         return $messages;
         }else{
           if(!empty($read)){
            $sqlsearch = "SELECT * FROM `tbl_liftclub_messages` WHERE `markasread`='".$read."' AND markasdeleted !='1' AND `recipentuserid`='".$thisuser."'".$where;
           }elseif(!empty($trash)){
            $sqlsearch = "SELECT * FROM `tbl_liftclub_messages` WHERE `markasdeleted`='".$trash."' AND `recipentuserid`='".$thisuser."'".$where;
           }else{
            $sqlsearch = "SELECT * FROM `tbl_liftclub_messages` WHERE `recipentuserid`='".$thisuser."'".$where;
           }
           $messages = $this->objDBDetails->getArray($sqlsearch);
		         return $messages;
         }    
    }
    function jsonGetMessages($start, $limit, $read=NULL, $trash=NULL) 
    {
								$params["start"] = ($this->getParam("start")) ? $this->getParam("start") : null;
								$params["limit"] = ($this->getParam("limit")) ? $this->getParam("limit") : null;
								$params["search"] = ($this->getParam("fields")) ? json_decode(stripslashes($this->getParam("fields"))) : null;
								$params["query"] = ($this->getParam("query")) ? $this->getParam("query") : null;
								$params["sort"] = ($this->getParam("sort")) ? $this->getParam("sort") : null;
        if(!empty($params["start"]))
        $start= $params["start"];
        if(!empty($params["limit"]))
        $limit= $params["limit"];
        $myMessages = $this->getMessages($start, $limit, $params, $read, $trash);
       	$msgCount = ( count ( $myMessages ) );
        //$str = '{"msgcount":"'.$msgCount.'","myMsgs":[';
        $searchArray = array();
        //msgid userid recipentuserid timesent markasread markasdeleted messagetitle messagebody  
        foreach($myMessages as $thisMsg){
          $infoArray = array();
          $infoArray['msgid'] = $thisMsg['id'];
          $senderusername = $thisMsg['userid'];
          if(!empty($thisMsg['userid']))
          $senderusername = $this->objUser->userName($thisMsg['userid']);
          $infoArray['sender'] = $senderusername;
          $infoArray['recipentuserid'] = $thisMsg['recipentuserid'];
          $infoArray['timesent'] = $thisMsg['timesent'];
          $infoArray['markasread'] = $thisMsg['markasread'];
          $infoArray['markasdeleted'] = $thisMsg['markasdeleted'];
          $infoArray['messagetitle'] = $thisMsg['messagetitle'];
          $infoArray['messagebody'] = $thisMsg['messagebody'];
          $searchArray[] = $infoArray;
        }
        return json_encode(array('msgcount' => $msgCount, 'searchresults' =>  $searchArray));
    }
}
?>
