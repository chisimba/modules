<?php
// Load Prototype Class
$objScriptaculous = $this->getObject('scriptaculous', 'ajaxwrapper');
$objScriptaculous->loadPrototype();
?>
<script type="text/javascript">
//<![CDATA[

var gettingChatMessage = false;

/* AJAX function to get the latest chat messages*/
function getLatestChat() {
	var url = 'index.php';
	var pars = 'module=ajaxchatjs&action=getlatestchat';
    
    if (gettingChatMessage == false) {
        gettingChatMessage = true;
        var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showResponse} );
    }
}

/* AJAX function to display latest chat messages*/
function showResponse (originalRequest) 
{
    gettingChatMessage = false;
    var newData = originalRequest.responseText;
    
    if (newData != '') { // Check that it is not empty
        $('chatmessages').innerHTML += newData;
        scroll();
    }
    
}

/* Method to scroll the messages to the latest messages */
function scroll()
{
    $('chatmessages').scrollTop = $('chatmessages').scrollHeight;
}

/* 
Method to check whether the user pressed [enter].
If yes, send the message to the server
*/
function submitenter(myfield,e)
{
    var keycode;
    if (window.event) keycode = window.event.keyCode;
    else if (e) keycode = e.which;
    else return true;

    if (keycode == 13) {
       sendMessage();
       return false;
   } else {
       return true;
   }
}

/* Method to send the message to the server*/
function sendMessage() {
	var url = 'index.php';
    
	var pars = 'module=ajaxchatjs&action=sendmessage&message='+encodeURIComponent($('chatinput').value)+'&font='+$('fontname').value+'&fontsize='+$('fontsize').value+'&fontcolor='+$('fontcolor').value;
    $('chatinput').value = '';
    $('chatinput').focus();
	var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onComplete: showResponse} );
}

/* Method to change the Font */
function updateFontStyle()
{
    $('chatinput').style.fontFamily=$('fontname').value;
    $('chatinput').focus();
}

/* Method to change the Font Size */
function updateFontSize()
{
    $('chatinput').style.fontSize=$('fontsize').value+'pt';
    $('chatinput').focus();
}

/* Method to change the Font Color */
function updateFontColor()
{
    $('chatinput').style.color=$('fontcolor').value;
    $('chatinput').focus();
}

// Get Messages From Server Every 2 Seconds
setInterval('getLatestChat()', 2000);

//]]>
</script>
<h1>Chat Room</h1>
<div id="chatmessages" style="border: 1px solid gray; height: 200px; overflow: scroll; overflow-y: scroll; overflow-x:auto;">
<?php
if (isset($message)) {
    echo $message;
}
?>
</div>
<div>
<br />
<select name="font" id="fontname"   onchange="updateFontStyle();">
<option value="Arial" style="font-family:Arial;">Arial</option>
<option value="Comic Sans MS" style="font-family:Comic Sans MS;">Comic Sans MS</option>
<option value="Courier" style="font-family:Courier;">Courier</option>
<option value="Times New Roman" style="font-family:Times New Roman;">Times New Roman</option>
<option value="Verdana" style="font-family:Verdana;" selected="selected">Verdana</option>
</select>

<select name="fontsizedropdown" id="fontsize"   onchange="updateFontSize();">
<option value="8" selected="selected">8</option>
<option value="10">10</option>
<option value="12">12</option>
<option value="14">14</option>
<option value="16">16</option>
<option value="18">18</option>
<option value="20">20</option>
</select>

<select name="colordropdown" id="fontcolor"   onchange="updateFontColor();">
<option value="Black" style="background-color:Black;color:#fff" selected="selected">Black</option>
<option value="Blue" style="background-color:Blue;color:#fff">Blue</option>
<option value="Brown" style="background-color:Brown;color:#fff">Brown</option>
<option value="Gray" style="background-color:Gray;color:#fff">Gray</option>
<option value="Green" style="background-color:Green;color:#fff">Green</option>
<option value="Khaki" style="background-color:Khaki;color:#000">Khaki</option>
<option value="Maroon" style="background-color:Maroon;color:#fff">Maroon</option>
<option value="Orange" style="background-color:Orange;color:#fff">Orange</option>
<option value="Navy" style="background-color:Navy;color:#fff">Navy</option>
<option value="Pink" style="background-color:Pink;color:#000">Pink</option>
<option value="Red" style="background-color:Red;color:#fff">Red</option>
<option value="SkyBlue" style="background-color:SkyBlue;color:#fff">SkyBlue</option>
<option value="Yellow" style="background-color:Yellow;color:#000">Yellow</option>
</select>
<br />
<textarea id="chatinput" style="width:300px; height:50px; font-family:Verdana;" onkeypress="return submitenter(this,event);"></textarea> 
<input type="button" onclick="sendMessage();" value="Send" />
</div>