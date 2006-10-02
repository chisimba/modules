<div style="width: 38%; float: left;"><?
$this->appendArrayVar('headerParams', $this->getJavascriptFile('TreeMenu.js','tree'));
echo $tree;
?>
</div>
<iframe name="klorframe" id="pages" src="/nextgen/usrfiles/klor" height="500" width="60%"></iframe>

<script lanuguage="javascript/text">

function changePage(page)
{
	e = document.getElementById["pages"];
	e.src = page;
}
</script>
<br clear="both" />
