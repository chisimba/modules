<script language="JavaScript">
parent.document.getElementById('contentdiv').innerHTML=
'<?php 
$content = $this->getContent();
//$content = preg_replace("/'/","\'",$content);
echo $content;
?>';
//scrolltoend();
parent.document.getElementById("contentdiv").scrollTop=parent.document.getElementById("contentdiv").scrollHeight;
</script>
<?php
//Put the main content
//echo $this->getContent();
?>