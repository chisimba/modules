<?php
// Security check - must be included in scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end of security

class webpresent extends controller
{


    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->objConfig = $this->getObject('altconfig', 'config');
        
        $this->objFiles = $this->getObject('dbwebpresentfiles');
        $this->objTags = $this->getObject('dbwebpresenttags');
    }
    
    public function requiresLogin($action)
    {
        $required = array('login', 'upload');
        
        if (in_array($action, $required)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    * Standard Dispatch Function for Controller
    *
    * @access public
    * @param string $action Action being run
    * @return string Filename of template to be displayed
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
    
    function __home()
    {
        $tagCloud = $this->objTags->getTagCloud();
        $this->setVarByRef('tagCloud', $tagCloud);
        
        $latestFiles = $this->objFiles->getLatestPresentations();
        $this->setVarByRef('latestFiles', $latestFiles);
        
        return 'home.php';
    }
    
    function __oldupload()
    {
        return 'upload.php';
    }
    
    function __olddoupload()
    {
        
        $id = $this->objFiles->autoCreateTitle();
        
        $objMkDir = $this->getObject('mkdir', 'files');
        $objMkDir->mkdirs($this->objConfig->getcontentBasePath().'/webpresent/'.$id);
        
        $objUpload = $this->newObject('upload', 'files');
        $objUpload->permittedTypes = array('ppt', 'pps', 'odp');
        $objUpload->overWrite = TRUE;
        //$objUpload->createFolder = TRUE;
        
        //$wordDoc = $this->objConfig->getcontentBasePath().'/muslimviews_archive/'.$id.'/';
        $objUpload->uploadFolder = $this->objConfig->getcontentBasePath().'/webpresent/'.$id.'/';
        
        $result = $objUpload->doUpload(TRUE, $id);
        
        //$file = '/home/tohir/www/chisimba_framework/app/usrfiles/muslimviews_archive/'.$id.'/'.$id.'.doc';
        
        
        if ($result['success'] == FALSE) {
            $this->objFiles->removeAutoCreatedTitle($id);
            rmdir($this->objConfig->getcontentBasePath().'/webpresent/'.$id);
            
            return $this->nextAction('upload', array('message'=>$result['message'], 'file'=>$_FILES['fileupload']['name']));
        } else {
            
            $filename = $_FILES['fileupload']['name'];
            $mimetype = $_FILES['fileupload']['type'];
            
            
            /*
            $file = $this->objConfig->getcontentBasePath().'/webpresent/'.$id.'/'.$id.'.doc';
            
            if (is_file($file)) {
                chmod($file, 0666);
            }
            */
            
            $this->objFiles->updateReadyForConversion($id, $filename, $mimetype);
            
            $uploadedFiles = $this->getSession('uploadedfiles', array());
            $uploadedFiles[] = $id;
            $this->setSession('uploadedfiles', $uploadedFiles);
            
            return $this->nextAction('process', array('id'=>$id));
        }
        
    }
    

    
    function __process()
    {
        $id = $this->getParam('id');
        
        $file = $this->objFiles->getFile($id);
        
        if ($file == FALSE) {
            return $this->nextAction('home', array('error'=>'norecord'));
        }
        
        $tags = $this->objTags->getTags($id);
        
        $this->setVarByRef('file', $file);
        $this->setVarByRef('tags', $tags);
        
        return 'process.php';
    }
    
    function __ajaxprocess()
    {
        $this->setPageTemplate(NULL);
        
        $id = $this->getParam('id');
        
        $file = $this->objFiles->getFile($id);
        
        if ($file == FALSE) {
            return $this->nextAction('home', array('error'=>'norecord'));
        }
        
        $tags = $this->objTags->getTags($id);
        
        $this->setVarByRef('file', $file);
        $this->setVarByRef('tags', $tags);
        
        return 'process.php';
    }
    
    
    function __updatedetails()
    {
        $id = $this->getParam('id');
        $title = $this->getParam('title');
        $description = $this->getParam('description');
        $tags = explode(',', $this->getParam('tags'));
        $newTags = array();
        
        // Clean up Spaces
        foreach ($tags as $tag)
        {
            $newTags[] = trim($tag);
        }
        
        $tags =  array_unique($newTags);
        $license = $this->getParam('creativecommons');
        
        $this->objFiles->updateFileDetails($id, $title, $description, $license);
        $this->objTags->addTags($id, $tags);
        
        return $this->nextAction('view', array('id'=>$id));
    }
    
    function __view()
    {
        $id = $this->getParam('id');
        
        $file = $this->objFiles->getFile($id);
        
        if ($file == FALSE) {
            return $this->nextAction('home', array('error'=>'norecord'));
        }
        
        $tags = $this->objTags->getTags($id);
        
        $this->setVarByRef('file', $file);
        $this->setVarByRef('tags', $tags);
        
        return 'view.php';
    }
    
    function __edit()
    {
        return $this->__process();
    }
    
    function __tag()
    {
        $tag = $this->getParam('tag');
        
        $files = $this->objTags->getFilesWithTag($tag);
        
        $this->setVarByRef('tag', $tag);
        $this->setVarByRef('files', $files);
        
        return 'tag.php';
    }
    
    function __testswf()
    {
        return 'testswf.php';
    }
    
    function __upload()
    {
        return 'testupload.php';
    }
    
    function __tempiframe()
    {
        echo '<pre>';
        print_r($_GET);
    }
    
    function __erroriframe()
    {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);
        
        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);
        
        $message = $this->getParam('message');
        $this->setVarByRef('message', $message);
        
        return 'erroriframe.php';
    }
    
    function __uploadiframe()
    {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);
        
        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);
        
        return 'uploadiframe.php';
    }
    
    
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
        $objUpload->permittedTypes = array('ppt', 'odp'); //'pps',
        $objUpload->overWrite = TRUE;
        $objUpload->uploadFolder = $destinationDir.'/';
        
        $result = $objUpload->doUpload(TRUE, $id);
        
        if ($result['success'] == FALSE) {
            $this->objFiles->removeAutoCreatedTitle($id);
            rmdir($this->objConfig->getcontentBasePath().'/webpresent/'.$id);
            
            return $this->nextAction('erroriframe', array('message'=>$result['message'], 'file'=>$_FILES['fileupload']['name'], 'id'=>$generatedid));
        } else {
            
            //$filename = $_FILES['fileupload']['name'];
            $mimetype = $_FILES['fileupload']['type'];
            
            $path_parts = pathinfo($_FILES['fileupload']['name']);
        
            $ext = $path_parts['extension'];
            
            
            $file = $this->objConfig->getcontentBasePath().'/webpresent/'.$id.'/'.$id.'.'.$ext;
            
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
        
        return 'ajaxuploadresults.php';
    }
    
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
}
?>