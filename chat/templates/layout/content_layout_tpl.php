<?php

//$this->setVar('pageSuppressXML',true);

$this->setVar('pageTemplate', NULL);
?>
<script type="text/javascript">
//alert(parent.location.href);
var contentpage = parent.document.getElementById('contentdiv');
contentpage.innerHTML='<?php 

$content = $this->getContent();
echo $content;
?>';
//scrolltoend();
//parent.document.getElementById("contentdiv").scrollTop=parent.document.getElementById("contentdiv").scrollHeight;
</script>
