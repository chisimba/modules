<?
$this->loadClass('htmlheading','htmlelements');
$this->loadClass('link', 'htmlelements');


$header = new htmlheading();
$header->type=1;
$header->str=$this->objLanguage->languageText('mod_forum_accessdeniedworkgroup');
echo $header->show();

echo '<p>';
echo $this->objLanguage->languageText('mod_forum_accessdeniedworkgroupmessage');
echo '</p>';
echo '<p>';
$prevPage = new link ('javascript: history.go(-1)');
$prevPage->link = $this->objLanguage->languageText('mod_forum_backtoprevpage');

echo $prevPage->show();

echo ' / ';

$backtoForumLink = new link ($this->uri(NULL));
$backtoForumLink->link = $this->objLanguage->languageText('mod_forum_backtoforumsincontent').' '.$contextTitle;

echo $backtoForumLink->show();

echo '</p>';
?>