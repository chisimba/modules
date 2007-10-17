<?php
	/**
	* converts distance/length measurements: centimeters, millimeters, feet,
	* yards, meters, kilometres and miles
	*
	* @author Hendry Thobela <2649282@uwc.ac.za> 
	* @author Raymond Williams <2541826@uwc.ac.za> 
	* @package convertions
	* @copyright UWC 2007
	* @filesource
	*/ 
class dist extends object
{
	public $value;

	public function init()
	{
		$this->objLanguage = $this->getObject('language', 'language');
	}

	//The following functions return a value that has been converted to miles or from miles
	public function convMilesToCentimeters($value)
	{
		$answer = ($value * 160934);
		return $answer;
	}

	public function convMilesToMillimeters($value)
	{
		$answer = ($value * 1609000);
		return $answer;
	}
	
	public function convMilesToFeet($value)
	{
		$answer = ($value * 5280);
		return $answer;
	}

	public function convMilesToYards($value)
	{
		$answer = ($value * 1760);
		return $answer;
	}

	public function convMilesToMeters($value)
	{
		$answer = ($value * 1609);
		return $answer;
	}

	public function convMilesToKilometers($value)
	{
		$answer = ($value * 1.60900);
		return $answer;
	}

	public function convCentimetersToMiles($value)
	{
		$answer = ($value * 0.000006214);
		return $answer;
	}

	public function convMillimetersToMiles($value)
	{
		$answer = ($value * 6.214e-7);
		return $answer;
	}

	public function convYardsToMiles($value)
	{
		$answer = ($value * 0.00056);
		return $answer;
	}

	public function convFeetToMiles($value)
	{
		$answer = ($value * 0.00019);
		return $answer;
	}

	public function convMetersToMiles($value)
	{
		$answer = ($value * 0.00062);
		return $answer;
	}

	public function convKilometersToMiles($value)
	{
		$answer = ($value * 0.62140);
		return $answer;
	}

	//the function below does the actual conversion
	public function doConversion($value = NULL, $from = NULL, $to = NULL)
	{
		/**
		* 1 = Centimeters
		* 2 = Millimeters
		* 3 = Feet
		* 4 = Yards
		* 5 = Meters
		* 6 = Kilometers
		* 7 = Miles
		* 
		* The variable $tempVal is used in cases where there is no direct conversion from one value to another
		* 
		*/
        // Check to See if the Value is empty and if empty ,returns an error message
		if(empty($value)){
				return $this->objLanguage->languageText('mod_conversions_insertError', 'conversions');
		}
		elseif($from == $to && !empty($value))
		{
			return $this->objLanguage->languageText('mod_conversions_itselfError', 'conversions');
		}
        
		elseif($from == "1" && $to == "2")
		{
			$tempVal = $this->convCentimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symCM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMillimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symMM", "conversions").".";
		}
       
		elseif($from == "1" && $to == "3")
		{
			$tempVal = $this->convCentimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symCM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToFeet($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symFT", "conversions").".";
		}
         
		elseif($from == "1" && $to == "4")
		{
			$tempVal = $this->convCentimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symCM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToYards($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symYD", "conversions").".";
		}
        
		elseif($from == "1" && $to == "5")
		{
			$tempVal = $this->convCentimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symCM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symM", "conversions").".";
		}
        
		elseif($from == "1" && $to == "6")
		{
			$tempVal = $this->convCentimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symCM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToKilometers($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symKM", "conversions").".";
		}
        
		elseif($from == "1" && $to == "7")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symCM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convCentimetersToMiles($value)),2)." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions").".";
		}
        
		elseif($from == "2" && $to == "1")
		{
			$tempVal = $this->convMillimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symMM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToCentimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symCM", "conversions").".";
		}
        
		elseif($from == "2" && $to == "3")
		{
			$tempVal = $this->convMillimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symMM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToFeet($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symFT", "conversions").".";
		}
		elseif($from == "2" && $to == "4")
		{
			$tempVal = $this->convMillimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symMM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToYards($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symYD", "conversions").".";
		}
        
		elseif($from == "2" && $to == "5")
		{
			$tempVal = $this->convMillimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symMM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symM", "conversions").".";
		}
        
		elseif($from == "2" && $to == "6")
		{
			$tempVal = $this->convMillimetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symMM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToKilometers($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symKM", "conversions").".";
		}
        
		elseif($from == "2" && $to == "7")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symMM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMillimetersToMiles($value)),2)." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions").".";
		}
        
		elseif($from == "3" && $to == "1")
		{
			$tempVal = $this->convFeetToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symFT", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToCentimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symCM", "conversions").".";
		}
        
		elseif($from == "3" && $to == "2")
		{
			$tempVal = $this->convFeetToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symFT", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMillimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symMM", "conversions").".";
		}
        
		elseif($from == "3" && $to == "4")
		{
			$tempVal = $this->convFeetToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symFT", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToYards($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symYD", "conversions").".";
		}
        
		elseif($from == "3" && $to == "5")
		{
			$tempVal = $this->convFeetToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symFT", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symM", "conversions").".";
		}
        
		elseif($from == "3" && $to == "6")
		{
			$tempVal = $this->convFeetToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symFT", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToKilometers($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symKM", "conversions").".";
		}
        
		elseif($from == "3" && $to == "7")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symFT", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convFeetToMiles($value)),2)." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions").".";
		}
        
		elseif($from == "4" && $to == "1")
		{
			$tempVal = $this->convYardsToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symYD", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToCentimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symCM", "conversions").".";
		}
        
		elseif($from == "4" && $to == "2")
		{
			$tempVal = $this->convYardsToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symYD", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMillimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symMM", "conversions").".";
		}
        
		elseif($from == "4" && $to == "3")
		{
			$tempVal = $this->convYardsToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symYD", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToFeet($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symFT", "conversions").".";
		}
        
		elseif($from == "4" && $to == "5")
		{
			$tempVal = $this->convYardsToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symYD", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symM", "conversions").".";
		}
         
		elseif($from == "4" && $to == "6")
		{
			$tempVal = $this->convYardsToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symYD", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToKilometers($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symKM", "conversions").".";
		}
        
		elseif($from == "4" && $to == "7")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symYD", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convYardsToMiles($value)),2)." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions").".";
		}
        
		elseif($from == "5" && $to == "1")
		{
			$tempVal = $this->convMetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToCentimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symCM", "conversions").".";
		}
        
		elseif($from == "5" && $to == "2")
		{
			$tempVal = $this->convMetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMillimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symMM", "conversions").".";
		}
        
		elseif($from == "5" && $to == "3")
		{
			$tempVal = $this->convMetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToFeet($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symFT", "conversions").".";
		}

		elseif($from == "5" && $to == "4")
		{
			$tempVal = $this->convMetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToYards($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symYD", "conversions").".";
		}

		elseif($from == "5" && $to == "6")
		{
			$tempVal = $this->convMetersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToKilometers($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symKM", "conversions").".";
		}

		elseif($from == "5" && $to == "7")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMetersToMiles($value)),2)." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions").".";
		}

		elseif($from == "6" && $to == "1")
		{
			$tempVal = $this->convKilometersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symKM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToCentimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symCM", "conversions").".";
		}

		elseif($from == "6" && $to == "2")
		{
			$tempVal = $this->convKilometersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symKM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMillimeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symMM", "conversions").".";
		}

		elseif($from == "6" && $to == "3")
		{
			$tempVal = $this->convKilometersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symKM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToFeet($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symFT", "conversions").".";
		}

		elseif($from == "6" && $to == "4")
		{
			$tempVal = $this->convKilometersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symKM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToYards($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symYD", "conversions").".";
		}

		elseif($from == "6" && $to == "5")
		{
			$tempVal = $this->convKilometersToMiles($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symKM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMeters($tempVal)),2).$this->objLanguage->languageText("mod_conversions_symM", "conversions").".";
		}

		elseif($from == "6" && $to == "7")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symKM", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convKilometersToMiles($value)),2)." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions").".";
		}

		elseif($from == "7" && $to == "1")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToCentimeters($value)),2).$this->objLanguage->languageText("mod_conversions_symCM", "conversions").".";
		}

		elseif($from == "7" && $to == "2")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMillimeters($value)),2).$this->objLanguage->languageText("mod_conversions_symMM", "conversions").".";
		}

		elseif($from == "7" && $to == "3")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToFeet($value)),2).$this->objLanguage->languageText("mod_conversions_symFT", "conversions").".";
		}

		elseif($from == "7" && $to == "4")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToYards($value)),2).$this->objLanguage->languageText("mod_conversions_symYD", "conversions").".";
		}

		elseif($from == "7" && $to == "5")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToMeters($value)),2).$this->objLanguage->languageText("mod_conversions_symM", "conversions").".";
		}

		elseif($from == "7" && $to == "6")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_Miles", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round(($this->convMilesToKilometers($value)),2).$this->objLanguage->languageText("mod_conversions_symKM", "conversions").".";
		}
		else{
			return  $this->objLanguage->languageText('mod_conversions_unknownError', 'conversions');
		}
	}
}
?>
