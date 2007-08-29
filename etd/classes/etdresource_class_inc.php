<?php
/**
* etdresource class extends object
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Provides a set of methods for displaying or manipulating resources
*
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

class etdresource extends object
{       
    /**
    * Constructor method
    */
    public function init()
    {
        $this->dbSubmit = $this->getObject('dbsubmissions', 'etd');
        
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objFeatureBox = $this->newObject('featureBox', 'navigation');

        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
    }
    
    /**
    * Method to display the ten most recent submissions / resources uploaded.
    *
    * @access public
    * @return string html
    */
    public function getRecentResources()
    {
        $data = $this->dbSubmit->getLatest();
        
        $hdHead = $this->objLanguage->languageText('phrase_recentadditions');
        $lbError = $this->objLanguage->languageText('mod_etd_noresourcessubmitted', 'etd');
        $hdTitle = $this->objLanguage->languageText('word_title');
        $hdAuthor = $this->objLanguage->languageText('word_author');
        $hdDate = $this->objLanguage->languageText('word_date');
        
        $str = '';
        
        if(!empty($data)){
            $str .= '<br />';
            
            $objTable = new htmltable();
            $objTable->cellspacing = '2';
            $objTable->cellpadding = '5';
            
            $hdArr = array();
            $hdArr[] = $hdTitle;
            $hdArr[] = $hdAuthor;
            $hdArr[] = $hdDate;
            
            $objTable->addHeader($hdArr);
            
            $class = 'odd';
            foreach($data as $item){
                $class = ($class == 'odd') ? 'even':'odd';
                
                $objLink = new link($this->uri(array('action' => 'viewtitle', 'id' => $item['thesis_id'])));
                $objLink->link = $item['dc_title'];
                
                $rowArr = array();
                $rowArr[] = $objLink->show();
                $rowArr[] = $item['dc_creator'];
                $rowArr[] = $item['dc_date'];
                
                $objTable->addRow($rowArr, $class);
            }
            $str .= $objTable->show();
        }else{
            $str .= "<p class='noRecordsMessage'>".$lbError.'</p>';
        }
        
        return $this->objFeatureBox->showContent($hdHead, $str);
    }
    
    /**
    * Method to display items saved on the virtual shelf or E-shelf
    * All items on the e-shelf are stored in session.
    *
    * @access public
    * @return string html
    */
    public function showEShelf()
    {
        $eshelf = $this->getSession('eshelf');
        $objThesis = $this->getObject('dbthesis', 'etd');
        $objIcon = $this->newObject('geticon', 'htmlelements');
        
        $head = $this->objLanguage->languageText('mod_etd_eshelf', 'etd');
        $lbNoSearches = $this->objLanguage->languageText('mod_etd_eshelfisempty', 'etd');
        $lbNoArticles = $this->objLanguage->languageText('mod_etd_noarticlesselected', 'etd');
        $hdSearches = $this->objLanguage->languageText('phrase_storedsearches');
        $lbRemove = $this->objLanguage->languageText('phrase_removearticles');
        $lbRepeat = $this->objLanguage->languageText('phrase_repeatsearch');
        $lbClear = $this->objLanguage->languageText('phrase_clearsearch');
        
        $objHead = new htmlheading();
        $objHead->str = $head;
        $objHead->type = 1;
        $str = $objHead->show();
        
        if(!empty($eshelf)){
            $eStr = ''; $i = 1;
            foreach($eshelf as $key => $item){
                $hdCirt = $item['criteria'];
                $link = '';
                
                $objLink = new link($this->uri(array('action' => 'repeatsearch', 'search' => 'key_'.$key)));
                $objLink->link = $lbRepeat;
                $shelfStr = $objLink->show();
                
                $objLink = new link($this->uri(array('action' => 'clearsearch', 'search' => 'key_'.$key)));
                $objLink->link = $lbClear;
                $shelfStr .= '&nbsp;&nbsp;'.$objLink->show();
                
                $objTable = new htmltable();
                $objTable->cellpadding = 5;
                
                if(!empty($item['selected'])){
                    // Get resource data from the DB
                    $data = $objThesis->getFromList($item['selected']);
                    
                    if(!empty($data)){
                        $class = 'even';
                        foreach($data as $article){
                             $class = ($class == 'odd') ? 'even' : 'odd';
                             
                             // check box for removing articles from shelf
                             $objCheck = new checkbox('articles[]');
                             $objCheck->setValue($article['metaid']);
                             $check = $objCheck->show();
                             
                             $objLink = new link($this->uri(array('action' => 'viewtitle', 'id' => $article['metaid'])));
                             $objLink->link = $article['dc_title'];
                             $title = $objLink->show();
                             
                             $objTable->startRow();
                             $objTable->addCell($check, '3%', '','', $class);
                             $objTable->addCell($title, '', '','', $class);
                             $objTable->endRow();
                        }
                    }
                    $objLink = new link('#');
                    $objLink->extra = "onclick=\"javascript: document.forms['remove{$i}'].submit();\"";
                    $objLink->link = $lbRemove;
                    $link = '<p>'.$objLink->show().'</p>';
                }else{
                    $objTable->addRow(array($lbNoArticles), 'noRecordsMessage');
                }
                
                // hidden element with the search criteria
                $objInput = new textinput('criteria', $item['criteria'], 'hidden');
                $hidden = $objInput->show();
                
                $objForm = new form('remove'.$i++, $this->uri(array('action' => 'removeeshelf')));
                $objForm->addToForm($hidden.$objTable->show().$link);
                $shelfStr .= $objForm->show();
                
                $eStr .= $this->objFeatureBox->showContent($hdCirt, $shelfStr);
            }
            
            $str .= $eStr; //$this->objFeatureBox->showContent($hdSearches, $eStr);
            
        }else{
            $str .= '<p class="noRecordsMessage">'.$lbNoSearches.'</p>';
        }
        
        return $str;
    }
    
    /**
    * Method to display the javascript for the e-shelf
    *
    * @access private
    * @return void
    */
    private function showJavascript()
    {
        $javascript = "<script type=\"text/javascript\">
        
                
        
            </script>";
        
        $this->appendArrayVar('headerParams', $javascript);
    }
    
    /**
    * Method that generates metadata tags for display in the resource.
    *
    * @access public
    * @param array $data The resource metadata.
    * @return string html
    */
    public function getMetadataTags($data)
    {
        if(!empty($data)){
            $str = "\n<link rel='schema.DC' href='http://purl.org/dc/elements/1.1/' />\n";
            
            foreach($data as $key => $item){
                $check = strpos($key, 'dc_');
                $check2 = strpos($key, 'thesis_');
                                
                if($check !== FALSE || $check2 !== FALSE){
                    $metaName = str_replace('_', '.', $key);
                    $metaContent = htmlentities($item);
                    $str .= "<meta name='{$metaName}' content=\"{$metaContent}\" />\n";
                }
            }
        }
        return $str;
    }
    
    /**
    * Method to display the citation in one of a given number of formats
    *
    * @access public
    * @return string html
    */
    public function showCitation()
    {
        /*
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        
        $lbCitation = $this->objLanguage->languageText('word_citation');
        $lbExportRW = $this->objLanguage->languageText('mod_etd_exportrefworks', 'etd');
        /*
        $formStr = '';
        $import = '';
        
        $objText = new textarea('ImportData', $import);
        
        $url = "http://www.refworks.com/express/expressimport.asp";
        $onclick = "javascript: openWindow('{$url}', 'RefWorksMain');";
        $objButton = new button('export', $lbExportRW);
        $objButton->setOnClick($onclick);
        $formStr .= $objButton->show();
        
        $refUrl = "http://www.refworks.com/express/ExpressImport.asp?vendor=Your%20Service%20Name&filter=RefWorks%20Tagged%20Format&encoding=65001";
        $objForm = new form('ExportRWForm', $refUrl);
        $objForm->addToForm($formStr);
        $str = $objForm->show();
        *
        
        $url = "http://www.refworks.com/express/expressimport.asp";
        $onclick = "javascript: window.open('{$url}', 'RefWorksMain', 'top=0, left=0, screenX=0, screenY=0');";
        $objButton = new button('export', $lbExportRW);
        $objButton->setOnClick($onclick);
        $str = $objButton->show();
        
        $objTab = new tabbedbox();
        $objTab->extra = 'style="background-color: #FCFAF2; padding: 2px;"';
        $objTab->addTabLabel($lbCitation);
        $objTab->addBoxContent($str);
        
        return $objTab->show();
        */
    }
}
?>