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
 * @package   helloforms
 * @author    Derke Keats dkeats@uwc.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: controller.php,v 1.4 2007-11-25 09:13:27 dkeats Exp $
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
class imviewer extends object {

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;

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
        $this->objDBIM = $this->getObject('dbim');

        $this->objIcon->setIcon ( 'green_bullet' );
        //$this->objIcon->setAlt($this->objLanguage->languageText('mod_im_available', 'im'));
        $this->activeIcon = $this->objIcon->show ();
        $this->objIcon->setIcon ( 'grey_bullet' );
        $this->inactiveIcon = $this->objIcon->show ();
    }

    public function renderOutputForBrowser($msgs) {
        $objWashout = $this->getObject ( 'washout', 'utilities' );
        $ret = "<tr>";

        $max = 1;
        $rownum = 0;
        foreach ( $msgs as $msg ) {
            $box = "";
            //log_debug($msg);
            // whip out a content featurebox and plak the messages in
            //$from = explode('/', $msg['person']);
            $fuser = $msg ['msgfrom'];
            $msgid = $msg ['id'];

            // get the presence info if it exists
            $objPres = $this->getObject ( 'dbimpresence' );
            if ($objPres->getPresence ( $msg ['msgfrom'] ) == "available") {
                $presence = $this->activeIcon;
            } else {
                $presence = $this->inactiveIcon;
            }

            $sentat = $this->objLanguage->languageText ( 'mod_im_sentat', 'im' );
            $fromuser = $this->objLanguage->languageText ( 'mod_im_sentfrom', 'im' );
            $prevmessages = "";
            /*foreach ( $msg ['messages'] as $prevmess ) {
                //get the message
                if($prevmess['parentid'] != "")
                {
                        $fromwho = "Counsellor";
                        $cssclass = "subdued";
                }else{
                        $fromwho = "User";
                        $cssclass = "";
                }
                $prevmessages .= '<span class="'.$cssclass.'">'.$objWashout->parseText ( nl2br ( htmlentities ( "$fromwho: ".$prevmess ['msgbody'] ) ) ) . '</span> <br/>';
                //get the reply(s) if there was any
                $replies = $this->objDBIM->getReplies($prevmess['id']);

                $lastmsgId = $prevmess ['id'];
            }

            $ajax = "<span class=\"subdued\" id=\"replydiv" . $lastmsgId . "\">[REPLY]</span>
                        <script charset=\"utf-8\">
                            new Ajax.InPlaceEditor('replydiv" . $lastmsgId . "', 'index.php', { callback: function(form, value) { return 'module=im&action=reply&msgid=" . $lastmsgId . "&fromuser=" . $msg ['person'] . "&myparam=' + escape(value) }})
                        </script>";

            $box .= '<td><div class="im_default" >' . '<p class="im_source">' . $presence . ' <b>' . $fromuser . "</b>: " . $msg ['msgfrom'] . ', &nbsp;&nbsp;<b>' . $sentat . '</b>: ' . $msg ['datesent'] . "</p>" . '<p style ="height : 200px; overflow : auto;" class="im_message">' . $prevmessages . '</p><p>' . $ajax . '</p></div></td>';
*/
            //var_dump($msg);
            $box = $this->objFeatureBox->showContent($presence." <b>".$fromuser."</b>: ".$msg['msgfrom'].', &nbsp;&nbsp;<b>' . $sentat . '</b>: ' . $msg ['datesent'], $objWashout->parseText ( nl2br ( htmlentities ($msg['msgbody']))) ."<br />");
            //try to put 4 conversations in a row
           /* $rownum ++;
            if ($rownum == $max) {
                $box .= "</tr><tr>";
                $rownum = 0;
            }*/

            $ret .= $box;

        }
        header("Content-Type: text/html;charset=utf-8");
        return "<table>" . $ret . "</table>";
    }
}
?>
