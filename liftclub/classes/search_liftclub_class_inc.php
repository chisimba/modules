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
    function getLiftsOffered($city, $start, $limit) 
    {
         if(!empty($start) && !empty($limit)){
           $destiny = $this->objDBDetails->getAll("WHERE userneed ='offer' LIKE '%".$city."%' LIMIT ".$start." , ".$limit);
           $sqlsearch = 'select det.userid, det.times, det.additionalinfo, det.specialoffer, det.emailnotifications, det.userneed, det.needtype, det.daterequired, det.createdormodified, det.monday, det.tuesday, det.wednesday, det.thursday, det.friday, det.saturday, det.sunday, ori.userid, ori.street, ori.suburb, des.userid, des.street, des.suburb from tbl_liftclub_details as det, tbl_liftclub_origin as ori, tbl_liftclub_destiny as des where det.userid=ori.userid AND ori.userid=des.userid AND des.userid=det.userid';
		         return $this->getAll("WHERE city LIKE '%".$city."%' LIMIT ".$start." , ".$limit);
         }else{
		         return $this->getAll("WHERE city LIKE '%".$city."%'");
         }
    }
 
     function jsonLiftsOffered($city, $start, $limit) 
    {
        $myCities = $this->getCities($city, $start, $limit);
       	$cityCount = ( count ( $myCities ) );
        $str = '{"citycount":"'.$cityCount.'","searchedcities":[';
        $searchArray = array();
        foreach($myCities as $thisCity){
          $infoArray = array();
          $infoArray['id'] = $thisCity['id'];
          $infoArray['city'] = $thisCity['city'];
          $searchArray[] = $infoArray;
        }
        return json_encode(array('citycount' => $cityCount, 'searchresults' =>  $searchArray));
    }
}
?>
