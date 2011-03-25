<?php

/**
 * Class to handle interaction with table tbl_podcaster_metadata_media
 * 
 * This table relates to metadata about media files such as images, audio, video and flash
 * 
 * PHP version 3
 * 
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation; either version 2 of the License, or 
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the 
 * Free Software Foundation, Inc., 
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * 
 * @category  Chisimba
 * @package   podcaster
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright 2011 University of the Witwatersrand
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License 
 * @version   $Id: dbmediafileinfo_class_inc.php 11050 2008-10-24 16:20:47Z charlvn $
 * @link      http://avoir.uwc.ac.za
 * @see       
 */

/**
 * Class to handle interaction with table tbl_files_metadata_media
 * 
 * This table relates to metadata about media files such as images, audio, video and flash
 * 
 * @category  Chisimba
 * @package   podcaster
 * @author    Paul Mungai <paulwando@gmail.com>
 * @copyright 2011 University of the Witwatersrand
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License 
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 * @see       
 */
class dbmediafiledata extends dbTable {

    /**
     * Constructor
     */
    function init() {
        parent::init('tbl_podcaster_metadata_media');
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     * Method to add metadata info about a file
     * @param  string $fileId    Record Id of the File
     * @param  array  $infoArray Array with details of the metadata
     * @return string Record Id of the Entry
     */
    function addMediaFileInfo($fileId, $infoArray, $filename, $pathId) {
        // Add File Id to Array
        $infoArray['fileid'] = $fileId;

        if (!isset($infoArray['creatorid'])) {
            $infoArray['creatorid'] = $this->objUser->userId();
        }

        if (!isset($infoArray['modifierid'])) {
            $infoArray['modifierid'] = $this->objUser->userId();
        }

        $infoArray['filename'] = $filename;
        $infoArray['uploadpathid'] = $pathId;
        $infoArray['datecreated'] = strftime('%Y-%m-%d', mktime());
        $infoArray['timecreated'] = strftime('%H:%M:%S', mktime());

        //Check if file is already recorded, if it is do an update
        $existCheck = $this->getFileByNameAndPathId($filename, $pathId);
        $existCheck = $existCheck[0];
        if (empty($existCheck)) {
            return $this->insert($infoArray);
        } else {
            return $this->update('id', $existCheck['id'], $infoArray);
        }
    }

    /**
     * Method to get the details of a file
     * @param string $name actual filename with extension
     * @param string $pathId the folder path id
     * @return array Details of the file
     */
    public function getFileByNameAndPathId($name, $pathId) {
        return $this->getAll('where filename="' . $name . '" AND uploadpathid="' . $pathId . '"');
    }

    /**
     * Method to get the details of a file
     * @param string $id Record ID of the file
     * @return array Details of the file
     */
    public function getFile($id) {
        return $this->getRow('id', $id);
    }

    /**
     * Method to get the details of a file
     * @param string $id Record ID of the file
     * @return array Details of the file
     */
    public function getFileByFileId($id) {
        return $this->getRow('fileid', $id);
    }

    /**
     * Function that returns the latest 10 podcasts
     * @return array
     */
    public function getLatestPodcasts() {
        return $this->getAll(' ORDER BY datecreated DESC, timecreated DESC');
    }
    
    /**
     * Function that returns the latest podcasts in a certain folder
     * @return array
     */
    public function getLatestFolderPodcasts($folderId) {
        return $this->getAll('where uploadpathid="' . $folderId . '" ORDER BY datecreated DESC, timecreated DESC');
    }
    /**
     * Function that returns the latest 10 podcasts by a certain author/creator/artist names
     * @return array
     */
    public function getLatestAuthorPodcasts($author) {
        return $this->getAll('where artist="' . $author . '" ORDER BY datecreated DESC, timecreated DESC');
    }

    /*
     * Function to get podcast based on passed params
     * @param string $filter the type of parameter to use
     * @param string $filtervalue the value supplied by the user
     * @return array
     */

    public function searchFileInAllFields($filter, $filtervalue) {
        $sql = "select A.id, A.fileid, A.filename, A.uploadpathid, A.width, A.height, A.playtime,
            A.format, A.mimetype, A.cclicense, A.framerate, A.bitrate, A.samplerate, A.title, A.artist,
            A.description, A.year, A.url, A.getid3info, A.creatorid, A.datecreated, A.timecreated,
            A.modifierid, A.datemodified, A.timemodified, B.id as tagId, B.tag from
            tbl_podcaster_metadata_media as A join tbl_podcaster_tags as B on A.id = B.fileid ";
        //Derermine the where clause based on filter
        switch ($filter) {
            case 'description':
                $sql .= "where A.description like '%" . $filtervalue . "%'";
                break;
            case 'title':
                $sql .= "where A.title like '%" . $filtervalue . "%'";
                break;
            case 'artist':
                $sql .= "where A.artist like '%" . $filtervalue . "%'";
                break;
            case 'filename':
                $sql .= "where A.filename like '%" . $filtervalue . "%'";
                break;
            case 'tag':
                $sql .= "where B.tag like '%" . $filtervalue . "%'";
                break;
            default:
                $sql .= "where A.description like '%" . $filtervalue . "%' 
                    or A.title like '%" . $filtervalue . "%' or A.artist like '%" . $filtervalue . "%'
                        or A.filename like '%" . $filtervalue . "%' or B.tag like '%" . $filtervalue . "%'";
                break;
        }

        $sql .= " ORDER BY datecreated DESC, timecreated DESC";

        $results = $this->getArray($sql);
        //Remove duplicates
        $checkDuplicate = array();
        $newresults = array();
        foreach ($results as $result) {
            //Avoid duplicate views of the same record
            if (!in_array($result['id'], $checkDuplicate)) {
                //add this record id to the array
                array_push($checkDuplicate, $result['id']);
                array_push($newresults, $result);
            }
        }
        return $newresults;
    }

    /**
     * Function that returns the latest podcast
     * @return array
     */
    public function getLatestPodcast() {
        return $this->getAll(' ORDER BY datecreated, timecreated DESC LIMIT 1');
    }

    /**
     * Function to update file details
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $license
     * @param string $license
     * @return array
     */
    public function updateFileDetails($id, $title, $description, $license, $artist) {
        return $this->update('id', $id, array(
            'title' => $title,
            'description' => $description,
            'cclicense' => $license,
            'artist' => $artist
        ));
    }

    /**
     * Method to clean up records that have no matching data in tbl_files
     */
    function cleanUpMismatchedMediaFiles() {
        $sql = 'SELECT tbl_podcaster_metadata_media.id, tbl_files.id as files_id FROM tbl_podcaster_metadata_media  LEFT JOIN tbl_files ON (tbl_podcaster_metadata_media.fileid = tbl_files.id) WHERE tbl_files.id IS NULL';

        $results = $this->getArray($sql);

        foreach ($results as $result) {
            $this->delete('id', $result['id']);
        }
    }

    /**
     * Method to update the width and height info of a file
     * @param string $fileId Record Id of the File
     * @param int $width Width of the File
     * @param int $height Height of the File
     */
    function updateWidthHeight($fileId, $width, $height) {
        return $this->update('fileid', $fileId, array('width' => $width, 'height' => $height));
    }

}

?>