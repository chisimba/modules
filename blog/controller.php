<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Controller class for the blog module that extends the base controller
 *
 * @author Paul Scott <pscott@uwc.ac.za>
 * @copyright AVOIR
 * @package blog
 * @category chisimba
 * @licence GPL
 */
class blog extends controller
{
    /**
     * User object
     *
     * @var user
     */
    public $objUser;

    /**
     * Language Object
     *
     * @var object
     */
    public $objLanguage;

    /**
     * Logger object
     *
     * @var object
     */
    public $objLog;

    /**
     * Feed object
     *
     * @var object
     */
    public $objFeed;

    /**
     * Feed creator object
     *
     * @var object
     */
    public $objFeedCreator;

    /**
     * HTTP Client object
     *
     * @var object
     */
    public $objClient;

    /**
     * Database abstraction object
     *
     * @var object
     */
    public $objDbBlog;

    /**
     * Configuration object
     *
     * @var object
     */
    public $objConfig;

    /**
     * Operations baseclass
     *
     * @var object
     */
    public $objblogOps;

    /**
     * HTML Cleaner object
     *
     * @var object
     */
    public $cleaner;

    /**
     * Icon Object
     *
     * @var object
     */
    public $objIcon;

    /**
     * Lucene indexer object
     *
     * @var object
     */
    public $luceneindexer;

    /**
     * Lucene document Object
     *
     * @var object
     */
    public $lucenedoc;

    /**
     * Blog import object
     *
     * @var object
     */
    public $objBlogImport;

    /**
     * IMAP / POP3 / NNTP comms class
     *
     * @var object
     */
    public $objImap;

    /**
     * DSN (data source name) for connecting to mail servers
     *
     * @var unknown_type
     */
    public $dsn;

    /**
     * Timeout message object
     *
     * @var object
     */
    public $objMsg;

    /**
     * Comment interface object
     *
     * @var object
     * @var object
     */
    public $objComments;

    /**
     * Trackback object
     *
     * @var object
     */
    public $objTB;

    /**
     * Proxyinfo object
     *
     * @var object
     */
    public $objProxy;

    /**
     * Constructor method to instantiate objects and get variables
     *
     * @param void
     * @return string
     * @access public
     */
    public function init()
    {
        try {
            //grab the blogimporter class, just in case we need it.
            //I think that the import stuff should all be done in a seperate module...
            $this->objBlogImport = &$this->getObject('blogimporter');
            //the bbcode parser object
            $this->getObject('bbcodeparser', 'utilities');
            //get the imap class to grab email to blog...
            //maybe a config here to check if we wanna use this?

            //feeds classes
            $this->objFeed = &$this->getObject('feeds', 'feed');
            //user object
            $this->objUser = $this->getObject('user', 'security');
            //feed creator subsystem
            $this->objFeedCreator = &$this->getObject('feeder', 'feed');
            //httpclient to grab remote data
            $this->objClient = $this->getObject('client','httpclient');
            //language object
            $this->objLanguage = $this->getObject('language', 'language');
            //database abstraction object
            $this->objDbBlog = $this->getObject('dbblog');
            //blog operations object
            $this->objblogOps = &$this->getObject('blogops');
            //HTML cleaner
            $this->cleaner = &$this->getObject('htmlcleaner', 'utilities');
            //icon object
            $this->objIcon = &$this->getObject('geticon', 'htmlelements');
            //comment interface class
            $this->objComments = &$this->getObject('commentapi', 'blogcomments');

            //Lucene indexing and search system
            //$this->lucenedoc = &$this->getObject('doc','lucene');
            //$this->luceneindexer = &$this->getObject('indexfactory', 'lucene');

            //timeoutmsg object
            $this->objMsg = &$this->getObject('timeoutmessage', 'htmlelements');
            //config object
            $this->objConfig = $this->getObject('altconfig', 'config');
            //proxy object
            $this->objProxy = $this->getObject('proxyparser', 'utilities');

            //Get the activity logger class
            $this->objLog = $this->newObject('logactivity', 'logger');
            //Log this module call
            $this->objLog->log();
        }
        catch(customException $e) {
            //oops, something not there - bail out
            echo customException::cleanUp();
            //we don't want to even attempt anything else right now.
            die();
        }
    }

    /**
     * Method to process actions to be taken from the querystring
     *
     * @param string $action String indicating action to be taken
     * @return string template
     */
    public function dispatch($action = Null)
    {
        switch ($action) {
            default:
                //check if the user is logged in
                if($this->objUser->isLoggedIn() == TRUE)
                {
                    //get the action
                    $act = $this->getParam('action');
                    //is the user asking for a random blog?
                    if($act != 'randblog')
                    {
                        //no, so lets go to the viewblog page
                        $this->nextAction('viewblog');
                        exit;
                    }

                }
                //we don't require login - preloin action
                $this->requiresLogin(FALSE);
                //get the userid if set
                $userid = $this->getParam('userid');
                if(!isset($userid))
                {


                    $this->nextAction('allblogs');
                    exit;

                    /*//no userid is set
                    $this->setVarByRef('message', $this->objLanguage->languageText("mod_blog_word_randomblog"));
                    //get a random blog from the blog table
                    $r = $this->objDbBlog->getRandBlog();
                    //a random blog is found!
                    if(!empty($r))
                    {
                        $userid = $r['userid'];
                        $this->setVarByRef('userid', $userid);
                    }
                    else {
                        //oh dear, no blogs on this instance of chisimba!
                        return 'noblogs_tpl.php';
                    }*/
                }

                    // $this->requiresLogin(FALSE);
                    $this->setVarByRef('userid', $userid);

                //carry on...
                //get the categories
                $catarr = $this->objDbBlog->getCatsTree($userid);
                //get the link categories
                $linkcats = $this->objDbBlog->getAllLinkCats($userid);
                //get all the posts by this user
                $posts = $this->objDbBlog->getAllPosts($userid, 0); //$catid = NULL);
                //get the sticky posts too
                
                $latestpost[0] = $this->objDbBlog->getLatestPost($userid);
                $rss = $this->objDbBlog->getUserRss($userid);
                $stickypost = $this->objDbBlog->getStickyPosts($userid);
                $this->setVarByRef('stickypost', $stickypost);
                //send all that to the template
                $this->setVarByRef('rss', $rss);
                
                $this->setVarByRef('latestpost', $latestpost);
                $this->setVarByRef('posts', $posts);
                $this->setVarByRef('linkcats', $linkcats);
                $this->setVarByRef('cats', $catarr);
                //return the template!
                return 'randblog_tpl.php';
                break;

                //The siteblog function added by Irshaad Hoosain
                case 'siteblog':

                 //get the category ID if any
                $catid = $this->getParam('catid');
                //grab the user id
                $userid = 1 ;//$this->getParam('userid');

                if(!isset($userid))
                {
                    //fix the user id just in case
                    if($this->objUser->isLoggedIn() == TRUE)
                    {
                        $userid = 1;//$this->objUser->userId();
                    }
                    else {
                        $this->nextAction('');
                        exit;
                    }
                }


                if(isset($catid))
                {
                    //grab all the posts in that category
                    $posts = $this->objDbBlog->getAllPosts($userid, $catid);
                }
                else {

                }

               $posts = $this->objDbBlog->getAllPosts($userid, $catid);
			
                //send all that to the template
                $this->setVarByRef('catid', $catid);
                $this->setVarByRef('posts', $posts);
                $this->setVarByRef('linkcats', $linkcats);
                $this->setVarByRef('cats', $catarr);
                $this->setVarByRef('userid', $userid);
                //return the template
                return 'siteblog_tpl.php';
                break;

            case 'viewsingle':
                //single post view for the bookmarks/comments etc
                $postid = $this->getParam('postid');
                $userid = $this->getParam('userid');
                if(isset($userid))
                {
                    $catarr = $this->objDbBlog->getCatsTree($userid);
                    $this->setVarByRef('cats', $catarr);
                }
                $posts = $this->objDbBlog->getPostByPostID($postid);
                //get the post with comments and trackbacks and display it.

                $this->setVarByRef('postid', $postid);
                $this->setVarByRef('posts', $posts);
                $this->setVarByRef('userid', $userid);
                return 'viewsingle_tpl.php';
                break;

            case 'setupmail':
                //check that the person trying to set this up is logged in and an admin
                if($this->objUser->isLoggedIn() == FALSE || $this->objUser->inAdminGroup($this->objUser->userId()) == FALSE)
                {
                    //user is not logged in, bust out of this case and go to the default
                    //echo "You don't have permissions to do this dude!";
                    $this->nextAction('');
                    exit;
                }
                else {

                    $sprot = $this->getParam("mprot");
                    $muser = $this->getParam("muser");
                    $mpass = $this->getParam("mpass");
                    $mserver = $this->getParam("mserver");
                    $mport = $this->getParam("mport");
                    $mbox = $this->getParam("mbox");

                    //check that all the settings are there!
                    if(empty($sprot) || empty($muser) || empty($mpass) || empty($mserver) || empty($mport) || empty($mbox))
                    {
                        //echo $sprot, $muser, $mpass, $mserver, $mport, $mbox;
                        return 'mailsetup_tpl.php';
                    }

                    //create the DSN
                    $newsettings = array("BLOG_MAIL_DSN" => $sprot.'://'.$muser.':'.$mpass.'@'.$mserver.':'.$mport.'/'.$mbox);
                    $this->objblogOps->setupConfig($newsettings);
                    $this->nextAction('blogadmin');
                    break;

                }

                //break to make dead sure we break...
                break;

            case 'mail2blog':
                //grab the DSN from the config file
                $this->objImap = $this->getObject('imap', 'mail');
                $this->dsn = $this->objConfig->getItem('BLOG_MAIL_DSN');
                try {
                    //grab a list of all valid users to an array for verification later
                    $valid = $this->objDbBlog->checkValidUser();
                    $valadds = array();
                    //cycle through the valid email addresses and check that the mail is from a real user
                    foreach($valid as $addys)
                    {
                        $valadds[] = array('address' => $addys['emailaddress'], 'userid' => $addys['userid']);
                    }

                    //connect to the IMAP/POP3 server
                    $this->conn = $this->objImap->factory($this->dsn);
                    //grab the mail headers
                    $this->objImap->getHeaders();
                    //var_dump($this->objImap->getHeaders());
                    //check mail
                    $this->thebox = $this->objImap->checkMbox();
                    //get the mail folders
                    $this->folders = $this->objImap->populateFolders($this->thebox);
                    //count the messages
                    $this->msgCount = $this->objImap->numMails();
                    echo $this->msgCount;
                    //get the meassge headers
                    $i = 1;
                    //parse the messages
                    while ($i <= $this->msgCount)
                    {
                        //get the header info
                        $headerinfo = $this->objImap->getHeaderInfo($i);
                        //from
                        $address = $headerinfo->fromaddress;
                        //subject
                        $subject = $headerinfo->subject;
                        //date
                        $date = $headerinfo->Date;
                        //message flag
                        $read = $headerinfo->Unseen;
                        //message body
                        $bod = $this->objImap->getMessage($i);

                        //check if there is an attachment
                        if(empty($bod[1]))
                        {
                            //nope no attachments
                            $attachments = NULL;
                        }
                        else {
                            //set the attachment
                            $attachments = $bod[1];
                            //loop through the attachments and write them down

                        }
                        //make sure the body doesn't have any nasty chars
                        $message = @htmlentities($bod[0]);

                        //check for a valid user
                        if(!empty($address))
                        {
                            //check the address against tbl_users to see if its valid.
                            //just get the email addy, we dont need the name as it can be faked
                            $fadd = $address;
                            //get rid of the RFC formatted email bits
                            $parts = explode("<", $fadd);
                            $parts = explode(">", $parts[1]);
                            //raw address string that we can use to check against
                            $addy = $parts[0];
                            //check if the address we get from the msg is in the array of valid addresses
                            foreach ($valadds as $user)
                            {
                                //check if there is a match to the user list
                                if($user['address'] != $addy)
                                {
                                    //Nope, no match, not validated!
                                    $validated = NULL;
                                }
                                else {
                                    //match found, you are a valid user dude!
                                    $validated = TRUE;
                                    //set the userid
                                    $userid = $user['userid'];
                                    //all is cool, so lets break out of this loop and carry on
                                    break;

                                }
                            }
                        }
                        if($validated == TRUE)
                        {
                            //insert the mail data into an array for manipulation
                            $data[] = array('userid' => $userid,'address' => $address, 'subject' => $subject, 'date' => $date, 'messageid' => $i, 'read' => $read,
                                            'body' => $message, 'attachments' => $attachments);
                        }

                        //delete the message as we don't need it anymore
                        echo "sorting " . $this->msgCount . "messages";
                        $this->objImap->delMsg($i);
                        $i++;
                    }
                    //is the data var set?
                    if(!isset($data))
                    {
                        $data = array();
                    }
                    //lets look at the data now
                    foreach ($data as $datum)
                    {
                        $newbod = $datum['body'];
                        //add the [img][/img] tags to the body so that the images show up
                        //we discard any other mimetypes for now...
                        if(!empty($datum['attachments']))
                        {
                            if(is_array($datum['attachments']))
                            {
                                foreach($datum['attachments'] as $files)
                                {
                                    //do check for multiple attachments
                                    //set the filename of the attachment
                                    $fname = $files['filename'];
                                    $filenamearr = explode(".", $fname);
                                    $ext = pathinfo($fname);
                                    $filename = $filenamearr[0] . "_" . time() . "." . $ext['extension'];
                                    //decode the attachment data
                                    $filedata = base64_decode($files['filedata']);
                                    //set the path to write down the file to
                                    $path = $this->objConfig->getContentBasePath() . 'blog/';
                                    //check that the data dir is there
                                    if(!file_exists($path))
                                    {
                                        //dir doesn't exist so create it quickly
                                        mkdir($path, 0777);
                                    }
                                    //fix up the filename a little
                                    $filename = str_replace(" ","_", $filename);
                                    //change directory to the data dir
                                    chdir($path);
                                    //write the file
                                    $handle = fopen($filename, 'wb');
                                    fwrite($handle, $filedata);
                                    fclose($handle);
                                    $type = mime_content_type($filename);
                                    $tparts = explode("/", $type);
                                    if($tparts[0] == "image")
                                    {
                                        //add the img stuff to the body at the end of the "post"
                                        $newbod .= "[img]" . $this->objConfig->getSiteRoot() . 'usrfiles/blog/' . $filename . "[/img]" . "<br />";
                                    }
                                    else {
                                        //add the img stuff to the body at the end of the "post"
                                        $newbod .= "[url]" . $this->objConfig->getSiteRoot() . 'usrfiles/blog/' . urlencode($filename) . "[/url]" . "<br />";
                                    }
                                }
                            }
                            else {
                                //set the filename of the attachment
                                $fname = $datum['attachments'][0]['filename'];
                                $filenamearr = explode(".", $fname);
                                $ext = pathinfo($fname);
                                $filename = $filenamearr[0] . "_" . time() . "." . $ext['extension'];
                                //decode the attachment data
                                $filedata = base64_decode($datum['attachments'][0]['filedata']);
                                //set the path to write down the file to
                                $path = $this->objConfig->getContentBasePath() . 'blog/';
                                //check that the data dir is there
                                //fix up the filename a little
                                $filename = str_replace(" ","_", $filename);
                                if(!file_exists($path))
                                {
                                    //dir doesn't exist so create it quickly
                                    mkdir($path, 0777);
                                }
                                //change directory to the data dir
                                chdir($path);
                                //write the file
                                $handle = fopen($filename, 'wb');
                                fwrite($handle, $filedata);
                                fclose($handle);
                                $type = mime_content_type($filename);
                                $tparts = explode("/", $type);
                                if($tparts[0] == "image")
                                {
                                    //add the img stuff to the body at the end of the "post"
                                    $newbod .= "[img]" . $this->objConfig->getSiteRoot() . 'usrfiles/blog/' . $filename . "[/img]" . "<br />";
                                }
                                else {
                                    //add the img stuff to the body at the end of the "post"
                                    $newbod .= "[url]" . $this->objConfig->getSiteRoot() . 'usrfiles/blog/' . urlencode($filename) . "[/url]" . "<br />";
                                }
                            }
                        }
                        else {
                            //no attachments to worry about
                            $newbod = $datum['body'];
                        }
                        //Write the new post to the database as a "Quick Post"
                        $this->objblogOps->quickPostAdd($datum['userid'], array('posttitle' => $datum['subject'], 'postcontent' => $newbod,
                                                    'postcat' => 0, 'postexcerpt' => '', 'poststatus' => '0',
                                                    'commentstatus' => 'Y',
                                                    'postmodified' => date('r'), 'commentcount' => 0, 'postdate' => $datum['date']), 'mail');
                    }

                }
                //any issues?
                catch(customException $e) {
                    //clean up and die!
                    customException::cleanUp();
                }
                break;

            case 'importblog':
                //check if the user is logged in
                if($this->objUser->isLoggedIn() == FALSE)
                {
                    //no, redirect to the main blog page
                    $this->nextAction('');
                    //get outta this action immediately
                    exit;
                }
                //get some info
                $username = $this->getParam('username');
                $server = $this->getParam('server');
                if(empty($username) || empty($server))
                {
                    return "importform_tpl.php";
                }
                else {
                    try {
                        //set up to connect to the server
                        $this->objBlogImport->setup($server);
                        //connect to the remote db
                        $this->objBlogImport->_dbObject();

                        $blog = $this->objBlogImport->importBlog($username);

                    /*
                    //dump it to screen as a debug
                    if(is_null($blog))
                    {
                        die("Incorrect user");
                    }
                    if($blog == 56)
                    {
                        die("blog table from remote is empty");
                    }
                    */
                    //else {
                    $userid = $this->objUser->userId();
                    foreach($blog as $blogs)
                    {
                        //create the post array in a format the this blog can understand...
                        $postarr = array('userid' => $userid,
                        'postdate' => strtotime($blogs['dateadded']),
                        'postcontent' => $this->objblogOps->html2txt(htmlentities($blogs['content']), TRUE),
                        'posttitle' => $this->objblogOps->html2txt(htmlentities($blogs['title']), TRUE),
                        'postcat' => 0,
                        'postexcerpt' => $this->objblogOps->html2txt(htmlentities($blogs['headline']), TRUE),
                        'poststatus' => 0,
                        'commentstatus' => 'Y',
                        'postmodified' => $blogs['dateadded'],
                        'commentcount' => 0,
                        'postdate' => $blogs['dateadded']
                        );
                        //use the insertPost methods to populate...
                        $this->objblogOps->quickPostAdd($userid, $postarr, 'import');
                        //clear $postarr
                        $postarr = NULL;
                    }
                    $this->nextAction('viewblog');
                    }
                    catch (customException $e)
                    {
                        customException::cleanUp();
                        exit;
                    }
                    //}
                }

                break;

            case 'feedurl':
                //get the feed format parameter from the querystring
                $format = $this->getParam('feedselector');

                //and the userid of the blog we are interested in
                $userid = $this->getParam('userid');

                // Create the feed url
                $url = $this->uri(array('action' => 'feed', 'feedselector' => $format, 'userid' => $userid));

                $this->setVarByRef('feed', $url);
                return 'showfeed_tpl.php';
                break;

            case 'feed':

                //get the feed format parameter from the querystring
                $format = $this->getParam('feedselector');

                //and the userid of the blog we are interested in
                $userid = $this->getParam('userid');
                $bloggerprofile = $this->objDbBlog->checkProfile($userid);
                //grab the feed items
                $posts = $this->objDbBlog->getAllPosts($userid, $catid = NULL);

                //set up the feed...
                //who's blog is this?
                if(isset($bloggerprofile['blog_name']))
                {
                    $fullname = htmlentities($bloggerprofile['blog_name']); //$this->getParam('blog_name');
                }
                else {
                    $fullname = htmlentities($this->objUser->fullname($userid));
                }

                //title of the feed
                $feedtitle = htmlentities($fullname);
                //description

                if(isset($bloggerprofile['blog_descrip']))
                {
                    $feedDescription = htmlentities($bloggerprofile['blog_descrip']); //$this->getParam('blog_name');
                }
                else {
                    $feedDescription = htmlentities($this->objLanguage->languageText("mod_blog_blogof", "blog")) . " " . $fullname;
                }

                //link back to the blog
                $feedLink = $this->objConfig->getSiteRoot() . "index.php?module=blog&action=randblog&userid=" . $userid;
                //sanitize the link
                $feedLink = htmlentities($feedLink);
                //set up the url
                $feedURL = $this->objConfig->getSiteRoot() . "index.php?module=blog&userid=" . $userid . "action=feed&format=" . $format;
                //print_r($feedURL);
                $feedURL = htmlentities($feedURL);
                //set up the feed
                $this->objFeedCreator->setupFeed(TRUE,$feedtitle, $feedDescription, $feedLink, $feedURL);
                //loop through the posts and create feed items from them
                foreach($posts as $feeditems)
                {
                    //use the post title as the feed item title
                    $itemTitle = $feeditems['post_title'];
                    $itemLink = $this->uri(array('action' => 'viewsingle', 'postid' => $feeditems['id'], 'userid' => $userid)); //todo - add this to the posts table!
                    //description
                    $itemDescription = $feeditems['post_content'];
                    //where are we getting this from
                    $itemSource = $this->objConfig->getSiteRoot() . "index.php?module=blog&userid=" . $userid;
                    //feed author
                    $itemAuthor = htmlentities($this->objUser->fullname($userid));
                    //add this item to the feed
                    $this->objFeedCreator->addItem($itemTitle, $itemLink, $itemDescription, $itemSource, $itemAuthor);
                }
                //check which format was chosen and output according to that
                switch ($format) {
                    case 'rss2':
                        $feed = $this->objFeedCreator->output('RSS2.0'); //defaults to RSS2.0
                        break;
                    case 'rss091':
                        $feed = $this->objFeedCreator->output('RSS0.91');
                        break;
                    case 'rss1':
                        $feed = $this->objFeedCreator->output('RSS1.0');
                        break;
                    case 'pie':
                        $feed = $this->objFeedCreator->output('PIE0.1');
                        break;
                    case 'mbox':
                        $feed = $this->objFeedCreator->output('MBOX');
                        break;
                    case 'opml':
                        $feed = $this->objFeedCreator->output('OPML');
                        break;
                    case 'atom':
                        $feed = $this->objFeedCreator->output('ATOM0.3');
                        break;
                    case 'html':
                        $feed = $this->objFeedCreator->output('HTML');
                        break;
                    case 'js':
                        $feed = $this->objFeedCreator->output('JS');
                        break;

                    default:
                        $feed = $this->objFeedCreator->output(); //defaults to RSS2.0
                        break;
                }
                //output the feed
                echo htmlentities($feed);
                break;

            case 'showallposts':
                $catid = NULL;
                $userid = $this->getParam('userid');
                if(!isset($userid))
                {
                    //fix the user id just in case
                    if($this->objUser->isLoggedIn() == TRUE)
                    {
                        $userid = $this->objUser->userId();
                    }
                    else {

                        $this->nextAction('');
                        exit;
                    }
                }
                //get the category tree
                $catarr = $this->objDbBlog->getCatsTree($userid);
                //get the links categories
                $linkcats = $this->objDbBlog->getAllLinkCats($userid);
                //make sure the category id is there
                if(isset($catid))
                {
                    //grab all the posts in that category
                    $posts = $this->objDbBlog->getAbsAllPostsNoDrafts($userid);
                }
                else {
                    //otherwise grab all the Published posts
                    $posts = $this->objDbBlog->getAbsAllPostsNoDrafts($userid);
                }
                $latestpost[0] = $this->objDbBlog->getLatestPost($userid);
                $this->setVarByRef('latestpost', $latestpost);
                //grab the user defined rss feeds if any
                $rss = $this->objDbBlog->getUserRss($userid);
                $stickypost = $this->objDbBlog->getStickyPosts($userid);
                $this->setVarByRef('stickypost', $stickypost);
                
                //send all that to the template
                $this->setVarByRef('rss', $rss);
                $this->setVarByRef('catid', $catid);
                $this->setVarByRef('posts', $posts);
                $this->setVarByRef('linkcats', $linkcats);
                $this->setVarByRef('cats', $catarr);
                $this->setVarByRef('userid', $userid);
                //return the template
                return 'myblog_tpl.php';
                break;

            case 'viewblog':

                //get the category ID if any
                $catid = $this->getParam('catid');
                //grab the user id
                $userid = $this->getParam('userid');
                if(!isset($userid))
                {
                    //fix the user id just in case
                    if($this->objUser->isLoggedIn() == TRUE)
                    {
                        $userid = $this->objUser->userId();
                    }
                    else {

                        $this->nextAction('');
                        exit;
                    }
                }
                //get the category tree
                $catarr = $this->objDbBlog->getCatsTree($userid);
                //get the links categories
                $linkcats = $this->objDbBlog->getAllLinkCats($userid);
                //make sure the category id is there
                if(isset($catid))
                {
                    //grab all the posts in that category
                    $posts = $this->objDbBlog->getAllPosts($userid, $catid);
                }
                else {
                    //otherwise grab all the Published posts
                    $posts = $this->objDbBlog->getAllPosts($userid, 0);//getAbsAllPostsNoDrafts($userid);
                }
                $latestpost[0] = $this->objDbBlog->getLatestPost($userid);
                $this->setVarByRef('latestpost', $latestpost);
                $rss = $this->objDbBlog->getUserRss($userid);
                $stickypost = $this->objDbBlog->getStickyPosts($userid);
                $this->setVarByRef('stickypost', $stickypost);
                
                //send all that to the template
                $this->setVarByRef('rss', $rss);
                $this->setVarByRef('catid', $catid);
                $this->setVarByRef('posts', $posts);
                $this->setVarByRef('linkcats', $linkcats);
                $this->setVarByRef('cats', $catarr);
                $this->setVarByRef('userid', $userid);
                //return the template
                return 'myblog_tpl.php';
                break;

            case 'viewblogbytag':

                //get the tag
                $tag = $this->getParam('tag');
                //grab the user id
                $userid = $this->getParam('userid');
                if(!isset($userid))
                {
                    //fix the user id just in case
                    if($this->objUser->isLoggedIn() == TRUE)
                    {
                        $userid = $this->objUser->userId();
                    }
                    else {

                        $this->nextAction('');
                        exit;
                    }
                }
                //get the category tree
                $catarr = $this->objDbBlog->getCatsTree($userid);
                //get the links categories
                $linkcats = $this->objDbBlog->getAllLinkCats($userid);
                //make sure the category id is there
                if(isset($tag))
                {
                    //grab all the posts with that tag
                    $posts = $this->objDbBlog->getAllPostsByTag($userid, $tag);
                }
                else {
                    //otherwise grab all the Published posts
                    $posts = $this->objDbBlog->getAllPosts($userid, 0);//getAbsAllPostsNoDrafts($userid);
                }
                $latestpost[0] = $this->objDbBlog->getLatestPost($userid);
                $this->setVarByRef('latestpost', $latestpost);
                $stickypost = $this->objDbBlog->getStickyPosts($userid);
                $this->setVarByRef('stickypost', $stickypost);
                
                //send all that to the template
                $this->setVarByRef('catid', $catid);
                $this->setVarByRef('posts', $posts);
                $this->setVarByRef('linkcats', $linkcats);
                $this->setVarByRef('cats', $catarr);
                $this->setVarByRef('userid', $userid);
                //return the template
                return 'mytagsblog_tpl.php';
                break;

            case 'blogadmin':
                //make sure the user is logged in
                if($this->objUser->isLoggedIn() == FALSE)
                {
                    //bail to the default page
                    $this->nextAction('');
                    //exit this action
                    exit;
                }
                //get the user id
                $userid = $this->objUser->userId();
                $this->setVarByRef('userid', $userid);
                //check the mode
                $mode = $this->getParam('mode');
                switch ($mode)
                {
                    //return a specific template for the chosen mode
                    case 'writepost':
                        return 'writepost_tpl.php';
                        break;
                    case 'editpost':
                        return 'editpost_tpl.php';
                        break;
                    case 'editcats':
                        return 'editcats_tpl.php';
                        break;

                }
                //return the default template for no mode set
                return 'blogadmin_tpl.php';
                break;

            case 'showarchives':
                //get the date and user id
                $date = $this->getParam('year');
                $userid = $this->getParam('userid');
                //grab the posts by month
                $posts = $this->objDbBlog->getPostsMonthly($date, $userid);
                //send out to the template
                $this->setVarByRef('userid', $userid);
                $this->setVarByRef('posts', $posts);
                //return a specific template and break the action
                return 'archive_tpl.php';
                break;

            case 'catadd':
                //add a category
                //check for login
                if($this->objUser->isLoggedIn() == FALSE)
                {
                    //not logged in - send to default action
                    $this->nextAction('');
                    exit;
                }
                //check the mode and cat name as wel as user id
                $mode = $this->getParam('mode');
                $list = $this->getParam('catname');
                $userid = $this->objUser->userId();
                $catname = $this->getParam('catname');
                $catparent = $this->getParam('catparent');
                $catdesc = $this->getParam('catdesc');
                $id = $this->getParam('id');
                //category quick add
                if($mode == 'quickadd')
                {
                    if(empty($list))
                    {
                        $this->nextAction('blogadmin');
                        break;
                    }
                    $this->objblogOps->quickCatAdd($list, $userid);
                    $this->nextAction('blogadmin');
                    break;
                }
                if($mode == 'edit')
                {
                    //update the records in the db
                    //build the array again
                    $entry = $this->objDbBlog->getCatForEdit($userid, $id);
                    $catarr = array('userid' => $userid,
                                    'cat_name' => $entry['cat_name'],
                                    'cat_nicename' => $entry['cat_nicename'],
                                    'cat_desc' => $entry['cat_desc'],
                                    'cat_parent' => $entry['cat_parent'],
                                    'id' => $id);

                    //display the cat editor with the values in the array, set that form to editcommit
                    $this->setVarByRef('catarr', $catarr);
                    $this->setVarByRef('userid', $userid);
                    $this->setVarByRef('catid', $id);
                    return 'cedit_tpl.php';
                    break;
                }
                if($mode == 'editcommit')
                {
                    $catarr = array('userid' => $userid, 'cat_name' => $catname, 'cat_nicename' => $catname, 'cat_desc' => $catdesc, 'cat_parent' => $catparent, 'id' => $id);
                    $this->objDbBlog->setCats($userid, $catarr, $mode);
                    $this->nextAction('blogadmin', array('mode' => 'editcats'));
                }

                if($mode == NULL)
                {
                    $catarr = array('userid' => $userid, 'cat_name' => $catname, 'cat_nicename' => $catname, 'cat_desc' => $catdesc, 'cat_parent' => $catparent);
                    //insert the category into the db
                    $this->objDbBlog->setCats($userid, $catarr);

                    $this->nextAction('blogadmin');
                    break;
                }
                break;

             case "add":
                        $this->objIcon = &$this->getObject('geticon', 'htmlelements');
                $edIcon = $this->objIcon->getEditIcon($this->uri(array('action' => 'postedit', 'id' => $post['id'], 'module' => 'blog')));
                $commentLink = $this->objComments->addCommentLink($type = NULL);
                        return "input_tpl.php";
                         break;


            case 'postadd':

                 //Added by Ishaad Hoosain for siteblog
                //if user clicked checkbox he is admin
                if($this->objUser->isLoggedIn() == FALSE)
                {
                    $this->nextAction('');
                    exit;
                }

                $mode = $this->getParam('mode');

                if(!$this->getParam('checkbox')==null){
                    $userid = 1 ;
                } else {
                    $userid = $this->objUser->userId();
                }

                $id = $this->getParam('id');
                $posttitle = $this->getParam('posttitle');
                $postcontent = $this->getParam('postcontent');
                $postcontent = $postcontent;
                $cclic = $this->getParam('creativecommons');
                $postdate = $this->getParam('postdate');
                $cat = $this->getParam('cat');
                $status = $this->getParam('status');
                $commentsallowed = $this->getParam('commentsallowed');
                $stickypost = $this->getParam('stickypost');
                $excerpt = $this->getParam('postexcerpt');
                $postts = $this->getParam('post_ts');
                $tags = $this->getParam('tags');
                $tagarray = explode(",", $tags);
                if($stickypost == "on")
                {
                	$stickypost = 1;
                }
                else {
                	$stickypost = 0;
                }
                //set up for Google Blog API
                $changesURL = $this->uri(array('module' => 'blog', 'action' => 'feed', 'userid' => $userid));
                $name = $this->objUser->fullname($userid) . " Chisimba blog";
                $blogURL = $this->uri(array('module' => 'blog', 'action' => 'randblog', 'userid' => $userid));
                //OK lets put it together...
                $gurl = "http://blogsearch.google.com/ping";
                //do the http request
                //echo $gurl;
                $gurl = str_replace('%26amp%3B', "&", $gurl);

                //get the proxy info if set
                $proxyArr = $this->objProxy->getProxy();
                //print_r($proxyArr); die();

                require_once "HTTP/Request.php";
                //define the request options
                $reqopts = array(
                    // Options for request directly
                    'useragent'         => 'Chisimba',
                    'strictness'        => 1,
                    'timeout'           => 30,          // seconds
                    'fetchlines'        => 30,
                    'fetchextra'        => true,
                    'http'              => "1.0",
                    'method'            => "GET",
                    'saveBody'          => true,
                );
                if(!empty($proxyArr) && $proxyArr['proxy_protocol'] != '')
                {
                    $parr = array(
                    'proxy_host'        => $proxyArr['proxy_host'],
                    'proxy_port'        => $proxyArr['proxy_port'],
                    'proxy_user'        => $proxyArr['proxy_user'],
                    'proxy_pass'        => $proxyArr['proxy_pass']
                    );

                    $proxyArr = array_merge($reqopts, $parr);
                }
                $req =& new HTTP_Request($gurl, $reqopts);

                $req->setMethod(HTTP_REQUEST_METHOD_GET);
                $req->addQueryString('name', $name);
                $req->addQueryString('url', $blogURL);
                $req->addQueryString('changesURL', $changesURL);

                $request = $req->sendRequest(true);
                //var_dump(urldecode($req->getUrl($req))); die();
                if(PEAR::isError($req))
                {
                    //var_dump($req);
                    log_debug($req->getMessage());
                }
                $code = $req->getResponseBody(); //$req->getResponseCode();

                switch ($code) {
                    case "Thanks for the ping.":
                        log_debug("Google blogs API Success! Google said: " . $code);
                        break;
                    default:
                        log_debug("Google blogs API Failure! Google said: " . $code);
                        break;
                }

                //post quick add
                if($mode == 'quickadd')
                {
                    $this->objblogOps->quickPostAdd($userid, array('posttitle' => $posttitle, 'postcontent' => $postcontent,
                                                    'postcat' => $cat, 'postexcerpt' => '', 'poststatus' => '0',
                                                    'commentstatus' => 'Y',
                                                    'postmodified' => date('r'), 'commentcount' => 0, 'postdate' => $postdate, 'cclic' => $cclic));

                    $this->nextAction('viewblog');
                    break;
                }
                elseif($mode == 'editpost') {
                    $insarredit = array('id' => $id,'posttitle' => $posttitle, 'postcontent' => $postcontent,
                                                    'postcat' => $cat, 'postexcerpt' => $excerpt, 'poststatus' => $status,
                                                    'commentstatus' => $commentsallowed,
                                                    'postmodified' => date('r'), 'commentcount' => 0, 'postdate' => $postdate, 'postts' => $postts, 'cclic' => $cclic, 'stickypost' => $stickypost);

                    $this->objblogOps->quickPostAdd($userid, $insarredit, $mode);

                    if(!empty($tagarray) && $tagarray[0] != "")
                    {
                        $etags = $this->objDbBlog->getPostTags($id);

                        if(count($tagarray) < count($etags))
                        {
                            //remove all the tags for the post so that we can populate with the new ones
                            foreach($etags as $rmtags)
                            {
                                //print_r($rmtags);
                                $this->objDbBlog->removeAllTags($rmtags['id']);
                            }
                            //echo "inserting ";
                            //print_r($tagarray);
                            $this->objDbBlog->insertTags($tagarray, $userid, $id);
                        }
                        //clean out the duplicate tags

                        //adding the extra tags
                        $tagarray = array_diff($tagarray, $etags);
                        if(!empty($etags))
                        {
                            foreach($etags as $t)
                            {
                                $things[] = $t['meta_value'];
                            }
                        }
                        else {
                            $things[] = NULL;
                        }

                        $tagarray = array_diff($tagarray, $things);

                        $this->objDbBlog->insertTags($tagarray, $userid, $id);
                    }


                    $this->nextAction('viewblog');
                    break;
                }
                else {
                    $this->objblogOps->quickPostAdd($userid, array('id' => $id, 'posttitle' => $posttitle, 'postcontent' => $postcontent,
                                                    'postcat' => $cat, 'postexcerpt' => $excerpt, 'poststatus' => $status,
                                                    'commentstatus' => $commentsallowed,
                                                    'postmodified' => date('r'), 'commentcount' => 0, 'postdate' => $postdate, 'cclic' => $cclic, 'stickypost' => $stickypost));
                    //dump in the tags
                    if(!empty($tagarray) && $tagarray[0] != "")
                    {
                        $posid = $this->objDbBlog->getLatestPost($userid);
                        $posid = $posid['id'];
                        $this->objDbBlog->insertTags($tagarray, $userid, $posid);
                    }
                    $this->nextAction('viewblog');
                    break;
                }

                break;

            case 'deletepost':
                if($this->objUser->isLoggedIn() == FALSE)
                {
                    $this->nextAction('');
                    exit;
                }
                $id = $this->getParam('id');
                $this->objDbBlog->deletePost($id);
                $this->nextAction('blogadmin', array('mode' => 'editpost'));

                break;

            case 'postedit':
                if($this->objUser->isLoggedIn() == FALSE)
                {
                    $this->nextAction('');
                    exit;
                }
                $userid = $this->objUser->userId();
                $id = $this->getParam('id');
                //check for the multidelete option
                $delarr = $this->getArrayParam('arrayList');
                if(!empty($delarr))
                {
                    //delete the posts and go back to the template...
                    foreach($delarr as $deletes)
                    {
                        $this->objDbBlog->deletePost($deletes);
                    }
                    $this->nextAction('blogadmin', array('mode' => 'editpost'));
                }
                //print_r($delarr); die();
                $this->setVarByRef('editid', $id);
                $this->setVarByRef('userid', $userid);
                return 'postedit_tpl.php';
                break;

            case 'allblogs':
                $ret = $this->objDbBlog->getUBlogs('userid', 'tbl_blog_posts');
                $this->setVarByRef('ret',$ret);
                return 'allblogs_tpl.php';
                break;

            case 'deletecat':
                if($this->objUser->isLoggedIn() == FALSE)
                {
                    $this->nextAction('');
                    exit;
                }
                //grab the vars that we need
                $id = $this->getParam('id');
                $userid = $this->objUser->userId();
                $mode = $this->getParam('mode');
                //echo $id, $mode;
                //search through the posts table to find all the posts linked to the cat
                $posts = $this->objDbBlog->getPostsFromCat($userid, $id);
                foreach($posts as $post)
                {
                    //update the found posts and set their cat to '0'
                    $insarredit = array('id' => $post['id'],'posttitle' => $post['post_title'], 'postcontent' => $post['post_content'],
                                                    'postcat' => 0, 'postexcerpt' => $post['post_excerpt'], 'poststatus' => $post['post_status'],
                                                    'commentstatus' => $post['comment_status'],
                                                    'postmodified' => $post['post_modified'], 'commentcount' => $post['comment_count'], 'postdate' => $post['post_date']);

                    $this->objblogOps->quickPostAdd($userid, $insarredit, 'editcommit');

                }
                //delete the cat from the table
                $this->objDbBlog->deleteCat($id);
                //nextaction back to the cats view thing
                $this->nextAction('blogadmin', array('mode' => 'editcats'));
                break;

            case 'tagcloud':
                $this->objTC = $this->getObject('tagcloud', 'utilities');

                //for the blog cloud, we want to get all the categories as tags
                //then the count of posts for each cat as the weight
                //the url to the cat as a link
                //last post time as the time
                //build the array
                //dump it into a featurebox
                //echo the cloud out
                //this action is a test action, this functionality will be moved to blogops soon.

                $tagarray = array(array('name' => 'PHP','url' => 'http://www.php.net', 'weight' => 1000, 'time' => strtotime('-56 days')),
                                  array('name' => 'Google SA','url' => 'http://www.google.co.za', 'weight' => 1250, 'time' => strtotime('-6 days')), //strtotime('-3 days')),
                                  array('name' => 'AVOIR','url' => 'http://avoir.uwc.ac.za', 'weight' => 1950, 'time' => time()),
                                  array('name' => 'FSIU','url' => 'http://fsiu.uwc.ac.za', 'weight' => 1560, 'time' => time()));

                print $this->objTC->buildCloud($tagarray);
                break;

            case 'tbreceive':
                $this->requiresLogin(FALSE);
                $req = $_REQUEST;
                $id = $this->getParam('postid');
                $userid = $this->getParam('userid');
                $pd = $_POST;
                $pd['host'] = $_SERVER['REMOTE_ADDR'];
                $pd['id'] =  $id;
                $pd['userid'] = $userid;
                $data = $pd;

                //do a check to see if it is valid
                if(!isset($data['title']) || !isset($data['excerpt']) || !isset($data['url']) || !isset($data['blog_name']))
                {
                    $theurl = $this->uri(array('action' => $req['action'], 'module' => $req['module'], 'userid' => $req['userid'], 'postid' => $req['postid']));

                    $this->setVarByRef('theurl', $theurl);
                    return 'tburl_tpl.php';
                }
                
                //check for trackback spam
                require_once 'Net/DNSBL.php';
				$dnsbl = new Net_DNSBL();
				$remoteIP = $pd['host'];
				
				$dnsbl->setBlacklists(array('dul.dnsbl.sorbs.net', 'rhsbl.sorbs.net', 'http.dnsbl.sorbs.net', 'socks.dnsbl.sorbs.net', 'misc.dnsbl.sorbs.net', 'smtp.dnsbl.sorbs.net',
							'web.dnsbl.sorbs.net', 'block.dnsbl.sorbs.net', 'zombie.dnsbl.sorbs.net', 'badconf.rhsbl.sorbs.net', 'sbl.spamhaus.org', 'dnsbl.njabl.org', 'relays.ordb.org'));
				if ($dnsbl->isListed($remoteIP)) {
    				return 'tburl_tpl.php';
				}
				else {

                	//add the $data array to a db table
                	$this->objDbBlog->setTrackback($data);

                	$options = array(
                    	// Options for trackback directly
                    	'strictness'        => 1,
                    	'timeout'           => 30,          // seconds
                    	'fetchlines'        => 30,
                    	'fetchextra'        => true,
                	);
                	$this->objTB = $this->getObject("trackback");
                	//use the factory
                	$this->objTB->setup($data, $options);
                	echo $this->objTB->recTB($data);
				}
                break;

            case 'tbsend':
                $postid = $this->getParam('postid');
                $title = $this->getParam('title');
                $excerpt = $this->getParam('excerpt');
                $bloggerprofile = $this->objDbBlog->checkProfile($this->objUser->userId());
                if(isset($bloggerprofile['blog_name']))
                {
                    $blog_name = $bloggerprofile['blog_name']; //$this->getParam('blog_name');
                }
                else {
                    $blog_name = $this->getParam('blog_name');
                }
                $url = $this->getParam('url');
                $url = urldecode($url);
                $trackback_url = $this->getParam('tburl');
                $extra = $this->getParam('extra');

                $tbarr = array('postid' => $postid, 'title' => $title, 'excerpt' => $excerpt, 'blog_name' => $blog_name, 'url' => $url, 'trackback_url' => $trackback_url);
                $this->setVarByRef('tbarr',$tbarr);

                //check that all necessary params arre there, otherwise return the template again...
                if(!isset($postid) || !isset($title) || !isset($excerpt) || !isset($blog_name) || !isset($url) || !isset($trackback_url))
                {
                    return 'tbsend_tpl.php';
                    break;
                }
                //otherwise simply send the trackback and return to the blog. :)
                $data = array('id' => $postid, 'title' => $title, 'excerpt' => $excerpt, 'blog_name' => $blog_name,
                              'url' => $url, 'trackback_url' => $trackback_url, 'extra' => $extra);

                //get the proxy info if set
                $proxyArr = $this->objProxy->getProxy();
                if(isset($proxyArr['proxy_host']))
                {
                    $options = array(
                        // Options for Services_Trackback directly
                        'strictness'        => 1,
                        'timeout'           => 30,          // seconds
                        'fetchlines'        => 30,
                        'fetchextra'        => true,
                        // Options for HTTP_Request class
                        'httprequest'       => array(
                            'allowRedirects'    => true,
                            'maxRedirects'      => 2,
                            'method'            => 'POST',
                            'useragent'         => 'Chisimba',
                            'proxy_host'        => $proxyArr['proxy_host'],
                            'proxy_port'        => $proxyArr['proxy_port'],
                            'proxy_user'        => $proxyArr['proxy_user'],
                            'proxy_pass'        => $proxyArr['proxy_pass']
                        ),
                    );
                }
                else {
                    $options = array(
                        // Options for Services_Trackback directly
                        'strictness'        => 1,
                        'timeout'           => 30,          // seconds
                        'fetchlines'        => 30,
                        'fetchextra'        => true,
                        // Options for HTTP_Request class
                        'httprequest'       => array(
                            'allowRedirects'    => true,
                            'maxRedirects'      => 2,
                            'method'            => 'POST',
                            'useragent'         => 'Chisimba',
                        ),
                    );
                }
                try {
                    $this->objTB = $this->getObject("trackback");
                    //use the factory
                    $this->objTB->setup($data, $options);
                    $this->objTB->sendTB($data);
                }
                catch(customException $e)
                {
                    customException::cleanUp();
                }
                $this->nextAction('');
            break;

        case "addcomment":
                $postid = $this->getParam('id');
                //$_SESSION['blogcomment'] = $_GET['id'];
                $this->setVar('pageSuppressToolbar', TRUE);
                $this->setVar('pageSuppressBanner', TRUE);
                $this->setVar('pageSuppressIM',TRUE);
                //Suppress footer in the page (keep it simple)
                $this->setVar('suppressFooter', TRUE);
                return "commentinput_tpl.php";
                break;

        case "addmetadata":
            //metadata can be a tag, a keyword or whatever else you want about a post
            $userid = $this->getParam('userid');
            $postid = $this->getParam('postid');
            $metakey = $this->getParam('metakey');
            $metavalue = $this->getParam('metavalue');

            //ok so lets sanitize the inputs and dump them to a db
            if(!isset($userid))
            {
                if($this->objUser->isLoggedIn() == TRUE)
                {
                    $userid = $this->objUser->userId();
                }
                else {
                    //who knows who this is????
                    $this->nextAction('');
                    exit;
                }
            }
            //lets strip out all the tags from the meta values as they should be plain text
            $metakey = strip_tags($metakey);
            $metavalue = strip_tags($metavalue);

            //is the post id sane?
            if(!isset($postid) || !isset($metakey) || !isset($metavalue))
            {
                return 'meta_tpl.php';
                exit;
            }

            //all set to go, lets insert it to the db...
            $insarr = array('userid' => $userid, 'post_id' => $postid, 'meta_key' => $metakey, 'meta_value' => $metavalue);
            $ret = $this->objDbBlog->insertMeta($insarr);
            $this->nextAction('postedit', array('id' => $postid));
            break;

        case 'addrss':
            $rssname = $this->getParam('name');
            $rssurl = $this->getParam('rssurl');
            $rssdesc = $this->getParam('description');
            $userid = $this->objUser->userId();
            $mode = $this->getParam('mode');
            if($mode == 'edit')
            {
                $id = $this->getParam('id');
                $rdata = $this->objDbBlog->getRssById($id);
                $this->setVarByRef('rdata', $rdata);
                return 'rssedit_tpl.php';
            }

            //get the cache
            //get the proxy info if set
            $proxyArr = $this->objProxy->getProxy();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $rssurl);
            //curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if(!empty($proxyArr) && $proxyArr['proxy_protocol'] != '')
            {
                curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'].":".$proxyArr['proxy_port']);
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'].":".$proxyArr['proxy_pass']);
            }
            $rsscache = curl_exec($ch);
            curl_close($ch);
//var_dump($rsscache);
            //put in a timestamp
            $addtime = time();
            $addarr = array('userid' => $userid, 'url' => $rssurl, 'name' => $rssname, 'description' => $rssdesc, 'rsscache' => htmlentities($rsscache), 'rsstime' => $addtime);

            //write the file down for caching
            //check that the blog dir exists
            if(!file_exists($this->objConfig->getContentBasePath() . "/blog"))
            {
            	mkdir($this->objConfig->getContentBasePath() . "/blog");
            	chmod($this->objConfig->getContentBasePath() . "/blog", 0777);
            }
            $path = $this->objConfig->getContentBasePath() . "/blog/rsscache/";
            $rsstime = time();
            if(!file_exists($path))
            {

                mkdir($path);
                chmod($path, 0777);
                $filename = $path . $this->objUser->userId() . "_" . $rsstime . ".xml";
                if(!file_exists($filename))
                {
                    touch($filename);

                }
                $handle = fopen($filename, 'wb');
                fwrite($handle, $rsscache);
            }
            else {
                $filename = $path . $this->objUser->userId() . "_" . $rsstime . ".xml";
                $handle = fopen($filename, 'wb');
                fwrite($handle, $rsscache);
            }
            //echo $path;
            //add into the db

            $rssurl = urlencode($rssurl); //, ENT_QUOTES);
            $rssname = htmlentities($rssname, ENT_QUOTES);
            $rssdesc = htmlentities($rssdesc, ENT_QUOTES);


            $addarr = array('userid' => $userid, 'url' => $rssurl, 'name' => $rssname, 'description' => $rssdesc, 'rsscache' => $filename, 'rsstime' => $rsstime);


            $this->objDbBlog->addRss($addarr);

            $this->nextAction('viewblog');
            break;

        case 'rssedit':
            $mode = $this->getParam('mode');
            $rssname = $this->getParam('name');
            $rssurl = $this->getParam('rssurl');
            $rssdesc = $this->getParam('description');
            $userid = $this->objUser->userId();
            $id = $this->getParam('id');

            if($mode == 'edit')
            {
                $addarr = array('id' => $id, 'userid' => $userid, 'url' => $rssurl, 'name' => $rssname, 'description' => $rssdesc);
                $this->objDbBlog->addRss($addarr, 'edit');
            }
            $userid = $this->objUser->userid();
            $rss = $this->objDbBlog->getUserRss($userid);
                //send all that to the template
                $this->setVarByRef('rss', $rss);
            $this->setVarByRef('userid', $userid);
            return 'rssedit_tpl.php';
            break;

        case 'deleterss':
            $id = $this->getParam('id');

            $this->objDbBlog->delRSS($id);
            $this->nextAction('rssedit');
            break;

        case 'setprofile':
            //profile stuff
            $mode = $this->getParam('mode');
            $userid = $this->objUser->userId();
            //ok lets check if this user already has a profile or not...
            $check = $this->objDbBlog->checkProfile($userid);
            if($check != FALSE)
            {
                $this->setVarByRef('userid', $userid);
                $this->setVarByRef('profile', $check);
                //return the template for editing the profile
                return 'profile_tpl.php';
            }
            if($mode == 'saveprofile')
            {
                $blogname = addslashes($this->getParam('blogname'));
                $blogdesc = addslashes($this->getParam('blogdesc'));
                $blogprofile = addslashes($this->getParam('blogprofile'));

                //save the profile to the table
                $prfarr = array('userid' => $userid, 'blog_name' => $blogname, 'blog_descrip' => $blogdesc, 'blogger_profile' => $blogprofile);
                $this->objDbBlog->saveProfile($prfarr);
                $this->nextAction('viewblog');
                break;
            }
            //set up the template
            $this->setVarByRef('userid', $userid);
            return 'profile_tpl.php';
            break;

        case 'editprofile':
            $mode = $this->getParam('mode');
            $userid = $this->objUser->userId();
            $id = $this->getParam('id');
            if($mode == 'editprofile')
            {
                $blogname = addslashes($this->getParam('blogname'));
                $blogdesc = addslashes($this->getParam('blogdesc'));
                $blogprofile = addslashes($this->getParam('blogprofile'));

                //save the profile to the table
                $prfarr = array('id' => $id, 'userid' => $userid, 'blog_name' => $blogname, 'blog_descrip' => $blogdesc, 'blogger_profile' => $blogprofile);
                $this->objDbBlog->updateProfile($prfarr);
                $this->nextAction('viewblog');
                break;
            }
            else {
                $this->nextAction('viewblog');
            }
            break;

        case 'viewprofile':
            $userid = $this->getParam('userid');
            $vprofile = $this->objDbBlog->checkProfile($userid);
            $this->setVarByRef('vprofile', $vprofile);
            $this->setVarByRef('userid', $userid);
            return 'viewprofile_tpl.php';
            break;

        case 'makepdf':
            $userid = $this->getParam('userid');
            $postid = $this->getParam('postid');
            //go and fetch the post in question from the db
            $data = $this->objDbBlog->getPostByPostID($postid);
            //create the pdf and send it out
            $header = stripslashes($data[0]['post_title']);
            $body = stripslashes($data[0]['post_content']);
            $postdate = $data[0]['post_date'];

            //put it all together
            //get the pdfmaker classes
            $objPdf = $this->getObject('fpdfwrapper','pdfmaker');
            $text = $header . "  " . $postdate . "\r\n" . strip_tags($body);
            $objPdf->simplePdf($text);
            //$this->nextAction('');
            break;

        case 'mail2friend':
            $bloggerid = $this->getParam('bloggerid');
            $postid = $this->getParam('postid');
            $emailadd = $this->getParam('emailadd');
            $emailadd = explode(",",$emailadd);
            foreach($emailadd as $emails)
            {
                $trimmed[] = trim($emails);
            }
            $emailadd = $trimmed;
            $message = $this->getParam('msg');
            $sendername = $this->getParam('sendername');

            if(empty($emailadd[0]))
            {
                $m2fdata = array('bloggerid' => $bloggerid, 'postid' => $postid);
                $this->setVarByRef('m2fdata', $m2fdata);
                //show the form
                return 'mail2friend_tpl.php';

            }
            else {
                //get the post from the post id
                $postcontent = $this->objDbBlog->getPostById($postid);
                //ok we have the content, lets parse for the [img] bbcode tags and replace them with real imgsrc
                preg_match_all('/\[img\](.*)\[\/img\]/U', $postcontent[0]['post_content'], $matches, PREG_PATTERN_ORDER);
                unset($matches[0]);
                //print_r($matches);
                $mcount = 0;
                foreach($matches as $match)
                {
                    $postcontent[0]['post_content'] = preg_replace('/\[img\](.*)\[\/img\]/U', "<img src='".$match[$mcount]."'/>", $postcontent[0]['post_content']); //$postcontent[0]['post_content'], $results, PREG_PATTERN_ORDER);
                    $mcount++;
                }
                //thump together an email string (this must be html email as the post is html
                $objMailer = $this->getObject('email', 'mail');
                //munge together the bodyText...
                $bodyText = $this->objLanguage->languageText("mod_blog_yourfriend", "blog") . ", " . $sendername . ", " .
                            $this->objLanguage->languageText("mod_blog_interestedin","blog"). ": <br /> " .
                            "<a href='".$this->uri(array('action' => 'viewsingle', 'postid' => $postid), 'blog')."'>".$this->uri(array('action' => 'viewsingle', 'postid' => $postid), 'blog')."</a>";
                $bodyText .= "<br /><br />";
                if(!empty($message))
                {
                    $bodyText .= $this->objLanguage->languageText("mod_blog_additionalcomments", "blog") . ": <br />";
                    $bodyText .= $message . "<br /><br />";
                }
                $bodyText .= stripslashes($postcontent[0]['post_date']);
                $bodyText .= "<br /><br />";
                $bodyText .= stripslashes($postcontent[0]['post_content']);
                $objMailer->setValue('IsHTML', TRUE);
                $objMailer->setValue('to', $emailadd);
                $objMailer->setValue('from', 'noreply@uwc.ac.za');
                $objMailer->setValue('fromName', $this->objLanguage->languageText("mod_blog_email2ffromname", "blog"));
                $objMailer->setValue('subject', $this->objLanguage->languageText("mod_blog_email2fsub", "blog"));
                $objMailer->setValue('body', $bodyText);
                $objMailer->send(TRUE);
                $this->nextAction('');

            }
            break;

        }//action

    }

    /**
     * Ovveride the login object in the parent class
     *
     * @param void
     * @return bool
     * @access public
     */
    public function requiresLogin()
    {
        return FALSE;
    }
}
?>