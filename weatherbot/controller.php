<?php

class weatherbot extends controller
{
    private $objCurl;

    public function init()
    {
        $this->objCurl = $this->getObject('curl', 'utilities');
    }

    public function dispatch()
    {
        $location = $this->getParam('body');
        $uri = 'http://www.google.com/ig/api?weather='.urlencode($location);
        $xml = $this->objCurl->exec($uri);
        $dom = new SimpleXMLElement($xml);
        if (is_object($dom)) {
            $weather = $dom->weather->current_conditions;
            if ($weather) {
                echo $dom->weather->forecast_information->city['data'];
                echo ': ';
                echo $weather->temp_c['data'];
                echo '°C / ';
                echo $weather->temp_f['data'];
                echo '°F, ';
                echo $weather->condition['data'];
                echo ', ';
                echo $weather->humidity['data'];
                echo ', ';
                echo $weather->wind_condition['data'];
            } else {
                echo 'Could not find weather data for your location.';
            }
        } else {
            echo 'Could not contact the weather service.';
        }
    }

    public function requiresLogin()
    {
        return FALSE;
    }
}
