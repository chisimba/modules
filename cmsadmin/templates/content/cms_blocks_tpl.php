<?php

/**
* Template for added removing blocks from a page
*
* @author Warren Windvogel
* @package cmsadmin
*/

// Suppress normal page elements and layout
$this->setVar('pageSuppressIM', FALSE);
$this->setVar('pageSuppressBanner', FALSE);
$this->setVar('pageSuppressToolbar', FALSE);
$this->setVar('suppressFooter', FALSE);

//Set layout template
$this->setLayoutTemplate('cms_blocks_layout_tpl.php');

echo $blockForm;

?>