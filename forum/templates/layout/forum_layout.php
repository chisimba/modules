<div style="padding: 5px;">
<?php
if($this->contextObject->isInContext())
{
    //$objContextUtils = $this->getObject('utilities','context');
    //echo $objContextUtils->getHiddenContextMenu('forum','none');
     
}
echo $this->getContent(); 
?>
</div>