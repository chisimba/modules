<?php
	/**
	* Returns a drop down list and the convertion answer for the left drop-down menu, in the module conversions  
	*
	* @author Ebrahim Vasta <2623441@uwc.ac.za> 
	* @package conversions
	* @copyright UWC 2007
	* @filesource
	*/ 
class navigate extends object
{
//	public $objDist;
	public $objTemp;
	public $objVol;
	public $objWeight;

	public function init()
	{
//			$this->objDist = $this->getObject('dist');
			$this->objTemp = $this->getObject('temp');
			$this->objVol = $this->getObject('vol');
			$this->objWeight = $this->getObject('weight');
			$this->objLanguage = $this->getObject('language', 'language');
	}
	public function conversionsFormNav(){ 
		$gform = new form('goTo', $this->uri(array(
			'action' => 'goto'
		)));
		//start a fieldset
		$this->loadClass('fieldset', 'htmlelements');
                $gfieldset = new fieldset;
		$gt = $this->newObject('htmltable', 'htmlelements');
		$gt->cellpadding = 5;
		//to dropdown
		$gtodrop = new dropdown('to');
		$gtodrop->addOption(1, $this->objLanguage->languageText("mod_conversions_Distance", "conversions"));
		$gtodrop->addOption(2, $this->objLanguage->languageText("mod_conversions_Temperature", "conversions"));
		$gtodrop->addOption(3, $this->objLanguage->languageText("mod_conversions_Volume", "conversions"));
		$gtodrop->addOption(4, $this->objLanguage->languageText("mod_conversions_Weight", "conversions"));
		$gt->startRow();
		$gtlabel = new label($this->objLanguage->languageText('mod_conversions_goTo', 'conversions') . ':', 'input_goTo');
		$gt->addCell($gtlabel->show());
		$gt->addCell($gtodrop->show());
		$gt->endRow();

		//end off the form and add the buttons
		$this->objconvButton2 = new button($this->objLanguage->languageText('mod_conversions_goTo', 'conversions'));
		$this->objconvButton2->setValue($this->objLanguage->languageText('mod_conversions_goTo', 'conversions'));
		$this->objconvButton2->setToSubmit();
		$gfieldset->addContent($gt->show());
		$gform->addToForm($gfieldset->show());
		$gform->addToForm($this->objconvButton2->show());
		$gform = $gform->show();
        
		$gobjFeatureBox = $this->getObject('featurebox', 'navigation');
		$gret = $gobjFeatureBox->showContent($this->objLanguage->languageText("mod_conversions_goTo", "conversions") , $gform);
		return $gret;
	}

	public function answer($value = NULL, $from = NULL, $to = NULL, $type = NULL)
	{
		if(isset($value))
		{
			switch ($type) 
			{
				case 'dist':
					$answer = $this->objDist->doConversion($value, $from, $to);
					return $answer;
					break;

				case 'temp':
					$answer = $this->objTemp->doConversion($value, $from, $to);
					return $answer;
					break;

		  		case 'vol':	
					$answer = $this->objVol->doConversion($value, $from, $to);
					return $answer;
					break;

				case 'weight':
					$answer = $this->objWeight->doConversion($value, $from, $to);
					return $answer;
					break; 
			}
         }
			else
			{
	 			return $this->objLanguage->languageText('mod_conversions_insertError', 'conversions');
			}
		}
	public function show($value = NULL, $from = NULL, $to = NULL, $type = NULL){
		//$ret = $this->conversionsForm();
$ret = NULL;
		$check = $this->answer($value, $from, $to, $type);
		if($check != NULL){
			$ret.= $check;
		}
		return $ret;		
	}
}
?>
