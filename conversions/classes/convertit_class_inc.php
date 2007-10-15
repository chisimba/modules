<?php
class convertIt extends object
{
    public function init()
    {
         $this->objLanguage = $this->getObject('language', 'language');
    }
	public function doChange($val = NULL){
		if($val == "dist"){
			return "dist_tpl.php";
		}
		elseif($val == "temp"){
			return "temp_tpl.php";
		}
		elseif($val == "vol"){
			return "vol_tpl.php";
		}
		elseif($val == "weight"){
			return "weight_tpl.php";
		}
		else{
			return $this->objLanguage->languageText('mod_conversions_unknownError', 'conversions');
		}
	}
}
?>
