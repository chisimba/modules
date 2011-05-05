<?php

require ('GoogleMapAPI-2.5/GoogleMapAPI.class.php');
$map = new GoogleMapAPI('map');
class googlemapapi extends object {

    function init() {
      
// setup database for geocode caching
        $this->map->setDSN('mysql://root:myprof123@localhost/unesco_oer');

// enter YOUR Google Map Key
        $map->setAPIKey('ABQIAAAAFo5PTfqWH9oGt-O0y4ZwHhQa0AoLrpozHyUSqPJ3C2GK7I7TexSw5J4Jm1UufM1pMCwCIgXg80aACg');
    }

    public function addMarkerByCoords($long, $lat, $title, $desc) {
        $this->map->addMarkerByCoords($long, $lat, $title,'<b>$desc</b>');
    }

    public function show() {
        $map->fetchURL("http://code.google.com/apis/maps/index.html#Geocoding_HTTP_Request");
        $map->disableDirections();
        $result = $this->map->printHeaderJS();
        $result.= $this->map->printMapJS();

        $result.=$this->map->printMap();
        $result.=$this->map->printSidebar();
        return $result;
    }

}
?>
