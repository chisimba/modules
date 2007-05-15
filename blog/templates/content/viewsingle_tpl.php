<script type="text/javascript">
//<![CDATA[
function init () {
	$('input_redraw').onclick = function () {
		redraw();
	}
}
function redraw () {
	var url = 'index.php';
	var pars = 'module=security&action=generatenewcaptcha';
	var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showResponse} );
}
function showLoad () {
	$('load').style.display = 'block';
}
function showResponse (originalRequest) {
	var newData = originalRequest.responseText;
	$('captchaDiv').innerHTML = newData;
}
//]]>
</script>
<?php
if(isset($msg))
{
	echo urldecode(stripslashes($msg));
}
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
$middleColumn = NULL;

$rightSideColumn .= $this->objblogOps->showBlogsLink(TRUE);
$rightSideColumn .= $this->objblogOps->blogTagCloud($userid);

//show all the posts
$middleColumn .= ($this->objblogOps->showPosts($posts, TRUE));
$middleColumn .= $this->objComments->showComments($postid);
$middleColumn .= $tracks = $this->objblogOps->showTrackbacks($postid);
if($this->objUser->isLoggedIn() == TRUE)
{
	$middleColumn .= $this->objblogOps->addCommentForm($postid, $userid, $captcha = FALSE);
}
else {
	$middleColumn .= $this->objblogOps->addCommentForm($postid, $userid, $captcha = TRUE);
}



//show the comments for this post


$leftCol = NULL;

if(!$this->objUser->isLoggedIn())
{
	$leftCol = $this->objblogOps->loginBox(TRUE);
}
else {
	$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);
}
//show the feeds section
$leftCol .= $this->objblogOps->showFeeds($userid, TRUE);
$leftCol .= $this->objblogOps->showBlinks($userid, TRUE);
$leftCol .= $this->objblogOps->showBroll($userid, TRUE);
$rightSideColumn .= $this->objblogOps->archiveBox($userid, TRUE);

//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>