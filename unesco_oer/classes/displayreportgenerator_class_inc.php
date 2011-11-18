<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class displayreportgenerator extends object
{
    public function init() {

        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('selectbox', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->objDbReporting = $this->getObject('dbreporting', 'unesco_oer');
        $this->objLanguage = $this->getObject("language", "language");

    }

    public function displayForm(){
        
        $uri = $this->uri ( array( 'action' => 'processReportingForm' ) );
        $objForm = new form('formReport',$uri);
        
        $countrySelect = new dropdown('countryDropdown[]');
        
        $temp = $this->objDbReporting->getBreakdownCountryAdaptations();
        $ArrayCount = sizeof($temp);

        $countrySelect->extra = ' multiple="1" size="4" style="width:200pt;" ';
        $countryArray = array();
        $countryCode = array();

        for ($i=0; $i < $ArrayCount; $i++)
            {
              $countryCode[] =  $temp[$i]["country"];
              $countryName = $this->objDbReporting->getCountryName($countryCode[$i]);
              $countryArray[] = $countryName;
            }

        sort($countryArray,SORT_STRING);

        for ($z=0; $z < $ArrayCount; $z++)
        {
            $countrySelect->addOption($countryCode[$z],$countryArray[$z]);
        }                                                                                 


        $content .= ' 
                <div class="topReportingDiv">
                	<fieldset>
                            <legend>'.
                                    $this->objLanguage->languageText('mod_unesco_oer_country_region', 'unesco_oer')
                                    .'</legend>
                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                   '.
                                    $this->objLanguage->languageText('mod_unesco_oer_reporting_select', 'unesco_oer')
                                    .'<br>'
                                   .$countrySelect->show().'<br>'.
                                    $this->objLanguage->languageText('mod_unesco_oer_ctrl', 'unesco_oer')
                                    .'<br><br> 
                                </div>
                                <div class="rightLegendContentHolder">
                                     ';

        $regionSelect = new dropdown('regionDropdown[]');
        $regionSelect->extra = ' multiple="1" size="4" style="width:200pt;" ';
        $regionSelect->addOption('Africa');
        $regionSelect->addOption('Arab States');
        $regionSelect->addOption('Asia and the Pacific');
        $regionSelect->addOption('Europe and North America');

        $content .= ' '.
                                    $this->objLanguage->languageText('mod_unesco_oer_reporting_select1', 'unesco_oer')
                                    .'<br>'.$regionSelect->show().'
                                	<br>
                                    '.
                                    $this->objLanguage->languageText('mod_unesco_oer_ctrl1', 'unesco_oer')
                                    .'
                                    </div>
                                </div>
                                </fieldset>
                            
                        

                        <br>
                        <fieldset>
                            <legend>'.
                                    $this->objLanguage->languageText('mod_unesco_oer_reporting_title', 'unesco_oer')
                                    .'</legend>
                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                	'.
                                    $this->objLanguage->languageText('mod_unesco_oer_reporting_select2', 'unesco_oer')
                                    .'<br>';

        $themeSelect = new dropdown('themeDropdown[]');
        $themeSelect->extra = ' multiple="1" size="4" style="width:200pt;" ';

        $themeArray = $this->objDbReporting->getProductThemes();
        $ArrayCount = sizeof($themeArray);
        $themeArray2 = array();

        for ($i=0; $i < $ArrayCount; $i++)
          {
             $themeArray2[] = $themeArray[$i]["theme"];
          }

        sort($themeArray2,SORT_STRING);

        for ($z=0; $z < $ArrayCount; $z++)
          {
            $themeSelect->addOption($themeArray2[$z]);
          }
          
        $keywordSelect = new dropdown('keywordDropdown[]');
        $keywordSelect->extra = ' multiple="1" size="4" style="width:200pt;" ';
        
        $keywordArray = $this->objDbReporting->getProductKeywords();
        $ArrayCount = sizeof($keywordArray);
        $keywordArray2 = array();

        for ($i=0; $i < $ArrayCount; $i++)
          {
             $keywordArray2[] = $keywordArray[$i]["keyword"];
          }

        sort($keywordArray2,SORT_STRING);

        for ($z=0; $z < $ArrayCount; $z++)
          {
            $keywordSelect->addOption($keywordArray2[$z]);
          }        

        $content .= $themeSelect->show().'
                                	<br>
                                    '.
                                    $this->objLanguage->languageText('mod_unesco_oer_ctrl2', 'unesco_oer')
                                    .'
                                </div>
                                <div class="rightLegendContentHolder">

                                	'.
                                    $this->objLanguage->languageText('mod_unesco_oer_reporting_select3', 'unesco_oer')
                                    .'<br>'.$keywordSelect->show().'
                                            <br>
                                    '.
                                    $this->objLanguage->languageText('mod_unesco_oer_ctrl3', 'unesco_oer')
                                    .'                                    
                                </div>
                            </div>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>'.
                                    $this->objLanguage->languageText('mod_unesco_oer_reporting_title1', 'unesco_oer')
                                    .'</legend>

                            <div class="legendContent">
                                <div class="legendWideContentHolder">';

        $temp = $this->objDbReporting->getBreakdownTypeAdaptation();
        $ArrayCount = sizeof($temp);
        $arrayTypes = array();

        for ($i=0; $i < $ArrayCount; $i++)
          {
            $arrayTypes[] = $temp[$i]["description"];
          }

        sort($arrayTypes,SORT_STRING);

        for ($z=0; $z < $ArrayCount; $z++)
          {
            $checkbox = new checkbox('AdaptationType[]');
            $checkbox->value = $arrayTypes[$z];
            $content .= $checkbox->show().$arrayTypes[$z].' ';

          }
        
        $content .= '</div>
                            </div>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>'.
                                    $this->objLanguage->languageText('mod_unesco_oer_reporting_title2', 'unesco_oer')
                                    .'</legend>
                            <div class="legendContent">
                                 <div class="legendWideContentHolder">';

        $temp = $this->objDbReporting->getInstitutionTypeBreakdownAdaptation();
        $ArrayCount = sizeof($temp);
        $arrayTypes = array();

        for ($i=0; $i < $ArrayCount; $i++)
          {
            $arrayTypes[] = $temp[$i]["type"];
          }

        sort($arrayTypes,SORT_STRING);
                        $checkbox = new checkbox('selectedusers[]', $products[$i]['id']);
                        $checkbox->value = $products[$i]['id'];
                        $checkbox->cssId = 'user_' . $products[$i]['id'];
        for ($z=0; $z < $ArrayCount; $z++)
          {
            $checkbox = new checkbox('InstitutionType[]');
            $checkbox->value = $arrayTypes[$z];
            $content .= $checkbox->show().$arrayTypes[$z].' ';

          }

        $content .= '</div>
                            </div>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>'.
                                    $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer')
                                    .'</legend>
                            <div class="legendContent">
                                <div class="leftLegendContentHolder">';

        $langSelect = new dropdown('langDropdown[]');
        $langSelect->extra = ' multiple="1" size="4" style="width:200pt;" ';

        $langArray = $this->objDbReporting->getLanguageBreakdownAdaptations();
        
        $ArrayCount = sizeof($langArray);
        $langArray2 = array();

        for ($i=0; $i < $ArrayCount; $i++)
          {
             $langArray2[] = $langArray[$i]["language"];
          }

        sort($langArray2,SORT_STRING);

        for ($z=0; $z < $ArrayCount; $z++)
          {
            $langSelect->addOption($langArray2[$z]);
          }

        $buttonGenerate = new button('generate');
        $ButtonTitle = $this->objLanguage->languageText('mod_unesco_oer_reporting_button', 'unesco_oer');
        $buttonGenerate->setValue($ButtonTitle);
        $buttonGenerate->setToSubmit();
        
        $ButtonTitle1 = $this->objLanguage->languageText('mod_unesco_oer_reporting_button1', 'unesco_oer');
        $buttonReset = new button('reset');
        $buttonReset->setValue($ButtonTitle1);
        $buttonReset->setToReset();
        

        $content .= $langSelect->show().'<br>
                                    '.
                                    $this->objLanguage->languageText('mod_unesco_oer_ctrl4', 'unesco_oer')
                                    .'
                                </div>
                                <div class="rightLegendContentHolder">&nbsp;</div>

                            </div>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>'.
                                    $this->objLanguage->languageText('mod_unesco_oer_reporting_title3', 'unesco_oer')
                                    .'</legend>
                            <div class="legendContent">
                                <div class="leftLegendContentHolder">
                                	'.
                                    $this->objLanguage->languageText('mod_unesco_oer_reporting_title4', 'unesco_oer')
                                    .'<br>

                                    <input type="radio" name="radio" id=""> PDF<br>

                                </div>

                            </div>
                        </fieldset>

                        <div class="legendContent tenPixelTopPadding">
                            <div class="saveCancelButtonHolder">

                            </div>
                            <div class="saveCancelButtonHolder">
                                
                                '.$buttonGenerate->show().' '.$buttonReset->show().'
                            </div>
                        </div>

                    </div>
                
                
                
                ';

        $objForm->addToForm($content);

        return $objForm->show();
    }




    
    
}




?>
