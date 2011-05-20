<?php
/**
 *
 * sahris collectionsman helper class
 *
 * PHP version 5.1.0+
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   sahriscollectionsman
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (! /**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
// end security check


/**
 *
 * sahriscollectionsman helper class
 *
 * PHP version 5.1.0+
 *
 * @author Paul Scott
 * @package sahriscollectionsman
 *
 */
class sahriscollectionsops extends object {

    /**
     * @var string $objLanguage String object property for holding the language object
     *
     * @access public
     */
    public $objLanguage;

    /**
     * @var string $objConfig String object property for holding the config object
     *
     * @access public
     */
    public $objConfig;

    /**
     * @var string $objSysConfig String object property for holding the sysconfig object
     *
     * @access public
     */
    public $objSysConfig;

    /**
     * @var string $objUser String object property for holding the user object
     *
     * @access public
     */
    public $objUser;

    /**
     * Constructor
     *
     * @access public
     */
    public function init() {
        $this->objLanguage    = $this->getObject('language', 'language');
        $this->objConfig      = $this->getObject('altconfig', 'config');
        $this->objSysConfig   = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $this->objUser        = $this->getObject('user', 'security');
        $this->objDbColl      = $this->getObject('dbsahriscollections');
        $this->objFileIndexer = $this->getObject('indexfileprocessor', 'filemanager');
    }

    /**
     * Method to show a form to create a collection
     *
     * @access public
     * @param void
     * @return string form
     */
    public function addCollectionForm(){
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'sahriscollectionsman', 'Required').'</span>';
        $ret = NULL;
        // start the form
        $form = new form ('addcoll', $this->uri(array('action'=>'createcollection'), 'sahriscollectionsman'));
        $table = $this->newObject('htmltable', 'htmlelements');
        $defmsg = $this->objLanguage->languageText("mod_sahriscollections_defcollmsg", "sahriscollectionsman");
        
        // collection name
        $cn = new textinput();
        $cn->name = 'cn';
        $cn->width ='50%';
        $cnLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_collname', 'sahriscollectionsman').'&nbsp;', 'input_cn');
        $table->addCell($cnLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($cn->show());
        $table->endRow();
        
        // comment
        $comment = $this->newObject('htmlarea', 'htmlelements');
        $comment->name = 'comment';
        $comment->value = $defmsg;
        $comment->width ='50%';
        $commentLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_comment', 'sahriscollectionsman').'&nbsp;', 'input_comment');
        $table->addCell($commentLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($comment->show());
        $table->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = $this->objLanguage->languageText('mod_sahriscollectionsman_addcollection', 'sahriscollectionsman');
        $fieldset->contents = $table->show();
        // add the form to the fieldset
        $form->addToForm($fieldset->show());
        $button = new button ('submitform', $this->objLanguage->languageText("mod_sahriscollectionsman_addcollection", "sahriscollectionsman"));
        $button->setToSubmit();
        $form->addToForm('<p align="center"><br />'.$button->show().'</p>');
        $ret .= $form->show();       
        
        return $ret;
    }
    
    /**
     * Method to show a form to insert a record to a collection
     *
     * @access public
     * @param void
     * @return string form
     */
    public function addRecordForm(){
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'sahriscollectionsman', 'Required').'</span>';
        //$headermsg = new htmlheading();
        //$headermsg->type = 1;
        //$headermsg->str = $this->objLanguage->languageText('phrase_addrecord', 'collectionsman');
        $ret = NULL;
        //$ret .= $headermsg->show();
        // start the form
        $form = new form ('add', $this->uri(array('action'=>'addrec'), 'sahriscollectionsman'));
        $form->extra = 'enctype="multipart/form-data"';
        $table = $this->newObject('htmltable', 'htmlelements');
        $defmsg = $this->objLanguage->languageText("mod_sahriscollections_defmsg", "sahriscollectionsman");
        $table->startRow();
        // add some rules
        // $form->addRule('', $this->objLanguage->languageText("mod_collectionsman_needsomething", "collectionsman"), 'required');

        // dropdown collection field
        $coll = new dropdown('coll');
        $list = $this->objDbColl->getCollectionNames();
        // var_dump($list);
        if(empty($list)) {
            $list = array();
        }
        foreach($list as $item) {
            $coll->addOption($item['id'], $item['collname']);
        }
        $collLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_collection', 'sahriscollectionsman').'&nbsp;', 'input_coll');
        $table->addCell($collLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($coll->show());
        $table->endRow();

        // accession number
        $ano = new textinput();
        $ano->name = 'ano';
        $ano->width ='50%';
        $anoLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_accno', 'sahriscollectionsman').'&nbsp;', 'input_ano');
        $table->addCell($anoLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($ano->show());
        $table->endRow();

        // title
        $title = new textinput();
        $title->name = 'title';
        $title->width ='50%';
        $titleLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_title', 'sahriscollectionsman').'&nbsp;', 'input_title');
        $table->addCell($titleLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($title->show());
        $table->endRow();

        // description
        $desc = $this->newObject('htmlarea', 'htmlelements');
        $desc->name = 'desc';
        $desc->value = $defmsg;
        $desc->width ='50%';
        $descLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_description', 'sahriscollectionsman').'&nbsp;', 'input_desc');
        $table->addCell($descLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $msg->toolbarSet = 'simple';
        $table->addCell($desc->show());
        $table->endRow();

        // media
        $objUpload = $this->newObject('selectfile', 'filemanager');
        $objUpload->name = 'media';
        // $objUpload->restrictFileList = array('mp3');
        
        //$media = new textinput();
        //$media->name = 'media';
        //$media->width ='50%';
        $mediaLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_media', 'sahriscollectionsman').'&nbsp;', 'input_media');
        $table->addCell($mediaLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($objUpload->show());
        $table->endRow();

        // comment
        $comment = new textinput();
        $comment->name = 'comment';
        $comment->width ='50%';
        $commentLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_comment', 'sahriscollectionsman').'&nbsp;', 'input_comment');
        $table->addCell($commentLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($comment->show());
        $table->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = ''; // $this->objLanguage->languageText('phrase_invitefriend', 'userregistration');
        $fieldset->contents = $table->show();
        // add the form to the fieldset
        $form->addToForm($fieldset->show());
        $button = new button ('submitform', $this->objLanguage->languageText("mod_sahriscollectionsman_addrecord", "sahriscollectionsman"));
        $button->setToSubmit();
        $form->addToForm('<p align="center"><br />'.$button->show().'</p>');
        $ret .= $form->show();

        return $ret;
    }
    
    /**
     * Method to show a form to upload a collection csv file
     *
     * @access public
     * @param void
     * @return string form
     */
    public function uploadCsvForm() {
        $this->loadClass('form', 'htmlelements');
        // $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        // $this->loadClass('htmlarea', 'htmlelements');
        // $this->loadClass('dropdown', 'htmlelements');
        $required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'sahriscollectionsman', 'Required').'</span>';
        $ret = NULL;
        // start the form
        $form = new form ('uploadcsv', $this->uri(array('action'=>'importcsv'), 'sahriscollectionsman'));
        $form->extra = 'enctype="multipart/form-data"';
        $table = $this->newObject('htmltable', 'htmlelements');        
        // add some rules
        //$form->addRule('csv', $this->objLanguage->languageText("mod_sahriscollectionsman_needcsv", "sahriscollectionsman"), 'required');

        // csv file
        $table->startRow();
        $objUpload = $this->newObject('selectfile', 'filemanager');
        $objUpload->name = 'csv';
        $objUpload->restrictFileList = array('csv');
        $csvLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_csvfile', 'sahriscollectionsman').'&nbsp;', 'input_csv');
        $table->addCell($csvLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($objUpload->show().$required);
        $table->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = ''; // $this->objLanguage->languageText('phrase_invitefriend', 'userregistration');
        $fieldset->contents = $table->show();
        // add the form to the fieldset
        $form->addToForm($fieldset->show());
        $button = new button ('submitform', $this->objLanguage->languageText("mod_sahriscollectionsman_uploadcsv", "sahriscollectionsman"));
        $button->setToSubmit();
        $form->addToForm('<p align="center"><br />'.$button->show().'</p>');
        $ret .= $form->show();

        return $ret;
    }

    /**
     * Method to format a single retrieved record and display it
     *
     * @access public
     * @param array $record
     * @return string
     */
    public function formatRecord($record)
    {
        // var_dump($record); die();
        // $record = $record[0];
        $this->objWashout = $this->getObject('washout', 'utilities');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        $ret = NULL;
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->startRow();

        $collLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_collection', 'sahriscollectionsman').'&nbsp;', 'input_coll');
        $table->addCell($collLabel->show(), 150, NULL, 'right');
        $collname = $this->objDbColl->getCollById($record['collection']);
        $collname = $collname[0]['collname'];
        $table->addCell('&nbsp;', 5);
        $table->addCell($collname);
        $table->endRow();

        // accession number
        $anoLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_accno', 'sahriscollectionsman').'&nbsp;', 'input_ano');
        $table->addCell($anoLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($record['accno']);
        $table->endRow();

        // title
        $titleLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_title', 'sahriscollectionsman').'&nbsp;', 'input_title');
        $table->addCell($titleLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($record['title']);
        $table->endRow();

        // description
        $descLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_description', 'sahriscollectionsman').'&nbsp;', 'input_desc');
        $table->addCell($descLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $msg->toolbarSet = 'simple';
        $table->addCell($this->objWashout->parseText($record['description']));
        $table->endRow();

        // date created
        $dcLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_datecreated', 'sahriscollectionsman').'&nbsp;', 'input_datecreated');
        $table->addCell($dcLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($record['datecreated']);
        $table->endRow();

        // media
        $objFile = $this->getObject('dbfile', 'filemanager');
        $mediaLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_media', 'sahriscollectionsman').'&nbsp;', 'input_media');
        $table->addCell($mediaLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($objFile->getFileName($record['media']));
        $table->endRow();

        // comment
        $commentLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_comment', 'sahriscollectionsman').'&nbsp;', 'input_comment');
        $table->addCell($commentLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($record['comment']);
        $table->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = ''; // $this->objLanguage->languageText('phrase_invitefriend', 'userregistration');
        $fieldset->contents = $table->show();

        $ret .= $fieldset->show();

        return $ret;
    }
    
    public function menuBox() {
        $ret = NULL;
        $menubox = $this->newObject('featurebox', 'navigation');
        
        // create a collection
        $createcoll = $this->newObject('link', 'htmlelements');
        $createcoll->href = $this->uri(array('action' => 'collform'));
        $createcoll->link = $this->objLanguage->languageText("mod_sahriscollectionsman_createcollection", "sahriscollectionsman");
        $createcoll = $createcoll->show();
        
        // add a collection record
        $addrec = $this->newObject('link', 'htmlelements');
        $addrec->href = $this->uri(array('action' => 'addform'));
        $addrec->link = $this->objLanguage->languageText("mod_sahriscollectionsman_addrectocoll", "sahriscollectionsman");
        $addrec = $addrec->show();
        
        // search a collection record
        $searchrec = $this->newObject('link', 'htmlelements');
        $searchrec->href = $this->uri(array('action' => 'search'));
        $searchrec->link = $this->objLanguage->languageText("mod_sahriscollectionsman_searchrecords", "sahriscollectionsman");
        $searchrec = $searchrec->show();
        
        // import CSV
        $csvin = $this->newObject('link', 'htmlelements');
        $csvin->href = $this->uri(array('action' => 'uploadcsv'));
        $csvin->link = $this->objLanguage->languageText("mod_sahriscollectionsman_uploaddatafile", "sahriscollectionsman");
        $csvin = $csvin->show();
        
        // Sites list
        $sl = $this->newObject('link', 'htmlelements');
        $sl->href = $this->uri(array('action' => 'viewsites'));
        $sl->link = $this->objLanguage->languageText("mod_sahriscollectionsman_siteslist", "sahriscollectionsman");
        $sl = $sl->show();
        
        $txt = "<ul";
        $txt .= "<li>".$sl."</li>";
        $txt .= "<li>".$createcoll."</li>";
        $txt .= "<li>".$addrec."</li>";
        $txt .= "<li>".$searchrec."</li>";
        $txt .= "<li>".$csvin."</li>";
        $txt .= "</ul>";
        
        $ret = $menubox->show($this->objLanguage->languageText("mod_sahriscollectionsman_menu", "sahriscollectionsman"), $txt);
        return $ret; 
    }
    
    public function formatSearchResults($results) {
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cellspacing='1';
        $table->cellpadding='10';
            
        $table->startHeaderRow();
        $table->addHeaderCell('Accession Number');
        $table->addHeaderCell('Title');
        $table->addHeaderCell('Description');
        $table->addHeaderCell('Comment');
        $table->addHeaderCell('Date Created');
        $table->addHeaderCell('Action');
        $table->endHeaderRow();
        
        foreach($results as $row) {
            $table->startRow();
            $table->addCell($row['accno']);
            $table->addCell($row['title']);
            $table->addCell($row['description']);
            $table->addCell($row['comment']);
            $table->addCell($row['datecreated']);
            $objIcon = $this->newObject('geticon', 'htmlelements');
            $url = $this->uri(array('action' => 'viewsingle', 'id' => $row['id']));
            $objIcon->setIcon('visible', 'gif');
            $v = $this->newObject('link', 'htmlelements');
            $v->href = $url;
            $v->link = $objIcon->show();
            $table->addCell($v->show());
            $table->endRow();
        }
        
        return $table->show();
        
    }
    
    public function searchForm() {
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'sahriscollectionsman', 'Required').'</span>';
        $ret = NULL;
        $form = new form ('search', $this->uri(array('action'=>'search'), 'sahriscollectionsman'));
        $table = $this->newObject('htmltable', 'htmlelements');
        $q = new textinput();
        $q->name = 'q';
        $q->width ='50%';
        $qLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_keyword', 'sahriscollectionsman').'&nbsp;', 'input_q');
        $table->addCell($qLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($q->show());
        $table->endRow();
        
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = '';
        $fieldset->contents = $table->show();
        // add the form to the fieldset
        $form->addToForm($fieldset->show());
        $button = new button ('submitform', $this->objLanguage->languageText("mod_sahriscollectionsman_search", "sahriscollectionsman"));
        $button->setToSubmit();
        $form->addToForm('<p align="center">'.$button->show().'</p>');
        $ret .= $form->show();
        
        return $ret;
    }
    
    public function parseCSV($file) {
        $row = 1;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $rows[] = $data;
                $row++;
            }
            fclose($handle);
        }
        return $rows;
    }
    
    public function processMediaFromCSV($file, $username, $fname) {
        $userid = $this->objUser->getUserId($username);
        if(!file_exists($this->objConfig->getContentBasePath().'users/'.$userid."/"))
        {
            @mkdir($this->objConfig->getContentBasePath().'users/'.$userid."/");
            @chmod($this->objConfig->getContentBasePath().'users/'.$userid."/", 0777);
        }
        
        $objOverwriteIncrement = $this->getObject('overwriteincrement', 'filemanager');
        $fname = $objOverwriteIncrement->checkfile($fname, 'users/'.$userid);
        
        $localfile = $this->objConfig->getContentBasePath().'users/'.$userid."/".$fname;
        file_put_contents($localfile, $file);

        $fmname = basename($fname);
        $fmpath = 'users/'.$userid.'/'.$fmname;

        // Add to users fileset
        $fileId = $this->objFileIndexer->processIndexedFile($fmpath, $userid);
        
        return $fileId;
    }
    
    public function processCsvData($collarr) {
        foreach($collarr as $rec) {
            $sitename = $rec[0];
            $collection = $rec[1];
            $accno = $rec[2];
            $objtype = $rec[3];
            $title = $rec[4];
            $description = $rec[5];
            $objloc = $rec[6];
            $objstatus = $rec[7];
            $comment = $rec[8];
            $media64 = $rec[9];
            $filename = $rec[10];
            $username = $rec[11];
            if($media64 != NULL) {
                $media = $this->processMediaFromCSV($media64, $username, $filename);
            }
            else {
                $media = NULL;
            }
                    
            // parse the site name and optionally create a new one if needs be
            $sid = $this->objDbColl->getSiteByName($sitename);
            if($sid == NULL) {
                $siteabbr = metaphone($sitename, 3);
                $siteins = array('userid' => $this->objUser->userId($username), 'sitename' => $sitename, 'siteabbr' => $siteabbr, 
                                 'sitemanager' => NULL, 'sitecontact' => NULL, 'lat' => NULL, 'lon' => NULL, 'comment' => NULL);
                $sid = $this->objDbColl->addSiteData($siteins);
            }
                    
            $sitedet = $this->objDbColl->getSiteDetails($sid);
            $siteaccabbr = $sitedet[0]['siteabbr'];
            $sitecount = $this->objDbColl->countItemsInSite($sid);
                  
            $siteacc = $siteaccabbr.$sitecount;
                    
            // get the collection id from name
            $collid = $this->objDbColl->getCollByName($collection);
            if($collid == NULL) {
                // create a collection as it doesn't exist
                $insarr = array('userid' => $this->objUser->userId($username), 'collname' => $collection, 'comment' => NULL, 
                                'sitename' => $sitename, 'siteid' => $sid);
                $collid = $this->objDbColl->insertCollection($insarr);
            }
                    
            $insarr = array('userid' => $this->objUser->userId($username), 'siteid' => $sid, 'siteacc' => $siteacc,
                            'accno' => $accno, 'objtype' => $objtype, 'collection' => $collid, 
                            'title' => $title, 'description' => $description, 'media' => $media, 'comment' => $comment, 'location' => $objloc, 
                            'status' => $objstatus);
            $res = $this->objDbColl->insertRecord($insarr);
            $insarr = NULL;
            $media = NULL;
        }
    }
    
    public function formatSites($sites) {
        $ret = NULL;
        foreach($sites as $site) {
            $fb = $this->newObject('featurebox', 'navigation');
            $table = $this->newObject('htmltable', 'htmlelements');
            $edit = $this->newObject('geticon', 'htmlelements');
            $edit->setIcon('edit_sm');
            // edit link
            $ed = $this->newObject('link', 'htmlelements');
            $ed->href = $this->uri(array('action' => 'editsite', 'siteid' => $site['id']));
            $ed->link = $edit->show();
            
            $table->startRow();
            $smLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_sitemanager', 'sahriscollectionsman').'&nbsp;', 'input_sitemanager');
            $table->addCell($smLabel->show(), 150, NULL, 'left');
            $table->addCell('&nbsp;', 5);
            $table->addCell($site['sitemanager']);
            $table->endRow();
            
            $table->startRow();
            $scLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_sitecontact', 'sahriscollectionsman').'&nbsp;', 'input_sitecontact');
            $table->addCell($scLabel->show(), 150, NULL, 'left');
            $table->addCell('&nbsp;', 5);
            $table->addCell($site['sitecontact']);
            $table->endRow();
            
            // get the number of collections at the site
            $numcoll = $this->objDbColl->getCollCountBySite($site['id']);
            // collections link list
            $c = $this->newObject('link', 'htmlelements');
            $c->href = $this->uri(array('action' => 'viewcollection', 'siteid' => $site['id']));
            $c->link = $this->objLanguage->languageText("mod_sahriscollectionsman_viewcollectionsinsite", "sahriscollectionsman");
            $c = $c->show();
        
            $table->startRow();
            $ncLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_numcoll', 'sahriscollectionsman').'&nbsp;', 'input_numcoll');
            $table->addCell($ncLabel->show(), 150, NULL, 'left');
            $table->addCell('&nbsp;', 5);
            $table->addCell($numcoll." (".$c.")");
            $table->endRow();
            
            $ret .= $fb->show($site['sitename']." (".$site['siteabbr'].") ".$ed->show(), $table->show()); 
        }
        
        return $ret;
    }
    
    /**
     * Method to show a form to edit a site
     *
     * @access public
     * @param void
     * @return string form
     */
    public function editSiteForm($siteid) {
        $d = $this->objDbColl->getSiteDetails($siteid);
        $d = $d[0];
        
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'sahriscollectionsman', 'Required').'</span>';
      
        $ret = NULL;
        
        // start the form
        $form = new form ('add', $this->uri(array('action'=>'updatesitedata', 'id' => $d['id']), 'sahriscollectionsman'));
        $form->extra = 'enctype="multipart/form-data"';
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->startRow();
        // add some rules
        // $form->addRule('', $this->objLanguage->languageText("mod_collectionsman_needsomething", "collectionsman"), 'required');

        // Site name
        $sn = new textinput();
        $sn->name = 'sn';
        $sn->width ='50%';
        $sn->setValue($d['sitename']);
        $snLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_sitename', 'sahriscollectionsman').'&nbsp;', 'input_sn');
        $table->startRow();
        $table->addCell($snLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($sn->show());
        $table->endRow();
        
        // Site Manager
        $sm = new textinput();
        $sm->setValue($d['sitemanager']);
        $sm->name = 'sm';
        $sm->width ='50%';
        $smLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_sitemanager', 'sahriscollectionsman').'&nbsp;', 'input_sm');
        $table->startRow();
        $table->addCell($smLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($sm->show());
        $table->endRow();

        // Site contact
        $sc = $this->newObject('htmlarea', 'htmlelements');
        $sc->name = 'sc';
        $sc->value = $d['sitecontact'];
        $sc->width ='80%';
        $scLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_sitecontact', 'sahriscollectionsman').'&nbsp;', 'input_sc');
        $table->startRow();
        $table->addCell($scLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $msg->toolbarSet = 'simple';
        $table->addCell($sc->show());
        $table->endRow();
        
        // Site comment
        $scom = new textinput();
        $scom->name = 'scom';
        $scom->setValue($d['comment']);
        $scom->width ='50%';
        $scomLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_sitecomment', 'sahriscollectionsman').'&nbsp;', 'input_scom');
        $table->startRow();
        $table->addCell($scomLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($scom->show());
        $table->endRow();
        
        // site location map
        $map = $this->geolocformelement($d);
        $gtlabel = new label($this->objLanguage->languageText("mod_sahriscollectionsman_geoposition", "sahriscollectionsman") . ':', 'input_geotags');
        $gtags = '<div id="map"></div>';
        $geotags = new textinput('geotag', NULL, NULL, '100%');
        if (isset($d['lat']) && isset($d['lon'])) {
            $geotags->setValue($d['lat'].", ".$d['lon']);
        }
        
        $table->startRow();
        $table->addCell($gtlabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($gtags.$geotags->show());
        $table->endRow();
        
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = ''; // $this->objLanguage->languageText('phrase_invitefriend', 'userregistration');
        $fieldset->contents = $table->show();
        // add the form to the fieldset
        $form->addToForm($fieldset->show());
        $button = new button ('submitform', $this->objLanguage->languageText("mod_sahriscollectionsman_updatesite", "sahriscollectionsman"));
        $button->setToSubmit();
        $form->addToForm('<p align="center"><br />'.$button->show().'</p>');
        $ret .= $form->show();

        return $ret;
    }
    
    /**
     * Method used to set geolocation coordinates
     *
     * Users are able to set geographic coordinates by either completing a text input or clicking on a map
     *
     * @param array $editparams
     * @param boolean $eventform
     * @return string
     */
    public function geolocformelement($d) {
        $lat = $d['lat'];
        $lon = $d['lon'];
        if($lat == NULL || $lon == NULL) {
            //latlon defaults -29.113775395114,  26.2353515625
            $lat = -29.113775395114;
            $lon = 26.2353515625;
        }
        $zoom = 5;
        $gmapsapikey = $this->objSysConfig->getValue('mod_simplemap_apikey', 'simplemap');
        $css = '<style type="text/css">
        #map {
            width: 100%;
            height: 350px;
            border: 1px solid black;
            background-color: white;
        }
    </style>';

        $google = "<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$gmapsapikey."' type=\"text/javascript\"></script>";
        $olsrc = $this->getJavascriptFile('lib/OpenLayers.js','georss');
        $js = "<script type=\"text/javascript\">
        var lon = 5;
        var lat = 40;
        var zoom = 17;
        var map, layer, drawControl, g;

        OpenLayers.ProxyHost = \"/proxy/?url=\";
        function init(){
            g = new OpenLayers.Format.GeoRSS();
            map = new OpenLayers.Map( 'map' , { controls: [] , 'numZoomLevels':20, projection: new OpenLayers.Projection(\"EPSG:900913\"), displayProjection: new OpenLayers.Projection(\"EPSG:4326\") });
            var normal = new OpenLayers.Layer.Google( \"Google Map\" , {type: G_NORMAL_MAP, 'maxZoomLevel':18} );
            var hybrid = new OpenLayers.Layer.Google( \"Google Hybrid Map\" , {type: G_HYBRID_MAP, 'maxZoomLevel':18} );
            
            map.addLayers([normal, hybrid]);

            map.addControl(new OpenLayers.Control.MousePosition());
            map.addControl( new OpenLayers.Control.MouseDefaults() );
            map.addControl( new OpenLayers.Control.LayerSwitcher() );
            map.addControl( new OpenLayers.Control.PanZoomBar() );

            map.setCenter(new OpenLayers.LonLat($lon,$lat), $zoom);

            map.events.register(\"click\", map, function(e) {
                var lonlat = map.getLonLatFromViewPortPx(e.xy);
                OpenLayers.Util.getElement(\"input_geotag\").value = lonlat.lat + \",  \" +
                                          + lonlat.lon
            });

        }
    </script>";

        // add the lot to the headerparams...
        $this->appendArrayVar('headerParams', $css.$google.$olsrc.$js);
        $this->appendArrayVar('bodyOnLoad', "init();");
    } 
    
    public function formatCollections($colls) {
        $ret = NULL;
        foreach($colls as $coll) {
            //var_dump($coll);
            $this->objWashout = $this->getObject('washout', 'utilities');
            $this->loadClass('label', 'htmlelements');
            $this->loadClass('htmlheading', 'htmlelements');
            $this->loadClass('htmlarea', 'htmlelements');
            $ret = NULL;
            $table = $this->newObject('htmltable', 'htmlelements');
            
            $table->startRow();
            $snLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_sitename', 'sahriscollectionsman').'&nbsp;', 'input_coll');
            $table->addCell($snLabel->show(), 150, NULL, 'right');
            $sname = $coll['sitename'];
            $table->addCell('&nbsp;', 5);
            $table->addCell($sname);
            $table->endRow();
            
            $table->startRow();
            $collLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_collection', 'sahriscollectionsman').'&nbsp;', 'input_coll');
            $table->addCell($collLabel->show(), 150, NULL, 'right');
            $collname = $coll['collname'];
            $table->addCell('&nbsp;', 5);
            $table->addCell($collname);
            $table->endRow();
            
            // comment
            $table->startRow();
            $commLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_notes', 'sahriscollectionsman').'&nbsp;', 'input_coll');
            $table->addCell($commLabel->show(), 150, NULL, 'right');
            $comm = $coll['comment'];
            $table->addCell('&nbsp;', 5);
            $table->addCell($comm);
            $table->endRow();
            
            // date created
            $table->startRow();
            $dcLabel = new label($this->objLanguage->languageText('mod_sahriscollectionsman_datecreated', 'sahriscollectionsman').'&nbsp;', 'input_coll');
            $table->addCell($dcLabel->show(), 150, NULL, 'right');
            $dc = $coll['datecreated'];
            $table->addCell('&nbsp;', 5);
            $table->addCell($dc);
            $table->endRow();
            
            // view records link
            $r = $this->newObject('link', 'htmlelements');
            $r->href = $this->uri(array('action' => 'viewrecords', 'collid' => $coll['id']));
            $r->link = $this->objLanguage->languageText("mod_sahriscollectionsman_viewrecordsincoll", "sahriscollectionsman");
            $r = $r->show();
            $table->startRow();
            $table->addCell('');
            $table->addCell('&nbsp;', 5);
            $table->addCell($r);
            $table->endRow();
            
            $fieldset = $this->newObject('fieldset', 'htmlelements');
            $fieldset->legend = ''; // $this->objLanguage->languageText('phrase_invitefriend', 'userregistration');
            $fieldset->contents = $table->show();

            $ret .= $fieldset->show();

        
        }
        return $ret;
    }  
}
?>
