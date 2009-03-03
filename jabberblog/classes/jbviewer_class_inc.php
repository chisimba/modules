<?php
/**
 *
 * Viewer class for rendering an array of messages to the browser
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
 * @package   jabberblog
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2007 AVOIR
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
 * Viewer class for rendering an array of messages to the browser
 *
 * @author Derek Keats
 * @package IM
 *
 */
class jbviewer extends object {

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;
    public $uImage;
    public $objWashout;

    /**
     *
     * Constructor

     * @access public
     *
     */
    public function init() {
        $this->objLanguage = $this->getObject ( 'language', 'language' );
        $this->objFeatureBox = $this->getObject ( 'featurebox', 'navigation' );
        $this->objIcon = $this->getObject ( 'geticon', 'htmlelements' );
        $this->objLink = $this->getObject ( 'link', 'htmlelements' );
        $this->objDBIM = $this->getObject ( 'dbjbim' );
        $this->objComment = $this->getObject('commentapi', 'blogcomments');
        $this->objUser = $this->getObject('user', 'security');
        $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $this->jposteruid = $this->objSysConfig->getValue ( 'jposteruid', 'jabberblog' );
        $this->uImage = $this->objUser->getSmallUserImage($this->jposteruid);
        $this->objWashout = $this->getObject ( 'washout', 'utilities' );
    }

    public function renderSingle($msg) {
        $msg = $msg[0];
        $msgbody = $this->objWashout->parseText($msg['msgbody']);
        $fuser = $msg ['msgfrom'];
        $msgid = $msg ['id'];
        $sentat = $this->objLanguage->languageText ( 'mod_im_sentat', 'jabberblog' );
        $fromuser = $this->objLanguage->languageText ( 'mod_im_sentfrom', 'jabberblog' );
        $commenttxt = $this->objComment->showJblogComments($msgid);
        $comment = $this->objComment->commentAddForm($msgid, 'jabberblog', 'tbl_jabberblog', $postuserid = NULL, $editor = TRUE, $featurebox = FALSE, $showtypes = FALSE, $captcha = FALSE, $comment = NULL, $useremail = NULL);
        $ret = '<div class="bubble"><div class="rounded">
                     <blockquote><p>'.nl2br($msgbody).'</p></blockquote>
                     </div>
                     <cite class="rounded"><strong>'.$this->objUser->fullName($this->jposteruid).'</strong> on '.$msg['datesent'].'</cite>
                     </div><br />'.$commenttxt."<br />".$comment;

        return $ret;

    }

    public function renderOutputForBrowser($msgs) {
        // append the javascript to the page header
        $ret = NULL;
        //var_dump($msgs);
        foreach ( $msgs as $msg ) {
            $msgbody = $this->objWashout->parseText($msg['msgbody']);
            $fuser = $msg ['msgfrom'];
            $msgid = $msg ['id'];
            $sentat = $this->objLanguage->languageText ( 'mod_im_sentat', 'jabberblog' );
            $fromuser = $this->objLanguage->languageText ( 'mod_im_sentfrom', 'jabberblog' );
            // set up a link for comments
            $clink = $this->getObject('link', 'htmlelements');
            $clink->href = $this->uri(array('postid' => $msgid, 'action' => 'viewsingle'));
            $clink->link = $this->objLanguage->languageText("mod_jabberblog_leavecomment", "jabberblog");
            // get the comment count
            $comments = $this->objComment->getCount($msgid);

            $ret .= '<div class="bubble"><div class="rounded">
                     <blockquote><p>'.nl2br($msgbody).'</p></blockquote>
                     </div>
                     <cite class="rounded"><strong>'.$this->objUser->fullName($this->jposteruid).'</strong> on '.$msg['datesent']." ".$clink->show()."  (".$comments.")".'</cite>
                     </div>';

            /*
            $ret .= '<div class="bubble">
                     <blockquote><p>'.$msg['msgbody'].'</p></blockquote>

                     <cite><strong>'.$msg['msgfrom'].'</strong> on '.$msg['datesent'].'</cite>
                     </div>'; */

        }
        header ( "Content-Type: text/html;charset=utf-8" );
        return $ret;
    }

    /**
     * Nethod to show the quick links to conversations
     */
    public function renderLinkList($msgs) {
        if (count ( $msgs ) > 0) {

            $anchor = $this->getObject ( 'link', 'htmlelements' );
            $str = '    <ul>';
            $class = ' class="first" ';

            foreach ( $msgs as $msg ) {

                $anchor->href = '#' . $msg ['person'];
                $anchor->link = $msg ['person'];
                $str .= "<li>" . $anchor->show () . "</li>";

                $class = "  class=\"personalspace\" ";

            }
            $str .= '</ul>';
            return $this->objFeatureBox->show ( 'Quick Links', $str );
        } else {
            return "";
        }

    }

    /**
     * Method to render stats
     */
    public function getStatsBox() {
        $this->objDbIm = $this->getObject ( 'dbjbim' );
        $str = $this->objLanguage->languageText("mod_jabberblog_nomsgs", "jabberblog");
        $str .= " ".$this->objDbIm->getNoMsgs();

        return $this->objFeatureBox->show ( $this->objLanguage->languageText("mod_jabberblog_stats", "jabberblog"), $str );
    }

    public function showUserMenu() {
        $menu = "<center>".$this->objUser->getUserImage($this->jposteruid )."</center>";
        $blurb = "I am an asshole";
        $objFeature = $this->newObject('featurebox', 'navigation');

        return $objFeature->show($this->objUser->fullName($this->jposteruid), $menu."<br />".$blurb);
    }

    public function rssBox() {
        $this->objFeed = $this->getObject('feeder', 'feed');
        //$this->objFeed->setrssImage($iTitle, $iURL, $iLink, $iDescription, $iTruncSize = 500, $desHTMLSyn = true);
        $this->objFeed->setupFeed($stylesheet = false, 'Chisimba JabberBlog', 'Jabber based microblogging', $this->uri(''), $this->uri(''));
        // get the latest say, 50 posts, and make a feed from em
        $this->objDbIm = $this->getObject ( 'dbjbim' );
        $count = $this->objDbIm->getNoMsgs();
        $num = $count - 50;
        if($num < 0) {
            $num = 0;
        }
        $items = $this->objDbIm->getRange($num, $count);
        array_reverse($items);
        // now we add the items to the feed
        foreach ($items as $item) {
            $this->objFeed->addItem($item['msgfrom'], $this->uri(array('action' => 'viewsingle', 'postid' => $item['id']), 'jabberblog'), $item['msgbody'], NULL, $this->objUser->userName($this->jposteruid), $item['datesent']);
        }

        return $this->objFeed->output("RSS2.0");
    }
}
?>
