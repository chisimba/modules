<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!
        /**
        * Description for $GLOBALS
        * @global string $GLOBALS['kewl_entry_point_run']
        * @name   $kewl_entry_point_run
        */
    $GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
class viewerutils extends object
{

    
    public function getLatestUploadsNavigator(){

        $str=      '<div class="c15l">
            <div id="lu_nav"></div>
            <h1>Latest Uploads</h1>
            <p>
                <img src="skins/wits_webpresent/images/arrow_left.png" id="lu_goprev" alt=""/>
                <img src="skins/wits_webpresent/images/arrow_right.png" id="lu_gonext" alt=""/>
            </p>
        </div>';
        return $str;
    }
    public function getLatestUpload(){
        $objFiles = $this->getObject('dbwebpresentfiles');
        $objView = $this->getObject("viewer", "webpresent");
        $filename='';
        $latestFile = $objFiles->getLatestPresentation();
        $preview='';
        $fileStr='';
        if (count($latestFile) == 0) {
            $latestFileContent = '';
        } else {
            $latestFileContent = '';
            $objTrim = $this->getObject('trimstr', 'strings');

            $objTrim = $this->getObject('trimstr', 'strings');

            $counter = 0;

            foreach ($latestFile as $file)
            {

                if (trim($file['title']) == '') {
                    $filename = $file['filename'];
                } else {
                    $filename = htmlentities($file['title']);
                }

                $link = new link ($this->uri(array('action'=>'view', 'id'=>$file['id'])));
                $link->link = $objView->getPresentationFirstSlide($file['id'],$filename);
                $preview=$link->show();
                $linkname = $objTrim->strTrim($filename, 45);
                $fileLink = new link ($this->uri(array('action'=>'view', 'id'=>$file['id'])));
                $fileLink->link =$linkname;
                $fileLink->title = $filename;
                $fileStr=$fileLink->show();
            }
        }

      

          $str='<div id="sidebar" class="c41r">
                   <div class="statstabs">
                   <div class="statslistcontainer">

                   <ul class="paneltabs">
                   <li><a href="javascript:void(0);" class="selected">Featured Presentation</a></li>
                   </ul>

                   <ul class="statslist">
                     <li>'.$fileStr.'</li>
                    '.$preview.'
                   </ul>

                   </div>
                   </div>
                   </div>';
        return $str;
    }

public function getMostViewed(){
$objStats = $this->getObject('dbwebpresentviewcounter');
$list = $objStats->getMostViewedList();


    $str='<div class="c15r">
           <div class="subcr">
           <ul class="paneltabs">
              <li><a href="#" class="selected">Statistics</a></li>
              </ul>
           <div class="tower">
           <font style="font-size:13pt;color:#5e6eb5;">
           
           Most Viewed
           
           </font>
           <p>
           <ul class="statslist">
           <li>'.$list.'</li>
           </ul>
          </p>
          </div>

          </div>
         </div>

';
return $str;
}

public function getMostDownloaded(){
$objStats = $this->getObject('dbwebpresentdownloadcounter');
$list = $objStats->getMostDownloadedList();
    $str='<div class="c15r">
           <div class="subcr">

           <div class="tower">
           <font style="font-size:13pt;color:#5e6eb5;">
            Most Downloaded
           </font>
           <p>
           <ul class="statslist">
           <li>'.$list.'</li>
           </ul>
          </p>
          </div>

          </div>
         </div>

';
return $str;
}
public function getMostUploaded(){
$objStats = $this->getObject('dbwebpresentuploadscounter');
$list = $objStats->getMostUploadedList();
    $str='<div class="c15r">
           <div class="subcr">

           <div class="tower">
           <font style="font-size:13pt;color:#0091B9;">
            Most Uploads
           </font>
           <p>
           <ul class="statslist">
           <li>'.$list.'</li>
           </ul>
          </p>
          </div>

          </div>
         </div>

';
return $str;
}

public function getTagCloudContent($tagCloud){

   $cloud= '<div id="sidebar" class="c41r">
                   <div class="statstabs">
                   <div class="statslistcontainer">

                   <ul class="paneltabs">
                   <li><a href="javascript:void(0);" class="selected">About</a></li>
                   </ul>
                   <br/>
                   <p>
                   This is a presentation sharing and conversion system. It allows users to upload PowerPoint and OpenOffice presentations, tag them, make them searchable, and have them converted into various formats such as PDF and SWF.
                   </p>
                   <ul class="paneltabs">
                   <li><a href="javascript:void(0);" class="selected">Tags</a></li>
                   </ul>
                   <ul class="statslist">
                   <li>'.$tagCloud.'</li>

                   </ul>
                   </div>
                   </div>
                   </div>';
    return $cloud;
}

    private function createCell($colType,$filename,$thumbNail,$desc,$tags,
        $uploader,$licence){
        $str='<div class="'.$colType.'">
              <div class="subcl">
              <div class="sectionstats_content">

              <div class="statslistcontainer">

              <ul class="statslist">

              <li class="sectionstats_first">
              
              '.$thumbNail->show().'
              <h3>
              <a href="#">'.$desc.'</a>
              </h3>
              <div class="clear"></div>
              </li>
              <li><strong>Tags:</strong><a  href="#">'.$tags.'</a></li>
              <li><strong>By:</strong>'.$uploader.'</li>
              '.$licence.'
              </ul>

              </div>
              </div>
              </div>
              </div>';
        return $str;
    }
    public function getLatestUploads(){
        $objLanguage = $this->getObject('language', 'language');
        $this->loadClass('link', 'htmlelements');
        $objFiles = $this->getObject('dbwebpresentfiles');
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objUser = $this->getObject('user', 'security');
        $objTags = $this->getObject('dbwebpresenttags');
        $latestFiles = $objFiles->getLatestPresentations();
        $objConfig = $this->getObject('altconfig', 'config');
        if (count($latestFiles) == 0) {
            $latestFilesContent = '';
        } else {
            $latestFilesContent = '';

            $objTrim = $this->getObject('trimstr', 'strings');
            $content='';
            $counter = 0;
            $title='
           
           <h1>Wits Presentation System </h1>
           
            <ul class="paneltabs">
              <li><a href="#" class="selected">Latest 10 Uploads</a></li>
                  </ul>
                   <br/>
                   <p>
                   Here you will find the 10 latest uploads, each with presentation
   tag, the uploader and the licence. Click on any of the links to get more information
                   </p>
              ';
            $row='<div class="sectionstats">';
            $row.='<div class="subcolumns">';
            $column=0;
            

            foreach ($latestFiles as $file)
            {
                if (trim($file['title']) == '') {
                    $filename = $file['filename'];
                } else {
                    $filename = htmlentities($file['title']);
                }
                $linkname = $objTrim->strTrim($filename, 45);
                $extra='';
                $columnDiv='';

                if($column == 0){
                    $columnDiv='c50l';
                }else{
                    $columnDiv='c50r';

                }
                $fileLink = new link ($this->uri(array('action'=>'view', 'id'=>$file['id'])));
                $fileLink->link = $objFiles->getPresentationThumbnail($file['id']);
                $fileLink->title = $filename;
                $desc='No description available';
                if ($file['description'] != '') {
                    $desc = ''
                    .nl2br(htmlentities($file['description']));

                }
                $tags = $objTags->getTags($file['id']);
                $tagsStr='';
                if (count($tags) == 0) {
                    $tagsStr .=  '<em>'
                    . $this->objLanguage->languageText("mod_webpresent_notags", "webpresent")
                    . ' </em>';
                } else {
                    $divider = '';
                    foreach ($tags as $tag) {
                        $tagLink = new link ($this->uri(array('action'=>'tag', 'tag'=>$tag['tag'])));
                        $tagLink->link = $tag['tag'];
                        $tagsStr .=  $divider.$tagLink->show();
                        $divider = ', ';
                    }
                }

                $fileTypes = array('odp'=>'OpenOffice Impress Presentation', 'ppt'=>'PowerPoint Presentation', 'pdf'=>'PDF Document');
                $objFileIcons = $this->getObject('fileicons', 'files');
                $uploaderLink = new link ($this->uri(array('action'=>'byuser', 'userid'=>$file['creatorid'])));
                $uploaderLink->link = $objUser->fullname($file['creatorid']);


                $objDisplayLicense = $this->getObject('displaylicense', 'creativecommons');
                $objDisplayLicense->icontype = 'small';
                $license = ($file['cclicense'] == '' ? 'copyright' : $file['cclicense']);
                '<p>'.$objDisplayLicense->show($license).'</p>';

                $row.=$this->createCell(
                    $columnDiv,
                    $filename,
                    $fileLink,
                    $desc,
                    $tagsStr,
                    $uploaderLink->show(),
                   '<p>'.$objDisplayLicense->show($license).'</p>'
            );

                $column++;
                $counter++;
                if($column >1 || count($latestFiles) ==1){
                    $row.='</div>';
                    $row.='</div>';
                    $content .=$row;
                    $row='<div class="sectionstats">';
                    $row.='<div class="subcolumns">';
                    $column=0;
                }
            }

            return $title.$content;
        }
    }

}

?>
