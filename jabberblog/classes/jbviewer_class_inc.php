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
        $this->objComment = $this->getObject ( 'commentapi', 'blogcomments' );
        $this->objUser = $this->getObject ( 'user', 'security' );
        $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $this->jposteruid = $this->objSysConfig->getValue ( 'jposteruid', 'jabberblog' );
        $this->uImage = $this->objUser->getSmallUserImage ( $this->jposteruid );
        $this->objWashout = $this->getObject ( 'washout', 'utilities' );
    }

    public function renderSingle($msg) {
        $this->loadClass ( 'htmlheading', 'htmlelements' );
        // Add in a comment heading
        $header = new htmlHeading ( );
        $header->str = $this->objLanguage->languageText ( 'mod_jabberblog_comments', 'jabberblog' );
        $header->type = 3;

        $msg = $msg [0];
        $msgbody = $this->objWashout->parseText ( $msg ['msgbody'] );
        // run the parsers on the body
        $msgbody = $this->renderHashTags( $msgbody );
        $msgbody = $this->renderAtTags( $msgbody );

        $msgid = $msg ['id'];
        $commenttxt = $this->objComment->showJblogComments ( $msgid );
        $comment = $this->objComment->commentAddForm ( $msgid, 'jabberblog', 'tbl_jabberblog', $postuserid = NULL, $editor = TRUE, $featurebox = FALSE, $showtypes = FALSE, $captcha = FALSE, $comment = NULL, $useremail = NULL );
        $objFeaturebox = $this->getObject ( 'featurebox', 'navigation' );
        $ret = $objFeaturebox->showContent ( '<strong>' . $this->objUser->fullName ( $this->jposteruid ) . '</strong> on ' . $msg ['datesent'], nl2br ( $msgbody ) . "<br />".$commenttxt . "<br />" . $comment );
        $ret .= "<hr />";

        return $ret;
    }

    public function renderOutputForBrowser($msgs) {
        $ret = NULL;
        foreach ( $msgs as $msg ) {
            $msgbody = $this->objWashout->parseText ( $msg ['msgbody'] );
            // run the parsers on the body
            $msgbody = $this->renderHashTags( $msgbody );
            $msgbody = $this->renderAtTags( $msgbody );

            $fuser = $msg ['msgfrom'];
            $msgid = $msg ['id'];
            $sentat = $this->objLanguage->languageText ( 'mod_im_sentat', 'jabberblog' );
            $fromuser = $this->objLanguage->languageText ( 'mod_im_sentfrom', 'jabberblog' );
            // set up a link for comments
            $clink = $this->getObject ( 'link', 'htmlelements' );
            $clink->href = $this->uri ( array ('postid' => $msgid, 'action' => 'viewsingle' ) );
            $clink->link = $this->objLanguage->languageText ( "mod_jabberblog_leavecomment", "jabberblog" );
            // get the comment count
            $comments = $this->objComment->getCount ( $msgid );

            // alt featurebox
            $objFeaturebox = $this->getObject ( 'featurebox', 'navigation' );
            $ret .= $objFeaturebox->showContent ( '<strong>' . $this->objUser->fullName ( $this->jposteruid ) . '</strong> on ' . $msg ['datesent'] . " " . $clink->show () . "  (" . $comments . ")", nl2br ( $msgbody ) . "<br />" );
            $ret .= "<hr />";
        }
        header ( "Content-Type: text/html;charset=utf-8" );
        return $ret;
    }

    /**
     * Method to render stats
     */
    public function getStatsBox() {
        $this->objDbIm = $this->getObject ( 'dbjbim' );
        $this->objDbSubs = $this->getObject('dbsubs');
        $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $this->juser = $this->objSysConfig->getValue ( 'jabberuser', 'jabberblog' );
        $this->jdomain = $this->objSysConfig->getValue ( 'jabberdomain', 'jabberblog' );
        $str = $this->objLanguage->languageText ( "mod_jabberblog_subinfo", "jabberblog" ).": ".$this->juser."@".$this->jdomain;
        $str .= "<br />";
        $str .= "<br />";
        $str .= $this->objLanguage->languageText ( "mod_jabberblog_nomsgs", "jabberblog" );
        $str .= " " . $this->objDbIm->getNoMsgs ();
        $str .= "<br />";
        $str .= $this->objLanguage->languageText ( "mod_jabberblog_numsubs", "jabberblog" );
        $str .= " " . $this->objDbSubs->getNoSubs ();

        return $this->objFeatureBox->show ( $this->objLanguage->languageText ( "mod_jabberblog_stats", "jabberblog" ), $str );
    }

    public function showUserMenu() {
        $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $objWashout = $this->getObject ( 'washout', 'utilities' );
        $this->profiletext = $this->objSysConfig->getValue ( 'jposterprofile', 'jabberblog' );
        $menu = "<center>" . $this->objUser->getUserImage ( $this->jposteruid, FALSE, 'user_image' ) . "</center>";
        $blurb = $objWashout->parseText ( $this->profiletext );
        $objFeature = $this->newObject ( 'featurebox', 'navigation' );

        return $objFeature->show ( $this->objUser->fullName ( $this->jposteruid ), $menu . "<br />" . $blurb );
    }

    public function searchBox() {
        $this->loadClass('textinput', 'htmlelements');
        $qseekform = new form('qseek', $this->uri(array(
            'action' => 'jbsearch',
        )));
        $qseekform->addRule('searchterm', $this->objLanguage->languageText("mod_jabberblog_phrase_searchtermreq", "jabberblog") , 'required');
        $qseekterm = new textinput('searchterm');
        $qseekterm->size = 15;
        $qseekform->addToForm($qseekterm->show());
        $this->objsTButton = &new button($this->objLanguage->languageText('word_search', 'system'));
        $this->objsTButton->setValue($this->objLanguage->languageText('word_search', 'system'));
        $this->objsTButton->setToSubmit();
        $qseekform->addToForm($this->objsTButton->show());
        $qseekform = $qseekform->show();
        $objFeatureBox = $this->getObject('featurebox', 'navigation');
        $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_jabberblog_qseek", "jabberblog") , $this->objLanguage->languageText("mod_jabberblog_qseekinstructions", "jabberblog") . "<br />" . $qseekform);

        return $ret;
    }

    public function rssBox() {
        $this->objFeed = $this->getObject ( 'feeder', 'feed' );
        //$this->objFeed->setrssImage($iTitle, $iURL, $iLink, $iDescription, $iTruncSize = 500, $desHTMLSyn = true);
        $this->objFeed->setupFeed ( $stylesheet = false, 'Chisimba JabberBlog', 'Jabber based microblogging', $this->uri ( '' ), $this->uri ( '' ) );
        // get the latest say, 50 posts, and make a feed from em
        $this->objDbIm = $this->getObject ( 'dbjbim' );
        $count = $this->objDbIm->getNoMsgs ();
        $num = $count - 50;
        if ($num < 0) {
            $num = 0;
        }
        $items = $this->objDbIm->getRange ( $num, $count );
        array_reverse ( $items );
        // now we add the items to the feed
        foreach ( $items as $item ) {
            $this->objFeed->addItem ( $item ['msgfrom'], $this->uri ( array ('action' => 'viewsingle', 'postid' => $item ['id'] ), 'jabberblog' ), $item ['msgbody'], NULL, $this->objUser->userName ( $this->jposteruid ), $item ['datesent'] );
        }

        return $this->objFeed->output ( "RSS2.0" );
    }

    public function parseHashtags($str, $itemId)
    {
        $str = stripslashes($str);
        preg_match_all('/\#([a-zA-Z0-9_]{1,15}) ?/', $str, $results);
        $counter = 0;
        foreach ($results[1] as $item)
        {
            $memetag = array($item);
            // add the $item to tbl_tags as a jabberblog meme for later
            $objTags = $this->getObject('dbtags', 'tagging');
            $objTags->insertHashTags($memetag, $this->jposteruid, $itemId, 'jabberblog', NULL, NULL);
            $counter++;
        }

        return $str;
    }

    public function renderHashTags($str) {
    	$str = stripslashes($str);
        preg_match_all('/\#([a-zA-Z0-9_]{1,15}) ?/', $str, $results);
        $counter = 0;
        foreach ($results[1] as $item) {
            // set up a link to the URI to display all posts in the meme
            $hashlink = $this->getObject ( 'link', 'htmlelements' );
            $hashlink->href = $this->uri ( array ('meme' => $item, 'action' => 'viewmeme' ) );
            $hashlink->link = $item;
            $str = str_replace($results[0][$counter], $hashlink->show()." ", $str);
            $counter++;
        }

        return $str;
    }

    public function parseAtTags($str, $itemId)
    {
        $str = stripslashes($str);
        preg_match_all('/\@([a-zA-Z0-9_]{1,15}) ?/', $str, $results);
        $counter = 0;
        foreach ($results[1] as $item)
        {
            $attag = array($item);
            // add the $item to tbl_tags as a jabberblog meme for later
            $objTags = $this->getObject('dbtags', 'tagging');
            $objTags->insertAtTags($attag, $this->jposteruid, $itemId, 'jabberblog', NULL, NULL);
            $counter++;
        }

        return $str;
    }

    public function renderAtTags($str) {
    	$str = stripslashes($str);
        preg_match_all('/\@([a-zA-Z0-9_]{1,15}) ?/', $str, $results);
        $counter = 0;
        foreach ($results[1] as $item) {
            // set up a link to the URI to display all posts in the meme
            $atlink = $this->getObject ( 'link', 'htmlelements' );
            $atlink->href = $this->uri ( array ('loc' => $item, 'action' => 'viewloc' ) );
            $atlink->link = $item;
            $str = str_replace($results[0][$counter], $atlink->show()." ", $str);
            $counter++;
        }

        return $str;
    }

    public function renderBoxen() {
        $leftColumn = NULL;
        $objIcon = $this->newObject ( 'geticon', 'htmlelements' );
        $this->loadClass('href', 'htmlelements');
        $objIcon->alt = 'SIOC';
        $objIcon->setIcon('sioc', 'gif');
        $sioclink = new href($this->uri(array('action' => 'sioc', 'sioc_type' => 'site')), $objIcon->show());

        $rssLink = $this->newObject ( 'link', 'htmlelements' );
        $rssLink->href = $this->uri ( array ('action' => 'rss' ) );
        $rssLink->link = $this->objLanguage->languageText ( "mod_jabberblog_showrss", "jabberblog" );

        $cloudLink = $this->newObject ( 'link', 'htmlelements' );
        $cloudLink->href = $this->uri ( array ('action' => 'clouds' ) );
        $cloudLink->link = $this->objLanguage->languageText ( "mod_jabberblog_showtagclouds", "jabberblog" );

        $objLT = $this->getObject ( 'block_lasttweet', 'twitter' );

        $leftColumn .= $this->getStatsBox ();
        $leftColumn .= $this->objFeatureBox->show ( $this->objLanguage->languageText ( "mod_jabberblog_feed", "jabberblog" ), $rssLink->show ()."<br />".$sioclink->show()."<br />".$cloudLink->show() );
        $leftColumn .= $this->searchBox();
        // show the last tweet block from the 'ol twitter stream
        $leftColumn .= $this->objFeatureBox->show ( $this->objLanguage->languageText ( "mod_jabberblog_twitterfeed", "jabberblog" ) );//, $objLT->show () );

        return $leftColumn;
    }
}
?>