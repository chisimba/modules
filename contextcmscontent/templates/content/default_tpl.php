<?php
/**
 * The default template for the contextcmscontent module
 */

echo '<h1>'.$this->_objContext->getTitle().'</h1>';
echo $this->_objContext->getAbout();
?>