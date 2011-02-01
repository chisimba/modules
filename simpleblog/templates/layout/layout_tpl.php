<?php
/**
*
*  A layout template for Simple blog
*  Author: Administrative User admin@localhost.local
*  Date: January 17, 2011, 1:41 pm
*
*  A layout template for use with dynamic canvas content. This layout
*  template is required in any module that will be parsing JSON templates.
*  See demo_tpl.php for an example of a JSON template. Modules using JSON
*  templates render all their content as blocks. No direct rendering to 
*  templates is done.
*
*/
$objBlocks = $this->getObject('blockfilter', 'dynamiccanvas');
$pageContent = $this->getVar('pageContent');
$pageContent = $objBlocks->parse($pageContent);
echo $pageContent;
?>