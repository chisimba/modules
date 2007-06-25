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
$middleColumn = NULL;
if (isset($comment) && isset($useremail)) {
    //$middleColumn = "CAPTCHA was kakka";
    $comment = urldecode($comment);
    $useremail = urldecode($useremail);
} else {
    $comment = NULL;
    $useremail = NULL;
}
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
//show all the posts
$middleColumn.= ($this->objblogOps->showPosts($posts, TRUE));
$middleColumn.= $this->objComments->showComments($postid);
$middleColumn.= $tracks = $this->objblogOps->showTrackbacks($postid);
if ($this->objUser->isLoggedIn() == TRUE) {
    $middleColumn.= $this->objblogOps->addCommentForm($postid, $userid, $captcha = FALSE, $comment, $useremail);
} else {
    $middleColumn.= $this->objblogOps->addCommentForm($postid, $userid, $captcha = TRUE, $comment, $useremail);
}
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
//dump the cssLayout to screen
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>