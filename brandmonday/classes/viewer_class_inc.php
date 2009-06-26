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
 * @package   brandmonday
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
 * @author Paul Scott
 * @package brandmonday
 *
 */
class viewer extends object {

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
    public $teeny;
    public $objConfig;

    /**
     *
     * Constructor

     * @access public
     *
     */
    public function init() {
        $this->objLanguage = $this->getObject ( 'language', 'language' );
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objFeatureBox = $this->getObject ( 'featurebox', 'navigation' );
        $this->objIcon = $this->getObject ( 'geticon', 'htmlelements' );
        $this->objLink = $this->getObject ( 'link', 'htmlelements' );
        
        $this->objUser = $this->getObject ( 'user', 'security' );
        //$this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $this->objWashout = $this->getObject ( 'washout', 'utilities' );
        $this->teeny = $this->getObject ( 'tiny', 'tinyurl');
    }

    public function renderCompView($plus = NULL, $minus = NULL, $fail = NULL) {
        if(is_object($plus)) {
            $plus = $plus->results;
        }
        if(is_object($minus)) {
            $minus = $minus->results;
        }
        if(is_object($fail)) {
            $fail = $fail->results;
        }
        $messages = NULL;
        $plusmessages = NULL;
        $minusmessages = NULL;
        $failmessages = NULL;
        // ok so lets do the iterations and build our output
        foreach($plus as $pos) {
            $text = $pos->text;
            $pic = $pos->profile_image_url;
            $user = $pos->from_user;
            $createdat = $pos->created_at;
            $usrlink = $this->newObject('link', 'htmlelements');
            $usrlink->href = "http://twitter.com/$user";
            $usrlink->link = $user;
            $txt = "<b>".$usrlink->show()."</b> ".$text."<br />".$createdat;
            $image = "<a href='http://twitter.com/".$user."'><img src='$pic' height='48', width='48' /></a>";
            // bust out a table to format the lot, then bang it in a feturebox
            $msgtbl = $this->newObject('htmltable', 'htmlelements');
            $msgtbl->cellpadding = 3;
            $msgtbl->cellspacing = 3;
            $msgtbl->startRow();
            $msgtbl->addCell($image, 1);
            $msgtbl->addCell($txt);
            $msgtbl->endRow();

            $plusmessages .= $msgtbl->show();
        }

        // now the minus messages
        foreach($minus as $neg) {
            $text = $neg->text;
            $pic = $neg->profile_image_url;
            $user = $neg->from_user;
            $createdat = $neg->created_at;
            $usrlink = $this->newObject('link', 'htmlelements');
            $usrlink->href = "http://twitter.com/$user";
            $usrlink->link = $user;
            $txt = "<b>".$usrlink->show()."</b> ".$text."<br />".$createdat;
            $image = "<a href='http://twitter.com/".$user."'><img src='$pic' height='48', width='48' /></a>";
            // bust out a table to format the lot, then bang it in a feturebox
            $msgtbl2 = $this->newObject('htmltable', 'htmlelements');
            $msgtbl2->cellpadding = 3;
            $msgtbl2->cellspacing = 3;
            $msgtbl2->startRow();
            $msgtbl2->addCell($image, 1);
            $msgtbl2->addCell($txt);
            $msgtbl2->endRow();

            $minusmessages .= $msgtbl2->show();
        }

        // fail messages
        foreach($fail as $bad) {
            $text = $bad->text;
            $pic = $bad->profile_image_url;
            $user = $bad->from_user;
            $createdat = $bad->created_at;
            $usrlink = $this->newObject('link', 'htmlelements');
            $usrlink->href = "http://twitter.com/$user";
            $usrlink->link = $user;
            $txt = "<b>".$usrlink->show()."</b> ".$text."<br />".$createdat;
            $image = "<a href='http://twitter.com/".$user."'><img src='$pic' height='48', width='48' /></a>";
            // bust out a table to format the lot, then bang it in a feturebox
            $msgtbl3 = $this->newObject('htmltable', 'htmlelements');
            $msgtbl3->cellpadding = 3;
            $msgtbl3->cellspacing = 3;
            $msgtbl3->startRow();
            $msgtbl3->addCell($image, 1);
            $msgtbl3->addCell($txt);
            $msgtbl3->endRow();

            $failmessages .= $msgtbl3->show();
        }

        $minusmessages = $minusmessages.$failmessages;

        // 2 more headings, BrandPlus and BrandMinus needed now
        $this->loadClass ( 'htmlheading', 'htmlelements' );
        $bp = new htmlHeading ( );
        $bp->str = $this->objLanguage->languageText ( 'mod_brandmonday_bp', 'brandmonday' );
        $bp->type = 3;

        $bm = new htmlHeading ( );
        $bm->str = $this->objLanguage->languageText ( 'mod_brandmonday_bm', 'brandmonday' );
        $bm->type = 3;

        $bigtbl = $this->newObject('htmltable', 'htmlelements');
        $bigtbl->cellpadding = 3;
        $bigtbl->cellspacing = 3;
        $bigtbl->border = 1;
        $bigtbl->startRow();
        $bigtbl->addCell($bp->show()."<br />".$plusmessages);
        $bigtbl->addCell($bm->show()."<br />".$minusmessages);
        $bigtbl->endRow();
        
        return $bigtbl->show();
    }

    public function renderLeftBlocks() {
        // Chisimba ad and link
        $this->objWashout = $this->getObject("washout", "utilities");
        $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $adhead1 = $this->objSysConfig->getValue ( 'adhead1', 'brandmonday' );
        $adhead2 = $this->objSysConfig->getValue ( 'adhead2', 'brandmonday' );
        $fbhead = $this->objSysConfig->getValue ( 'fbhead', 'brandmonday' );
        $fbtext = $this->objSysConfig->getValue ( 'fbtext', 'brandmonday' );
        $adtext1 = $this->objSysConfig->getValue ( 'adtext1', 'brandmonday' );
        $adtext2 = $this->objSysConfig->getValue ( 'adtext2', 'brandmonday' );
        $chistext = $this->objSysConfig->getValue ( 'chistext', 'brandmonday' );
        $chishead = $this->objSysConfig->getValue ( 'chishead', 'brandmonday' );

        $ret = NULL;

        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $ret .= $objFeatureBox->show($chishead, $this->objWashout->parseText($chistext));

        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $ret .= $objFeatureBox->show($fbhead, $this->objWashout->parseText($fbtext));

        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $ret .= $objFeatureBox->show($adhead1, $this->objWashout->parseText($adtext1));

        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $ret .= $objFeatureBox->show($adhead2, $this->objWashout->parseText($adtext2));

        $objTwitterRemote = $this->getObject("twitterremote", "twitter");
        $objTwitterRemote->userName = "CapeTown";
        $tweets = $objTwitterRemote->showTimeline(FALSE, 'user');
        
//var_dump($tweets);
        $objFeatureBox = $this->newObject('featurebox', 'navigation');
        $text = $tweets;
        $ret .= $objFeatureBox->show("@CapeTown", $this->objWashout->parseText($text));

        return $ret;
    }

}
?>