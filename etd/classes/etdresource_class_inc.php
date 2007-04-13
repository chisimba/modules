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
        
        $objHead = new htmlheading();
        $objHead->str = $hdHead;
        $objHead->type = 3;
        $headStr = $objHead->show();
        
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
        
        return $this->objFeatureBox->showContent($headStr, $str);
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
}
?>