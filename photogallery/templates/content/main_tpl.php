<?


$str = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="700" height="450">
  <param name="movie" value="'.$resourcePath.'">
  <param name="quality" value="high">
  <param name="wmode" value="transparent">
  <embed src="'.$resourcePath.'one.swf?videopath='.$resourcePath.'" width="700" height="450" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed>
</object>';

echo $str;


?>