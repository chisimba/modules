<?php
/**
* @package etd
*/

/**
* Template for the front page of the etd module.
* @param array $etd The etd data to be displayed.
* @param array $files The list files attached to the etd.
*/

$this->setLayoutTemplate('etd_layout_tpl.php');

// set up html elements
$objFeatureBox = $this->newObject('featurebox', 'navigation');
$objHead = $this->newObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$this->loadClass('tabbedbox', 'htmlelements');

// set up language items
$lbAbstract = $this->objLanguage->languageText('word_abstract');
$lbAudience = $this->objLanguage->languageText('word_audience');
$lbContributor = $this->objLanguage->languageText('word_contributor');
$lbCountry = $this->objLanguage->languageText('word_country');
$lbFaculty = $this->objLanguage->languageText('word_faculty');
$lbDegree = $this->objLanguage->languageText('phrase_degreeobtained');
$lbLevel = $this->objLanguage->languageText('phrase_degreelevel');
$lbGrantor = $this->objLanguage->languageText('word_grantor');
$lbDepartment = $this->objLanguage->languageText('word_department');
$lbFormat = $this->objLanguage->languageText('word_format');
$lbGrantor = $this->objLanguage->languageText('word_grantor');
$lbKeywords = $this->objLanguage->languageText('word_keywords');
$lbLanguage = $this->objLanguage->languageText('word_language');
$lbPublisher = $this->objLanguage->languageText('word_publisher');
$lbRelationship = $this->objLanguage->languageText('word_relationship');
$lbRights = $this->objLanguage->languageText('word_rights');
$lbSource = $this->objLanguage->languageText('word_source');
$lbDocType = $this->objLanguage->languageText('phrase_documenttype');
$lbYear = $this->objLanguage->languageText('word_year');
$lbFulltext = $this->objLanguage->languageText('mod_etd_downloadfulltext', 'etd');

/*
$lbNoTitle = $this->objLanguage->languageText('mod_etd_notitle');
$lbAuthor = $this->objLanguage->languageText('mod_etd_author');

$hdFile = $this->objLanguage->languageText('mod_etd_file');
$hdDescription = $this->objLanguage->languageText('mod_etd_description');
$hdsize = $this->objLanguage->languageText('mod_etd_size');
$hdFormat = $this->objLanguage->languageText('mod_etd_format');
$hdSubmitFiles = $this->objLanguage->languageText('mod_etd_submittedfiles');
$lbNoFiles = $this->objLanguage->languageText('mod_etd_nofiles');

$lbKb = $this->objLanguage->languageText('mod_etd_kb');
$lbBytes = $this->objLanguage->languageText('mod_etd_bytes');
$lbMb = $this->objLanguage->languageText('mod_etd_mb');

$typePDF = $this->objLanguage->languageText('mod_etd_pdf');
$typeWord = $this->objLanguage->languageText('mod_etd_msword');
$typeExcel = $this->objLanguage->languageText('mod_etd_excel');
$typeText = $this->objLanguage->languageText('mod_etd_plaintext');
$typeJpeg = $this->objLanguage->languageText('mod_etd_jpegimage');
$typeGif = $this->objLanguage->languageText('mod_etd_gifimage');
$typeBmp = $this->objLanguage->languageText('mod_etd_bmpimage');

$lbBack = $this->objLanguage->languageText('word_back');
$lbEditMeta = $this->objLanguage->languageText('mod_etd_editmetadata');
$lbMeta = $this->objLanguage->languageText('mod_etd_metadata');
$lbEditFiles = $this->objLanguage->languageText('mod_etd_editfiles');
$lbViewAdd = $this->objLanguage->languageText('mod_etd_viewaddinfo');
*/

//echo '<pre>'; print_r($resource); echo '</pre>';

$resourceStr = '';
if(!empty($resource)){
    
    // Create the heading using the title and author
    $objHead->str = $resource['dc_title'];
    $objHead->type = 2;
    $headStr = $objHead->show();
    
    $headStr .= '<p><u>'.$resource['dc_creator'].'</u></p>';
    
    // Display abstract
    $resourceStr .= '<p><b>'.$lbAbstract.':</b></p>';
    $resourceStr .= '<p style="padding-left: 10px;">'.$resource['dc_description'].'</p>';
    $resourceStr .= '<hr />';
    
    // Download the full text
    $fileData = $this->etdFiles->getFile($resource['submitid']);
    $url = $fileData[0]['filepath'].$fileData[0]['storedname'];
    $objIcon->setIcon('fulltext');
    $objIcon->title = $lbFulltext;
    $objLink = new link($url);
    $objLink->link = $objIcon->show();
    
    $resourceStr .= '<p><b>'.$lbFulltext.':</b> &nbsp; '.$objLink->show().'</p>';
    $resourceStr .= '<hr />';
        
    $objTable = new htmltable();
    $objTable->cellpadding = '5';

    // faculty
    if(!empty($resource['thesis_degree_discipline'])){
        $objTable->addRow(array('<b>'.$lbFaculty.':</b>', $resource['thesis_degree_discipline']));
    }

    // Degree name
    if(!empty($resource['thesis_degree_name'])){
        $objTable->addRow(array('<b>'.$lbDegree.':</b>', $resource['thesis_degree_name']));
    }
    
    // level
    if(!empty($resource['thesis_degree_level'])){
        $objTable->addRow(array('<b>'.$lbLevel.':</b>', $resource['thesis_degree_level']));
    }
       
    // grantor
    if(!empty($resource['thesis_degree_grantor'])){
        $objTable->addRow(array('<b>'.$lbGrantor.':</b>', $resource['thesis_degree_grantor']));
    }
    
    // Year/date
    if(!empty($resource['dc_date'])){
        $objTable->startRow();
        $objTable->addCell('<b>'.$lbYear.':</b>', '20%');
        $objTable->addCell($resource['dc_date']);
        $objTable->endRow();
    }
    
    $objTable->addRow(array('<hr />', '<hr />'));
       
    // Type
    if(!empty($resource['dc_type'])){
        $objTable->addRow(array('<b>'.$lbDocType.':</b>', $resource['dc_type']));
    }

    // Format
    if(!empty($resource['dc_format'])){
        $objTable->addRow(array('<b>'.$lbFormat.':</b>', $resource['dc_format']));
    }

    // Country
    if(!empty($resource['dc_coverage'])){
        $objTable->addRow(array('<b>'.$lbCountry.':</b>', $this->objLangCode->getName($resource['dc_coverage'])));
    }
    
    // Keywords
    if(!empty($resource['dc_subject'])){
        $objTable->addRow(array('<b>'.$lbKeywords.':</b>', $resource['dc_subject']));
    }
    
    $objTable->addRow(array('<hr />', '<hr />'));
    
    // Contributor
    if(!empty($resource['dc_contributor'])){
        $objTable->addRow(array('<b>'.$lbContributor.':</b>', $resource['dc_contributor']));
    }
    
    // Relationship
    if(!empty($resource['dc_relationship'])){
        $objTable->addRow(array('<b>'.$lbRelationship.':</b>', $resource['dc_relationship']));
    }
    
    // Rights
    if(!empty($resource['dc_rights'])){
        $objTable->addRow(array('<b>'.$lbRights.':</b>', $resource['dc_rights']));
    }
    
    // Publisher
    if(!empty($resource['dc_publisher'])){
        $objTable->addRow(array('<b>'.$lbPublisher.':</b>', $resource['dc_publisher']));
    }
       
    // Source
    if(!empty($resource['dc_source'])){
        $objTable->addRow(array('<b>'.$lbSource.':</b>', $resource['dc_source']));
    }

    // Language
    if(!empty($resource['dc_language'])){
        $objTable->addRow(array('<b>'.$lbLanguage.':</b>', $resource['dc_language']));
    }
    
    // Audience
    if(!empty($resource['dc_audience'])){
        $objTable->addRow(array('<b>'.$lbAudience.':</b>', $resource['dc_audience']));
    }
    
    $objTable->addRow(array('<hr />', '<hr />'));

    $resourceStr .= $objTable->show();

    
    /* *** Files attached to the resource *** *

    if($mode){
        $objLink = new link($this->uri(array('action' => 'editmetadata', 'page' => 3, 'submitId' => $resource['submitId'])));
        $objLink->link = $lbEditFiles;
        $hdSubmitFiles .= '&nbsp;&nbsp;(&nbsp;'.$objLink->show().'&nbsp;)';
    }

    $embargoed = FALSE;
    if(isset($resource['dc_rights']) && !(strpos(strtolower($resource['dc_rights']), 'embargo') === FALSE)){
        $time = substr($resource['dc_rights'], 10);
        if($time > time()){
            $embargoed = TRUE;
        }else{
            $embargoed = FALSE;
        }
    }

    if(!$embargoed){
        $objHead->str = $hdSubmitFiles;
        $objHead->type = 4;

        $fileStr = $objHead->show();

        if(!empty($files)){
            $objTable->init();
            $objTable->cellspacing = 2;
            $objTable->cellpadding = 4;

            $arrHd = array();
            $arrHd[] = $hdFile;
            $arrHd[] = $hdDescription;
            $arrHd[] = $hdsize;
            $arrHd[] = $hdFormat;
            $arrHd[] = '';

            $objTable->addHeader($arrHd, 'heading');

            $i = 0;
            foreach($files as $item){
                $class = (($i++ % 2) == 0) ? 'odd':'even';

                // format size
                if($item['size'] < 1000){
                    $size = $item['size'].'&nbsp;'.$lbBytes; // bytes
                }else if($item['size'] > 1000000){
                    $size = round($item['size']/1000000,2).'&nbsp;'.$lbMb; // megabytes
                }else{
                    $size = round($item['size']/1000).'&nbsp;'.$lbKb; // kilobytes
                }

                // format type
                $format = $item['filetype'];
                if(strpos($item['filetype'], 'pdf')){
                    $format = $typePDF;
                }else if(strpos($item['filetype'], 'msword')){
                    $format = $typeWord;
                }else if(strpos($item['filetype'], 'excel')){
                    $format = $typeExcel;
                }else if(!(strpos($item['filetype'], 'text/plain')===FALSE)){
                    $format = $typeText;
                }else if(!(strpos($item['filetype'], 'image/jpeg')===FALSE)){
                    $format = $typeJpeg;
                }else if(!(strpos($item['filetype'], 'image/gif')===FALSE)){
                    $format = $typeGif;
                }else if(!(strpos($item['filetype'], 'image/bmp')===FALSE)){
                    $format = $typeBmp;
                }

                $urlFullText = $this->uri(array('action' => 'downloadfile', 'fileid' => $item['fileId']));
                $icons = $objIcon->getLinkedIcon($urlFullText, 'fulltext');

                $objLink = new link($urlFullText);
                $objLink->link = $item['fileName'];
                $lnDownload = $objLink->show();

                $row = array();
                $row[] = $lnDownload;
                $row[] = $item['description'];
                $row[] = $size;
                $row[] = $format;
                $row[] = $icons;

                $objTable->addRow($row, $class);
            }
            $fileStr .= $objTable->show();
        }else{
            $fileStr .= '<i><b>'.$lbNoFiles.'</b></i>';
        }
    }else{
        $date = date('Y-m-d', $time);
        if(!empty($date)){
            $date = $this->objDate->formatDate($date);
        }
        $arrDate = array('enddate' => $date);
        $lbIsEmbargoed = $this->objLanguage->code2Txt('mod_etd_thesisunderembargo', $arrDate);
        $fileStr = '<p><b><i>'.$lbIsEmbargoed.'</i></b></p>';
    }
    $objLayer->cssClass = 'etdbox';
    $objLayer->str = $fileStr;
    $objLayer->align = 'left';

    $str .= $objLayer->show();
}
/* *** End ETD *** */

}

// Set session for printing / emailing resource
$this->setSession('resource', $resourceStr);

$str = $objFeatureBox->showContent('<center>'.$headStr.'</center>', $resourceStr);

echo $str.'<br />';
?>