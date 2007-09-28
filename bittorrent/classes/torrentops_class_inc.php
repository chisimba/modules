<?php
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class to handle bittorrent elements
 * This object can be used elsewhere in the system to render certain aspects of the interface
 *
 * @author    Paul Scott
 * @copyright GNU/GPL, AVOIR
 * @package   bittorrent
 * @access    public
 */
class torrentops extends object
{
	
	public function init()
	{
		require_once($this->getPearResource('File/Bittorrent2/MakeTorrent.php'));
	}
	
	public function makeTorrent($file, $trackerUrl, $comment, $piecelength = 256)
	{
		$this->MakeTorrent = new File_Bittorrent2_MakeTorrent($file);
        // Set the announce URL
		$this->MakeTorrent->setAnnounce($trackerUrl);
		// Set the comment
		$this->MakeTorrent->setComment($comment);
		// Set the piece length (in KB)
		$this->MakeTorrent->setPieceLength($piecelength);
		// Build the torrent
		$metainfo = $this->MakeTorrent->buildTorrent();
		// Then put this into a file, instead of echoing it normally...
		return $metainfo;
	}
	
	public function scrapeTorrrent()
	{
		
	}
	
	public function torrentInfo($file)
	{
		require_once($this->getPearResource('File/Bittorrent2/Decode.php'));
        $this->decoder = new File_Bittorrent2_Decode;
        $info = $this->decoder->decodeFile($file);
        return $info;
	}
}