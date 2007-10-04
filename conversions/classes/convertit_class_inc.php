<?php
class convertIt
{
    public function init()
    {
            $this->objLanguage = $this->getObject('language', 'language');
    }
	public function doChange($val = NULL){
		if($val == "1"){
			return "dist_tpl.php";
		}
		elseif($val == "2"){
			return "temp_tpl.php";
		}
		elseif($val == "3"){
			return "vol_tpl.php";
		}
		elseif($val == "4"){
			return "weight_tpl.php";
		}
		else{
			return $this->objLanguage->languageText('mod_conversions_unknownError', 'conversions');
		}
	}
}
?>
