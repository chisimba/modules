<script type="text/javascript">
//<![CDATA[
function init () {
    $('input_redraw').onclick = function () {
        redraw();
    }
}
function redraw () {
    var url = 'index.php';
    var pars = 'module=security&action=generatenewcaptcha';
    var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showResponse} );
}
function showLoad () {
    $('load').style.display = 'block';
}
function showResponse (originalRequest) {
    var newData = originalRequest.responseText;
    $('captchaDiv').innerHTML = newData;
}
//]]>
</script>
<?php
header ( "Content-Type: text/html;charset=utf-8" );
$cssLayout = $this->newObject ( 'csslayout', 'htmlelements' );
$cssLayout->setNumColumns ( 2 );

// get the sidebar object
$this->leftMenu = $this->newObject ( 'usermenu', 'toolbar' );
$this->loadClass ( 'htmlheading', 'htmlelements' );
$this->objFeatureBox = $this->getObject ( 'featurebox', 'navigation' );
$objWashout = $this->getObject ( 'washout', 'utilities' );
$objImView = $this->getObject ( 'jbviewer' );


$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading ( );
$header->str = $this->objLanguage->languageText ( 'mod_jabberblog_jabberblogof', 'jabberblog' ) . " " . $this->objUser->fullName ( $this->jposteruid );
$header->type = 1;

$hlink = $this->getObject ( 'link', 'htmlelements' );
$hlink->href = $this->uri ( array ('action' => NULL ) );
$hlink->link = $header->show ();

$script = '<script type="text/JavaScript" src="resources/rounded_corners.inc.js"></script>
    <script type="text/JavaScript">
      window.onload = function() {
          settings = {
              tl: { radius: 10 },
              tr: { radius: 10 },
              bl: { radius: 10 },
              br: { radius: 10 },
              antiAlias: true,
              autoPad: true
          }
          var myBoxObject = new curvyCorners(settings, "rounded");
          myBoxObject.applyCornersToAll();
      }
    </script>';
$this->appendArrayVar ( 'headerParams', $script );

$middleColumn .= $hlink->show ();
$middleColumn .= $output;

$rssLink = $this->getObject ( 'link', 'htmlelements' );
$rssLink->href = $this->uri ( array ('action' => 'rss' ) );
$rssLink->link = $this->objLanguage->languageText ( "mod_jabberblog_showrss", "jabberblog" );

$objIcon = $this->newObject ( 'geticon', 'htmlelements' );
$this->loadClass('href', 'htmlelements');
$objIcon->alt = 'SIOC';
$objIcon->setIcon('sioc', 'gif');
$sioclink = new href($this->uri(array('action' => 'sioc', 'sioc_type' => 'site')), $objIcon->show());

$objLT = $this->getObject ( 'block_lasttweet', 'twitter' );

if (! $this->objUser->isLoggedIn ()) {
    $leftColumn .= $objImView->showUserMenu ();
    $leftColumn .= $objImView->getStatsBox ();
    $leftColumn .= $this->objFeatureBox->show ( $this->objLanguage->languageText ( "mod_jabberblog_feed", "jabberblog" ), $rssLink->show ()."<br />".$sioclink->show() );
    $leftColumn .= $this->objFeatureBox->show ( $this->objLanguage->languageText ( "mod_jabberblog_twitterfeed", "jabberblog" ), $objLT->show () );
} else {
    $leftColumn .= $this->leftMenu->show ();
    $leftColumn .= $objImView->getStatsBox ();
    $leftColumn .= $this->objFeatureBox->show ( $this->objLanguage->languageText ( "mod_jabberblog_feed", "jabberblog" ), $rssLink->show ()."<br />".$sioclink->show() );
    $leftColumn .= $this->objFeatureBox->show ( $this->objLanguage->languageText ( "mod_jabberblog_twitterfeed", "jabberblog" ), $objLT->show () );
}

$cssLayout->setMiddleColumnContent ( $middleColumn );
$cssLayout->setLeftColumnContent ( $leftColumn );
echo $cssLayout->show ();