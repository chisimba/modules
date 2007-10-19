<?php
   /**
    * converts weight measurements: Kilograms, grams, metric tons, pounds and ounces 
    *
    * @author  Faizel Lodewyk<2528194@uwc.ac.za> 
    * @author  Keanon Wagner<2456923@uwc.ac.za> 
    * @package convertions
    * @copyright UWC 2007
    * @filesource
    */ 
class weight extends object
{
    public $value ;

    public function init()
    {
            $this->objLanguage = $this->getObject('language', 'language');
    }
   	public function kilogramsToMetricton($value = NULL)
	{
		$answer = ($value * 0.001);
		return $answer;
	}
	public function metrictonToKilograms($value = NULL)
	{
		$answer = ($value * 1000);
		return $answer;
	}
	public function gramsToMetricton($value = NULL)
	{
		$answer = ($value * 0.00001);
		return $answer;
	}
	public function metrictonToGrams($value = NULL)
	{
		$answer = ($value * 1000000);
		return $answer;
	}
	public function poundsToMetricton($value = NULL)
	{
		$answer = ($value * 0.000454);
		return $answer;
	}
	public function metrictonToPounds($value = NULL)
	{
		$answer = ($value * 2204.6);
		return $answer;
	}
	public function ouncesToMetricton($value = NULL)
	{
		$answer = ($value * 0.00045);
		return $answer;
	}
	public function metrictonToOunces($value  = NULL)
	{
		$answer = ($value * 2222.2222);
		return $answer;
	}
	public function doConversion($value = NULL, $from = NULL, $to = NULL)
	{
		if(!is_numeric($value)){
				return $this->objLanguage->languageText('mod_conversions_insertNumError', 'conversions');
		}
		elseif($from == $to && !empty($value))
		{
    	    return $this->objLanguage->languageText('mod_conversions_itselfError', 'conversions');
		}
		elseif($from == "1" && $to == "2")
		{
			$tempvalue = $this->kilogramsToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symKG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToGrams($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symG", "conversions").".";
		}
		elseif($from == "1" && $to == "3")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symKG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->kilogramsToMetricton($value),2)." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions").".";
		}
		elseif($from == "1" && $to == "4")
		{
			$tempvalue = $this->kilogramsToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symKG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToPounds($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symLBS", "conversions").".";
		}
		elseif($from == "1" && $to == "5")
		{
			$tempvalue = $this->kilogramsToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symKG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
		elseif($from == "2" && $to == "1")
		{
			$tempvalue = $this->gramsToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToKilograms($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symKG", "conversions").".";
		}
		elseif($from == "2" && $to == "3")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->gramsToMetricton($value),2)." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions").".";
		}
		elseif($from == "2" && $to == "4")
		{
			$tempvalue = $this->gramsToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToPounds($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symLBS", "conversions").".";
		}
		elseif($from == "2" && $to == "5")
		{
			$tempvalue = $this->gramsToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
		elseif($from == "3" && $to == "1")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToKilograms($value),2).$this->objLanguage->languageText("mod_conversions_symKG", "conversions").".";
		}
		elseif($from == "3" && $to == "2")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToGrams($value),2).$this->objLanguage->languageText("mod_conversions_symG", "conversions").".";
		}
		elseif($from == "3" && $to == "4")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToPounds($value),2).$this->objLanguage->languageText("mod_conversions_symLBS", "conversions").".";
		}
		elseif($from == "3" && $to == "5")
		{
			return $value." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($value),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
		elseif($from == "4" && $to == "1")
		{
			$tempvalue = $this->poundsToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symLBS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToKilograms($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symKG", "conversions").".";
		}
		elseif($from == "4" && $to == "2")
		{
			$tempvalue = $this->poundsToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symLBS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToGrams($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symG", "conversions").".";
		}
		elseif($from == "4" && $to == "3")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symLBS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->poundsToMetricton($value),2)." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions").".";
		}
		elseif($from == "4" && $to == "5")
		{
			$tempvalue = $this->poundsToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symLBS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
		elseif($from == "5" && $to == "1")
		{
			$tempvalue = $this->ouncesToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symOZ", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToKilograms($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symKG", "conversions").".";
		}
		elseif($from == "5" && $to == "2")
		{
			$tempvalue = $this->ouncesToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symOZ", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToGrams($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symG", "conversions").".";
		}
		elseif($from == "5" && $to == "3")
		{
			return $value.$this->objLanguage->languageText("mod_conversions_symOZ", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->ouncesToMetricton($value),2)." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions").".";
		}
		elseif($from == "5" && $to == "4")
		{
			$tempvalue = $this->ouncesToMetricton($value);
			return $value.$this->objLanguage->languageText("mod_conversions_symOZ", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($tempvalue),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
   		else{
           		return  $this->objLanguage->languageText('mod_conversions_insertError', 'conversions');
        	}
	}
}
?>
