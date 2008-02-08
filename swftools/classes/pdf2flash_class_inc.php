<?php
/**
 *
 * Convert PDF to Flash
 *
 * This class uses SWFTools to convert a document from PDF to Flash
 *
 * PHP version 5
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
 * @package   swftools
 * @author    Tohir Solomons _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
* Convert PDF to Flash
*
* @author Tohir Solomons
* @package swftools
*
*/
class pdf2flash extends object
{

    /**
    *
    * Intialiser for the pdf2flash class
    * @access public
    *
    */
    public function init()
    {
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objMkdir = $this->getObject('mkdir', 'files');
        $this->objCleanUrl = $this->getObject('cleanurl', 'filemanager');
    }
    
    /**
     * Method to convert a PDF to Flash
     * @param string $pdfFilePath Full Path to PDF File
     * @param string $destination Destination + filename.swf
     *      It will automatically append usrfiles/ to the destination
     *      Has to end in .swf
     * @return boolean Whether file has been created or not
     */
    public function convert2PDF($pdfFilePath, $destination)
    {
        // Create var for destination directory
        $path = $this->objConfig->getcontentBasePath().dirname('/'.$destination);
        // Clean up file name
        $path = $this->objCleanUrl->cleanUpUrl($path);
        
        // Create var for destination file
        $filepath = $this->objConfig->getcontentBasePath().'/'.$destination;
        // Clean Up file name
        $filepath = $this->objCleanUrl->cleanUpUrl($filepath);
        
        // Get full path to viewer. This is the file with the prev/next buttons
        $viewport = $this->getResourcePath('viewport.swf');
        
        // Create Directory
        $this->objMkdir->mkdirs($path, 0777);
        
        /*
         List of Original Coummand
         pdf2swf -t -o tmp.swf fsiu_elearn.pdf
         swfcombine -o flashfile.swf myviewport.swf viewport=tmp.swf 
         swfcombine --dummy `swfdump -XY tmp.swf` flashfile.swf -o flashfile.swf
        */
        
        // First Create SWF from PDF
        $command = 'pdf2swf -t -o '.$filepath.'1'.' '.$pdfFilePath;
        
        exec($command);
        
        if (!file_exists($filepath.'1')) {
            return FALSE;
        }
        
        // Then include the navigation
        $command = 'swfcombine -o '.$filepath.' '.$viewport.' viewport='.$filepath.'1';
        
        exec($command);
        
        if (!file_exists($filepath)) {
            return FALSE;
        }
        
        // Then fix the resolution
        $command = 'swfcombine --dummy `swfdump -XY '.$filepath.'1'.'` '.$filepath.' -o '.$filepath;
        
        exec($command);
        
        // Delete temp file
        unlink($filepath.'1');
        
        return TRUE;

    }

}
?>
