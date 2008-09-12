<?php
    /**
     * Webpresent is used for sharing presentations online.
     * Registered users can upload and manage thier presentations
     * Supported file formats range from open office, power point to pdf.
     * JODConverter is used primarly as the document converter engine, although
     * in same cases we are using swftools.
     *
     *  PHP version 5
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
     * @package   webpresent
     * @author    Tohir Solomons, Derek Keats and later modifications by David Wafula
     *
     * @copyright 2008 Free Software Innnovation Unit
     * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
     * @link      http://avoir.uwc.ac.za
     * @see       References to other sections (if any)...
     */
if(!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end of security

class webpresent extends controller
{

    public  $objConfig;

    public  $realtimeManager;

    public  $presentManager;
    /**
     * Constructor
     */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->objConfig = $this->getObject('altconfig', 'config');

        $this->objFiles = $this->getObject('dbwebpresentfiles');
        $this->objTags = $this->getObject('dbwebpresenttags');
        $this->objSlides = $this->getObject('dbwebpresentslides');
        $this->objSchedules = $this->getObject('dbwebpresentschedules');
        $this->realtimeManager = $this->getObject('realtimemanager');
        //$this->presentManager = $this->getObject('presentmanager');
    }
        /**
         * Method to override login for certain actions
         * @param <type> $action
         * @return <type>
         */
    public function requiresLogin($action)
    {
        $required = array('login', 'upload', 'edit', 'updatedetails', 'tempiframe', 'erroriframe', 'uploadiframe', 'doajaxupload', 'ajaxuploadresults', 'delete', 'admindelete', 'deleteslide', 'deleteconfirm', 'regenerate','schedule','willappletrun');

        if (in_array($action, $required)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }



       /**
        * Standard Dispatch Function for Controller
        * @param <type> $action
        * @return <type>
        */
    public function dispatch($action)
    {

        /*
        * Convert the action into a method (alternative to
        * using case selections)
        */
        $method = $this->getMethod($action);
        /*
        * Return the template determined by the method resulting
        * from action
        */
        return $this->$method();
    }

      /**
       *
       * Method to convert the action parameter into the name of
       * a method of this class.
       *
       * @access private
       * @param string $action The action parameter passed byref
       * @return string the name of the method
       *
       */
    function getMethod(& $action)
    {
        if ($this->validAction($action)) {
            return '__'.$action;
        } else {
            return '__home';
        }
    }

      /**
       *
       * Method to check if a given action is a valid method
       * of this class preceded by double underscore (__). If it __action
       * is not a valid method it returns FALSE, if it is a valid method
       * of this class it returns TRUE.
       *
       * @access private
       * @param string $action The action parameter passed byref
       * @return boolean TRUE|FALSE
       *
       */
    function validAction(& $action)
    {
        if (method_exists($this, '__'.$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

      /**
       * Method to show the Home Page of the Module
       */
    function __home()
    {
        $tagCloud = $this->objTags->getTagCloud();
        $this->setVarByRef('tagCloud', $tagCloud);

        $latestFiles = $this->objFiles->getLatestPresentations();
        $this->setVarByRef('latestFiles', $latestFiles);

        return 'home_tpl.php';
    }



    /**
     * function to test whether target machine has java well installed
     */
    public function __willappletrun()
    {

        $actiontype= $this->getParam('actiontype');
        $id= $this->getParam('id');
        $agenda= $this->getParam('agenda');
        
        $this->setVarByRef('appletaction', $actiontype);
        $this->setVarByRef('id', $id);
       
        $this->setVarByRef('agenda', $agenda);
  
        return "willappletrun_tpl.php";
    }

    /**
     * This calls function that displays actual applet after veryifying that java exists
     * The applet is invoked in presenter mode
     *  @return <type>
     */
    public function __showpresenterapplet()
    {
        return $this->showapplet('true');
    }

    /**
     * Calls function to display applet, but in participant mode
     * @return <type>
     */
    public function __showaudienceapplet()
    {
        return $this->showapplet('false');
    }
    
    
    /**
     * Displays actual applet by returning the template responsible for this
     * @param <type> $isPresenter
     * @return <type>
     */
    private  function showapplet($isPresenter){
        
        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $supernodeHost=$objSysConfig->getValue('SUPERNODE_HOST', 'realtime');
        $supernodePort=$objSysConfig->getValue('SUPERNODE_PORT', 'realtime');

        $this->setVarByRef('supernodeHost', $supernodeHost);
        $this->setVarByRef('supernodePort', $supernodePort);

        $slideServerId=$this->realtimeManager->randomString(32);//'gen19Srv8Nme50';
        $this->realtimeManager->startSlidesServer($slideServerId);
        
        $id= $this->getParam('id');
        $title=$this->getParam('agenda');

        $filePath=$this->objConfig->getContentBasePath().'/webpresent/'.$id;
        $this->setVarByRef('filePath', $filePath);
        $this->setVarByRef('sessionTitle',$title);

        $this->setVarByRef('sessionid', $id);
        $this->setVarByRef('slideServerId',$slideServerId);
        $this->setVarByRef('isPresenter', $isPresenter);
        
       // $this->setVar('pageSuppressBanner', TRUE);
       // $this->setVar('suppressFooter', TRUE);

        return "showapplet_tpl.php";
    }

    /**
     * displayes error
     */
    public function __showerror()
    {
        $title= $this->getParam('title');
        $content= $this->getParam('content');
        $content.='<br><a href="http://java.com">Download here</a>';

        $desc= $this->getParam('desc');

        $this->setVarByRef('title',$title);
        $this->setVarByRef('content',$content);
        $this->setVarByRef('desc',$desc);

        return "dump_tpl.php";
        
    }
    /**
     * Method to display the search results
     */
    public function __search()
    {
        $query = $this->getParam('q');

        $this->setVarByRef('query', $query);

        return 'search_tpl.php';
    }




    /**
     * Method to edit the details of a presentation
     *
     */
    function __edit()
    {
        $id = $this->getParam('id');

        $file = $this->objFiles->getFile($id);

        if ($file == FALSE) {
            return $this->nextAction('home', array('error'=>'norecord'));
        }

        $tags = $this->objTags->getTags($id);

        $this->setVarByRef('file', $file);
        $this->setVarByRef('tags', $tags);

        $mode = $this->getParam('mode', 'window');
        $this->setVarByRef('mode', $mode);

        if ($mode == 'submodal') {
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('suppressFooter', TRUE);
        }

        return 'process_tpl.php';
    }


    /**
     * Method to update the details of a presentation
     *
     */
    function __updatedetails()
    {
        $id = $this->getParam('id');
        $title = $this->getParam('title');
        $description = $this->getParam('description');
        $tags = explode(',', $this->getParam('tags'));
        $newTags = array();

        // Create an Array to store problems
        $problems = array();
        // Check that username is available
        if ($title == '') {
            $problems[] = 'emptytitle';
        $title=$id;
            
        }
        //
        // Clean up Spaces
        foreach ($tags as $tag)
        {
            $newTags[] = trim($tag);
        }

        $tags =  array_unique($newTags);
        $license = $this->getParam('creativecommons');

        $this->objFiles->updateFileDetails($id, $title, $description, $license);
        $this->objTags->addTags($id, $tags);

        $file = $this->objFiles->getFile($id);
        $tags = $this->objTags->getTagsAsArray($id);
        $slides = $this->objSlides->getSlides($id);

        $file['tags'] = $tags;
        $file['slides'] = $slides;

        $this->_luceneclearFileIndex($id);
        $this->_prepareDataForSearch($file);

        if (count($problems) > 0) {
            $this->setVar('mode', 'addfixup');
            $this->setVarByRef('problems', $problems);
            return 'process_tpl.php';
        }else{
            return $this->nextAction('view', array('id'=>$id, 'message'=>'infoupdated'));
        }
    }
        /**
         * Method to view the details of a presentation
         *
         */
    function __view()
    {
        $id = $this->getParam('id');

        $file = $this->objFiles->getFile($id);

        if ($file == FALSE) {
            return $this->nextAction('home', array('error'=>'norecord'));
        }

        $numSlides = $this->objSlides->getNumSlides($id);

        if ($numSlides == 0)
        {
            $objBackground = $this->newObject('background', 'utilities');

            //check the users connection status,
            //only needs to be done once, then it becomes internal
            $status = $objBackground->isUserConn();

            //keep the user connection alive, even if browser is closed!
            $callback = $objBackground->keepAlive();

            $this->objSlides->scanPresentationDir($id);

            $call2 = $objBackground->setCallback("tohir@tohir.co.za","Your Script","The really long running process that you requested is complete!");


        }

        $tags = $this->objTags->getTags($id);

        $slideContent = $this->objSlides->getPresentationSlidesContent($id, TRUE);

        $this->setVarByRef('slideContent', $slideContent);
        $this->setVarByRef('file', $file);
        $this->setVarByRef('tags', $tags);

        $this->setVar('pageTitle', $this->objConfig->getSiteName().' - '.$file['title']);

        $objViewCounter = $this->getObject('dbwebpresentviewcounter');
        $objViewCounter->addView($id);

        return 'view_tpl.php';
    }

        /**
         * Method to download a presentation
         */
    function __download()
    {
        $id = $this->getParam('id');
        $type = $this->getParam('type');

        $fullPath = $this->objConfig->getcontentBasePath().'webpresent/'.$id.'/'.$id.'.'.$type;

        if (file_exists($fullPath)) {
            $relLink = $this->objConfig->getcontentPath().'webpresent/'.$id.'/'.$id.'.'.$type;

            $objDownloadCounter = $this->getObject('dbwebpresentdownloadcounter');
            $objDownloadCounter->addDownload($id, $type);

            header('Location:'.$relLink);
        } else {
            return $this->nextAction(NULL, array('error'=>'cannotfindfile'));
        }
    }


        /**
         * Method to view a list of presentations that match a particular tag
         *
         */
    function __tag()
    {
        $tag = $this->getParam('tag');
        $sort = $this->getParam('sort', 'dateuploaded_desc');

        // Check that sort options provided is valid
        if (!preg_match('/(dateuploaded|title|creatorname)_(asc|desc)/', strtolower($sort))) {
            $sort = 'dateuploaded_desc';
        }

        if (trim($tag) != '') {
            $tagCounter = $this->getObject('dbwebpresenttagviewcounter');
            $tagCounter->addView($tag);
        }

        $files = $this->objTags->getFilesWithTag($tag, str_replace('_', ' ',$sort));

        $this->setVarByRef('tag', $tag);
        $this->setVarByRef('files', $files);
        $this->setVarByRef('sort', $sort);

        return 'tag_tpl.php';
    }

    /**
     * Used to view a list of presentations uploaded by a particular user
     *
     */
    function __byuser()
    {
        $userid = $this->getParam('userid');
        $sort = $this->getParam('sort', 'dateuploaded_desc');

        // Check that sort options provided is valid
        if (!preg_match('/(dateuploaded|title)_(asc|desc)/', strtolower($sort))) {
            $sort = 'dateuploaded_desc';
        }

        $files = $this->objFiles->getByUser($userid, str_replace('_', ' ', $sort));

        $this->setVarByRef('userid', $userid);
        $this->setVarByRef('files', $files);
        $this->setVarByRef('sort', $sort);

        return 'byuser_tpl.php';
    }

    /**
     * Used to show a tag cloud for all tags
     */
    function __tagcloud()
    {
        $tagCloud = $this->objTags->getCompleteTagCloud();
        $this->setVarByRef('tagCloud', $tagCloud);

        return 'tagcloud_tpl.php';
    }

    /**
     * Ajax method to return statistics from another period/source
     */
    function __ajaxgetstats()
    {
        $period = $this->getParam('period');
        $type = $this->getParam('type');

        switch ($type)
        {
            case 'downloads':
            $objSource = $this->getObject('dbwebpresentdownloadcounter');
            break;
            case 'tags':
            $objSource = $this->getObject('dbwebpresenttagviewcounter');
            break;
            case 'uploads':
            $objSource = $this->getObject('dbwebpresentuploadscounter');
            break;
            default:
            $objSource = $this->getObject('dbwebpresentviewcounter');
            break;
        }

        echo $objSource->getAjaxData($period);
    }

    /**
     * Used to show interface to upload a presentation
     *
     */
    function __upload()
    {
        return 'testupload_tpl.php';
    }

    /**
     * Used to show a temporary iframe
     * (it is hidden, and thus does nothing)
     *
     */
    function __tempiframe()
    {
        echo '<pre>';
        print_r($_GET);
    }

    /**
     * Used to show upload errors
     *
     */
    function __erroriframe()
    {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);

        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);

        $message = $this->getParam('message');
        $this->setVarByRef('message', $message);

        return 'erroriframe_tpl.php';
    }

    /**
     * Used to show upload results if the upload was successful
     *
     */
    function __uploadiframe()
    {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);

        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);

        return 'uploadiframe.php';
    }

    /**
     * Ajax Process to display form for user to add presentation info
     *
     */
    function __ajaxprocess()
    {
        $this->setPageTemplate(NULL);

        $id = $this->getParam('id');

        $file = $this->objFiles->getFile($id);

        if ($file == FALSE) {
            return $this->nextAction('home', array('error'=>'norecord'));
        }

        // Set Filename as title in this process
        // Based on the filename, it might make it easier for users to complete the name
        $file['title'] = $file['filename'];

        $tags = $this->objTags->getTags($id);

        $this->setVarByRef('file', $file);
        $this->setVarByRef('tags', $tags);

        return 'process_tpl.php';
    }
  /**
     * Method to display the error messages/problems in the user registration
     * @param string $problem Problem Code
     * @return string Explanation of Problem
     */
    protected function explainProblemsInfo($problem)
    {
        switch ($problem) {
            case 'emptytitle':
                return 'Title of Presentation Required';
          }
    }
    /**
     * Used to do the actual upload
     *
     */
    function __doajaxupload()
    {
        $generatedid = $this->getParam('id');
        $filename = $this->getParam('filename');

        $id = $this->objFiles->autoCreateTitle();

        $objMkDir = $this->getObject('mkdir', 'files');

        $destinationDir = $this->objConfig->getcontentBasePath().'/webpresent/'.$id;
        $objMkDir->mkdirs($destinationDir);

        @chmod($destinationDir, 0777);

        $objUpload = $this->newObject('upload', 'files');
        $objUpload->permittedTypes = array('ppt', 'odp', 'pps'); //'pps',
        $objUpload->overWrite = TRUE;
        $objUpload->uploadFolder = $destinationDir.'/';

        $result = $objUpload->doUpload(TRUE, $id);

        //echo $generatedid;

        if ($result['success'] == FALSE) {
            $this->objFiles->removeAutoCreatedTitle($id);
            rmdir($this->objConfig->getcontentBasePath().'/webpresent/'.$id);

            $filename = isset($_FILES['fileupload']['name']) ? $_FILES['fileupload']['name'] : '';

            return $this->nextAction('erroriframe', array('message'=>$result['message'], 'file'=>$filename, 'id'=>$generatedid));
        } else {

            //$filename = $_FILES['fileupload']['name'];
            $mimetype = $_FILES['fileupload']['type'];

            $path_parts = pathinfo($_FILES['fileupload']['name']);

            $ext = $path_parts['extension'];


            $file = $this->objConfig->getcontentBasePath().'/webpresent/'.$id.'/'.$id.'.'.$ext;

            if ($ext == 'pps')
            {
                $rename = $this->objConfig->getcontentBasePath().'/webpresent/'.$id.'/'.$id.'.ppt';

                rename($file, $rename);

                $filename = $path_parts['filename'].'.ppt';
            }


            if (is_file($file)) {
                @chmod($file, 0777);
            }

            $this->objFiles->updateReadyForConversion($id, $filename, $mimetype);

            $uploadedFiles = $this->getSession('uploadedfiles', array());
            $uploadedFiles[] = $id;
            $this->setSession('uploadedfiles', $uploadedFiles);

            return $this->nextAction('ajaxuploadresults', array('id'=>$generatedid, 'fileid'=>$id, 'filename'=>$filename));
        }
    }

    /**
     * Used to push through upload results for AJAX
     */
    function __ajaxuploadresults()
    {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);

        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);

        $fileid = $this->getParam('fileid');
        $this->setVarByRef('fileid', $fileid);

        $filename = $this->getParam('filename');
        $this->setVarByRef('filename', $filename);

        return 'ajaxuploadresults_tpl.php';
    }

    /**
     * Used to Start the Conversions of Files
     *
     * This method is called using an Ajax process and is then
     * run as a background process, so that it continues, even
     * if the user closes the browser, or moves away.
     */
    function __ajaxprocessconversions()
    {
        $objBackground = $this->newObject('background', 'utilities');

        //check the users connection status,
        //only needs to be done once, then it becomes internal
        $status = $objBackground->isUserConn();

        //keep the user connection alive, even if browser is closed!
        $callback = $objBackground->keepAlive();

        $result = $this->objFiles->convertFiles();

        $call2 = $objBackground->setCallback("john.doe@tohir.co.za","Your Script","The really long running process that you requested is complete!");

        echo $result;
    }


    /**
     * Used to delete a presentation
     * Check: Users can only upload their own presentations
     */
    function __delete()
    {
        $id = $this->getParam('id');

        $file = $this->objFiles->getFile($id);

        if ($file == FALSE) {
            return $this->nextAction('home', array('error'=>'norecord'));
        }

        if ($file['creatorid'] != $this->objUser->userId()) {
            return $this->nextAction('view', array('id'=>$id, 'error'=>'cannotdeleteslidesofothers'));
        }

        return $this->_deleteslide($file);
    }

    /**
     * Used when an administrator deletes the file of another person
     */
    function __admindelete()
    {
        $id = $this->getParam('id');

        $file = $this->objFiles->getFile($id);

        if ($file == FALSE) {
            return $this->nextAction('home', array('error'=>'norecord'));
        }

        return $this->_deleteslide($file);
    }

    /**
     * Used to display the delete form interface
     * This method is called once it is verified the user can delete the presentation
     *
     * @access private
     */
    private function _deleteslide($file)
    {
        $this->setVarByRef('file', $file);

        $randNum = rand(0,500000);
        $this->setSession('delete_'.$file['id'], $randNum);

        $this->setVar('randNum', $randNum);

        $mode = $this->getParam('mode', 'window');
        $this->setVarByRef('mode', $mode);

        if ($mode == 'submodal') {
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('suppressFooter', TRUE);
        }


        return 'delete_tpl.php';
    }

    /**
     * Used to delete a presentation if user confirms delete
     *
     */
    private function __deleteconfirm()
    {
        // Get Id
        $id = $this->getParam('id');

        // Get Value
        $deletevalue = $this->getParam('deletevalue');

        // Get File
        $file = $this->objFiles->getFile($id);

        // Check that File Exists
        if ($file == FALSE) {
            return $this->nextAction('home', array('error'=>'norecord'));
        }

        // Check that user is owner of file, or is admin -> then delete
        if ($file['creatorid'] == $this->objUser->userId() || $this->isValid('admindelete')) {
            if ($deletevalue == $this->getSession('delete_'.$id) && $this->getParam('confirm') == 'yes')
            {
                $this->objFiles->deleteFile($id);
                $this->_luceneclearFileIndex($id);
                return $this->nextAction(NULL);
            } else {
                return $this->nextAction('view', array('id'=>$id, 'message'=>'deletecancelled'));
            }

            // Else User cannot delete files of others
        } else {
            return $this->nextAction('view', array('id'=>$id, 'error'=>'cannotdeleteslidesofothers'));
        }


    }

    /**
     * Used to display the latest presentations RSS Feed
     *
     */
    function __latestrssfeed()
    {
        $objViewer = $this->getObject('viewer');
        echo $objViewer->getLatestFeed();
    }

    /**
     * Used to show a RSS Feed of presentations matching a tag
     *
     */
    function __tagrss()
    {
        $tag = $this->getParam('tag');
        $objViewer = $this->getObject('viewer');
        echo $objViewer->getTagFeed($tag);
    }

    /**
     * Used to display the latest presentations of a user RSS Feed
     *
     */
    public function __userrss()
    {
        $userid = $this->getParam('userid');
        $objViewer = $this->getObject('viewer');
        echo $objViewer->getUserFeed($userid);
    }

    /**
     * Used to rebuild the search index
     */
    public function __rebuildsearch()
    {
        $files = $this->objFiles->getAll();

        $objWebPresentSearch = $this->getObject('webpresentsearch');
        $objWebPresentSearch->clearCache();
        //return '';

        if (count($files) > 0)
        {
            $file = $files[0];
            foreach ($files as $file)
            {
                $tags = $this->objTags->getTagsAsArray($file['id']);
                $slides = $this->objSlides->getSlides($file['id']);

                $file['tags'] = $tags;
                $file['slides'] = $slides;

                $this->_prepareDataForSearch($file);
            }
        }

        //return $this->nextAction(NULL);

    }

    /**
     * Used to take file information and make as much of that information available
     * for search purposes
     *
     * @param array $file File Information
     */
    private function _prepareDataForSearch($file)
    {
        $content = $file['filename'];

        $content .= ($file['description'] == '') ? '' : ', '.$file['description'];
        $content .= ($file['title'] == '') ? '' : ', '.$file['title'];


        if (count($file['tags']) > 0)
        {
            $divider = '';
            foreach ($file['tags'] as $tag)
            {
                $content .= $divider.$tag;
                $divider = ', ';
            }
        }

        $content .= ', ';

        $divider = '';
        foreach ($file['slides'] as $slide)
        {
            if (preg_match('/slide \d+/', $slide['slidetitle']))
            {
                $content .= $divider.$slide['slidetitle'];
                $divider = ', ';
            }

            if ($slide['slidecontent'] != '<h1></h1>')
            {
                $content .= $divider.strip_tags($slide['slidecontent']);
                $divider = ',';
            }


        }

        $file['content'] = $content;

        $this->_luceneIndex($file);
    }

    /**
     * Used to add a file to the search index
     *
     * @param array $file File Information
     */
    private function _luceneIndex($file)
    {
        //print_r($data); die();
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject('user', 'security');
        $indexPath = $this->objConfig->getcontentBasePath();
        if (file_exists($indexPath . 'webpresentsearch/segments')) {
            @chmod($indexPath . 'webpresentsearch', 0777);
            //we build onto the previous index
            $index = new Zend_Search_Lucene($indexPath . 'webpresentsearch', false);


            //echo 'Add to Index';
        } else {
            //instantiate the lucene engine and create a new index
            @mkdir($indexPath . 'webpresentsearch');
            @chmod($indexPath . 'webpresentsearch', 0777);
            $index = new Zend_Search_Lucene($indexPath . 'webpresentsearch', true);

            //echo 'Create New Index';
        }
        //hook up the document parser
        $document = new Zend_Search_Lucene_Document();
        //change directory to the index path
        chdir($indexPath);
        //set the properties that we want to use in our index
        //id for the index and optimization
        $document->addField(Zend_Search_Lucene_Field::Text('docid', 'webpresent_'.$file['id']));
        //date
        $document->addField(Zend_Search_Lucene_Field::UnIndexed('date', $file['dateuploaded']));
        //url
        $document->addField(Zend_Search_Lucene_Field::UnIndexed('url', $this->uri(array(
            'module' => 'webpresent',
            'action' => 'view',
            'id' => $file['id'],
                    ))));
        //createdBy
        $document->addField(Zend_Search_Lucene_Field::Text('createdBy', $this->objUser->fullName($file['creatorid'])));

        $document->addField(Zend_Search_Lucene_Field::UnIndexed('userid', $file['creatorid']));

        //document teaser
        $document->addField(Zend_Search_Lucene_Field::Text('teaser', $file['description']));



        if ($file['title'] == '') {
            $title = $file['filename'];
        } else {
            $title = $file['title'];
        }

        //doc title
        $document->addField(Zend_Search_Lucene_Field::Text('title', $title));
        //doc author
        $document->addField(Zend_Search_Lucene_Field::Text('author', $this->objUser->fullName($file['creatorid'])));
        //document body
        //NOTE: this is not actually put into the index, so as to keep the index nice and small
        //      only a reference is inserted to the index.
        $document->addField(Zend_Search_Lucene_Field::UnStored('contents', strtolower($file['content'])));
        //what else do we need here???
        //add the document to the index
        $index->addDocument($document);
        //commit the index to disc
        $index->commit();
        //optimize the thing
        //$index->optimize();

    }
    /**
     * Test deletion
     */
    public function __testdelete()
    {
        $fileId = $this->getParam('id');
        $this->_luceneclearFileIndex($fileId);
    }

    /**
     * Used to remove a file from the search index
     * @param string $fileId
     */
    private function _luceneclearFileIndex($fileId)
    {
        //print_r($data); die();
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject('user', 'security');
        $indexPath = $this->objConfig->getcontentBasePath();
        if (file_exists($indexPath . 'webpresentsearch/segments')) {
            @chmod($indexPath . 'webpresentsearch', 0777);
            //we build onto the previous index
            $index = new Zend_Search_Lucene($indexPath . 'webpresentsearch', false);


            //echo 'Add to Index';
        } else {
            //instantiate the lucene engine and create a new index
            @mkdir($indexPath . 'webpresentsearch');
            @chmod($indexPath . 'webpresentsearch', 0777);
            $index = new Zend_Search_Lucene($indexPath . 'webpresentsearch', true);

            //echo 'Create New Index';
        }

        $removePath = 'webpresent_'.$fileId;

        $hits = $index->find('docid:'.$removePath);

        if (count($hits) > 0) {
            foreach($hits as $hit) {
                if ($hit->docid == $removePath) {
                    //echo '<br />'.$hit->docid.' - '.$hit->title.' / '.$hit->id.'<br />';
                    $index->delete($hit->id);
                }
            }
        }

        //commit the index to disc
        $index->commit();
        //optimize the thing
        //$index->optimize();

    }


    /**
     * Method to regenerate the Flash or PDF version of a file
     */
    public function __regenerate()
    {
        $id = $this->getParam('id');
        $type = $this->getParam('type');

        $result = $this->objFiles->regenerateFile($id, $type);

        return $this->nextAction('view', array('id'=>$id, 'message'=>'regeneration', 'type'=>$type, 'result'=>$result));
    }

    /**
     * Method to listall Presentations
     * Used for testing purposes
     * @access private
     */
    private function __listall()
    {
        $results = $this->objFiles->getAll(' ORDER BY dateuploaded DESC');

        if (count($results) > 0)
        {
            $this->loadClass('link', 'htmlelements');

            foreach ($results as $file)
            {
                echo $file['title'];

                $link = new link ($this->uri(array('action'=>'regenerate', 'type'=>'flash', 'id'=>$file['id'])));
                $link->link = 'Flash';

                echo ' - '.$link->show();

                $link = new link ($this->uri(array('action'=>'regenerate', 'type'=>'flash', 'id'=>$file['id'])));
                $link->link = 'Slides';

                echo ' / '.$link->show().'<br />';
            }
        }

    }
}
?>
