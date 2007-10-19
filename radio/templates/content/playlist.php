<?php
clearstatcache();
$playlist_type = $this->getParam('type');
$station_temp = explode(".",$playlist_type);
if($station_temp[0] != "" && $station_temp != $playlist_type){$station = $station_temp[0]; $playlist_type = $station_temp[1];}
if($playlist_type == ""){
	$playlist_type = "m3u";
}

If($playlist_type == m3u)
{
header("content-type:audio/x-mpegurl;charset=utf-8");
header("Content-Disposition: attachment; filename=$station.m3u");
?>
#EXTM3U
#EXTINF:-1,<?php echo $station; ?>
<?php echo  $this->uri(array('action'=>'stream','station'=>$station,'mediaplayer'=>''),'radio');
}
If($playlist_type == xml)
{
	header("content-type:application/xml;charset=utf-8");
header("Content-Disposition: attachment; filename=$station.xml");
?>
<playlist version="1" xmlns="http://xspf.org/ns/0/">
	<trackList>

		<track>
			<title><?php echo $station; ?></title>
			<location><?php echo $this->uri(array('action'=>'stream','station'=>$station,'mediaplayer'=>'flash.mp3'),'radio');?></location>
		</track>

	</trackList>
</playlist>
<?php
}
if($playlist_type == asx)
{
header("content-type:video/x-ms-asf;charset=utf-8");
header("Content-Disposition: attachment; filename=$station.asx");
?>
<asx version="3.0">

  <entry>
    <title><?php echo $station; ?></title>
    <ref href="<?php echo $this->uri(array('action'=>'stream','station'=>$station,'mediaplayer'=>'wmp'),'radio'); ?>">
    <copyright>�2007 <?php echo $station; ?></copyright>
  </entry>
</asx>
<?php
}


if($playlist_type == pls)
{
header("content-type:audio/x-scpls;charset=utf-8");
header("Content-Disposition: attachment; filename=$station.pls");
?>
[Playlist]\n
NumberOfEntries=1\n
File1=<?php echo $this->uri(array('action'=>'stream','station'=>$station,'mediaplayer'=>''),'radio'); ?>\n
Title1=<?php echo $station; ?>\n
Length1=-1\n
Version=2
<?php
}
if($playlist_type == qtl)
{
header("content-type:application/x-quicktime-media-link;charset=utf-8");
header("Content-Disposition: attachment; filename=$station.QTL");
echo "<?xml version=\"1.0\"?>
<?quicktime type=\"application/x-quicktime-media-link\"?>";
?>
<embed
 autoplay="true"
 moviename="<?php echo $station; ?>"
 src="<?php echo $this->uri(array('action'=>'stream','station'=>$station,'mediaplayer'=>'qt&p=.mp3'),'radio'); ?>"
 />
<?php
}
if($playlist_type == ram)
{
header("content-type:audio/x-pn-realaudio;charset=utf-8");
header("Content-Disposition: attachment; filename=$station.ram");
?>
<?php echo $this->uri(array('action'=>'stream','station'=>$station,'mediaplayer'=>'rp'),'radio'); ?>
<?php
}
?>
