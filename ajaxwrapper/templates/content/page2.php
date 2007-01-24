<h1>Advanced Ajax Test</h1>
<p>Enter a username. You will receive feedback whether the username exists or not.<br />In addition, the submit button will either be enabled or disabled.<br />Also try an 'illegal character' like slashes, apostraphes, etc.</p>
<input name="usernametest" id="username" type="text" size="50" onkeyup="xajax_userNameExists(this.value);" />
<div id ="someDiv"></div>
<p><input type="button" id="submitbutton" value="Submit Button" onclick="alert('You submitted '+document.getElementById('username').value);" /></p>
<a href="<?php echo $this->uri(NULL); ?>">Simple AJAX test</a>