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
    public $val;

    public function init()
    {
            $this->objLanguage = $this->getObject('language', 'language');
    }

    public function setup($val = NULL)
    {
      $this->val = $val;
    }

	public function kilogramsToMetricton($val)
	{
		$answer = ($val * 0.001);
		return $answer;
	}
	public function metrictonToKilograms($val)
	{
		$answer = ($val * 1000);
		return $answer;
	}
	public function gramsToMetricton($val)
	{
		$answer = ($val * 0.00001);
		return $answer;
	}
	public function metrictonToGrams($val)
	{
		$answer = ($val * 1000000);
		return $answer;
	}
	public function poundsToMetricton($val)
	{
		$answer = ($val * 0.000454);
		return $answer;
	}
	public function metrictonToPounds($val)
	{
		$answer = ($val * 2204.6);
		return $answer;
	}
	public function ouncesToMetricton($val)
	{
		$answer = ($val * 0.00045);
		return $answer;
	}
	public function metrictonToOunces($val)
	{
		$answer = ($val * 2222.2222);
		return $answer;
	}
	public function doConversion($val, $from, $to)
	{
		if(empty($val)){
   	    	return $this->objLanguage->languageText('mod_conversions_InsertError', 'conversions');
    	}
		elseif($from == $to && !empty($val))
		{
    	    return $this->objLanguage->languageText('mod_conversions_itselfError', 'conversions');
		}
		elseif($from == "1" && $to == "2")
		{
			$tempVal = $this->kilogramsToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symKG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToGrams($tempVal),2).$this->objLanguage->languageText("mod_conversions_symG", "conversions").".";
		}
		elseif($from == "1" && $to == "3")
		{
			return $val.$this->objLanguage->languageText("mod_conversions_symKG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->kilogramsToMetricton($val),2)." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions").".";
		}
		elseif($from == "1" && $to == "4")
		{
			$tempVal = $this->kilogramsToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symKG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToPounds($tempVal),2).$this->objLanguage->languageText("mod_conversions_symLBS", "conversions").".";
		}
		elseif($from == "1" && $to == "5")
		{
			$tempVal = $this->kilogramsToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symKG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($tempVal),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
		elseif($from == "2" && $to == "1")
		{
			$tempVal = $this->gramsToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToKilograms($tempVal),2).$this->objLanguage->languageText("mod_conversions_symKG", "conversions").".";
		}
		elseif($from == "2" && $to == "3")
		{
			return $val.$this->objLanguage->languageText("mod_conversions_symG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->gramsToMetricton($val),2)." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions").".";
		}
		elseif($from == "2" && $to == "4")
		{
			$tempVal = $this->gramsToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToPounds($tempVal),2).$this->objLanguage->languageText("mod_conversions_symLBS", "conversions").".";
		}
		elseif($from == "2" && $to == "5")
		{
			$tempVal = $this->gramsToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symG", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($tempVal),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
		elseif($from == "3" && $to == "1")
		{
			return $val." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToKilograms($val),2).$this->objLanguage->languageText("mod_conversions_symKG", "conversions").".";
		}
		elseif($from == "3" && $to == "2")
		{
			return $val." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToGrams($val),2).$this->objLanguage->languageText("mod_conversions_symG", "conversions").".";
		}
		elseif($from == "3" && $to == "4")
		{
			return $val." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToPounds($val),2).$this->objLanguage->languageText("mod_conversions_symLBS", "conversions").".";
		}
		elseif($from == "3" && $to == "5")
		{
			return $val." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($val),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
		elseif($from == "4" && $to == "1")
		{
			$tempVal = $this->poundsToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symLBS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToKilograms($tempVal),2).$this->objLanguage->languageText("mod_conversions_symKG", "conversions").".";
		}
		elseif($from == "4" && $to == "2")
		{
			$tempVal = $this->poundsToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symLBS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToGrams($tempVal),2).$this->objLanguage->languageText("mod_conversions_symG", "conversions").".";
		}
		elseif($from == "4" && $to == "3")
		{
			return $val.$this->objLanguage->languageText("mod_conversions_symLBS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->poundsToMetricton($val),2)." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions").".";
		}
		elseif($from == "4" && $to == "5")
		{
			$tempVal = $this->poundsToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symLBS", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($tempVal),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
		elseif($from == "5" && $to == "1")
		{
			$tempVal = $this->ouncesToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symOZ", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToKilograms($tempVal),2).$this->objLanguage->languageText("mod_conversions_symKG", "conversions").".";
		}
		elseif($from == "5" && $to == "2")
		{
			$tempVal = $this->ouncesToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symOZ", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToGrams($tempVal),2).$this->objLanguage->languageText("mod_conversions_symG", "conversions").".";
		}
		elseif($from == "5" && $to == "3")
		{
			return $val.$this->objLanguage->languageText("mod_conversions_symOZ", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->ouncesToMetricton($val),2)." ".$this->objLanguage->languageText("mod_conversions_symTONS", "conversions").".";
		}
		elseif($from == "5" && $to == "4")
		{
			$tempVal = $this->ouncesToMetricton($val);
			return $val.$this->objLanguage->languageText("mod_conversions_symOZ", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->metrictonToOunces($tempVal),2).$this->objLanguage->languageText("mod_conversions_symOZ", "conversions").".";
		}
   		else{
           		return  $this->objLanguage->languageText('mod_conversions_unknownError', 'conversions');
        	}
	}
}
?>
