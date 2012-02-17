<?php

/**
 * Contains util methods for managing groups
 *
 * @author davidwaf
 */
class groupmanager extends object {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject("user", "security");
    }

    /**
     * saves group step one as a context 
     */
    function saveGroupStep1() {

        $contextCode = $this->genRandomString();
        $title = $this->getParam("name");
        $status = 'Published';
        $access = 'Public';
        $about = $this->getParam("description");
        $showcomment = 0;
        $alerts = 0;
        $canvas = 'None';
        $objContext = $this->getObject('dbcontext', 'context');
        $objContext->createContext($contextCode, $title, $status, $access, $about, '', $showcomment, $alerts, $canvas);


        $address = $this->getParam('address');
        $city = $this->getParam('city');
        $state = $this->getParam('state');
        $country = $this->getParam('country');
        $postalcode = $this->getParam('postalcode');
        $website = $this->getParam('website');
        $email = $this->getParam("email");
        $data = array(
            "contextcode" => $contextCode,
            "email" => $email,
            "address" => $address,
            "city" => $city,
            "state" => $state,
            "postalcode" => $postalcode,
            "website" => $website,
            "country" => $country
        );
        $dbGroups = $this->getObject("dbgroups", "oer");
        $dbGroups->saveNewGroup($data);
        return $contextCode;
    }

    /**
     * creates a group listing
     * @return type 
     */
    public function getGroupListing() {
        $dbGroups = $this->getObject("dbgroups", "oer");
        $objContext = $this->getObject('dbcontext', 'context');

        $groups = $dbGroups->getAllGroups();


        $newgrouplink = new link($this->uri(array("action" => "creategroupstep1")));
        $newgrouplink->link = $this->objLanguage->languageText('mod_oer_group_new', 'oer');


        $controlBand =
                '<div id="groups_controlband">';

        $controlBand.='<br/>&nbsp;' . $this->objLanguage->languageText('mod_oer_viewas', 'oer') . ': ';
        $gridthumbnail = '<img src="skins/oer/images/sort-by-grid.png"/>';
        $gridlink = new link($this->uri(array("action" => "home")));
        $gridlink->link = $gridthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_grid', 'oer');
        $controlBand.=$gridlink->show();

        $listthumbnail = '&nbsp;|&nbsp;<img src="skins/oer/images/sort-by-list.png"/>';
        $listlink = new link($this->uri(array("action" => "showproductlistingaslist")));
        $listlink->link = $listthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_list', 'oer');
        $controlBand.=$listlink->show();

        if ($this->objUser->isLoggedIn()) {
            $newthumbnail = '&nbsp;<img src="skins/oer/images/document-new.png" width="19" height="15"/>';
            $controlBand.= '&nbsp;|&nbsp;' . $newthumbnail . $newgrouplink->show();
        }


        $sortbydropdown = new dropdown('sortby');
        $sortbydropdown->addOption('', $this->objLanguage->languageText('mod_oer_none', 'oer'));

        $controlBand.='<br/><br/>' . $this->objLanguage->languageText('mod_oer_sortby', 'oer');
        $controlBand.=$sortbydropdown->show();

        $controlBand.= '</div> ';
        $startNewRow = TRUE;
        $count = 1;
        $table = $this->getObject('htmltable', 'htmlelements');
        $table->attributes = "style='table-layout:fixed;'";
        $table->cellspacing = 10;
        $table->cellpadding = 10;
        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroups->getId("ProductCreators");
        $objGroupOps = $this->getObject("groupops", "groupadmin");
        $userId = $this->objUser->userId();
        $maxCol = 3;
        $editImg = '<img src="skins/oer/images/icons/edit.png">';
        $deleteImg = '<img src="skins/oer/images/icons/delete.png">';

        foreach ($groups as $group) {
            if ($startNewRow) {
                $startNewRow = FALSE;
                $table->startRow();
            }
            $context = $objContext->getContext($group['contextcode']);

            $titleLink = new link($this->uri(array("action" => "viewgroup", "contextcode" => $group['contextcode'])));
            $titleLink->cssClass = 'group_listing_title';
            $titleLink->link = $context['title'];
            $thumbnail = '<img src="usrfiles/' . $group['thumbnail'] . '"  width="79" height="101" align="bottom"/>';
            if ($group['thumbnail'] == '') {
                $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="bottom"/>';
            }


            $thumbnailLink = new link($this->uri(array("action" => "viewgroup", "contextcode" => $group['contextcode'])));
            $thumbnailLink->link = $thumbnail . '<br/>';
            $thumbnailLink->cssClass = 'group_listing_thumbail';

            $editControls = "";
            if ($this->objUser->isLoggedIn()) {
                $editLink = new link($this->uri(array("action" => "editgroupstep1", "contextcode" => $group['contextcode'])));
                $editLink->link = $editImg;
                $editLink->cssClass = "editoriginalproduct";
                $editControls = "" . $editLink->show();
            }
            $groupStr = $thumbnailLink->show() . $titleLink->show() . $editControls;

            $table->addCell($groupStr, null, "top", "left", "view_group");

            if ($count == $maxCol) {

                $table->endRow();
                $startNewRow = TRUE;
                $count = 1;
            }
            $count++;
        }

        $totalGroups = count($groups);
        $reminder = $totalGroups % $maxCol;

        if ($reminder != 0) {

            $table->endRow();
        }
        return $controlBand . $table->show();
    }

    public function genRandomString() {
        $length = 5;
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    /**
     * Used fo uploading product thumbnail
     * @todo this will be renamed to a meaningful name
     */
    function doajaxupload() {
        $dir = $this->objConfig->getcontentBasePath();

        $generatedid = $this->getParam('id');
        $filename = $this->getParam('filename');

        $objMkDir = $this->getObject('mkdir', 'files');

        $productid = $this->getParam('itemid');
        $destinationDir = $dir . '/oer/groups/' . $productid;

        $objMkDir->mkdirs($destinationDir);
        // @chmod($destinationDir, 0777);

        $objUpload = $this->newObject('upload', 'files');
        $objUpload->permittedTypes = array(
            'all'
        );
        $objUpload->overWrite = TRUE;
        $objUpload->uploadFolder = $destinationDir . '/';

        $result = $objUpload->doUpload(TRUE, $filename);


        if ($result['success'] == FALSE) {

            $filename = isset($_FILES['fileupload']['name']) ? $_FILES['fileupload']['name'] : '';
            $error = $this->objLanguage->languageText('mod_oer_uploaderror', 'oer');
            return array('message' => $error, 'file' => $filename, 'id' => $generatedid);
        } else {
            $filename = $result['filename'];
            $data = array("thumbnail" => "/oer/groups/" . $productid . "/" . $filename);
            $this->dbproducts->updateOriginalProduct($data, $productid);


            $params = array('action' => 'showthumbnailuploadresults', 'id' => $generatedid, 'fileid' => $id, 'filename' => $filename);

            return $params;
        }
    }

}

?>
