<?php
$this->loadClass('treemenu','tree');
$this->loadClass('htmllist','tree');

$menu = new treemenu();


//$cmsadmin = $this->getObject('modulelinks_cmsadmin', 'cmsadmin');
$forum = $this->getObject('modulelinks_forum', 'forum');


//$menu->addItem($cmsadmin->show());
$menu->addItem($forum->show());


$htmllist  = &new htmllist($menu);

echo $htmllist->getMenu();
?>