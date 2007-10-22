<?php
/**
 * converts temperature measurements: Kelvin, celcius, and fahrenheit
 *
 * @author     Nazheera Khan <2524939@uwc.ac.za>
 * @author     Ebrahim Vasta <2623441@uwc.ac.za>
 * @package    convertions
 * @copyright  UWC 2007
 * @filesource
 */
class temp extends object
{

    /**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @return void  
     * @access public
     */
    public function init() 
    {
        $this->objLanguage = $this->getObject('language', 'language');
    }
    //The following functions return a value that has been converted to Celsius or from Celsius


    /**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $value Parameter description (if any) ...
     * @return unknown Return description (if any) ...
     * @access public 
     */
    public function convCelsToFahren($value = NULL) 
    {
        $answer = ((9/5) *($value) +32);
        return $answer;
    }

    /**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $value Parameter description (if any) ...
     * @return unknown Return description (if any) ...
     * @access public 
     */
    public function convCelToKel($value = NULL) 
    {
        $answer = $value+273.15;
        return $answer;
    }

    /**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  number  $value Parameter description (if any) ...
     * @return unknown Return description (if any) ...
     * @access public 
     */
    public function convFahrenToCels($value = NULL) 
    {
        $answer = (5/9) *($value-32);
        return $answer;
    }

    /**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  number  $value Parameter description (if any) ...
     * @return unknown Return description (if any) ...
     * @access public 
     */
    public function convKelToCels($value = NULL) 
    {
        $answer = $value-273.15;
        return $answer;
    }
    //the function below does the actual conversion


    /**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  string $value Parameter description (if any) ...
     * @param  string $from  Parameter description (if any) ...
     * @param  string $to    Parameter description (if any) ...
     * @return mixed  Return description (if any) ...
     * @access public
     */
    public function doConversion($value = NULL, $from = NULL, $to = NULL) 
    {
        /**
         * 1 = Celsius
         * 2 = Fahrenheit
         * 3 = Kelvin
         *
         * The variable $tempVal is used in cases where there is no direct convertion from one value to another
         *
         */
        if (!is_numeric($value)) {
            return $this->objLanguage->languageText('mod_conversions_insertNumError', 'conversions');
        } elseif ($from == $to && !empty($value)) {
            return $this->objLanguage->languageText('mod_conversions_itselfError', 'conversions');
        } elseif ($from == "1" && $to == "2") {
            return $value . " " . $this->objLanguage->languageText("mod_conversions_Celsius", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round(($this->convCelsToFahren($value)) , 2) . " " . $this->objLanguage->languageText("mod_conversions_Fahrenheit", "conversions");
        } elseif ($from == "2" && $to == "1") {
            return $value . " " . $this->objLanguage->languageText("mod_conversions_Fahrenheit", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round(($this->convFahrenToCels($value)) , 2) . " " . $this->objLanguage->languageText("mod_conversions_Celsius", "conversions") . ".";
        } elseif ($from == "2" && $to == "3") {
            $tempVal = $this->convFahrenToCels($value);
            return $value . " " . $this->objLanguage->languageText("mod_conversions_Fahrenheit", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round(($this->convCelToKel($tempVal)) , 2) . " " . $this->objLanguage->languageText("mod_conversions_Kelvin", "conversions") . ".";
        } elseif ($from == "3" && $to == "2") {
            $tempVal = $this->convKelToCels($value);
            return $value . " " . $this->objLanguage->languageText("mod_conversions_Kelvin", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round(($this->convCelsToFahren($tempVal)) , 2) . " " . $this->objLanguage->languageText("mod_conversions_Fahrenheit", "conversions") . ".";
        } elseif ($from == "1" && $to == "3") {
            return $value . " " . $this->objLanguage->languageText("mod_conversions_Celsius", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round(($this->convCelToKel($value)) , 2) . " " . $this->objLanguage->languageText("mod_conversions_Kelvin", "conversions") . ".";
        } elseif ($from == "3" && $to == "1") {
            return $value . " " . $this->objLanguage->languageText("mod_conversions_Kelvin", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round(($this->convKelToCels($value)) , 2) . " " . $this->objLanguage->languageText("mod_conversions_Celsius", "conversions") . ".";
        } else {
            return $this->objLanguage->languageText('mod_conversions_insertError', 'conversions');
        }
    }
}
?>
