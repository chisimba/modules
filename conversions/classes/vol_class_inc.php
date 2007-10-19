<?php
/**
 * converts volume measurements: Litres, Millilitres, Cubic Decimeters, Cubic Meters & Cubic Centimeters
 *
 * @author Nonhlanhla Gangeni <2539399@uwc.ac.za>
 * @package convertions
 * @copyright UWC 2007
 * @filesource
 */
class vol extends object
{
    public $value;
    public function init() 
    {
        $this->objLanguage = $this->getObject('language', 'language');
    }
    //The following functions return a value that has been converted to Cubic Meters or from Cubic Meters
    public function convLitresToCubicMeters($value = NULL) 
    {
        $answer = $value/10000;
        return $answer;
    }
    //Following function converts MillilitresToCubicMeters
    public function convMillilitresToCubicMeters($value = NULL) 
    {
        $answer = $value/10000000;
        return $answer;
    }
    //Following function converts CubicMetersToLitres
    public function convCubicMetersToLitres($value = NULL) 
    {
        $answer = $value*10000;
        return $answer;
    }
    //Following function converts CubicMetersToMillilitres
    public function convCubicMetersToMillilitres($value = NULL) 
    {
        $answer = $value*10000000;
        return $answer;
    }
    //Following function converts CubicDecimetersToCubicMeters
    public function convCubicDecimetersToCubicMeters($value = NULL) 
    {
        $answer = $value/1000;
        return $answer;
    }
    // Following function converts CubicMetersToCubicDecimeters
    public function convCubicMetersToCubicDecimeters($value = NULL) 
    {
        $answer = $value*1000;
        return $answer;
    }
    // Following function converts CubicCentimetersToCubicMeters
    public function convCubicCentimetersToCubicMeters($value = NULL) 
    {
        $answer = $value/1000000;
        return $answer;
    }
    // Following function converts CubicMetersToCubicCentimeters
    public function convCubicMetersToCubicCentimeters($value = NULL) 
    {
        $answer = $value*1000000;
        return $answer;
    }
    //the function below does the actual conversion
    public function doConversion($value = NULL, $from = NULL, $to = NULL) 
    {
        /**
         * 1 = Litres
         * 2 = Millilitres
         * 3 = Cubic Decimeter
         * 4 = Cubic Meter
         * 5 = Cubic Centimeter
         *
         * The variable $tempVal is used in cases where there is no direct conversion from one value to another
         *
         */
        //the following error trapping prohibits invalid or null values to be executed
        if (!is_numeric($value)) {
            return $this->objLanguage->languageText('mod_conversions_insertNumError', 'conversions');
        } elseif ($from == $to && !empty($value)) {
            return $this->objLanguage->languageText('mod_conversions_itselfError', 'conversions');
        }
        //Calling LitrestoCubicMeters Function
        elseif ($from == "1" && $to == "2") {
            $tempVal = $this->convLitresToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symL", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToMillilitres($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symML", "conversions") . ".";
        } elseif ($from == "1" && $to == "3") {
            $tempVal = $this->convLitresToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symL", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToCubicDecimeters($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symDM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "1" && $to == "4") {
            return $value . $this->objLanguage->languageText("mod_conversions_symL", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convLitresToCubicMeters($value) , 2) . $this->objLanguage->languageText("mod_conversions_symM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "1" && $to == "5") {
            $tempVal = $this->convLitresToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symL", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToCubicCentimeters($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symCM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "2" && $to == "1") {
            $tempVal = $this->convMillilitresToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symML", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToLitres($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symL", "conversions") . ".";
        } elseif ($from == "2" && $to == "3") {
            $tempVal = $this->convMillilitresToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symML", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToCubicDecimeters($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symDM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "2" && $to == "4") {
            return $value . $this->objLanguage->languageText("mod_conversions_symML", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMillilitresToCubicMeters($value) , 2) . $this->objLanguage->languageText("mod_conversions_symM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "2" && $to == "5") {
            $tempVal = $this->convMillilitersToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symML", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToCubicCentimeters($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symCM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "3" && $to == "4") {
            return $value . $this->objLanguage->languageText("mod_conversions_symDM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicDecimetersToCubicMeters($value) , 2) . $this->objLanguage->languageText("mod_conversions_symM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "4" && $to == "1") {
            return $value . $this->objLanguage->languageText("mod_conversions_symM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToLitres($value) , 2) . $this->objLanguage->languageText("mod_conversions_symL", "conversions") . ".";
        } elseif ($from == "4" && $to == "2") {
            return $value . $this->objLanguage->languageText("mod_conversions_symM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToMillilitres($value) , 2) . $this->objLanguage->languageText("mod_conversions_symML", "conversions") . ".";
        } elseif ($from == "4" && $to == "3") {
            return $value . $this->objLanguage->languageText("mod_conversions_symM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToCubicDecimeters($value) , 2) . $this->objLanguage->languageText("mod_conversions_symDM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "5" && $to == "4") {
            return $value . $this->objLanguage->languageText("mod_conversions_symCM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicCentimetersToCubicMeters($value) , 2) . $this->objLanguage->languageText("mod_conversions_symM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "4" && $to == "5") {
            return $value . $this->objLanguage->languageText("mod_conversions_symM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToCubicCentimeters($value) , 2) . $this->objLanguage->languageText("mod_conversions_symCM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "5" && $to == "3") {
            $tempVal = $this->convCubicCentimetersToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToCubicDecimeters($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symDM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "3" && $to == "5") {
            $tempVal = $this->convCubicDecimetersToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symDM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToCubicCentimeters($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symCM", "conversions") . "<sup>3</sup>" . ".";
        } elseif ($from == "3" && $to == "1") {
            $tempVal = $this->convCubicDecimetersToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symDM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToLitres($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symL", "conversions") . ".";
        } elseif ($from == "3" && $to == "2") {
            $tempVal = $this->convCubicDecimetersToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symDM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToMillilitres($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symMM", "conversions") . ".";
        } elseif ($from == "5" && $to == "1") {
            $tempVal = $this->convCubicCentimetersToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symCM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToLitres($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symL", "conversions") . ".";
        } elseif ($from == "5" && $to == "2") {
            $tempVal = $this->convCubicCentimetersToCubicMeters($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symCM", "conversions") . "<sup>3</sup>" . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convCubicMetersToMillilitres($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symML", "conversions") . ".";
        } else {
            return $this->objLanguage->languageText('mod_conversions_insertError', 'conversions');
        }
    }
}
?>
