<?php
class viewforms extends object {
    
    public function init() {
        $this->loadElements();
        $this->count = 0;
        $this->curForm="";
    }

    public function loadElements() {
        $this->objLanguage = $this->getObject("language", "language");
    }
    
    public function setData($data) {
        $this->data = $data;
    }

    public function getForm($curForm, $end) {
        $this->curForm = $curForm;
        $this->end = $this->count + $end;
        $title = $this->getTitle();

        $allFormData = "<fieldset><legend>";
        $allFormData .= $title."</legend>";
        $allFormData .= $this->getAll();
        $allFormData .= "</fieldset>";
        
        return $allFormData;
    }

    public function getTitle() {
        $titleStr = 'mod_ads_title'. $this->curForm;
        return $this->objLanguage->languageText($titleStr,'ads');
    }
    
    public function getAll() {
        $allFormData = "";
        
        for($i=$this->count;$i<$this->end;$i++) {
            $question = $this->getQuestion($i);
            $answer = "<b>".$this->getAnswer($i)."</b>";
            $allFormData .= $question;
            $allFormData .= ": ".$answer;
            $allFormData .= "<br>";
        }
        $this->count = $i--;

        return $allFormData;
    }

    public function getQuestion($i) {
        switch($this->curForm) {
              case "A": switch ($i) {
                            case 0: return $this->objLanguage->languageText('mod_ads_unit_name', 'ads');
                            case 1: return $this->objLanguage->languageText('mod_ads_thisisa','ads');
                            case 2: return $this->objLanguage->languageText('mod_ads_motiv', 'ads');
                            case 3: return $this->objLanguage->languageText('mod_ads_unit_qual', 'ads');
                            case 4: return $this->objLanguage->languageText('mod_ads_proposaltype','ads');
                        }
              case "B": switch($i) {
                            case 5:  return $this->objLanguage->languageText('mod_ads_b1', 'ads');
                            case 6:  return $this->objLanguage->languageText('mod_ads_b2', 'ads');
                            case 7:  return $this->objLanguage->languageText('mod_ads_b3a', 'ads');
                            case 8:  return $this->objLanguage->languageText('mod_ads_b3b', 'ads');
                            case 9:  return $this->objLanguage->languageText('mod_ads_b4a', 'ads');
                            case 10: return $this->objLanguage->languageText('mod_ads_b4b', 'ads');
                            case 11: return $this->objLanguage->languageText('mod_ads_b4c', 'ads');
                            case 12: return $this->objLanguage->languageText('mod_ads_b5a', 'ads');
                            case 13: return $this->objLanguage->languageText('mod_ads_b5b', 'ads');
                            case 14: return $this->objLanguage->languageText('mod_ads_b6a', 'ads');
                            case 15: return $this->objLanguage->languageText('mod_ads_b6b', 'ads');
                        }
              case "C": switch($i) {
                            case 16: return $this->objLanguage->languageText('mod_formC_C1', 'ads');
                            case 17: return $this->objLanguage->languageText('mod_formC_C2a', 'ads');
                            case 18: return $this->objLanguage->languageText('mod_formC_C2b', 'ads');
                            case 19: return $this->objLanguage->languageText('mod_formC_C3', 'ads');
                            case 20: return $this->objLanguage->languageText('mod_formC_C4a', 'ads');
                            case 21: return $this->objLanguage->languageText('mod_formC_C4b_1', 'ads');
                        }
              case "D": switch($i) {
                            case 22: return $this->objLanguage->languageText('mod_formD_D1', 'ads');
                            case 23: return $this->objLanguage->languageText('mod_formD_D2_1', 'ads');
                            case 24: return $this->objLanguage->languageText('mod_formD_D2_2', 'ads');
                            case 25: return $this->objLanguage->languageText('mod_formD_D2_3', 'ads');
                            case 26: return $this->objLanguage->languageText('mod_formD_D3', 'ads');
                            case 27: return $this->objLanguage->languageText('mod_formD_D4_1', 'ads');
                            case 28: return $this->objLanguage->languageText('mod_formD_D4_2', 'ads');
                            case 29: return $this->objLanguage->languageText('mod_formD_D4_3', 'ads');
                            case 30: return $this->objLanguage->languageText('mod_formD_D4_4', 'ads');
                            case 31: return $this->objLanguage->languageText('mod_formD_D4_5', 'ads');
                            case 32: return $this->objLanguage->languageText('mod_formD_D4_6', 'ads');
                            case 33: return $this->objLanguage->languageText('mod_formD_D4_7', 'ads');
                            case 34: return $this->objLanguage->languageText('mod_formD_D4_8', 'ads');
                            case 35: return $this->objLanguage->languageText('mod_formD_D2_1', 'ads');
                            case 36: return $this->objLanguage->languageText('mod_formD_D5a', 'ads');
                            case 37: return $this->objLanguage->languageText('mod_formD_D5b', 'ads');
                            case 38: return $this->objLanguage->languageText('mod_formD_D5c', 'ads');
                            case 39: return $this->objLanguage->languageText('mod_formD_D5d', 'ads');
                            case 40: return $this->objLanguage->languageText('mod_formD_D5e', 'ads');
                            case 41: return $this->objLanguage->languageText('mod_formD_D5f', 'ads');
                            case 42: return $this->objLanguage->languageText('mod_formD_D5h', 'ads');
                            case 43: return $this->objLanguage->languageText('mod_formD_D5i', 'ads');
                            case 44: return $this->objLanguage->languageText('mod_formD_D6', 'ads');
                            case 45: return $this->objLanguage->languageText('mod_formD_D7', 'ads');
                        }
              case "E": switch($i) {
                            case 46: return $this->objLanguage->languageText('mod_task2_e1a', 'ads');
                            case 47: return $this->objLanguage->languageText('mod_task2_e1b', 'ads');
                            case 48: return $this->objLanguage->languageText('mod_task2_e2a', 'ads');
                            case 49: return $this->objLanguage->languageText('mod_task2_e2b', 'ads');
                            case 50: return $this->objLanguage->languageText('mod_task2_e2c', 'ads');
                            case 51: return $this->objLanguage->languageText('mod_task2_e3a', 'ads');
                            case 52: return $this->objLanguage->languageText('mod_task2_e3b', 'ads');
                            case 53: return $this->objLanguage->languageText('mod_task2_e3c', 'ads');
                            case 54: return $this->objLanguage->languageText('mod_task2_e4', 'ads');
                            case 55: return $this->objLanguage->languageText('mod_task2_e5a', 'ads');
                            case 56: return $this->objLanguage->languageText('mod_task2_e5b', 'ads');
                        }
              case "F": switch($i) {
                            case 57: return $this->objLanguage->languageText('mod_task2_f1a', 'ads');
                            case 58: return $this->objLanguage->languageText('mod_task2_f1b', 'ads');
                            case 59: return $this->objLanguage->languageText('mod_task2_f2a', 'ads');
                            case 60: return $this->objLanguage->languageText('mod_task2_f2b', 'ads');
                            case 61: return $this->objLanguage->languageText('mod_task2_f3a', 'ads');
                            case 62: return $this->objLanguage->languageText('mod_task2_f3b', 'ads');
                            case 63: return $this->objLanguage->languageText('mod_task2_f4', 'ads');
                        }
              case "G": switch($i) {
                            case 64: return $this->objLanguage->languageText('mod_ads_g1a', 'ads');
                            case 65: return $this->objLanguage->languageText('mod_ads_g1b', 'ads');
                            case 66: return $this->objLanguage->languageText('mod_ads_g2a', 'ads');
                            case 67: return $this->objLanguage->languageText('mod_ads_g2b', 'ads');
                            case 68: return $this->objLanguage->languageText('mod_ads_g3a', 'ads');
                            case 69: return $this->objLanguage->languageText('mod_ads_g3b', 'ads');
                            case 70: return $this->objLanguage->languageText('mod_ads_g4a', 'ads');
                            case 71: return $this->objLanguage->languageText('mod_ads_g4b', 'ads');
                        }
              case "H": switch($i) {
                            case 72: return $this->objLanguage->languageText('mod_ads_h1', 'ads');
                            case 73: return $this->objLanguage->languageText('mod_ads_h2a', 'ads');
                            case 74: return $this->objLanguage->languageText('mod_ads_h2b', 'ads');
                            case 75: return $this->objLanguage->languageText('mod_ads_h3a', 'ads');
                            case 76: return $this->objLanguage->languageText('mod_ads_h3b', 'ads');
                        }
              default:  break;
        }
    }

    public function getAnswer($i) {
        return $this->data[$i]['value'];
        //return strtoupper($this->data[$i]['value']);
    }
}
?>
