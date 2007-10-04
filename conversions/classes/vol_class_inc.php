<?php
   /**
    * converts volume measurements: litres, millilitres, and Cubic Decimeter, Cubic Meter & Cubic Centimeter 
    *
    * @author Nonhlanhla Gangeni <2539399@uwc.ac.za>
    * @package convertions
    * @copyright UWC 2007
    * @filesource
    */
class vol extends object
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

    public function convLitresToMillilitres($val = NULL)
    {
        $answer = $val * 1000;       
		return $answer;   
    }

     public function convMillilitresToLitres($val = NULL)
    {
        $answer = $val / 1000;       
		return $answer;    
    }

    public function convCubicDecimeterToCubicMeter($val = NULL)
    {
        $answer = $val / 1000;
		return $answer;    
    }

    public function convCubicMeterToCubicDecimeter($val = NULL)
    {
        $answer = $val * 1000;                     
		return $answer;          
    }

    public function convCubicCentimeterToCubicMeter($val = NULL)
    {
        $answer = $val / 1000000;               
		return $answer;   
    }

    public function convCubicMeterToCubicCentimeter($val = NULL)
    {
        $answer = $val * 1000000;
		return $answer;
    }

	public function doConversion($val = NULL, $from = NULL, $to = NULL){
	   	if(empty($val)){
   	    		return $this->objLanguage->languageText('mod_conversions_insertError', 'conversions');
    		}
		elseif($from == $to && !empty($val))
		{
    	    return $this->objLanguage->languageText('mod_conversions_itselfError', 'conversions');
		}
		elseif( ($from == "1" && $to == "3") || ($from == "1" && $to == "4") || ($from == "1" && $to == "5") || ($from == "2" && $to == "3") || ($from == "1" && $to == "4") || ($from == "1" && $to == "5") || ($from == "3" && $to == "1") || ($from == "3" && $to == "2") || ($from == "4" && $to == "1") || ($from == "4" && $to == "2") || ($from == "5" && $to == "1") || ($from == "5" && $to == "2") ){
   	    	return $this->objLanguage->languageText('mod_conversions_convError', 'conversions');
		}
		elseif($from == "1" && $to == "2"){
		    return $val.$this->objLanguage->languageText("mod_conversions_symL", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->convLitresToMillilitres($val),2).$this->objLanguage->languageText("mod_conversions_symML", "conversions").".";
		}
		elseif($from == "2" && $to == "1"){
		    return $val.$this->objLanguage->languageText("mod_conversions_symML", "conversions")." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->convMillilitresToLitres($val),2).$this->objLanguage->languageText("mod_conversions_symL", "conversions").".";
		}
		elseif($from == "3" && $to == "4"){
		    return $val.$this->objLanguage->languageText("mod_conversions_symDM", "conversions")."<sup>3</sup>"." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->convCubicDecimeterToCubicMeter($val),2).$this->objLanguage->languageText("mod_conversions_symM", "conversions")."<sup>3</sup>".".";
		}
		elseif($from == "4" && $to == "3"){
		    return $val.$this->objLanguage->languageText("mod_conversions_symM", "conversions")."<sup>3</sup>"." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->convCubicMeterToCubicDecimeter($val),2).$this->objLanguage->languageText("mod_conversions_symDM", "conversions")."<sup>3</sup>".".";
		}
		elseif($from == "5" && $to == "4"){
		    return $val.$this->objLanguage->languageText("mod_conversions_symCM", "conversions")."<sup>3</sup>"." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->convCubicCentimeterToCubicMeter($val),2).$this->objLanguage->languageText("mod_conversions_symM", "conversions")."<sup>3</sup>".".";
		}
		elseif($from == "4" && $to == "5"){
		    return $val.$this->objLanguage->languageText("mod_conversions_symM", "conversions")."<sup>3</sup>"." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->convCubicMeterToCubicCentimeter($val),2).$this->objLanguage->languageText("mod_conversions_symCM", "conversions")."<sup>3</sup>".".";
		}
		elseif($from == "5" && $to == "3"){
		    $tempVal = $this->convCubicCentimeterToCubicMeter($val);
		    return $val.$this->objLanguage->languageText("mod_conversions_symM", "conversions")."<sup>3</sup>"." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->convCubicMeterToCubicDecimeter($tempVal),2).$this->objLanguage->languageText("mod_conversions_symDM", "conversions")."<sup>3</sup>".".";
		}
		elseif($from == "3" && $to == "5"){
		    $tempVal = $this->convCubicDecimeterToCubicMeter;
		    return $val.$this->objLanguage->languageText("mod_conversions_symDM", "conversions")."<sup>3</sup>"." ".$this->objLanguage->languageText("mod_conversions_convertedTo", "conversions")." ".round($this->convCubicMeterToCubicCentimeter($tempVal),2).$this->objLanguage->languageText("mod_conversions_symCM", "conversions")."<sup>3</sup>".".";
		}
        	else{
           		return  $this->objLanguage->languageText('mod_conversions_unknownError', 'conversions');
        	}
	}
}
?>
