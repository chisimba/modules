<?php
$this->loadClass('link','htmlelements');
$jnlpPath = "packages/rtt/resources/$nickname.jnlp";
$redirectJS =
        '
<script type="text/javascript">
var jnlpPath="' . $jnlpPath . '";
function redirect() {
document.location.href = jnlpPath;
}
</script>
';
$this->appendArrayVar('headerParams', $redirectJS);
$params = 'onload="javascript: redirect()"';
$this->setVar("bodyParams", $params);

echo $this->objLanguage->languageText('mod_rtt_demolaunching','rtt',"Launching ...")."<br/>";

$link=new link($this->uri(array("action"=>"demo")));
$link->link=$this->objLanguage->languageText('mod_rtt_demoback','rtt','Back to home page');
echo $link->show();
?>
