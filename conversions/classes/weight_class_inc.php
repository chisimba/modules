<?php
/**
 * converts weight measurements: kilograms, grams, metric tons, pounds and ounces
 *
 * @author     Faizel Lodewyk <2528194@uwc.ac.za>
 * @author     Keanon Wagner <2456923@uwc.ac.za>
 * @package    convertions
 * @copyright  UWC 2007
 * @filesource
 */
class weight extends object
{
    /**
     * Constructor method to instantiate objects and get variables
     *
     * @return void
     * @access public
     */
    public function init() 
    {
        $this->objLanguage = $this->getObject('language', 'language');
    }
    /**
     * The following function converts Kilograms to Metric Ton
     *
     * @param  numerical value ($value)
     * @return metric ton equivalent
     * @access public
     */
    public function convKilogramsToMetricton($value = NULL) 
    {
        $answer = ($value*0.001);
        return $answer;
    }
    /**
     * The following function converts Metric Ton to Kilograms
     *
     * @param  numerical value ($value)
     * @return kilogams equivalent
     * @access public
     */
    public function convMetrictonToKilograms($value = NULL) 
    {
        $answer = ($value*1000);
        return $answer;
    }
    /**
     * The following function converts Grams to Metric Ton
     *
     * @param  numerical value ($value)
     * @return cubic metric ton equivalent
     * @access public
     */
    public function convGramsToMetricton($value = NULL) 
    {
        $answer = ($value*0.00001);
        return $answer;
    }
    /**
     * The following function converts Metric Ton to Grams
     *
     * @param  numerical value ($value)
     * @return cubic grams equivalent
     * @access public
     */
    public function convMetrictonToGrams($value = NULL) 
    {
        $answer = ($value*1000000);
        return $answer;
    }
    /**
     * The following function converts Pounds to Metric Ton
     *
     * @param  numerical value ($value)
     * @return metric ton equivalent
     * @access public
     */
    public function convPoundsToMetricton($value = NULL) 
    {
        $answer = ($value*0.000454);
        return $answer;
    }
    /**
     * The following function converts Metric Ton to Pounds
     *
     * @param  numerical value ($value)
     * @return pounds equivalent
     * @access public
     */
    public function convMetrictonToPounds($value = NULL) 
    {
        $answer = ($value*2204.6);
        return $answer;
    }
    /**
     * The following function converts Ounces to Metric Ton
     *
     * @param  numerical value ($value)
     * @return metric ton equivalent
     * @access public
     */
    public function convOuncesToMetricton($value = NULL) 
    {
        $answer = ($value*0.00045);
        return $answer;
    }
    /**
     * The following function converts Metric Ton to Ounces
     *
     * @param  numerical value ($value)
     * @return ounces equivalent
     * @access public
     */
    public function convMetrictonToOunces($value = NULL) 
    {
        $answer = ($value*2222.2222);
        return $answer;
    }
    /**
     * The following function below does the actual conversion
     *
     * @param  numerical value $value
     * @param  string $from  Unit to be converted from
     * @param  string $to    Unit to be converted to
     * @return converted value
     * @access public
     */
    public function doConversion($value = NULL, $from = NULL, $to = NULL) 
    {
        /**
         * 1 = Kilograms
         * 2 = Grams
         * 3 = Metric Tons
         * 4 = Pounds
         * 5 = Ounces
         *
         * The variable $tempVal is used in cases where there is no direct conversion from one value to another
         *
         */
        // Check to see if $value is a numerical vaulue
        if (!is_numeric($value)) {
            return $this->objLanguage->languageText('mod_conversions_insertNumError', 'conversions');
        }
        // Checks to see if $from and $to are equal
        elseif ($from == $to && !empty($value)) {
            return $this->objLanguage->languageText('mod_conversions_itselfError', 'conversions');
        }
        //Does the convertion from Kilograms to Grams and returns the answer
        elseif ($from == "1" && $to == "2") {
            $tempVal = $this->convKilogramsToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symKG", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToGrams($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symG", "conversions") . ".";
        }
        //Does the convertion from Kilograms to Metric Ton and returns the answer
        elseif ($from == "1" && $to == "3") {
            return $value . $this->objLanguage->languageText("mod_conversions_symKG", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convKilogramsToMetricton($value) , 2) . " " . $this->objLanguage->languageText("mod_conversions_symTONS", "conversions") . ".";
        }
        //Does the convertion from Kilograms to Pounds and returns the answer
        elseif ($from == "1" && $to == "4") {
            $tempVal = $this->convKilogramsToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symKG", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToPounds($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symLBS", "conversions") . ".";
        }
        //Does the convertion from Kilograms to Ounces and returns the answer
        elseif ($from == "1" && $to == "5") {
            $tempVal = $this->convKilogramsToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symKG", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToOunces($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symOZ", "conversions") . ".";
        }
        //Does the convertion from Grams to Kilograms and returns the answer
        elseif ($from == "2" && $to == "1") {
            $tempVal = $this->convGramsToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symG", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToKilograms($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symKG", "conversions") . ".";
        }
        //Does the convertion from Grams to Metric Ton and returns the answer
        elseif ($from == "2" && $to == "3") {
            return $value . $this->objLanguage->languageText("mod_conversions_symG", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convGramsToMetricton($value) , 2) . " " . $this->objLanguage->languageText("mod_conversions_symTONS", "conversions") . ".";
        }
        //Does the convertion from Grams to Pounds and returns the answer
        elseif ($from == "2" && $to == "4") {
            $tempVal = $this->convGramsToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symG", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToPounds($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symLBS", "conversions") . ".";
        }
        //Does the convertion from Grams to Ounces and returns the answer
        elseif ($from == "2" && $to == "5") {
            $tempVal = $this->convGramsToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symG", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToOunces($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symOZ", "conversions") . ".";
        }
        //Does the convertion from Metric Ton to Kilograms and returns the answer
        elseif ($from == "3" && $to == "1") {
            return $value . " " . $this->objLanguage->languageText("mod_conversions_symTONS", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToKilograms($value) , 2) . $this->objLanguage->languageText("mod_conversions_symKG", "conversions") . ".";
        }
        //Does the convertion from Metric Ton to Grams and returns the answer
        elseif ($from == "3" && $to == "2") {
            return $value . " " . $this->objLanguage->languageText("mod_conversions_symTONS", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToGrams($value) , 2) . $this->objLanguage->languageText("mod_conversions_symG", "conversions") . ".";
        }
        //Does the convertion from Metric Ton to Pounds and returns the answer
        elseif ($from == "3" && $to == "4") {
            return $value . " " . $this->objLanguage->languageText("mod_conversions_symTONS", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToPounds($value) , 2) . $this->objLanguage->languageText("mod_conversions_symLBS", "conversions") . ".";
        }
        //Does the convertion from Metric Ton to Ounces and returns the answer
        elseif ($from == "3" && $to == "5") {
            return $value . " " . $this->objLanguage->languageText("mod_conversions_symTONS", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToOunces($value) , 2) . $this->objLanguage->languageText("mod_conversions_symOZ", "conversions") . ".";
        }
        //Does the convertion from Pounds to Kilograms and returns the answer
        elseif ($from == "4" && $to == "1") {
            $tempVal = $this->convPoundsToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symLBS", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToKilograms($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symKG", "conversions") . ".";
        }
        //Does the convertion from Pounds to Grams and returns the answer
        elseif ($from == "4" && $to == "2") {
            $tempVal = $this->convPoundsToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symLBS", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToGrams($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symG", "conversions") . ".";
        }
        //Does the convertion from Pounds to Metric Ton and returns the answer
        elseif ($from == "4" && $to == "3") {
            return $value . $this->objLanguage->languageText("mod_conversions_symLBS", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convPoundsToMetricton($value) , 2) . " " . $this->objLanguage->languageText("mod_conversions_symTONS", "conversions") . ".";
        }
        //Does the convertion from Pounds to Ounces and returns the answer
        elseif ($from == "4" && $to == "5") {
            $tempVal = $this->convPoundsToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symLBS", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToOunces($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symOZ", "conversions") . ".";
        }
        //Does the convertion from Ounces to Kilograms and returns the answer
        elseif ($from == "5" && $to == "1") {
            $tempVal = $this->convOuncesToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symOZ", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToKilograms($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symKG", "conversions") . ".";
        }
        //Does the convertion from Ounces to Grams and returns the answer
        elseif ($from == "5" && $to == "2") {
            $tempVal = $this->convOuncesToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symOZ", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToGrams($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symG", "conversions") . ".";
        }
        //Does the convertion from Ounces to Metric Ton and returns the answer
        elseif ($from == "5" && $to == "3") {
            return $value . $this->objLanguage->languageText("mod_conversions_symOZ", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convOuncesToMetricton($value) , 2) . " " . $this->objLanguage->languageText("mod_conversions_symTONS", "conversions") . ".";
        }
        //Does the convertion from Ounces to Ounces and returns the answer
        elseif ($from == "5" && $to == "4") {
            $tempVal = $this->convOuncesToMetricton($value);
            return $value . $this->objLanguage->languageText("mod_conversions_symOZ", "conversions") . " " . $this->objLanguage->languageText("mod_conversions_convertedTo", "conversions") . " " . round($this->convMetrictonToOunces($tempVal) , 2) . $this->objLanguage->languageText("mod_conversions_symOZ", "conversions") . ".";
        }
        //Checks to see if $value is NULL
        else {
            return $this->objLanguage->languageText('mod_conversions_insertError', 'conversions');
        }
    }
}
?>