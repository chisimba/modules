<?php
   /**
    * converts temperature measurements: Kelvin, celcius, and fahrenheit 
    *
    * @author Nazheera Khan <2524939@uwc.ac.za> 
    * @author Ebrahim Vasta <2623441@uwc.ac.za> 
    * @package convertions
    * @copyright UWC 2007
    * @filesource
    */ 
class temp extends object
{
    public $val;

    public function init()
    {
            $this->objLanguage = $this->getObject('language', 'language');
    }

    public function setup($val = NULL)
    {
      $this->val = $val;
    }

    public function convCelsToFahren($val = NULL)
    {
        $answer = ((9/5) * ($val)+ 32) ;
        return $answer;
    }

    public function convCelToKel($val = NULL)
    {
        $answer = $val + 273.15;
        return $answer;
    }

    public function convFahrenToCels($val = NULL)
    {
        $answer = (5 / 9) * ($val - 32);
        return $answer;
    }

    public function convKelToCels($val = NULL)
    {
        $answer = $val - 273.15; 
        return $answer;
    }

    public function doConversion($val = NULL, $from = NULL, $to = NULL)
    {
	if(empty($val)){
   	    	return $this->objLanguage->languageText('mod_conversions_insertError', 'conversions');
    	}
	elseif($from == $to && !empty($val))
	{
    	    return $this->objLanguage->languageText('mod_conversions_itselfError', 'conversions');
	}
    	elseif($from == "1" && $to == "2")
    	{
    	    return  $val." ".$this->objLanguage->languageText("mod_conversions_Celsius", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convCelsToFahren($val)),2)." ".$this->objLanguage->languageText("mod_conversions_Fahrenheit", "conversions");
    	}
    	elseif($from == "2" && $to == "1"){
    	    return  $val." ".$this->objLanguage->languageText("mod_conversions_Fahrenheit", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convFahrenToCels($val)),2)." ".$this->objLanguage->languageText("mod_conversions_Celsius", "conversions").".";
    	}
    	elseif($from == "2" && $to == "3")
    	{
			$tempVal = $this->convFahrenToCels($val);
    	    return  $val." ".$this->objLanguage->languageText("mod_conversions_Fahrenheit", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convCelToKel($tempVal)),2)." ".$this->objLanguage->languageText("mod_conversions_Kelvin", "conversions").".";
    	}
    	elseif($from == "3" && $to == "2")
    	{
			$tempVal = $this->convKelToCels($val);
    	    return $val." ".$this->objLanguage->languageText("mod_conversions_Kelvin", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convCelsToFahren($tempVal)),2)." ".$this->objLanguage->languageText("mod_conversions_Fahrenheit", "conversions").".";
    	}
    	elseif($from == "1" && $to == "3")
    	{
    	    return  $val." ".$this->objLanguage->languageText("mod_conversions_Celsius", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convCelToKel($val)),2)." ".$this->objLanguage->languageText("mod_conversions_Kelvin", "conversions").".";
    	}
 		elseif($from == "3" && $to == "1")
        {
           	return  $val." ".$this->objLanguage->languageText("mod_conversions_Kelvin", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convKelToCels($val)),2)." ".$this->objLanguage->languageText("mod_conversions_Celsius", "conversions").".";
        }
        else{
           	return  $this->objLanguage->languageText('mod_conversions_unknownError', 'conversions');
        }
    }
}
?>
