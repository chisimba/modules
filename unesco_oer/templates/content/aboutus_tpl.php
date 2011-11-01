<?php
$this->loadClass('htmlheading','htmlelements');
$objDateTime = $this->getObject('dateandtime', 'utilities');
$objWashOut = $this->getObject('washout', 'utilities');
foreach ($stories as $story) {

    $header = new htmlheading();
    $header->type = 1;
    $header->cssClass = "newsstorytitleh1";
    $header->str = $story['storytitle'];
    $str = '<div id="newsstoryheader">' . $header->show();

    $str .= '<p>' . $objDateTime->formatDateOnly($story['storydate']) . '</p></div>';

    $objWashOut = $this->getObject('washout', 'utilities');

    $str .='<div id="newsstorybody">' . $objWashOut->parseText($story['storytext']) . '</div>';

    if ($story['storysource'] != '') {
        $objUrl = &$this->getObject('url', 'strings');

        $source = $story['storysource'];

        $source = $objUrl->makeClickableLinks(htmlentities($source));

        $str .= '<p><strong>' . $this->objLanguage->languageText('word_source', 'word', 'Source') . ':</strong><br />' . $source . '</p>';
    }
    echo '<fieldset>'. $str.'</fieldset>';
}
?>