<?php
/**
* dbStatistics class extends dbTable
* @package etd
* @filesource
*/

/**
* Class for calculating and displaying statistics on the resources
* @author Megan Watson
* @copyright (c) 2006 University of the Western Cape
* @version 0.1
*/

class dbStatistics extends dbTable
{
    
    /**
    * Constructor for the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        parent::init('tbl_etd_statistics');
        $this->table = 'tbl_etd_statistics';
                
        $this->etdDbSubmissions =& $this->getObject('dbsubmissions', 'etd');
                
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objUserStats =& $this->getObject('dbuserstats', 'sitestats');  
        $this->objLoginHistory =& $this->getObject('dbloginhistory', 'userstats');
        $this->objIpCountry =& $this->getObject('iptocountry', 'utilities');
        $this->objDate =& $this->getObject('datetime', 'utilities');
        
        $this->objHead =& $this->newObject('htmlheading', 'htmlelements');
        $this->objLayer =& $this->newObject('layer', 'htmlelements');
        
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('tabbedbox', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('tabbedbox', 'htmlelements');

        $this->userId = NULL;
        if($this->objUser->isLoggedIn()){
            $this->userId = $this->objUser->userId();
        }
    }
    
    /**
    * Method to add a new statistic.
    * Type = hit to the site, view on a resource, download of a resource
    *
    * @access private
    * @param string $type The type of hit
    * @param string $submitId The submission if the hit is on a resource
    * @return
    */
    private function addStatistic($type = 'hit', $submitId = NULL)
    {    
        $ip = $_SERVER['REMOTE_ADDR'];
        $code = $this->objIpCountry->getCountryByIP($ip);
        
        $fields = array();
        $fields['submitid'] = $submitId;
        $fields['hittype'] = $type;
        $fields['ipaddress'] = $ip;
        $fields['countrycode'] = $code;
        $fields['creatorid'] = $this->userId;
        $fields['datecreated'] = date('Y-m-d H:i:s');
        $this->insert($fields);
    }
    
    /**
    * Method to get a statistic
    *
    * @access private
    * @param string $type
    * @param string $submitId
    * @return array
    */
    private function getStatistic($type = 'hit', $submitId = NULL)
    {
        $sql = "SELECT COUNT(*) AS count FROM {$this->table}";
        $sql .= " WHERE hittype = '$type'";
        if(isset($submitId) && !empty($submitId)){
            $sql .= " AND submitid = '$submitId'";
        }
        
        $data = $this->getArray($sql);
        
        if(!empty($data)){
            return $data[0]['count'];
        }
        return 0;
    }
    
    /**
    * Method to get the statistics by month
    *
    * @access private
    * @param string $type
    * @return array $data
    */    
    private function getStatsByMonth($type)
    {
        $year = date('Y');
//        $sql = "SELECT count(*) as cnt, MONTH(datecreated) as month FROM {$this->table}";
//        $sql .= " WHERE YEAR(datecreated) = '$year' AND hittype = '$type' GROUP BY MONTH(datecreated)";

        
        $sql = "SELECT count(*) as cnt, to_char(datecreated, 'MM') as month FROM {$this->table}";
        $sql .= " WHERE to_char(datecreated, 'YY') = '$year' AND hittype = '$type' GROUP BY to_char(datecreated, 'MM')";
        
        $data = $this->getArray($sql);
        
        return $data;
    }
    
    /**
    * Method to return all the countries represented by visitors to the site with the number of visits per country.
    *
    * @access private
    * @return array $countries
    */
    private function getCountries()
    {
        $sql = 'SELECT DISTINCT(countrycode), count(*) as cnt FROM '.$this->table.' group by countrycode';
        
        $data = $this->getArray($sql);
        
        return $data;
    }
    
    /**
    * Method to record a hit on the site
    * The method checks session to see if this is a new user or the current one
    *
    * @access public
    * @return
    */
    public function recordHit()
    {
        $check = $this->getSession('newhit');
        
        if($check == 'yes'){
            return TRUE;
        }
        $this->setSession('newhit', 'yes');
        $this->addStatistic('hit');
        return FALSE;
    }
    
    /**
    * Method to record a visit to a resource
    * The method checks session to see if the user is still busy on the same resource
    *
    * @access public
    * @param string $submitId
    * @return
    */
    public function recordVisit($submitId)
    {
        $check = $this->getSession('newvisit');
        
        if($check == $submitId){
            return TRUE;
        }
        $this->setSession('newvisit', $submitId);
        $this->addStatistic('visit', $submitId);
        return FALSE;
    }
    
    /**
    * Method to record the download of a resource
    * The method uses the submitId recorded in session from the hit to the resource
    *
    * @access public
    * @return
    */
    public function recordDownload()
    {
        $submitId = $this->getSession('newvisit');
        
        $this->addStatistic('download', $submitId);
        return FALSE;
    }

    /**
    * Method to record the submission of a new resource
    * The method uses the submitId recorded in session from the hit to the archive
    *
    * @access public
    * @return
    */
    public function recordUpload($submitId)
    {        
        $this->addStatistic('upload', $submitId);
        return FALSE;
    }

    /**
    * Method to show the statistics for a resource
    *
    * @access public
    * @param string $submitId
    * @return string html
    */
    public function showResourceStats($submitId)
    {
        $hits = $this->getStatistic('visit', $submitId);
        $downloads = $this->getStatistic('download', $submitId);
        
        $lbStats = $this->objLanguage->languageText('word_statistics');
        $lbHits = $this->objLanguage->languageText('mod_etd_thisresourcehasbeenvisited', 'etd');
        $lbDownloads = $this->objLanguage->languageText('mod_etd_thisresourcehasbeendownloaded', 'etd');
        $lbTimes = strtolower($this->objLanguage->languageText('word_times'));
        $lbTime = strtolower($this->objLanguage->languageText('word_time'));
        
        $str = '<p>'.$lbHits.'&nbsp;'.$hits.'&nbsp;';
        if($hits == 1){
            $str .= $lbTime;
        }else{
            $str .= $lbTimes;
        }
        $str .= '</p>';
        
        $str .= '<p>'.$lbDownloads.'&nbsp;'.$downloads.'&nbsp;';
        if($downloads == 1){
            $str .= $lbTime;
        }else{
            $str .= $lbTimes;
        }
        $str .= '</p>';
        
        $objTab = new tabbedbox();
        $objTab->extra = 'style="background-color: #FCFAF2; padding: 2px;"';
        $objTab->addTabLabel($lbStats);
        $objTab->addBoxContent($str);
        
        return $objTab->show();
    }
        
    /**
    * Method to generate the left side 'menu' for the statistics page
    *
    * @access public
    * @return string html
    */
    public function getSideBlock()
    {
        $userCount = $this->objUserStats->countUsers();
        $countryCount = 0;//$this->objUserStats->getTotalCountries();
        $totalLogins = 0;//$this->objLoginHistory->getTotalLogins();
        $countries = '';//$this->objUserStats->getFlags();
        
        $hdUser = $this->objLanguage->languageText('phrase_userstats');
        $lbTotalUsers = $this->objLanguage->languageText('phrase_totalusers');
        $lbTotalLogins = $this->objLanguage->languageText('mod_etd_totallogins', 'etd');
        $lbUserCountries = $this->objLanguage->languageText('mod_etd_numcountriesrepresented', 'etd');
        
        $str = '<p>'.$lbTotalUsers.': '.$userCount.'</p>';
        $str .= '<p>'.$lbTotalLogins.': '.$totalLogins.'</p>';
        $str .= '<p>'.$lbUserCountries.': '.$countryCount.'<br />';
        $str .= $countries.'</p>';
        
        $objTab = new tabbedbox();
        $objTab->extra = 'style="background-color: #FCFAF2; padding: 5px;"';
        $objTab->addTabLabel($hdUser);
        $objTab->addBoxContent($str);
        
        return '<p>'.$objTab->show().'</p>';
    }
    
    /**
    * Method to generate the site statistics
    *
    * @access private
    * @return string html
    */
    private function getSiteStats()
    {
        $siteVisitsCount = $this->getStatistic('hit');
        $countries = $this->getCountries();
        
        $hdSite = $this->objLanguage->languageText('phrase_sitestats');
        $lbVisits = $this->objLanguage->languageText('mod_etd_visitstosite', 'etd');
        $lbCountries = $this->objLanguage->languageText('mod_etd_countriesrepresented', 'etd');
        
        $str = '<p>'.$lbVisits.': '.$siteVisitsCount.'</p>';
        
        $i = 0; $flags = '';
        if(!empty($countries)){
            foreach($countries as $item){
                if(!empty($item['countrycode'])){
                    $i++;
                    $image = $this->objIpCountry->getCountryFlag($item['countrycode']);
                    $country = $this->objIpCountry->getCountryName($item['countrycode']);
                                        
                    $flags .= "<img src = '$image' alt = '$country' title = '$country' />&nbsp;&nbsp;";
                }
            }
        }
        
        $str .= '<p>'.$lbCountries.': '.$i.'</p>';
        
        $this->objLayer->str = $flags;
        $this->objLayer->padding = '5px; padding-left: 10px';
        $str .= $this->objLayer->show();
                
        $objTab = new tabbedbox();
        $objTab->extra = 'style="background-color: #FCFAF2; padding: 5px;"';
        $objTab->addTabLabel($hdSite);
        $objTab->addBoxContent($str);
        
        return $objTab->show();        
    }
    
    /**
    * Method to generate the statistics by resource
    *
    * @access private
    * @return string html
    */
    private function getStatsByResource()
    {
        $resVisitsCount = $this->getStatistic('visit');
        $downloadsCount = $this->getStatistic('download');
        $resourceCount = $this->etdDbSubmissions->getCount();
        $monthVisit = $this->getStatsByMonth('visit');
        $monthDownload = $this->getStatsByMonth('download');
        $monthUpload = $this->getStatsByMonth('upload');
        
        $aveResourceVisits = 0;
        $aveResourceDownloads = 0;
        if($resourceCount > 0){
            $aveResourceVisits = round($resVisitsCount/$resourceCount,0);
            $aveResourceDownloads = round($downloadsCount/$resourceCount,0);
        }
                
        $hdResource = $this->objLanguage->languageText('phrase_resourcestats');
        $lbTotalResource = $this->objLanguage->languageText('mod_etd_totalresourcesavailable', 'etd');
        $lbTotalDownloads = $this->objLanguage->languageText('mod_etd_totalresourcesdownloaded', 'etd');
        $lbTotalHits = $this->objLanguage->languageText('mod_etd_totalvisitstoresources', 'etd');
        $lbAveRes = $this->objLanguage->languageText('mod_etd_avevisitsperresource', 'etd');
        $lbAveDown = $this->objLanguage->languageText('mod_etd_avedownloadsperresource', 'etd');
        $lbMonth = $this->objLanguage->languageText('word_month');
        $lbVisits = $this->objLanguage->languageText('word_visits');
        $lbDownloads = $this->objLanguage->languageText('word_downloads');
        $lbUploads = $this->objLanguage->languageText('phrase_newsubmissions');
        
        $objTable = new htmltable();
        $objTable->cellpadding = 5;
        
        $objTable->addRow(array($lbTotalResource.': '.$resourceCount));
        
        $objTable->startRow();
        $objTable->addCell($lbTotalHits.': '.$resVisitsCount, '40%');
        $objTable->addCell($lbAveRes.': '.$aveResourceVisits);
        $objTable->endRow();
        
        $objTable->addRow(array($lbTotalDownloads.': '.$downloadsCount, $lbAveDown.': '.$aveResourceDownloads));
        
        $str = $objTable->show();
        $str .= '<br /><br />';
        
        $hitArr = array();
        if(!empty($monthVisit)){
            foreach($monthVisit as $item){
                $hitArr[$item['month']]['visit'] = $item['cnt'];
            }
        }
        if(!empty($monthDownload)){
            foreach($monthDownload as $item){
                $hitArr[$item['month']]['download'] = $item['cnt'];
            }
        }
        if(!empty($monthUpload)){
            foreach($monthUpload as $item){
                $hitArr[$item['month']]['upload'] = $item['cnt'];
            }
        }
        
        $objTable = new htmltable();
        $objTable->cellpadding = 5;
        $objTable->width = '50%';
        $objTable->border = '1';
        $objTable->addHeader(array($lbMonth, $lbVisits, $lbDownloads, $lbUploads));
        
        if(!empty($hitArr)){
            foreach($hitArr as $key => $item){
                $month = $this->objDate->monthFull($key);
                $visits = 0; $downloads = 0; $uploads = 0;
                
                if(isset($item['visit'])){
                    $visits = $item['visit'];
                }
                if(isset($item['download'])){
                    $downloads = $item['download'];
                }
                if(isset($item['upload'])){
                    $uploads = $item['upload'];
                }
                
                $objTable->startRow();
                $objTable->addCell($month, '50%');
                $objTable->addCell($visits, '25%', '', 'center');
                $objTable->addCell($downloads, '25%', '', 'center');
                $objTable->addCell($uploads, '25%', '', 'center');
                $objTable->endRow();
            }
        }
        $this->objLayer->init();
        $this->objLayer->width = '80%';
        $this->objLayer->align = 'center';
        $this->objLayer->str = $objTable->show();
        $str .= $this->objLayer->show();
        $str .= '<br />';
        
        $objTab = new tabbedbox();
        $objTab->extra = 'style="background-color: #FCFAF2; padding: 5px;"';
        $objTab->addTabLabel($hdResource);
        $objTab->addBoxContent($str);
        
        return $objTab->show();
    }

    /**
    * Method to display the statistics for the whole site
    *
    * @access public
    * @return string html
    */
    public function showAll()
    {
        $head = $this->objLanguage->languageText('word_statistics');
        
        $this->objHead->str = $head;
        $this->objHead->type = 1;
        $str = $this->objHead->show();
        
        $layerStr = $this->getSiteStats();
        $layerStr .= '<p>'.$this->getSideBlock().'</p>';
        $layerStr .= '<p>'.$this->getStatsByResource().'</p>';
        
        $this->objLayer->init();
        $this->objLayer->str = $layerStr;
        $str .= $this->objLayer->show();
        
        return $str;
    }
}
?>