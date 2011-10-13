<?php

/**
 *
 *
 * @version $Id: dump_tpl.php 21037 2011-03-29 13:20:11Z joconnor $
 * @copyright 2009
 */

$objZipManager = $this->newObject('zipmanager', 'utilities');

echo $objZipManager->packFilesZip('Temp.zip', array('usrfiles/Temp.txt')).'<br />';
echo $objZipManager->unPackFilesFromZip('Temp.zip', 'C:/Documents and Settings/User/My Documents/Temp/')?'OK':'FAIL';

?>