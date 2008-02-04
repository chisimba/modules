<?php
if (isset($str)) {
   echo $str; 
}
$url = $this->uri(array(
    "action" => "sendtweet"), "twitter");
?>

<form action="<?php echo $url; ?>" method="post">
Enter your text here!<br />
<textarea name="tweet" id="tweet" cols="80" rows="10">
</textarea><br />
<div id="charlimitinfo">140</div>
<input type="submit" value="Tweet" />
</form>