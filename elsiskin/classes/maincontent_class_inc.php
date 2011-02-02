<?php

/*
 *
 * A class to get the main content of the elsi wits site. The content displayed will depends on the
 * page being displayed. It could be content for home page, about us, staff or contact us.
 *
 * PHP version 5
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
 * @package   elsinewskin
 * @author    Nguni Phakela nonkululeko.phakela@wits.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: maincontent_class_inc.php,v 1.1 2011-01-12 09:13:27 nguni52 Exp $
 * @link      http://avoir.uwc.ac.za
 *
 *
 *
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security
class maincontent extends object {

    // page main content displayed according to current action
    private $currentAction;
    // path to root folder of skin
    private $skinpath;
    // news stories object
    private $objNews;
    // news category object
    private $objCategory;
    // file manager object
    private $objFileManager;
    // object for language elements
    private $objLanguage;

    private $objDbBlog;

    private $objUser;

    private $newsId;

    // default message if no content
    private $documentation;

    private $category;
    private $sidebar;

    /**
     * Blog posts object
     *
     * @var    object
     * @access public
     */
    public $objblogPosts;

    /**
     * Constructor
     */
    public function init() {
        $this->currentAction = 'home';
        $this->documentation = "Content has not yet been set up";
        $this->objNews = $this->getObject('dbnewsstories', 'news');
        $this->objCategory = $this->getObject('dbnewscategories', 'news');
        $this->objFileManager = $this->getObject('dbfile', 'filemanager');
        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbBlog = $this->getObject('dbblog', 'blog');
        $this->objUser = $this->getObject('user', 'security');
        $this->objHumanizeDate = $this->getObject("translatedatedifference", "utilities");
        $this->objblogPosts = $this->getObject('blogposts', 'blog');
        $this->objLanguage = $this->getObject("language", "language");
        $this->sidebar = $this->getObject('sidebar');

    }

    /* Method to set the current action of the page
     * @param $action the current page that is being loaded
     * @return none
     * @access public
     */

    public function setCurrentAction($action) {
        $this->currentAction = $action;
    }

    /**
     * Method to show the Toolbar
     * @param string $skinpath the default skinpath for elsi skin
     * @return none
     * @access public
     */
    public function setSkinPath($skinpath) {
        $this->skinpath = $skinpath;
    }

    public function setNewsId($newsId) {
        $this->newsId = $newsId;
    }

    /**
     * Method to show the rotating images with news right below the toolbar
     * @return string
     * @access public
     */
    public function show() {
        switch ($this->currentAction) {
            case 'about': return $this->showAboutMain();
            case 'staff': return $this->showStaffMain();
            case 'contact': return $this->showContactMain();
            case 'viewsingle':return $this->showSingleBlog();
            case 'viewstory': return $this->showNewsStory();
            default: return $this->showHomeMain();
        }
    }

    /**
     * Method to show the main content of the home page
     * @return string
     * @access public
     */
    public function showHomeMain() {
        $bloginfo = $this->getBlogs();
        //$bloginfo = "";
        if(empty($bloginfo)) {
            $bloginfo = "There are no blogs yet.";
        }
        $alllink = new link($this->uri(array("action"=>"siteblog"), "blog"));
        $alllink->link = 'View all blogs';
        $retstr = '
                   <div class="clear">&nbsp;</div>
                   <div class="grid_2">
                        <div class="info-box-holder">
                            <div class="left_wrap">
                                <h2>ELSI Staff Blog</h2>
                            </div>
                        </div>
                    </div>
                    <div class="grid_2"><p>&nbsp;</p></div>
                    <!-- end .grid_1 --> <div class="clear">&nbsp;</div>
                    <div class="grid_2">'.
                    $bloginfo
                    .'</div>
                    <div class="grid_2">
                        <div class="info-box-holder">
                            <div class="right_wrap">
                                <h2>Support and Help</h2>
                            </div>
                            <div id="supportandhelp">'
                                    .$this->getSupportAndDocumentation().'
                            </div>
                        </div>
                        <div class="grid_1">
                            '.
                    $this->sidebar->getFacebook()
                        .'</div>
                        <div class="grid_1 push_1">Twitter</div>
                        <div class="clear">&nbsp;</div>
                    </div>
                    <div class="grid_2"><p>&nbsp;</p></div>
                    <div class="grid_2">
                    </div>';


        return $retstr;
    }

    /**
     * Method to show the main content of the about page
     * @return string
     * @access public
     */
    public function showAboutMain() {
        $retstr = '<div class="grid_3">
                        '.$this->getAboutContent().'
                    </div>
                    <!-- end .grid_3 -->';

        return $retstr;
    }

    /**
     * Method to show the main content of the staff page
     * @return string
     * @access public
     */
    public function showStaffMain() {
		$retstr = '
                    <div class="grid_3">
                    <p>ELSI staff have an interest and the ability to assist with the effective educational use of ICTs
                    </p><div id="container">


                    <ul class="business_cards">
                            <li>
                                    <a name="modal" href="#dialog11">
                                    <h3 class="name">Agnes Chigona</h3>
                                    <img width="75" height="90" class="left" alt="" src="'.$this->skinpath.'images/AgnesChigona.jpg">
                                            <p class="jobtitle">Research Fellow</p>
                                            <span class="phone">+ 27 11 717 7181</span><br>
                                            <span class="email">agnes.chigona@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog6">
                                    <h3 class="name">Shakira Choonara</h3>
                                    <img width="75" height="90" alt="Shakira Choonara" class="left" src="'.$this->skinpath.'images/schoonara.jpg">
                                            <p class="jobtitle">Office Administrator</p>
                                            <span class="phone">+27 11 717 7161</span><br>
                                            <span class="email">shakira.choonara2@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog2">
                                    <h3 class="name">Rabelani Dagada</h3>
                                    <img width="75" height="90" class="left" alt="Rabelani Dagada" src="'.$this->skinpath.'images/RDagada.jpg">
                                            <p class="jobtitle">e Learning Manager</p>
                                            <span class="phone">+ 27 11 717 7184</span><br>
                                            <span class="email">rabelani.dagada@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog1">
                                    <h3 class="name">Taurai Hungwe</h3>
                                    <img width="75" height="90" class="left" alt="T Hungwe" src="'.$this->skinpath.'images/THungwe.jpg">
                                            <p class="jobtitle">Instructional Designer</p>
                                            <span class="phone">+ 27 11 717 7184</span><br>
                                            <span class="email">taurai.hungwe@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog15"><h3 class="name">Noxolo Mbana </h3>
                                    <img width="75" height="90" class="left" alt="" src="'.$this->skinpath.'images/noxolombana.jpg">
                                            <p class="jobtitle">Researcher</p>
                                            <span class="phone">+27 11 717 7164</span><br>
                                            <span class="email">noxolo.mbana@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog14">
                                    <h3 class="name">Reginald Moledi</h3>
                                    <img width="75" height="90" class="left" alt="" src="'.$this->skinpath.'images/regimoledi.jpg">
                                            <p class="jobtitle">Instructional Developer</p>
                                            <span class="phone">+27 11 717 7170</span><br>
                                            <span class="email">reginald.moledi@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog4">
                                    <h3 class="name">Derek Moore</h3>
                                    <img width="75" height="90" alt="Derek Moore" class="left" src="'.$this->skinpath.'images/dmoore.jpg">
                                                    <p class="jobtitle">Content Developer</p>
                                                    <span class="phone">+ 27 11 717 7171</span><br>
                                                    <span class="email">derek.moore@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog13">
                                    <h3 class="name">Paul Mungai</h3>
                                    <img width="75" height="90" class="left" alt="Paul Mungai" src="'.$this->skinpath.'images/PaulMungai.jpg">
                                                    <p class="jobtitle">Software Developer</p>
                                                    <span class="phone">+27 11 717 7183</span><br>
                                                    <span class="email">paul.mungai@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog10">
                                    <h3 class="name">Tessa Murray </h3>
                                    <img width="75" height="90" class="left" alt="Tessa Murray" src="'.$this->skinpath.'images/tessa_murry.jpg">
                                            <p class="jobtitle">Team leader: Content developer</p>
                                            <span class="phone">+ 27 11 717 7178</span><br>
                                            <span class="email">tessa.murray@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog12">
                                    <h3 class="name"> Neo Petlele </h3>
                                    <img width="75" height="90" class="left" alt="Neo.Petlele" src="'.$this->skinpath.'images/Neo.Petlele.jpg">
                                                    <p class="jobtitle">Research Assistant</p>
                                                    <span class="phone">+27 11 717 7183</span><br>
                                                    <span class="email">neo.Petlele@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog3">
                                    <h3 class="name">Nkululeko (Nguni) Phakela</h3>
                                    <img width="75" height="90" class="left" alt="Nkululeko Phakela" src="'.$this->skinpath.'images/NkululekoPhakela.jpg">
                                    <p class="jobtitle">Software Developer</p>
                                    <span class="phone">+ 27 11 717 7182</span><br>
                                    <span class="email">nguni52@gmail.com</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog7">
                                    <h3 class="name">Fatima Rahiman</h3>
                                    <img width="75" height="90" alt="Fatima Rahiman" class="left" src="'.$this->skinpath.'images/frahiman.jpg">
                                    <p class="jobtitle">Team leader: Instructional designer</p>
                                    <span class="phone">+ 27 11 717 7174</span><br>
                                    <span class="email">fatima.rahiman@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog5">
                                    <h3 class="name">James Smurthwaite</h3>
                                    <img width="75" height="90" class="left" alt="James Smurthwaite" src="'.$this->skinpath.'images/Profile_Pic_James.gif">
                                    <p class="jobtitle">Content Developer</p>
                                    <span class="phone">+27 11 717 7169</span><br>
                                    <span class="email">james.smurthwaite@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog8">
                                    <h3 class="name">Ofentse Tabane</h3>
                                    <img width="75" height="90" alt="Ofentse Tabane" class="left" src="'.$this->skinpath.'images/otabane.jpg">
                                    <p>Assistant Instructional Designer</p>
                                    <span class="phone">+ 27 11 717 7172</span><br>
                                    <span class="email">ofentse.tabane@wits.ac.za</span><br>
                                    </a>
                            </li>
                            <li>
                                    <a name="modal" href="#dialog9">
                                    <h3 class="name">David Wafula </h3>
                                    <img width="75" height="90" class="left" alt="" src="'.$this->skinpath.'images/DavidWafula.jpg">
                                    <p class="jobtitle">Team Leader Software Development</p>
                                    <span class="phone">+ 27 11 717 7180</span><br>
                                    <span class="email">david.wafula@wits.ac.za</span><br>
                                    </a>
                            </li>
                    </ul>
                    </div>
                    </div>
                    <!-- end .grid_3 -->';

		return $retstr;
	 }
        /**
         * Method to show the main content of the contact us page
         * @return string
         * @access public
         */
        public function showContactMain() {
            $submission = $this->getParam('submission');
            $message = "";
            if(!empty($submission)) {
                $message .= "<h3>".$this->objLanguage->languagetext('mod_elsiskin_contactsubmissionmessage', 'elsiskin')."</h3>";
            }
            $this->loadClass('label','htmlelements');
            $this->loadClass('button', 'htmlelements');
            $this->loadClass('textarea','htmlelements');

            $topics = array(
                array('text'=>'I\'d like to make ...'),
                array('value'=>'General', 'text'=>'A general enquiry '),
                array('value'=>'Admissions', 'text'=>'An admissions enquiry'),
                array('value'=>'Finance', 'text'=>'A financial enquiry'),
                array('value'=>'Other', 'text'=>'An enquiry about another matter')
            );

            $retstr = '
                 <div class="grid_3">
                       '.$message.'
                       <h4>Fill in the form</h4>
                       <br><br>
                       <form id="contactForm" name="contactForm" method="POST" action="?module=elsiskin&action=contactformsubmit">
                            <fieldset id="topdialogue">
                            <legend><span>'.$this->objLanguage->languagetext('mod_elsiskin_please','elsiskin').'</span> '.$this->objLanguage->languagetext('mod_elsiskin_contactdetails','elsiskin').'</legend>
                            <label>Subject</label>
                            <em>*</em>
                            <select size="1" class="required" name="c_topic" id="c_topic">';

                            foreach($topics as $row) {
                                $retstr .= '
                                    <option value="';
                                if(!empty($row['value']) ) {
                                    $retstr .= $row['value'];
                                }
                                $retstr .= '">';
                                $retstr .= $row['text'];
                                $retstr .='</option>';
                            }

                            $retstr .= '
                                </select>
                            <br />';
                            $nameLabel = new label($this->objLanguage->languageText('mod_elsiskin_namelabel','elsiskin'));

                            $retstr .= $nameLabel->show().'
                            <em>*</em><input type="text" class="required" name="c_name" maxlength="50" size="35">
                            <br />';
                            $emailLabel = new label($this->objLanguage->languageText('mod_elsiskin_email','elsiskin'));
                            $retstr .= $emailLabel->show().'
                            <em>*</em><input type="text" class="required" name="c_email" maxlength="50" size="35">
                            <br />';
                            $commentsLabel = new label($this->objLanguage->languageText('mod_elsiskin_comment','elsiskin'));
                            $retstr .= $commentsLabel->show();
	    $objTextarea = new textarea($this->objLanguage->languageText('mod_elsiskin_contentmesage','elsiskin'), '', '7', '40');
            $objTextarea->setCssClass("required");
            $objButton = new button($this->objLanguage->languageText('mod_elsiskin_submit','elsiskin'), $this->objLanguage->languageText('mod_elsiskin_send','elsiskin'));
            $objButton->setToSubmit();

            $retstr.= '<em>*</em>'.$objTextarea->show();
            $retstr.= '<br />';
	    $retstr .= $objButton->show();
            $retstr .= '
                            </fieldset>
                        </form>
                    </div>';

            return $retstr;
	 }

     /**
     * Method to show the most recent blogs, six of them
     * @return string
     * @access private
     */
     private function getBlogs() {
        $this->loadClass('href', 'htmlelements');
        $num = 3;
        $data = $this->objDbBlog->getLastPosts($num);
        $ret = "";
        if (!empty($data)) {
            $ret .= '<div class="grid_2">';
            foreach ($data as $item) {
                $ret .= '<div class="blog-post-preview">
                            <p>';
                $linkuri = $this->uri(array(
                            'action' => 'viewsingle',
                            'postid' => $item['id'],
                            'userid' => $item['userid']
                        ));
                $user = $this->objUser->fullname($item['userid']);
                $link = new href($linkuri, stripslashes($item['post_title']));
                $postExcerpt = $item['post_excerpt'];
                $fixedTime = strtotime($item['post_date']);
                $fixedTime = date('Y-m-d H:i:s', $fixedTime);
                $postDate = $this->objHumanizeDate->getDifference($fixedTime);
                $userlink = new link($this->uri(array()));
                $userlink->link = $this->objUser->getUserImage($item['userid']);
                $userlink->title = $user;
                $allBlogs = new link($this->uri(array("action"=>"randblog", "userid"=>$item['userid']), "blog"));
                $allBlogs->link = 'All posts by '. $user;

                $ret .= $userlink->show();
                $ret .= '<strong>'.$link->show().'</strong>
                         <br>';
            
                $ret .= '   </p>
                            <p>'.$postExcerpt.'
                            
                            </p>
                        <p class="post-details">
                            Written '.$postDate.' on '
                                     .date('Y-m-d', strtotime($item['post_date'])).' at '
                                     .date('H:i:s', strtotime($item['post_date']))
                                     .'<!-- Filed under: TAG. NUMBER comments-->
                        <br>By '.$user.' | '. $allBlogs->show().'
                        </p>
                        </div>';
                    
            }
            $ret .= '</div>';
        }

        return $ret;
    }

    /**
     * Method to show single blog content
     * @return string
     * @access private
     */
    private function showSingleBlog() {
        $postid = $this->getParam('postid');
        $posts = $this->objDbBlog->getPostByPostID($postid);
        $retstr = '<div class="grid_3">';
        $retstr .= $this->objblogPosts->showPosts($posts, TRUE);
        $retstr .= '</div>';

        return $retstr;
    }

    /**
     * Method to show single news story
     * @return string
     * @access private
     */
    private function showNewsStory() {
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $objNews = $this->getObject('dbnewsstories','news');
        $objWashout = $this->getObject('washout', 'utilities');
        $objTrimString = $this->getObject('trimstr', 'strings');
        $story = $objNews->getStory($this->newsId);

        $objDateTime = $this->getObject('dateandtime', 'utilities');

        $header = new htmlheading();
        $header->type = 1;
        $header->cssClass="newsstorytitleh1";
        $header->str = $story['storytitle'];
        //$this->setVar('pageTitle', $story['storytitle']);

        $str ='<div class="grid_3"><div id="newsstoryheader">'. $header->show();

        $str .= '<p>'.$objDateTime->formatDateOnly($story['storydate']).'</p></div>';

        $objWashOut = $this->getObject('washout', 'utilities');

        $str .='<div id="newsstorybody">'. $objWashOut->parseText($story['storytext']).'</div>';

        $objSocialBookmarking = $this->getObject('socialbookmarking', 'utilities');

        $str .= $objSocialBookmarking->diggThis();
        $str .= $objSocialBookmarking->show();
        $str .= "</div>";
        
        return $str;
    }

    private function getSupportAndDocumentation() {
        $this->category = "documentation";
        $this->documentation = "No documents have been set up";
        return $this->getContent();
    }

    private function getAboutContent() {
        $this->category = "about_Content";
        $this->documentation = "Content has not yet been set up";
        return $this->getContent();
    }

    private function getContent() {
        $retstr = "";
        $objCategories = $this->getObject("dbnewscategories", "news");
        $news = $this->getObject("dbnewsstories", "news");
        $categories = $objCategories->getCategories();

        foreach ($categories as $cat) {
            if ($cat['categoryname'] == $this->category) {//'documentation') {
                $documentationId = $cat['id'];
                $documentationStories = $news->getCategoryStories($documentationId);
                $this->documentation = $documentationStories[0]['storytext'];
            }
        }

        $retstr .= $this->documentation;
        return $retstr;
    }
}