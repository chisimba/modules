<?php
    // security check - must be included in all scripts
    if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }
    // end security check

/**
* Data access class for the template manager.
*
* @package cmsadmin
* @category chisimba
* @copyright AVOIR
* @license GNU GPL
* @author Charl Mert <charl.mert@gmail.com>
*/

    class dbtemplate extends dbTable
    {

        /**
        * The user object
        *
        * @access private
        * @var object
        */
        protected $_objUser;


        /**
        * The dbfrontpage object
        *
        * @access private
        * @var object
        */
        protected $_objFrontPage;

        /**
        * The language object
        *
        * @access private
        * @var object
        */
        protected $_objLanguage;

        /**
        * The blocks object
        *
        * @access private
        * @var object
        */
        protected $_objBlocks;

        /**
        * @var string $templateBasePath Path to fckeditor templates (not uri but file access path)
        */
        public $templateBasePath;

        /**
        * @var string $fck_version Which version of FCKEditor to load (2.5.1 vs 2.6.3)
        */
        public $fckVersion;


        /**
        * Class Constructor
        *
        * @access public
        * @return void
        */
        public function init()
        {
            try {
                parent::init('tbl_cms_templates');
                $this->table = 'tbl_cms_templates';
                $this->_objSectionGroup = & $this->getObject('dbsectiongroup', 'cmsadmin');
                $this->_objUser = & $this->getObject('user', 'security');
                $this->_objSecurity = & $this->getObject('dbsecurity', 'cmsadmin');
                $this->_objFrontPage = & $this->newObject('dbcontentfrontpage', 'cmsadmin');
                $this->_objLanguage = & $this->newObject('language', 'language');
                $this->_objBlocks = & $this->newObject('dbblocks', 'cmsadmin');
                $this->_objSysConf = $this->getObject('dbsysconfig', 'sysconfig');
                //Loading the default FCK version from config
                $this->fckVersion = $this->_objSysConf->getValue('FCKEDITOR_VERSION', 'htmlelements');
            } catch (Exception $e){
                throw customException($e->getMessage());
                exit();
            }
        }


        /**
         * Method to save the FCK Template XML file
         * This file gets used to load content templates when creating content
         *
         * @access public
         * @return bool
         */
        public function saveXml()
        {
            $objConfig =  $this->newObject('altconfig', 'config');

            $arrTemplate = $this->getTemplatePages();

$xmlTemplateFile = '<?xml version="1.0" encoding="utf-8" ?>
<!--
 * This file was generated by Chisimba Content Management System\'s Template Manager
 *
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2007 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * This is the sample templates definitions file. It makes the "templates"
 * command completely customizable.
 *
 * See FCKConfig.TemplatesXmlPath in the configuration file.
<Templates imagesBasePath="'.$this->templateBasePath = $objConfig->getcontentBasePath().'cmstemplates/'.'">
-->
<Templates imagesBasePath="fck_template/images/">
';
            
            foreach ($arrTemplate as $template) {

                    //<Template title="'.htmlentities($template['title']).'" image="'.$objConfig->getSitePath().htmlentities($template['image']).'"> //TEMPLATE_IMAGE
                if ($template['published'] == '1') {
                    $xmlTemplateFile .= '
                        <Template title="'.htmlentities($template['title']).'" image="'.htmlentities($template['image']).'">
                            <Description>'.htmlentities($template['description']).'</Description>
                            <Html>
                                <![CDATA[
                                    '.stripslashes($template['body']).'
                                ]]>
                            </Html>
                        </Template>
                    ';
                }
            }

            $xmlTemplateFile .= '</Templates>';

            $this->templateBasePath = $objConfig->getcontentBasePath().'cmstemplates/'.$this->fckVersion.'/fcktemplates.xml';

            //Ensuring the fcktemplate file is created
            if(!file_exists($objConfig->getcontentBasePath().'cmstemplates/'.$this->fckVersion.'/'))
            {
                mkdir($objConfig->getcontentBasePath().'cmstemplates/'.$this->fckVersion.'/', 0777, true);
            }

            //Writting the file to disk
            $fp = fopen($this->templateBasePath, 'w') or log_debug('CMS Templates: Could\'nt Save template file: ['.$this->templateBasePath.']');
            fwrite($fp, $xmlTemplateFile);
            fclose($fp);

            return true;
        }


        /**
         * Method to return the current and next levels child template
         *
         * @access public
         * @return bool
         */
        public function getChildTemplate($sectionid, $admin, $filter){
            //get current parents child template
            $arrTemplate = $this->getAll('WHERE sectionid = \''.$sectionid.'\' AND published=1 AND trash=0 '.$filter);
            //getting the next level of sections child template
            $nodes = $this->_objSectionGroup->getChildNodes($sectionid, $admin);
                        
            if (!empty($nodes)) {
                //var_dump($nodes);
                foreach($nodes as $node) {
                    $subSecId = $node[id];
                    //var_dump($subSecId.$node);
                    $nextArrTemplate = $this->getAll('WHERE sectionid = \''.$subSecId.'\' AND published=1 AND trash=0 '.$filter);
                    //var_dump($nextArrTemplate);
                    if (!empty($nextArrTemplate)){
                        //var_dump('Root node is empty');
                        array_push($arrTemplate, $nextArrTemplate);
                    }
                                        
                }
            }
            //var_dump($arrTemplate[0]);
            return $arrTemplate;	
        }




        /**
         * Method to save a record to the database
         *
         * @access public
         * @return bool
         */
        public function add()
        {
            //Create htmlcleaner object
            $objHtmlCleaner = $this->newObject('htmlcleaner', 'utilities');

            //Get details of the new entry
            $title = $this->getParam('title');
            $imagePath = $this->getParam('imagepath',null);
            $description = $this->getParam('description');
            $published = ($this->getParam('published') == '1') ? 1 : 0;
            $creatorid = $this->getParam('creator',null);
            if ($creatorid==NUll) {
                $creatorid = $this->_objUser->userId();
            }
            $fullText = $this->getParam('body');
            $created_by = $this->getParam('title_alias',null);
            $fullText = str_ireplace("<br />", " <br /> ", $fullText);

            $newArr = array(
                          'title' => $title ,
                          'description' => $description ,
                          'image' => $imagePath ,
                          'body' => addslashes($fullText),
                          'published' => $published,
                          'created' => $this->now(),
                          'created_by' => $creatorid
            );
            $newId = $this->insert($newArr);
            $newArr['id'] = $newId;

            //Saving the FCKEditor Templates XML File
            $this->saveXml();

            return $newId;
        }


        /**
         * Method to add a template to the database
         * @param string $title The title of the new section
         * @param string $description The Description of the template
         * @param string $body The content of the template
         * @param string $imagePath The path to the templates description image
         * @param bool $published Whether template will be visible or not
         * @access public
         * @return templateId on success and FALSE on faliure
         */
        public function addTemplate($title, $description, $body, $imagePath, $published)
        {
            $creatorid = $this->_objUser->userId();

            $fullText = str_ireplace("<br />", " <br /> ", $body);

            $newArr = array(
                          'title' => $title ,
                          'description' => $description ,
                          'image' => $imagePath ,
                          'body' => addslashes($fullText),
                          'published' => $published,
                          'created' => $this->now(),
                          'created_by' => $creatorid
            );
            $newId = $this->insert($newArr);
            $newArr['id'] = $newId;

            //Saving the FCKEditor Templates XML File
            $this->saveXml();

            return $newId;
        }




        /**
         * Method to edit a record
         *
         * @access public
         * @return bool
         */
        public function edit()
        {
            $id = $this->getParam('id', '');

            //Get details of the new entry
            $title = $this->getParam('title');
            $imagePath = $this->getParam('imagepath',null);
            $description = $this->getParam('description');
            $published = ($this->getParam('published') == '1') ? 1 : 0;
            $creatorid = $this->getParam('creator',null);
            if ($creatorid==NUll) {
                $creatorid = $this->_objUser->userId();
            }
            $fullText = $this->getParam('body');
            $fullText = str_ireplace("<br />", " <br /> ", $fullText);
            $created_by = $this->getParam('title_alias',null);

            $newArr = array(
                          'title' => $title ,
                          'description' => $description ,
                          'image' => $imagePath ,
                          'body' => addslashes($fullText),
                          'published' => $published,
                          'created' => $this->now(),
                          'created_by' => $creatorid
            );

            $result = $this->update('id', $id, $newArr);

            //Saving the FCKEditor Templates XML File
            $this->saveXml();
            
            return $result;
        }

        /**
        * Method to delete a template
        *
        * @param string $id The id of the template
        * @return boolean
        * @access public
        */
        public function deleteTemplate($id)
        {
            //Delete Template
            $result = $this->delete('id', $id);
            
            return $result;
        }

        /**
         * Method move a record to trash
         *
         * @param string $id The id of the record that needs to be deleted
         * @access public
         * @return bool
         */
        public function trashTemplate($id)
        {  
            //First remove from front page
            $this->_objFrontPage->removeIfExists($id);
            
            $fields = array('trash' => 1, 'ordering' => '', 'end_publish' => $this->now());
            $result =  $this->update('id', $id, $fields);
            
            // Get the section id of the page - re order pages
            $pageData = $this->getTemplatePage($id);
            $sectionId = $pageData['sectionid'];
            $this->reorderTemplate($sectionId);
            
            $objLucene = $this->getObject('indexdata', 'search');
            $objLucene->removeIndex('cms_page_'.$id);
            
            return $result;
        }

        /**
        * Method to reorder the template in a section 
        * After a page is trashed, etc
        *
        * @author Megan Watson
        * @param string $sectionId The id of the section containing the template
        * @access private
        * @return bool
        */
        private function reorderTemplate($sectionId)
        {   
            // Get all pages in the section
            $sectionData = $this->getPagesInSection($sectionId, FALSE);
            
            if(!empty($sectionData)){
                // Reorder the pages
                $i = 1;
                foreach($sectionData as $key => $item){
                    if($item['trash'] == 0){
                        $this->update('id', $item['id'], array('ordering' => $i));
                        $sectionData[$key]['ordering'] = $i++;
                    }
                }
                
                // Get the ordering position of the last section
                $newData = array_reverse($sectionData);
                $lastOrder = $newData[0]['ordering']+1;
                
                // Remove all null and negative numbers
                foreach($sectionData as $key => $item){
                    if(($item['ordering'] < 0 || is_null($item['ordering'])) && $item['trash'] == 0){
                        $this->update('id', $item['id'], array('ordering' => $lastOrder));
                        $sectionData[$key]['ordering'] = $lastOrder++;
                    }
                }
            }
        }

        /**
         * Method to undelete template
         *
         * @param string $id The id of the record that needs to be deleted
         * @access public
         * @return bool
         */
        public function undelete($id)
        {
            $page = $this->getRow('id', $id);
            
            if ($page == FALSE)
            {
                return FALSE;
            } else {
                $order = $this->getOrdering($page['sectionid']);
                $fields = array('trash' => 0, 'ordering' => $order);
                
                $this->luceneIndex($page);
                
                return $this->update('id', $id, $fields);
            }
        }

        /**
         * Method to get the template
         *
         * @param string $filter The Filter
         * @return  array An array of associative arrays of all template pages in relationto filter specified
         * @access public
         */
        public function getTemplatePages($filter = '')
        {
            if ($filter == 'trash') {
                $filter = ' WHERE trash=1 ';
            } else {
                $filter = ' WHERE trash=0 ';
            }
            
            //return $this->getAll($filter.' ORDER BY ordering'); //TODO: Will implement ordering for templates at a later stage
            return $this->getAll($filter);
        }

        /**
         * Method to get the archived template
         *
         * @author Megan Watson
         * @param string $filter The Filter
         * @return  array An array of associative arrays of all template pages in relationto filter specified
         * @access public
         */
        public function getArchivePages($filter = '')
        {
            $sql = "SELECT * FROM {$this->table} WHERE trash = 1 ";
            
            if(!empty($filter)){
                $sql .= "AND LOWER(title) LIKE '%".strtolower($filter)."%' ";
            }
            
            $sql .= 'ORDER BY ordering';
            return $this->getArray($sql);
        }

        /**
         * Method to get a page template record
         *
         * @param string $id The id of the page template
         * @access public
         * @return array $template An associative array of template page details
         */
        public function getTemplatePage($id)
        {
            $template = $this->getRow('id', $id );
            return $template;
        }

        /**
         * Method to get a filtered page template record
         *
         * @param string $id The id of the page template
         * @access public
         * @return array $template An associative array of template page details
         */
        public function getTemplatePageFiltered($id, $filter = '')
        {
            if ($filter == 'trash') {
                $filter = ' WHERE trash=1 ';
            } else {
                $filter = ' WHERE trash=0 ';
            }
            
            $template = $this->getAll($filter." AND id = '$id' ORDER BY ordering");
            $template = $template[0];
            
            return $template;
        }
        
        
        /**
         * Method to toggle the publish field
         *
         * @param string id The id if the template
         * @access public
         * @return boolean
         * @author Wesley Nitsckie
         */
        public function togglePublish($id)
        {
            $row = $this->getTemplatePage($id);

            if ($row['published'] == 1) {
                return $this->update('id', $id , array('published' => 0, 'end_publish' => $this->now(), 'start_publish' => '') );
            } else {
                return $this->update('id', $id , array('published' => 1, 'start_publish' => $this->now()) );
            }
        }
        
        /**
         * Method to publish or unpublish template 
         * 
         * @param string id The id if the template
         * @param string $task Publish or unpublish
         * @access public
         * @return boolean
         * @author Megan Watson, Charl Mert
         */
        public function publish($id, $task = 'publish')
        {
            switch($task){
                case 'publish':
                $fields['published'] = 1;
                break;
                case 'unpublish':
                $fields['published'] = 0;
                break;
            }
            $newId = $this->update('id', $id, $fields);

            //Saving the FCKEditor Templates XML File
            $this->saveXml();
            
            return $newId;
        }


        /**
        * Method to update all the template with the
        * sections that will be deleted
        *
        * @param string $sectionId The section Id
        * @return boolean
        * @access public
        */
        public function resetSection($sectionId)
        {   
            $arrTemplate = $this->getAll("WHERE sectionid = '$sectionId'");
            $result = '';
            
            if(!empty($arrTemplate)){
                foreach ($arrTemplate as $page) {
                    //First remove from front page
                    $this->_objFrontPage->removeIfExists($page['id']);
                    
                    // Trash / archive
                    $fields = array('trash' => 1, 'ordering' => '');
                    $result =  $this->update('id', $page['id'], $fields);
                }
            }
            return $result;
        }
        
        /**
        * Method to update all the template with the
        * sections that will be deleted
        *
        * @param string $sectionId The section Id
        * @return boolean
        * @access public
        */
        public function unarchiveSection($sectionId)
        {   
            $arrTemplate = $this->getAll("WHERE sectionid = '$sectionId'");
            $result = '';
            
            if(!empty($arrTemplate)){
                $order = 1;
                foreach ($arrTemplate as $page) {
                    // Restore
                    $fields = array('trash' => 0, 'ordering' => $order++);
                    $result =  $this->update('id', $page['id'], $fields);
                }
            }
            return $result;
        }

        /**
         * Method to get all pages in a specific section
         *
         * @param string $sectionId The id of the section
         * @return array $pages An array of all pages in the section
         * @access public
         * @author Warren Windvogel
         */
        public function getPagesInSection($sectionId, $isPublished=FALSE)
        {
            $filter = "WHERE sectionid = '$sectionId' AND trash='0' ";
            if($isPublished){
                $filter .= "AND published='1' ";
            }
            $pages = $this->getAll($filter.' ORDER BY ordering');

            $secureData = array();
            foreach ($pages as $d){
                if ($this->_objSecurity->canUserReadTemplate($d['id'])){
                    array_push($secureData, $d);
                }
            }
            return $secureData;            
        }

        /**
         * Method to get all pages in a specific section, including those on the front page
         *
         * @access public
         * @author Megan Watson, Charl Mert
         * @param string $sectionId The id of the section
         * @return array $data An array of all pages in the section
         */
        public function getPagesInSectionJoinFront($sectionId)
        {
            $sql = "SELECT *, co.public_access as public_access, fr.id AS front_id, co.id AS page_id, co.ordering AS co_order 
                FROM tbl_cms_template AS co 
                LEFT JOIN tbl_cms_template_frontpage AS fr ON (fr.template_id = co.id)
                WHERE sectionid = '$sectionId' AND trash='0'
                ORDER BY co.ordering";
            
            $data = $this->getArray($sql);
            
            $secureData = array();
            foreach ($data as $d){
                if ($this->_objSecurity->canUserReadTemplate($d['page_id'])){
                    array_push($secureData, $d);
                }
            }

            return $secureData;
        }

        /**
         * Method to get the title and id of all pages in a specific section
         *
         * @param string $title The title of the section. Returns pages from all sections if NULL. Defaults to NULL
         * @param int $limit The amount of records to return. Returns all pages if NULL. Defaults to NULL
         * @return array $titles An array of associative arrays containing the id and title of all pages in the section
         * @access public
         * @author Warren Windvogel
         */
        public function getTitles($title = NULL, $limit = NULL)
        {
            //If only the section id is set, return all records in the section
            if($title == NULL && $limit != NULL){
                $sql = "SELECT id, title FROM tbl_cms_template WHERE trash = '0' ORDER BY created DESC LIMIT '$limit'";
                //If only the limit is set, return set amount of pages from all sections
            } else if($title != NULL && $limit == NULL){
                $sql = "SELECT id, title FROM tbl_cms_template WHERE title = '$title' ORDER BY created DESC";
                //If both params are set, return set amount of pages from specified section
            } else if($title != NULL && $limit != NULL){
                $sql = "SELECT id, title FROM tbl_cms_template WHERE title = '$title' ORDER BY created DESC LIMIT '$limit'";
                //Else if neither param is set, return all records
            } else {
                $sql = "SELECT id, title FROM tbl_cms_template WHERE trash = '0' ORDER BY created DESC";
            }
            $titles = $this->getArray($sql);
            return $titles;
        }
        
        /**
         * Method to get the title and id of the last 5 pages added
         *
         * @return array $lastFiveTitles An array of associative arrays containing the id and title of 
         * the last $n pages added
         * @param int $n The number of pages whose titles we should get
         * @access public
         * @author Warren Windvogel / added by Derek Keats 2007 01 17
         */
        public function getLatestTitles($n=5)
        {
            $sql = "SELECT id, title FROM tbl_cms_template WHERE trash = '0' ORDER BY created DESC LIMIT $n";
            return $this->getArray($sql);
        }

        /**
         * Method to get the number of pages in a specific section
         *
         * @param string $sectionId The id of the section
         * @return int $noPages The number of pages in the section
         * @access public
         * @author Warren Windvogel
         */
        public function getNumberOfPagesInSection($sectionId)
        {
            $noPages = '0';
            $pages = $this->getAll("WHERE sectionid = '$sectionId' AND trash='0' ORDER BY ordering");
            $noPages = count($pages);
            return $noPages;
        }

        /**
         * Method to return the ordering value of new template (gets added last)
         *
         * @param string $sectionId The id(pk) of the section the template is attached to
         * @return int $ordering The value to insert into the ordering field
         * @access public
         * @author Warren Windvogel
          */
        public function getPageOrder($pageId)
        {
            //get last order value
            $lastOrder = $this->getRow('id', $pageId);
            //add after this value
            $ordering = $lastOrder['ordering'];
            return $ordering;
        }

        /**
         * Method to return the ordering value of new template (gets added last)
         *
         * @param string $sectionId The id(pk) of the section the template is attached to
         * @return int $ordering The value to insert into the ordering field
         * @access public
         * @author Warren Windvogel
          */
        public function getOrdering($sectionId)
        {
            $ordering = 1;
            //get last order value
            $lastOrder = $this->getAll("WHERE sectionid = '$sectionId' AND trash = '0' ORDER BY ordering DESC LIMIT 1");
            //add after this value
            if (!empty($lastOrder)) {
                $ordering = $lastOrder['0']['ordering'] + 1;
            }

            return $ordering;
        }

        /**
         * Method to return the links to be displayed in the order column on the table
         *
         * @param string $id The id of the entry
         * @return string $links The html for the links
         * @access public
         * @author Warren Windvogel
         */
        public function getOrderingLink($sectionid, $id)
        {   
            //Get the number of pages in the section
            $lastOrd = $this->getAll("WHERE sectionid = '$sectionid' AND trash = '0' ORDER BY ordering DESC LIMIT 1");
            $topOrder = $lastOrd['0']['ordering'];
            $links = " ";

            if ($topOrder > '1') {
                //Get the order position
                $entry = $this->getRow('id', $id);
                //Create geticon obj
                $this->objIcon = & $this->newObject('geticon', 'htmlelements');

                if ($entry['ordering'] == '1') {
                    //return down arrow link
                    //icon
                    $this->objIcon->setIcon('downend');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderdown', 'cmsadmin');
                    //link
                    $downLink = & $this->newObject('link', 'htmlelements');
                    $downLink->href = $this->uri(array('action' => 'changetemplateorder', 'id' => $id, 'ordering' => 'up', 'sectionid' => $sectionid));
                    $downLink->link = $this->objIcon->show();
                    $links .= $downLink->show();
                } else if ($entry['ordering'] == $topOrder) {
                    //return up arrow
                    //icon
                    $this->objIcon->setIcon('upend');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink = & $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changetemplateorder', 'id' => $id, 'ordering' => 'down', 'sectionid' => $sectionid));
                    $upLink->link = $this->objIcon->show();
                    $links .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $upLink->show();
                } else {
                    //return both arrows
                    //icon
                    $this->objIcon->setIcon('down');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderdown', 'cmsadmin');
                    //link
                    $downLink = & $this->newObject('link', 'htmlelements');
                    $downLink->href = $this->uri(array('action' => 'changetemplateorder', 'id' => $id, 'ordering' => 'up', 'sectionid' => $sectionid));
                    $downLink->link = $this->objIcon->show();
                    //icon
                    $this->objIcon->setIcon('up');
                    $this->objIcon->title = $this->_objLanguage->languageText('mod_cmsadmin_changeorderup', 'cmsadmin');
                    //link
                    $upLink = & $this->newObject('link', 'htmlelements');
                    $upLink->href = $this->uri(array('action' => 'changetemplateorder', 'id' => $id, 'ordering' => 'down', 'sectionid' => $sectionid));
                    $upLink->link = $this->objIcon->show();
                    $links .= $downLink->show() . '&nbsp;' . $upLink->show();
                }
            }

            return $links;
        }

        /**
         * Method to update the order of the frontpage
         *
         * @param string $id The id of the entry
         * @param string $id The id of the entry to move
         * @param int $ordering How to update the order(up or down).
         * @access public
         * @return bool
         * @author Warren Windvogel
         */
        public function changeOrder($sectionid, $id, $ordering)
        {
            //Get array of all page entries
            $fpTemplate = $this->getAll("WHERE sectionid = '$sectionid' AND trash = '0' ORDER BY ordering");
            //Search for entry to be reordered and update order
            foreach($fpTemplate as $template) {
                if ($template['id'] == $id) {
                    if ($ordering == 'up') {
                        $changeTo = $template['ordering'];
                        $toChange = $template['ordering'] + 1;
                        $updateArray = array(
                                           'modified' => $this->now(),
                                           'ordering' => $toChange
                        );
                        $this->update('id', $id, $updateArray);
                    } else {
                        $changeTo = $template['ordering'];
                        $toChange = $template['ordering'] - 1;
                        $updateArray = array(
                                           'ordering' => $toChange,
                                           'modified' => $this->now()
                        );
                        $this->update('id', $id, $updateArray);
                    }
                }
            }

            //Get other entry to change
            $entries = $this->getAll("WHERE sectionid = '$sectionid' AND ordering = '$toChange' AND trash = '0'");
            foreach($entries as $entry) {
                if ($entry['id'] != $id) {
                    $upArr = array(
                                 'ordering' => $changeTo,
                                 'modified' => $this->now()
                    );
                    $result = $this->update('id', $entry['id'], $upArr);
                }
            }
            
            // Reorder the template
            $this->reorderTemplate($sectionid);
            return $result;
        }

    /**
         * Method to scrub grubby html
         *
         * @param string $document
         * @return string
         */
        public function html2txt($document, $scrub = TRUE)
        {
            if($scrub == TRUE)
            {
                $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                   /*'@<[\/\!]*?[^<>]*?>@si',*/            // Strip out HTML tags
                   /*'@<style[^>]*?>.*?</style>@siU',*/    // Strip style tags properly
                   '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
                );

            }
            else {
                $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                   /*'@<style[^>]*?>.*?</style>@siU',*/    // Strip style tags properly
                   '@<![\s\S]*?--[ \t\n\r]*>@',        // Strip multi-line comments including CDATA
                   '!(\n*(.+)\n*!x',                   //strip out newlines...
                );
            }
            $text = preg_replace($search, '', $document);
            $text = str_replace("<br /><br />", '' ,$text);
            //$text = str_replace("<br />", '' ,$text);
            //$text = str_replace( '\n\n\n' , '\n' ,$text);
            $text = str_replace("<br />  <br />", "<br />", $text);
            $text = str_replace("<br\">","",$text);
            $text = str_replace("<br />", " <br /> ", $text);
            //$text = str_replace("<", " <", $text);
            //$text = str_replace(">", "> ", $text);
            $text = rtrim($text, "\n");
            return $text;
        }
        
        /**
         * The method implements the lucene indexer
         * The method accepts an array of data,
         * generates a document to be indexed based on the
         * url and template inserted into the database 
         *
         * @param array $data
         */
        public function luceneIndex($data)
        {
            $objLucene = $this->getObject('indexdata', 'search');
        
            $docId = 'cms_page_'.$data['id'];
        
            $url = $this->uri(array
                ('module' => 'cms', 
                            'action' => 'showfulltext', 
                            'id' => $data['id'],
                            'sectionid'=> $data['sectionid']), 'cms');
        
            $objLucene->luceneIndex($docId, $data['created'], $url, $data['title'], $data['title'].$data['body'], $data['introtext'], 'cms', $data['created_by']);
        }
    
    /**
         * Method to return the Parent of the given template item
         *
         * @access public
         * @return array (Parent items record) or false if record couldn't be found
         */
        public function getParent($templateId){
            //get current parents child template
            //Getting the template record to find the parent
            $arrTemplate = $this->getAll("WHERE id = '$templateId'", 'tbl_cms_template');
            
            if (isset($arrTemplate[0]['sectionid'])){
                $sectionId = $arrTemplate[0]['sectionid'];
            } else if (isset($arrTemplate['sectionid'])) {
                $sectionId = $arrTemplate['sectionid'];
            } else {
                return false;
            }
            //getting the parent record
            $arrSection = $this->getArray("SELECT * FROM tbl_cms_sections WHERE id = '$sectionId'");
            return $arrSection[0];	
        }


    }

?>
