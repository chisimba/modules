<?php
	/**
	* converts distance/length measurements:  
	*
	* @author  <2@uwc.ac.za> 
	* @author  <2@uwc.ac.za> 
	* @package conversions
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
	//The following functions return a value that has been converted to Celsius or from Celsius


	//the function below does the actual conversion
	public function doConversion($value = NULL, $from = NULL, $to = NULL)
	{
		/**
		* 1 = 
		* 2 = 
		* 3 = 
		* 4 = 
		* 5 = 
		* 6 = 
		* 7 = 
		* 
		* The variable $tempVal is used in cases where there is no direct convertion from one value to another
		* 
		*/
		if(empty($value)){
				return $this->objLanguage->languageText('mod_conversions_insertError', 'conversions');
		}
		elseif($from == $to && !empty($value))
		{
			return $this->objLanguage->languageText('mod_conversions_itselfError', 'conversions');
		}
		elseif($from == "1" && $to == "2")
		{

		}
		elseif($from == "1" && $to == "3")
		{

		}
		elseif($from == "1" && $to == "4")
		{

		}
		elseif($from == "1" && $to == "5")
		{

		}
		elseif($from == "1" && $to == "6")
		{

		}
		elseif($from == "1" && $to == "7")
		{

		}
		elseif($from == "2" && $to == "1")
		{

		}
		elseif($from == "2" && $to == "3")
		{

		}
		elseif($from == "2" && $to == "4")
		{

		}
		elseif($from == "2" && $to == "5")
		{

		}
		elseif($from == "2" && $to == "6")
		{

		}
		elseif($from == "2" && $to == "7")
		{

		}
		elseif($from == "3" && $to == "1")
		{

		}
		elseif($from == "3" && $to == "2")
		{

		}
		elseif($from == "3" && $to == "4")
		{

		}
		elseif($from == "3" && $to == "5")
		{

		}
		elseif($from == "3" && $to == "6")
		{

		}
		elseif($from == "3" && $to == "7")
		{

		}
		elseif($from == "4" && $to == "1")
		{

		}
		elseif($from == "4" && $to == "2")
		{

		}
		elseif($from == "4" && $to == "3")
		{

		}
		elseif($from == "4" && $to == "5")
		{

		}
		elseif($from == "4" && $to == "6")
		{

		}
		elseif($from == "4" && $to == "7")
		{

		}
		elseif($from == "5" && $to == "1")
		{

		}
		elseif($from == "5" && $to == "2")
		{

		}
		elseif($from == "5" && $to == "3")
		{

		}
		elseif($from == "5" && $to == "4")
		{

		}
		elseif($from == "5" && $to == "6")
		{

		}
		elseif($from == "5" && $to == "7")
		{

		}
		elseif($from == "6" && $to == "1")
		{

		}
		elseif($from == "6" && $to == "2")
		{

		}
		elseif($from == "6" && $to == "3")
		{

		}
		elseif($from == "6" && $to == "4")
		{

		}
		elseif($from == "6" && $to == "5")
		{

		}
		elseif($from == "6" && $to == "7")
		{

		}
		elseif($from == "7" && $to == "1")
		{

		}
		elseif($from == "7" && $to == "2")
		{

		}
		elseif($from == "7" && $to == "3")
		{

		}
		elseif($from == "7" && $to == "4")
		{

		}
		elseif($from == "7" && $to == "5")
		{

		}
		elseif($from == "7" && $to == "6")
		{

		}
		else{
			return  $this->objLanguage->languageText('mod_conversions_unknownError', 'conversions');
		}
	}
}
?>
