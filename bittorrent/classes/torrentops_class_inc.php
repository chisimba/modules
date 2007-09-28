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
	
	public function torrentFormattedInfo($torrent)
	{
		if (!is_readable($torrent)) {
			throw new customException($this->objLanguage->languageText('mod_bittorrent_torrentunreadable', 'bittorrent'));
			// Pedant in me says we should exit here, although unnecessary
			exit;
		}

		$File_Bittorrent2_Decode = new File_Bittorrent2_Decode;
		$info = $File_Bittorrent2_Decode->decodeFile($torrent);
		$ret = NULL;
		foreach ($info as $key => $val) {
			$ret .= str_pad($key . ': ', 20, ' ', STR_PAD_LEFT);
			switch($key) {
				case 'files':
					$n = 1;
					$files_n = count($val);
					$n_length = strlen($files_n);
					$ret .= '(' . $files_n . ")<br />";
					foreach ($val as $file) {
						$ret .= str_repeat(' ', 20) . '' . str_pad($n, $n_length, ' ', STR_PAD_LEFT) . ': ' . $file['filename'] . "<br />";
						$n++;
					}
					break;
				case 'announce_list':
					$ret .= "<br />";
					foreach ($val as $list) {
						$ret .= str_repeat(' ', 20) . '- ' . join(', ', $list) . "<br />";
					}
					break;
				default:
					$ret .= $val . "<br />";
			}
		}

		$ret .= "<br />";
		return $ret;
	}
	
	public function torrentInfo($file)
	{
		require_once($this->getPearResource('File/Bittorrent2/Decode.php'));
        $this->decoder = new File_Bittorrent2_Decode;
        $info = $this->decoder->decodeFile($file);
        return $info;
	}
}