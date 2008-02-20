<?php

include('s3_class_inc.php');
$s = new s3;
$s->setup("1GSMXXZVM079YC8SA382", "P1ijEs5b7MN3V8QYzU523Q3UHEBmoyaIUOEX2B7U");
//var_dump($s->getBuckets());
//echo "<br /><br />";
//$s->createBucket('Chisimba_UWC');
//var_dump($s->getBuckets());
//echo "<br /><br />";
//var_dump($s->putObject('Chisimba_UWC', 'paultest', '/var/www/lamigra.3gp', true));
//var_dump($s->getBucketContents('Chisimba_UWC'));
//echo "<br /><br />";
//var_dump($s->getObject('Chisimba_UWC', 'paultest'));
//var_dump($s->downloadObject('Chisimba_UWC', 'paultest','/var/www/amazon/'));
//var_dump($s->deleteObject('Chisimba_UWC', 'paultest'));
//var_dump($s->deleteBucket('Chisimba_UWC'));
//var_dump($s->directorySize('Chisimba_UWC'));
//var_dump($s->getObjectInfo('Chisimba_UWC', 'paultest'));
//var_dump($s->recursiveDelete('Chisimba_UWC', 'paultest'));
//echo "<br /><br />";
//var_dump($s->getBucketContents('Chisimba_UWC'));