<?php

/**
 * @Author: Emmanel Natalis
 * @University Computing center
 * @University of dar es salaam
 */
/**
 * This is the template file which displays the module's main menu
 */
/**
 * Retriving the user's fullname and concanating the welcome string
 * @var $welcome  This variable displays the welcome message
 */
$this->objUser = & $this->getObject('user', 'security');
/**
 *
 * @var fullname A variable for the user's fullname
 */
$this->fullname = $this->objUser->fullname();
/**
 *
 * Concanating the welcome string
 */
$this->objLanguage = $this->getObject('language', 'language');
$this->objAltconfig = $this->getObject('altconfig', 'config');
$arrayfullname = array('FULLNAME' => $this->fullname);
$this->welcome = "<font size=4 color=green>" . $this->objLanguage->code2Txt("mod_bestwishes_welcome", "bestwishes", $arrayfullname) . "</font>";
$this->objHtmltable = $this->getObject('htmltable', 'htmlelements');
$this->objTabcontent = $this->getObject('tabcontent', 'htmlelements');
$this->objTabcontent->width = '600px';
/**
 *
 * Java script for removal confirmation
 */
$confirmRemoval = "<script>
    function confirmRemove()
    {
    if(confirm(\"Are you sure you want to remove your birthdate? \")){
          window.location.href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes&action=bdremovedate';
      }
    }
    </script>";
echo $confirmRemoval;
/**
 *
 * Contents for the happy birthday menu
 */
$this->happybirthdayContent = "<table style=\"text-align: left; width: 511px; height: 85px;\"
 border=\"0\" cellpadding=\"2\" cellspacing=\"2\">
  <tbody>
    <tr>
      <td style=\"width: 141px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes&action=bdenterdate'><img alt=\"\" src=\"" . $this->objAltconfig->getsiteRoot() . "/skins/_common/icons/add.gif\"></a></center></td>
      <td style=\"width: 176px;\"><center><a href='javascript:onclick=confirmRemove()'><img alt=\"\" src=\"" . $this->objAltconfig->getsiteRoot() . "/skins/_common/icons/cancel.gif\"></a></center></td>
      <td style=\"width: 166px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes&viewbdusers'><img alt=\"\" src=\"" . $this->objAltconfig->getsiteRoot() . "/skins/_common/icons/view.gif\"></a></center></td>
    </tr>
    <tr>
      <td style=\"width: 141px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes&action=bdenterdate'>" . $this->objLanguage->languageText("mod_bestwishes_enterdate", "bestwishes") . "</a></center></td>
      <td style=\"width: 176px;\"><center><a href='javascript:onclick=confirmRemove()'>" . $this->objLanguage->languageText("mod_bestwishes_removedate", "bestwishes") . "</a></center></td>
      <td style=\"width: 166px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes&action=viewbdusers'>" . $this->objLanguage->languageText("mod_bestwishes_viewusers", "bestwishes") . "</a></center></td>
    </tr>
  </tbody>
</table>";


/**
 *
 * Contents for posting messages menu menu
 */
$this->postingmsgContent = "<table style=\"text-align: left; width: 511px; height: 85px;\"
 border=\"0\" cellpadding=\"2\" cellspacing=\"2\">
  <tbody>
    <tr>
      <td style=\"width: 141px;\"></td>
      <td style=\"width: 176px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes&action=enterevent'><img alt=\"\" src=\"" . $this->objAltconfig->getsiteRoot() . "/skins/_common/icons/forward.gif\"></a></center></td>
      <td style=\"width: 166px;\"></td>
    </tr>
    <tr>
      <td style=\"width: 141px;\"></td>
      <td style=\"width: 176px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes&action=enterevent'>" . $this->objLanguage->languageText("mod_bestwishes_postingmsg", "bestwishes") . "</a></center></td>
      <td style=\"width: 166px;\"></td>
    </tr>
  </tbody>
</table>";

/**
 *
 * Contents for view events menu
 */
$this->vieweventsContent = "<table style=\"text-align: left; width: 511px; height: 85px;\"
 border=\"0\" cellpadding=\"2\" cellspacing=\"2\">
  <tbody>
    <tr>
      <td style=\"width: 141px;\"></td>
      <td style=\"width: 176px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes&action=viewevent'><img alt=\"\" src=\"" . $this->objAltconfig->getsiteRoot() . "/skins/_common/icons/icq_noac.gif\"></a></center></td>
      <td style=\"width: 166px;\"></td>
    </tr>
    <tr>
      <td style=\"width: 141px;\"></td>
      <td style=\"width: 176px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes&action=viewevents'>" . $this->objLanguage->languageText("mod_bestwishes_vieweventsinner", "bestwishes") . "</a></center></td>
      <td style=\"width: 166px;\"></td>
    </tr>
  </tbody>
</table>";


/**
 *
 * Contents for the template cards
 */
$this->templatecardsContent = "<table style=\"text-align: left; width: 511px; height: 85px;\"
 border=\"0\" cellpadding=\"2\" cellspacing=\"2\">
  <tbody>
    <tr>
      <td style=\"width: 141px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes'><img alt=\"\" src=\"" . $this->objAltconfig->getsiteRoot() . "/skins/_common/icons/article.png\"></a></center></td>
      <td style=\"width: 176px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes'><img alt=\"\" src=\"" . $this->objAltconfig->getsiteRoot() . "/skins/_common/icons/cancel.gif\"></a></center></td>
      <td style=\"width: 166px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes'><img alt=\"\" src=\"" . $this->objAltconfig->getsiteRoot() . "/skins/_common/icons/view.gif\"></a></center></td>
    </tr>
    <tr>
      <td style=\"width: 141px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes'>" . $this->objLanguage->languageText("mod_bestwishes_sendcard", "bestwishes") . "</a></center></td>
      <td style=\"width: 176px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes'>" . $this->objLanguage->languageText("mod_bestwishes_viewcardcategory", "bestwishes") . "</a></center></td>
      <td style=\"width: 166px;\"><center><a href='" . $this->objAltconfig->getsiteRoot() . "?module=bestwishes'>" . $this->objLanguage->languageText("mod_bestwishes_makingcard", "bestwishes") . "</a></center></td>
    </tr>
  </tbody>
</table>";


$this->objTabcontent->addTab($this->objLanguage->languageText("mod_bestwishes_happybirthday", "bestwishes"), $this->happybirthdayContent);
$this->objTabcontent->addTab($this->objLanguage->languageText("mod_bestwishes_postingmsgs", "bestwishes"), $this->postingmsgContent);
$this->objTabcontent->addTab($this->objLanguage->languageText("mod_bestwishes_viewevents", "bestwishes"), $this->vieweventsContent);
$this->objTabcontent->addTab($this->objLanguage->languageText("mod_bestwishes_templatecards", "bestwishes"), $this->templatecardsContent);
$this->objHtmltable->startRow();
$this->objHtmltable->addCell("", 250);
$this->objHtmltable->addCell($this->welcome);
$this->objHtmltable->addCell("", 30);
$this->objHtmltable->endRow();
$this->objHtmltable->startRow();
$this->objHtmltable->addCell("<br>", 250);
$this->objHtmltable->endRow();
$this->objHtmltable->startRow();
$this->objHtmltable->addCell("", 250);
$this->objHtmltable->addCell($this->objTabcontent->show(), 40);
$this->objHtmltable->addCell("", 30);
echo("<br><br>");
echo $this->objHtmltable->show();
?>