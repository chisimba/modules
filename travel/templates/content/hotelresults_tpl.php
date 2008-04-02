<?php

$this->loadClass('form','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('link','htmlelements');

//Add the templage heading to the main layer
$objH = $this->getObject('htmlheading', 'htmlelements');
//Heading H3 tag
$objH->type=3; 
$objH->str = $this->objLanguage->languageText("mod_travel_hotelresults","travel")." ".ucwords(strtolower($cityString));

if (isset($countryString)) {
    $results = $this->objHotels->citySearch($cityString,$page,$countryString);
    $country = $this->objCountryCodes->getRow('code',$countryString);
    $objH->str .= ", {$country['name']}";
} else {
    $results = $this->objHotels->citySearch($cityString,$page);
}
$cIn = $this->getParam('checkin');
$cOut = $this->getParam('checkout');

list($iYear,$iMonth,$iDay) = split('-',$cIn);
list($oYear,$oMonth,$oDay) = split('-',$cOut);
$interval = mktime(0,0,0,$oMonth,$oDay,$oYear) - mktime(0,0,0,$iMonth,$iDay,$iYear);

$nights = $interval/86400;
if ($nights == 1) {
    $nights .= " ".$this->objLanguage->languageText('word_night');
} else {
    $nights .= " ".$this->objLanguage->languageText('word_nights');
}

if (($rooms = $this->getParam('searchRooms')) == 1) {
    $rooms .= " ".$this->objLanguage->languageText('word_room');
} else {
    $rooms .= " ".$this->objLanguage->languageText('word_rooms');
}

if (($adults = $this->getParam('searchAdults')) == 1) {
    $adults .= " ".$this->objLanguage->languageText('word_adult');
} else {
    $adults .= " ".$this->objLanguage->languageText('word_adults');
}

switch ($kids = $this->getParam('searchChildren')) {
    case 0:
        $kids ='';
        break;
    case 1:
        $kids = ", $kids ".$this->objLanguage->languageText('word_child');
        break;
    default:
        $kids = ", $kids ".$this->objLanguage->languageText('word_children');
        break;
}

$change = $this->getObject('link','htmlelements');
$change->link($this->uri(array('action'=>'search hotels')));
$change->link = $this->objLanguage->languageText('word_change');

$recapStr = "<span class='minute'>".$this->objLanguage->languageText('mod_travel_checkin','travel').': '.str_replace('-','/',$cIn).
            ", ".$this->objLanguage->languageText('mod_travel_checkout','travel').": ".str_replace('-','/',$cOut).
            ", $rooms, $nights, $adults$kids | ".$change->show()."</span>";
            
            
$list = "<div id='hotel_results'>";

foreach ($results as $hotel) {
    $hotel_location = '';
    if ($hotel['address3']) {
        $hotel_location = "{$hotel['address3']}, ";
    }
    $hotel_location .= $hotel['city'];
    $name = htmlentities(ucwords(strtolower(strip_tags($hotel['name']))));
    $image = $this->objHotelImages->getImage($hotel['id']);
    $uri = $this->uri(array('action'=>'view hotel','id'=>$hotel['id']));
    $list .= "<div class='hotel_match'>
                    <div class='star_rating'></div>
                    <div class='hotel_name'><a href='$uri'>{$name}</a></div>
                    <div class='hotel_picture'><a href='$uri'><img src='{$image['thumbnail']}' alt='{$hotel['name']} - {$image['caption']}' /></a></div>
                    <div class='hotel_info'><strong>$hotel_location</strong></div>
              </div><br />";
}

$list .= "</div>";
$link = new link($this->uri(array('action'=>'search hotels')));
$link->link = $this->objLanguage->languageText('word_back');

$content = $objH->show().$recapStr.$list.$link->show();

echo $content;
?>