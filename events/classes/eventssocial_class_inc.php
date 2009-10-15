<?php
/**
 *
 * Events social helper class
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
 * @package   events
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 AVOIR
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
 * Events social helper class
 *
 * PHP version 5.1.0+
 *
 * @author Paul Scott
 * @package events
 *
 */
class eventssocial extends object {

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
     * @var string $objWashout String object property for holding the washout object
     *
     * @access public
     */
    public $objWashout;

    /**
     * @var string $objUser String object property for holding the user object
     *
     * @access public
     */
    public $objUser;

    /**
     * @var string $objCurl String object property for holding the curl object
     *
     * @access public
     */
    public $objCurl;

    public $objTags;

    public $objDbAct;


    /**
     * Constructor
     *
     * @access public
     */
    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser     = $this->getObject('user', 'security');
        $this->objDbAct    = $this->getObject('activitydb', 'activitystreamer');
    }
    
    public function searchFriendsForm() {
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        $required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'events', 'Required').'</span>';
        $headersrch = new htmlheading();
        $headersrch->type = 2;
        $headersrch->str = $this->objLanguage->languageText('mod_events_searchmate', 'events');
        $ret = NULL;
        $ret .= $headersrch->show();
        // start the form
        $form = new form ('searchfriends', $this->uri(array('action'=>'searchfriends')));
        // add some rules
        $form->addRule('friend_name', $this->objLanguage->languageText("mod_events_needfriendname", "events"), 'required');
        // friend name
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->startRow();
        $friendname = new textinput('friend_name');
        $friendnameLabel = new label($this->objLanguage->languageText('mod_events_friendname', 'events').':', 'input_friendname');
        $table->addCell($friendnameLabel->show(), 150, NULL, 'right');
        $table->addCell('&nbsp;', 5);
        $table->addCell($friendname->show().$required);
        $table->endRow();
        
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = $this->objLanguage->languageText('mod_events_searchfriends', 'events');
        $fieldset->contents = $table->show();
        // add the form to the fieldset
        $form->addToForm($fieldset->show());
        $button = new button ('submitform', $this->objLanguage->languageText("mod_events_search", "events"));
        $button->setToSubmit();
        $form->addToForm('<p align="center"><br />'.$button->show().'</p>');
        $ret .= $form->show();
        
        return $ret;
    }
    
    public function formatFriendSearch($res) {
        $ret = NULL;
        if(!array($res)) {
            return "sommin went wron";
        }
        else {
            foreach($res as $r) {
                $objFb = $this->newObject('featurebox', 'navigation');
                $userimg = $this->objUser->getUserImage($r['userid'], FALSE, $this->objUser->fullName($r['userid']));
                $mflink = $this->newObject('link', 'htmlelements');
                $mflink->href = $this->uri(array('action' => 'makefriend', 'fuserid' => $r['userid']));
                $mflink->link = $this->objLanguage->languageText("mod_events_makefriend", "events");
                $mflink = $mflink->show();
                $ret .= $objFb->show($r['firstname']." ".$r['surname']." (".$r['username'].")", $userimg.$mflink);
            }
        }
        return $ret;
    }

    /**
     * Method to display the last ten activity streamer posts as a block
     *
     * @access public
     * @param  integer $num        The number of posts to display. Default = 10
     * @param  bool    $featurebox Return the posts as a string or formatted in a featurebox. Default = false, return as a string
     * @return string  html
     */
    public function showLastTenActivities($num = 10, $featurebox = FALSE)
    {
        $this->loadClass('href', 'htmlelements');
        $objUser = $this->getObject('user', 'security');
        $this->loadClass('link', 'htmlelements');
        $data = $this->objDbAct->getActivities("WHERE module = 'events'", $num);
        $str = '';
        // Display the posts
        if (!empty($data)) {
            foreach($data as $item) {
                if(isset($item['link'])) {
                    if($item['title'] == 'Location change') {
                        $link = new href($item['link'], $this->objLanguage->languageText("mod_events_whereisthat", "events"));
                    }
                    else {
                        $link = new href($item['link'], $this->objLanguage->languageText("mod_events_word_more", "events"));
                    }
                    $link = $link->show();
                }
                else {
                    $link = NULL;
                }
                $str.= '<p><font class="minute">'.$item['description']." ".$link.'</font>';
                $str.= '</p>';
            }
        }
        // Display either as a string for the block or in a featurebox
        if ($featurebox == FALSE) {
            return $str;
        } else {
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_block_latestblogs", "blog") , $str);
            return $ret;
        }
    }

    public function showLastTenPostsStripped($num = 10, $featurebox = FALSE)
    {
        $objUser = $this->getObject('user', 'security');
        $this->loadClass('link', 'htmlelements');
        $data = $this->objDbBlog->getLastPosts($num);
        $str = '';
        // Display the posts
        if (!empty($data)) {
            foreach($data as $item) {
                $linkuri = $this->uri(array(
                    'action' => 'viewsingle',
                    'postid' => $item['id'],
                    'userid' => $item['userid']
                ));
                //$link = new href($linkuri, stripslashes($item['post_title']));

                //$str.= $link->show();
                if ($this->showfullname == 'FALSE') {
                    $nameshow = $this->objUser->userName($item['userid']);
                } else {
                    $nameshow = $this->objUser->fullname($item['userid']);
                }
                $str.= $nameshow." ".$item['post_title']." ".$linkuri."\r\n";
            }
        }
        // Display either as a string for the block or in a featurebox
        if ($featurebox == FALSE) {
            return $str;
        } else {
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_block_latestblogs", "blog") , $str);
            return $ret;
        }
    }
}
?>