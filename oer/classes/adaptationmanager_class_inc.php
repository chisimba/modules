<?php

/**
 * Contains util methods for managing adaptations
 *
 * @author pwando
 */
class adaptationmanager extends object {

    private $dbproducts;
    private $dbInstitution;
    private $objLanguage;
    public $objConfig;
    private $objUser;

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject("user", "security");
        $this->dbInstitution = $this->getObject("dbinstitution", "oer");
        $this->dbInstitutionType = $this->getObject("dbinstitutiontypes", "oer");
        $this->dbproducts = $this->getObject("dbproducts", "oer");
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('hiddeninput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        $this->addJS();
        $this->setupLanguageItems();
    }

    /**
     * Used fo uploading product thumbnail
     *
     */
    function uploadProductThumbnail() {
        $dir = $this->objConfig->getcontentBasePath();

        $generatedid = $this->getParam('id');
        $filename = $this->getParam('filename');

        $objMkDir = $this->getObject('mkdir', 'files');

        $productid = $this->getParam('productid');
        $destinationDir = $dir . '/oer/products/' . $productid;

        $objMkDir->mkdirs($destinationDir);
        // @chmod($destinationDir, 0777);

        $objUpload = $this->newObject('upload', 'files');
        $objUpload->permittedTypes = array(
            'all'
        );
        $objUpload->overWrite = TRUE;
        $objUpload->uploadFolder = $destinationDir . '/';

        $result = $objUpload->doUpload(TRUE, "thumbnail");


        if ($result['success'] == FALSE) {

            $filename = isset($_FILES['fileupload']['name']) ? $_FILES['fileupload']['name'] : '';
            $error = $this->objLanguage->languageText('mod_oer_uploaderror', 'oer');
            return array('message' => $error, 'file' => $filename, 'id' => $generatedid);
        } else {
            $data = array("thumbnail" => "/oer/products/" . $productid . "/thumbnail.png");
            $this->dbproducts->updateOriginalProduct($data, $productid);
            $filename = $result['filename'];

            $params = array('action' => 'showproductthumbnailuploadresults', 'id' => $generatedid, 'fileid' => $id, 'filename' => $filename);

            return $params;
        }
    }

    /**
     * adds essential js
     */
    function addJS() {
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/validate/jquery.validate.min.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('adaptation.js', 'oer'));
    }

    /**
     * sets up necessary lang items for use in js
     */
    function setupLanguageItems() {
        // Serialize language items to Javascript
        $arrayVars['status_success'] = "mod_oer_status_success";
        $arrayVars['status_fail'] = "mod_oer_status_fail";
        $arrayVars['confirm_delete_original_product'] = "mod_oer_confirm_delete_original_product";
        $arrayVars['loading'] = "mod_oer_loading";
        $objSerialize = $this->getObject('serializevars', 'utilities');
        $objSerialize->languagetojs($arrayVars, 'oer');
    }

    /**
     * setup make new adaptation form
     * @param $id The id of the product
     * @param $mode Whether its a new adaptation or editing an existing one
     * return string
     */
    public function makeNewAdaptation($id, $mode) {
        $objTable = $this->getObject('htmltable', 'htmlelements');
        if ($mode == "new") {
            $objSectionManager = $this->getObject('sectionmanager', 'oer');

            $createInLang = '<div id="createin">' . $this->objLanguage->languageText('mod_oer_currentpath', 'oer') .
                    " : (" . $this->objLanguage->languageText('mod_oer_required', 'oer') . ")" . '<div>';
            $selected = '';
            /* if ($section != null) {
              $selected = $section['path'];
              } */
            $createInDdown = $objSectionManager->buildSectionsTree($id, '', "false", 'htmldropdown', $selected) . '</div>';

            //Store the mode
            $hidMode = new hiddeninput('mode');
            $hidMode->cssId = "mode";
            $hidMode->value = $mode;

            if ($id != null) {
                //Get adapted-product data
                $product = $this->dbproducts->getProduct($id);

                $hidId = new hiddeninput('id');
                $hidId->cssId = "id";
                $hidId->value = $id;
                $objTable->startRow();
                $objTable->addCell($hidId->show() . $hidMode->show());
                $objTable->endRow();
            } else {
                $objTable->startRow();
                $objTable->addCell($hidMode->show());
                $objTable->endRow();
            }

            //the title
            $objTable->startRow();
            $objTable->addCell($this->objLanguage->languageText('mod_oer_sectiontitle', 'oer') .
                    " : (" . $this->objLanguage->languageText('mod_oer_required', 'oer') . ")");
            $objTable->endRow();

            $objTable->startRow();
            $textinput = new textinput('section_title');
            $textinput->size = 60;
            $textinput->cssClass = 'required';
            if ($product != null) {
                $textinput->value = $product['title'];
            }
            $objTable->addCell($textinput->show());
            $objTable->endRow();

            //Current path lang item
            $objTable->startRow();
            $objTable->addCell($createInLang);
            $objTable->endRow();

            //current path drop down
            $objTable->startRow();
            $objTable->addCell($createInDdown);
            $objTable->endRow();

            //section content
            $objTable->startRow();
            $objTable->addCell($this->objLanguage->languageText('mod_oer_sectioncontent', 'oer') .
                    " : (" . $this->objLanguage->languageText('mod_oer_required', 'oer') . ")");
            $objTable->endRow();

            $objTable->startRow();
            $description = $this->newObject('htmlarea', 'htmlelements');
            $description->name = 'section_content';
            if ($product != null) {
                //$description->value = $product['section_content'];
            }
            $description->height = '150px';
            $description->setBasicToolBar();
            $objTable->addCell($description->show());
            $objTable->endRow();

            //published status
            $objTable->startRow();
            $objTable->addCell($this->objLanguage->languageText('mod_oer_status', 'oer') .
                    " : (" . $this->objLanguage->languageText('mod_oer_required', 'oer') . ")");
            $objTable->endRow();
            $objTable->startRow();
            $published = new dropdown('status');
            $published->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
            $published->cssClass = "required";
            $published->addOption('disabled', $this->objLanguage->languageText('mod_oer_disabled', 'oer'));
            $published->addOption('draft', $this->objLanguage->languageText('mod_oer_draft', 'oer'));
            $published->addOption('published', $this->objLanguage->languageText('mod_oer_published', 'oer'));

            //$published->setSelected($curriculum['status']);
            $objTable->addCell($published->show());
            $objTable->endRow();

            //attach file
            $objTable->startRow();
            $objTable->addCell($this->objLanguage->languageText('mod_oer_attachfile', 'oer'));
            $objTable->endRow();

            $hidAttachment = new hiddeninput('attachment');
            $hidAttachment->value = "";
            $objTable->startRow();
            $objTable->addCell($hidAttachment->show());
            $objTable->endRow();
            
            //keywords
            $objTable->startRow();
            $objTable->addCell($this->objLanguage->languageText('mod_oer_keywords', 'oer') . " (" . $this->objLanguage->languageText('mod_oer_keywordsInstruction', 'oer') . ")");
            $objTable->endRow();

            $objTable->startRow();
            $textinput = new textinput('keywords');
            $textinput->size = 60;
            $textinput->cssClass = 'required';
            if ($product != null) {
                $textinput->value = $product['keywords'];
            }
            $objTable->addCell($textinput->show());
            $objTable->endRow();

            //publisher
            $objTable->startRow();
            $objTable->addCell($this->objLanguage->languageText('mod_oer_contributedby', 'oer'));
            $objTable->endRow();

            $objTable->startRow();
            $textinput = new textinput('contributed_by');
            $textinput->size = 60;
            $textinput->cssClass = 'required';
            if ($product != null) {
                //$textinput->value = $product['contributed_by'];
            }
            $objTable->addCell($textinput->show());
            $objTable->endRow();


            //adaptation notes
            $objTable->startRow();
            $objTable->addCell($this->objLanguage->languageText('mod_oer_adaptationotes', 'oer'));
            $objTable->endRow();

            $objTable->startRow();
            $textarea = new textarea('adaptation_notes', '', 5, 60);
            $textarea->cssClass = 'required';
            if ($product != null) {
                //$textarea->value = $product['adaptation_notes'];
            }
            $objTable->addCell($textarea->show());
            $objTable->endRow();

            $fieldset = $this->newObject('fieldset', 'htmlelements');
            $fieldset->setLegend($this->objLanguage->languageText('mod_oer_sectionviewaddadaptation', 'oer'));
            $fieldset->addContent($objTable->show());


            $action = "savedaptationsecdata";
            $formData = new form('saveAdaptation', $this->uri(array("action" => $action, "parentproduct_id" => $id, "mode" => $mode)));
            $formData->addToForm($fieldset);

            $button = new button('save', $this->objLanguage->languageText('word_save', 'system', 'Save'));
            $button->setToSubmit();
            $formData->addToForm('<br/>' . $button->show());


            $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
            $uri = $this->uri(array("action" => "adaptationlist"));
            $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
            $formData->addToForm('&nbsp;&nbsp;' . $button->show());

            $header = new htmlheading();
            $header->type = 2;
            $header->cssClass = "original_product_title";
            $header->str = $product['title'];

            return $header->show() . $formData->show();
        } else {
            return "Coming soon - edit adaptation";
        }
    }

    /**
     * setup make new adaptation form
     * @param $id The id of the product
     * @param $mode Whether its a new adaptation or editing an existing one
     * return string
     */
    public function makeNewAdaptation1($id, $mode) {
        $formStr = '<div class="blueHorizontalStrip"></div>
    <div class="mainWrapper">

        <div class="mainContentHolder">
        	<div class="subNavigation"></div>
            <div class="wideTopContentHolderDiv">

                <div class="topHeadingDiv">
                <div class="breadCrumb tenPixelLeftPadding">
                	<a href="#" class="pinkTextLink">Product adaptations</a> |
                    <a href="#" class="greyTextLink">Politechnic of Namibia</a> |
                    Model Curricula for Journalism
                </div>
                </div>

                <div class="topWideAdaptationDiv">
                    <div class="adaptationsBackgroundColor">
                    	<div class="adaptationListViewTop tenPixelLeftPadding tenPixelTopPadding eightPixelBottomPadding">
                            <div class="productAdaptationViewLeftColumnTop">
                            <div class="leftTopImage">
                            	<img src="skins/oer/images/adapted-product-grid-institution-logo-placeholder.jpg" width="45" height="49">
                            </div>
                            <div class="leftFloatDiv">
                                <h3><a href="#" class="adaptationListingLink">Model Curricula for Journalism</a></h3><br>
                                <img src="skins/oer/images/icon-product.png" alt="' . $this->objLanguage->languageText('mod_oer_bookmark', 'oer') . '" width="18" height="18" class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>
                            </div>
                    	</div>
                        <div class="middleAdaptedByIcon">
                            <img src="skins/oer/images/icon-adapted-by.png" alt="' . $this->objLanguage->languageText('mod_oer_adaptedby', 'oer') . '" width="24" height="24"><br>
                            <span class="pinkText">' . $this->objLanguage->languageText('mod_oer_adaptedby', 'oer') . '</span>
                        </div>
                    <div class="productAdaptationListViewRightColumn">
                    	<div class="productAdaptationViewLeftColumnTop">
                            <div class="leftTopImage">
                            	<img src="skins/oer/images/adapted-product-grid-institution-logo-placeholder.jpg" width="45" height="49">
                            </div>
                            <div class="leftFloatDiv">
                                <h3 class="darkGreyColour">Polytechnic of Namibia</h3><br>
                                <img src="skins/oer/images/icon-product.png" alt="' . $this->objLanguage->languageText('mod_oer_bookmark', 'oer') . '" width="18" height="18" class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="productsLink">Full view of product</a></div>
                            </div>
                    	</div>


                    </div>
                </div>

                    </div>
                    </div>


            </div>
        	<!-- Left Wide column DIv -->
            <div class="adaptationsBackgroundColor">
          	<div class="WideColumnDiv">

              <div class="pageBreadCrumb">
              	<a href="#" class="greyTextLink">Barchelor</a> |
                <a href="#" class="greyTextLink">Year 1</a> |
                <a href="#" class="greyTextLink">Term 1</a> |
                <span class="greyText">Foundation of Journalism Writing</span><br><br>
             </div>

                <div class="headingHolder">
                	<div class="heading"><h2 class="pinkTextLink">Editing:Foundations of Journalism: Writing</h2></div>
                </div>
            <div class="contentWideDivThree">
                  <h4  class="greyText fontBold labelSpacing">Section title (required):</h4>
                  <input type="text" class="wideInputTextField">
                  <br>
        		<br>
                    <h4  class="greyText fontBold">Current path (required):</h4>
                    <div class="floatLeftText greyText">Barchelor | Year 1 | Term 1 | Foundation of Journalism Writing</div>

                    <a href=""><img src="skins/oer/images/button-search.png" class="changeImage" alt="' . $this->objLanguage->languageText('word_search', 'system') . '"></a>
                    <a href="" class="searchGoLink">CHANGE</a>
                    <br><br>
                    <h4  class="greyText fontBold labelSpacing">Section Content (required):</h4>
                    <textarea name="" class="wysiwig" id="profile"></textarea><br>
                    <br>
                    <h4  class="greyText fontBold labelSpacing">Status (required):</h4>
                    <select name="" class="wysiwigDropDown">
                    	<option value="">Published</option>
                    </select>

                    <br><br>
                    <h4  class="greyText fontBold labelSpacing">Attach file :</h4>
                    <span class="greyText">Select file:</span> <input type="file" name="" class="fileInput">
                    <br><br>
                    <div class="buttonSubmit"><a href=""><img src="skins/oer/images/button-search.png" alt="' . $this->objLanguage->languageText('word_search', 'system') . '"></a></div>
                    <div class="textNextoSubmitButton"><a href="" class="searchGoLink">ADD</a></div>
                    <br><br>
                    <div class="uploadedFiles">
                    	<a href="#" class="uploadedFilesLink">Document 1.pdf</a><br>
                        <a href="#" class="uploadedFilesLink">Document2.doc</a><br>
                    </div>
                    <br><br>
                    <h4  class="greyText fontBold labelSpacing">Keywords :</h4>
              			<div class="wideDivider">

                            <div class="floatLeftText">
                            <table border="0" cellspacing="0" cellpadding="0" class="bottomRTbl">
                            	<tr>
                                	<td><span class="greyText fontBold labelSpacing">Add to the</span></td>
                                    <td>
                                    	<select name="" class="smallDropDown">
                                            <option value=""></option>
                                        </select>
                                    </td>
                                    <td>
                                    <div class="buttonSubmit"><a href=""><img src="skins/oer/images/button-search.png" alt="' . $this->objLanguage->languageText('word_search', 'system') . '"></a></div>
                    				<div class="textNextoSubmitButton"><a href="" class="searchGoLink">ADD</a></div>

                                     </td>
                                 </tr>
                                 <tr>
                                	<td><span class="greyText fontBold labelSpacing">Add your own</span></td>
                                    <td><input type="text" class="smallInputBox"></td>
                                    <td>
                                    <div class="buttonSubmit"><a href=""><img src="skins/oer/images/button-search.png" alt="' . $this->objLanguage->languageText('word_search', 'system') . '"></a></div>
                    				<div class="textNextoSubmitButton"><a href="" class="searchGoLink">ADD</a></div>
                                    </td>
                                 </tr>
                            </table>
                            </div>

                            <div class="rightKeywordDiv">
                                <div class="box">
                                    Gender Ethics
                                </div>
                            </div>


                            <div class="leftKeywordDiv">



                        <br>
                    </div>

                    </div>

                    <div class="wideDivider">
                    <h4  class="greyText fontBold labelSpacing">Contributed by :</h4>
                  	<input type="text" class="wideInputTextField">
                    <br><br>
              		</div>

                    <div class="wideDivider">
                    <img src="skins/oer/images/icon-attention.png" width="18" height="18" class="smallLisitngIcons">
                    <h4  class="greyText fontBold labelSpacing">Adaptation notes : (required)</h4>

                    <textarea name="" class="wideInputTextAreaField"></textarea>
                    <br>
                    <br>
              		</div>

                    <div class="wideDivider rightTextFloat">
                    	<div class="saveCancelButtonHolder">

                        <div class="buttonSubmit"><a href=""><img src="skins/oer/images/button-search.png" alt="' . $this->objLanguage->languageText('word_search', 'system') . '"></a></div>
                    	<div class="textNextoSubmitButton"><a href="" class="searchGoLink">CANCEL</a></div>
                        </div>
                      <div class="saveCancelButtonHolder">

                        <div class="buttonSubmit"><a href=""><img src="skins/oer/images/button-search.png" alt="' . $this->objLanguage->languageText('word_search', 'system') . '"></a></div>
                    	<div class="textNextoSubmitButton"><a href="" class="searchGoLink">SAVE</a></div>
                      </div>
                    </div>

                </div>
            </div>

            </div>
            </div>
        <!-- Footer-->
        <div class="footerDiv">
        	<div class="footerLinksLists">
            	<div class="footerLinksHeadings">Links Set One</div>
                <a href="" class="footerLink">Link 1</a><br>
                <a href="" class="footerLink">Link 2</a><br>
                <a href="" class="footerLink">Link 3</a>
            </div>
            <div class="footerLinksLists">
            	<div class="footerLinksHeadings">Links Set Two</div>
                <a href="" class="footerLink">Link 4</a><br>
                <a href="" class="footerLink">Link 5</a><br>
                <a href="" class="footerLink">Link 6</a>
            </div>
            <div class="footerLinksLists">
            	<div class="footerLinksHeadings">Links Set Three</div>
                <a href="" class="footerLink">Link 7</a><br>
                <a href="" class="footerLink">Link 8</a><br>
                <a href="" class="footerLink">Link 9</a>
            </div>
            <div class="footerLinksLists">
            	<div class="footerLinksHeadings">Links Set Four</div>
                <a href="" class="footerLink">Link 10</a><br>
                <a href="" class="footerLink">Link 11</a><br>
                <a href="" class="footerLink">Link 12</a>
            </div>
            <div class="footerBottomText">
            	<img src="images/icon-footer.png" alt="CC" width="80" height="15" class="imageFooterPad">
                <a href="" class="footerLink">UNESCO</a> |
                <a href="" class="footerLink">Communication and Information</a> |
                <a href="" class="footerLink">About OER Platform</a> |
                <a href="" class="footerLink">F.A.Q.</a> |
                <a href="" class="footerLink">Glossary</a> |
                <a href="" class="footerLink">Terms of use</a> |
                <a href="" class="footerLink">Contact</a> |
                <a href="" class="footerLink">Sitemap</a>
            </div>
        </div>
    </div>';

        return $formStr;
    }

    /**
     * this constructs the  form for managing an adaptation
     * @return type FORM
     */
    public function buildAdaptationFormStep1($id, $mode) {


        $objTable = $this->getObject('htmltable', 'htmlelements');

        if ($id != null) {
            //Get adapted-product data
            $product = $this->dbproducts->getProduct($id);

            $hidId = new hiddeninput('id');
            $hidId->cssId = "id";
            $hidId->value = $id;
            $hidMode = new hiddeninput('mode');
            $hidMode->cssId = "mode";
            $hidMode->value = $mode;
            $objTable->startRow();
            $objTable->addCell($hidId->show() . $hidMode->show());
            $objTable->endRow();
        }
        //the title
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_title', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('title');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        if ($product != null) {
            $textinput->value = $product['title'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();


        //alternative title
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_alttitle', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('alternative_title');
        $textinput->size = 60;
        if ($product != null) {
            $textinput->value = $product['alternative_title'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //author
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_author', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('author');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        if ($product != null) {
            $textinput->value = $product['author'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //other contributors
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_othercontributors', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textarea = new textarea('othercontributors', '', 5, 55);
        $textarea->cssClass = 'required';
        if ($product != null) {
            $textarea->value = $product['othercontributors'];
        }
        $objTable->addCell($textarea->show());
        $objTable->endRow();


        //publisher
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_publisher', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('publisher');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        if ($product != null) {
            $textinput->value = $product['publisher'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();

        //keywords
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_keywords', 'oer') . " (" . $this->objLanguage->languageText('mod_oer_keywordsInstruction', 'oer') . ")");
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('keywords');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        if ($product != null) {
            $textinput->value = $product['keywords'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();

        //Institution
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_group_institution', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $institution = new dropdown('institution');
        $institution->cssClass = 'required';

        $institution->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        //Get institutions
        $currentInstitutions = $this->dbInstitution->getAllInstitution();
        //Generate dropdown from existing institutions
        if ($currentInstitutions != Null) {
            foreach ($currentInstitutions as $currentInstitution) {
                $institution->addOption($currentInstitution['id'], $currentInstitution['name']);
            }
        }
        //Set selected
        if ($product != null) {
            $institution->selected = $product['institutionid'];
        }
        $objTable->addCell($institution->show());
        $objTable->endRow();

        //language
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_language', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $language = new dropdown('language');
        $language->cssClass = 'required';

        if ($product != null) {
            $language->selected = $product['language'];
        } else {
            $language->selected = "en";
        }
        $language->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $language->addOption('en', $this->objLanguage->languageText('mod_oer_english', 'oer'));
        $objTable->addCell($language->show());
        $objTable->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_oer_adaptation_heading_step1', 'oer'));
        $fieldset->addContent($objTable->show());


        $action = "saveadaptationstep1";
        $formData = new form('adaptationForm1', $this->uri(array("action" => $action, "id" => $id, "mode" => $mode)));
        $formData->addToForm($fieldset);

        $button = new button('save', $this->objLanguage->languageText('word_next', 'system', 'Next'));
        $button->setToSubmit();
        $formData->addToForm('<br/>' . $button->show());


        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array("action" => "adaptationlist"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "original_product_title";
        $header->str = $product['title'];


        return $header->show() . $formData->show();
    }

    public function buildAdaptationFormStep2($id) {

        $objTable = $this->getObject('htmltable', 'htmlelements');

        if ($id != null) {
            //Check if adaptation has data
            $adaptation = $this->dbproducts->getProduct($id);
            if (empty($adaptation['description']) && empty($adaptation['abstract']) && empty($adaptation['provenonce'])) {
                $product = $this->dbproducts->getParentData($id);
            } else {
                $product = $adaptation;
            }

            $hidId = new hiddeninput('id');
            $hidId->cssId = "id";
            $hidId->value = $id;
            $objTable->startRow();
            $objTable->addCell($hidId->show());
            $objTable->endRow();
        }
        //translation
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_translationof', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $translation = new dropdown('translation');
        $translation->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $translation->addOption('none', $this->objLanguage->languageText('mod_oer_none', 'oer'));

        $originalProducts = $this->dbproducts->getOriginalProducts();
        foreach ($originalProducts as $originalProduct) {
            if ($originalProduct['id'] != $id) {
                $translation->addOption($originalProduct['id'], $originalProduct['title']);
            }
        }

        if ($product != null) {
            $translation->selected = $product['translation_of'];
        }
        $objTable->addCell($translation->show());
        $objTable->endRow();


        //description
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_description', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $description = $this->newObject('htmlarea', 'htmlelements');
        $description->name = 'description';
        if ($product != null) {
            $description->value = $product['description'];
        }
        $description->height = '150px';
        $description->setBasicToolBar();
        $objTable->addCell($description->show());
        $objTable->endRow();


        //abstract
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_abstract', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $abstract = $this->newObject('htmlarea', 'htmlelements');
        $abstract->name = 'abstract';
        $abstract->height = '150px';
        if ($product != null) {
            $abstract->value = $product['abstract'];
        }
        $abstract->setBasicToolBar();
        $objTable->addCell($abstract->show());
        $objTable->endRow();


        //provenonce
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_provenonce', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $provenonce = $this->newObject('htmlarea', 'htmlelements');
        $provenonce->name = 'provenonce';
        $provenonce->height = '150px';
        if ($product != null) {
            $provenonce->value = $product['provenonce'];
        }
        $provenonce->setBasicToolBar();
        $objTable->addCell($provenonce->show());
        $objTable->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_oer_adaptation_heading_step2', 'oer'));
        $fieldset->addContent($objTable->show());

        $formData = new form('adaptationForm2', $this->uri(array("action" => "saveadaptationstep2")));
        $formData->addToForm($fieldset);

        $button = new button('save', $this->objLanguage->languageText('word_next', 'system', 'Next'));
        $button->setToSubmit();
        $formData->addToForm('<br/>' . $button->show());


        $button = new button('back', $this->objLanguage->languageText('word_back'));
        $uri = $this->uri(array("action" => "editadaptationstep1", "id" => $id, "mode" => "edit"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array("action" => "adaptationlist"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "original_product_title";
        $header->str = $product['title'];


        return $header->show() . $formData->show();
    }

    /**
     * Builds the step 3 adaptation form
     * @param type $id
     */
    public function buildAdaptationFormStep3($id) {

        $objTable = $this->getObject('htmltable', 'htmlelements');
        if ($id != null) {
            //Check if adaptation has data
            $adaptation = $this->dbproducts->getProduct($id);
            if (empty($adaptation['accreditation_date']) &&
                    empty($adaptation['contacts']) &&
                    empty($adaptation['relation_type']) &&
                    empty($adaptation['relation']) &&
                    empty($adaptation['coverage']) &&
                    empty($adaptation['status']) &&
                    empty($adaptation['rights'])) {
                $product = $this->dbproducts->getParentData($id);
            } else {
                $product = $adaptation;
            }

            $hidId = new hiddeninput('id');
            $hidId->cssId = "id";
            $hidId->value = $id;
            $objTable->startRow();
            $objTable->addCell($hidId->show());
            $objTable->endRow();
        }

        //resource type
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_oerresource', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $oerresource = new dropdown('oerresource');
        $oerresource->cssClass = 'required';
        $oerresource->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $oerresource->addOption('curriculum', $this->objLanguage->languageText('mod_oer_curriculum', 'oer'));
        if ($product != null) {
            $oerresource->selected = $product['oerresource'];
        }
        $objTable->addCell($oerresource->show());
        $objTable->endRow();

        //licence
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_licence', 'oer'));
        $objTable->endRow();

        $objDisplayLicense = $this->getObject('licensechooserdropdown', 'creativecommons');

        $license = $product['rights'] == '' ? 'copyright' : $product['rights'];
        $rightCell = $objDisplayLicense->show($license);

        $objTable->startRow();
        $objTable->addCell($rightCell);
        $objTable->endRow();



        //needs accredited
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_accredited', 'oer') . '?');
        $objTable->endRow();

        $radio = new radio('accredited');
        $radio->addOption('yes', $this->objLanguage->languageText('word_yes', 'system'));
        $radio->addOption('no', $this->objLanguage->languageText('word_no', 'system'));
        if ($product != null) {
            $radio->selected = $product['accredited'];
        }
        $objTable->startRow();
        $objTable->addCell($radio->show());
        $objTable->endRow();

        //accreditationbody
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_accreditationbody', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('accreditationbody');
        $textinput->size = 60;
        if ($product != null) {
            $textinput->value = $product['accreditation_body'];
        }


        $objTable->addCell($textinput->show());
        $objTable->endRow();

        //accreditationdate
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_accreditationdate', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('accreditationdate');
        $textinput->size = 60;
        if ($product != null) {
            $textinput->value = $product['accreditation_date'];
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();

        //contacts
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_contacts', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textarea = new textarea('contacts', '', 5, 55);
        if ($product != null) {
            $textarea->value = $product['contacts'];
        }
        $objTable->addCell($textarea->show());
        $objTable->endRow();


        //relationtype
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_relationtype', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $relationtype = new dropdown('relationtype');
        $relationtype->addOption('select', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $relationtype->addOption('ispartof', $this->objLanguage->languageText('mod_oer_ispartof', 'oer'));
        $relationtype->addOption('requires', $this->objLanguage->languageText('mod_oer_requires', 'oer'));
        $relationtype->addOption('isrequiredby', $this->objLanguage->languageText('mod_oer_isrequiredby', 'oer'));
        $relationtype->addOption('haspartof', $this->objLanguage->languageText('mod_oer_haspartof', 'oer'));
        $relationtype->addOption('references', $this->objLanguage->languageText('mod_oer_references', 'oer'));
        $relationtype->addOption('isversionof', $this->objLanguage->languageText('mod_oer_isversionof', 'oer'));
        if ($product != null) {
            $relationtype->selected = $product['relation_type'];
        }
        $objTable->addCell($relationtype->show());
        $objTable->endRow();

        //relatedproduct
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_relatedproduct', 'oer'));
        $objTable->endRow();
        $objTable->startRow();
        $relatedproduct = new dropdown('relatedproduct');
        $relatedproduct->addOption('none', $this->objLanguage->languageText('mod_oer_none', 'oer'));

        $originalProducts = $this->dbproducts->getOriginalProducts();
        foreach ($originalProducts as $originalProduct) {
            if ($originalProduct['id'] != $id) {
                $relatedproduct->addOption($originalProduct['id'], $originalProduct['title']);
            }
        }
        if ($product != null) {
            $relatedproduct->selected = $product['relation'];
        }

        $objTable->addCell($relatedproduct->show());
        $objTable->endRow();


        //coverage
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_coverage', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textarea = new textarea('coverage', '', 5, 55);
        if ($product != null) {
            $textarea->value = $product['coverage'];
        }
        $objTable->addCell($textarea->show());
        $objTable->endRow();

        //published status
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_published', 'oer'));
        $objTable->endRow();
        $objTable->startRow();
        $published = new dropdown('status');
        $published->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $published->cssClass = "required";
        $published->addOption('disabled', $this->objLanguage->languageText('mod_oer_disabled', 'oer'));
        $published->addOption('draft', $this->objLanguage->languageText('mod_oer_draft', 'oer'));
        $published->addOption('published', $this->objLanguage->languageText('mod_oer_published', 'oer'));
        if ($product != null) {
            $published->selected = $product['status'];
        }
        $objTable->addCell($published->show());
        $objTable->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_oer_adaptation_heading_step3', 'oer'));
        $fieldset->addContent($objTable->show());

        $formData = new form('adaptationForm3', $this->uri(array("action" => "saveadaptationstep3")));
        $formData->addToForm($fieldset);

        $button = new button('save', $this->objLanguage->languageText('word_next', 'system', 'Next'));
        $button->setToSubmit();
        $formData->addToForm('<br/>' . $button->show());


        $button = new button('back', $this->objLanguage->languageText('word_back'));
        $uri = $this->uri(array("action" => "editadaptationstep2", "id" => $id));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array("action" => "adaptationlist"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "original_product_title";
        $header->str = $product['title'];


        return $header->show() . $formData->show();
    }

    /**
     * creates a table and returns the list of current adaptations
     * @return type
     */
    public function getAdaptationsListingAsGrid() {
        $originalProducts = $this->dbproducts->getAdaptedProducts();


        $controlBand =
                '<div id="originalproducts_controlband">';

        $controlBand.='<br/>&nbsp;' . $this->objLanguage->languageText('mod_oer_viewas', 'oer') . ': ';
        $gridthumbnail = '<img src="skins/oer/images/sort-by-grid.png"/>';
        $gridlink = new link($this->uri(array("action" => "adaptationlist")));
        $gridlink->link = $gridthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_grid', 'oer');
        $controlBand.=$gridlink->show();

        $listthumbnail = '&nbsp;|&nbsp;<img src="skins/oer/images/sort-by-list.png"/>';
        $listlink = new link($this->uri(array("action" => "adaptationlist")));
        $listlink->link = $listthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_list', 'oer');
        $controlBand.=$listlink->show();

        $sortbydropdown = new dropdown('sortby');
        $sortbydropdown->addOption('', $this->objLanguage->languageText('mod_oer_none', 'oer'));

        $controlBand.='<br/><br/>' . $this->objLanguage->languageText('mod_oer_sortby', 'oer');
        $controlBand.=$sortbydropdown->show();

        $controlBand.= '</div> ';
        $startNewRow = TRUE;
        $count = 2;
        $table = $this->getObject('htmltable', 'htmlelements');
        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroups->getId("ProductCreators");
        $objGroupOps = $this->getObject("groupops", "groupadmin");
        $userId = $this->objUser->userId();

        foreach ($originalProducts as $originalProduct) {
            if ($startNewRow) {
                $startNewRow = FALSE;
                $table->startRow();
            }
            //Get parent product related data(institution, institution type)
            $parentData = $this->dbproducts->getProduct($originalProduct['parent_id']);
            $institutionData = $this->dbInstitution->getInstitutionById($originalProduct['institutionid']);
            $institutionTypeName = $this->dbInstitutionType->getInstitutionTypeName($institutionData['type']);
            $thumbnail = '<img src="usrfiles/' . $originalProduct['thumbnail'] . '"  width="79" height="101" align="bottom"/>';
            if ($originalProduct['thumbnail'] == '') {
                $thumbnail = '<img src="skins/oer/images/documentdefault.png"  width="79" height="101" align="bottom"/>';
            }
            $link = new link($this->uri(array("action" => "viewadaptation", "id" => $originalProduct['id'])));
            $link->link = $thumbnail . '<br/>';
            $product = $link->show();

            $link->link = "<div id='producttitle'>" . $parentData['title'] . "</div>";
            $link->cssClass = 'original_product_listing_title';
            $product.= $link->show();
            $product.= "<br /><div id='producttitle'>" . $this->objLanguage->languageText('mod_oer_adaptedby', 'oer') . "</div>";
            $product.= "<br /><div id='institutionva'>" . $institutionData['name'] . "</div>";
            $product.= "<br /><div id='institutiontype'>" . $institutionTypeName . " | " . $institutionData['country'] . "</div>";
            //Display language if english, todo, allow other language items
            if (!empty($originalProduct['language'])) {
                if ($originalProduct['language'] == 'en') {
                    $language = "English";
                    $product.= "<br /><div id='institutionlang'>" . $language . "</div>";
                }
            }


            if ($objGroupOps->isGroupMember($groupId, $userId)) {
                $editImg = '<img src="skins/oer/images/icons/edit.png">';
                $deleteImg = '<img src="skins/oer/images/icons/delete.png">';
                $adaptImg = '<img src="skins/oer/images/icons/add.png">';

                $adaptLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $originalProduct['id'], "mode" => "new")));
                $adaptLink->link = $adaptImg;
                $product.="<br />" . $adaptLink->show();

                $editLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $originalProduct['id'], "mode" => "edit")));
                $editLink->link = $editImg;
                $product.="&nbsp;" . $editLink->show();

                $deleteLink = new link($this->uri(array("action" => "deleteadaptation", "id" => $originalProduct['id'])));
                $deleteLink->link = $deleteImg;
                $deleteLink->cssClass = "deleteoriginalproduct";
                $product.="&nbsp;" . $deleteLink->show();
            }

            $commentsThumbnail = '<img src="skins/oer/images/comments.png"/>';

            $languageField = new dropdown('language');
            $languageField->cssClass = 'original_product_languageField';
            $languageField->setSelected($product['language']);
            $languageField->addOption('en', $this->objLanguage->languageText('mod_oer_english', 'oer'));
            $product.='<br/><br/>' . $commentsThumbnail . '&nbsp;' . $languageField->show();

            $adaptionsCount = 0;
            $adaptationsLink = new link($this->uri(array("action" => "viewadaptions", "id" => $originalProduct['id'])));
            $adaptationsLink->link = $this->objLanguage->languageText('mod_oer_adaptationscount', 'oer');
            $product.="<br/>" . $adaptionsCount . '&nbsp;' . $adaptationsLink->show();

            //addCell($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
            $table->addCell($product, null, null, null, "view_original_product");
            if ($count > 3) {
                $table->endRow();
                $startNewRow = TRUE;
                $count = 1;
            }
            $count++;
        }
        return $controlBand . $table->show();
    }

    /**
     * creates a table and returns the list of adaptable products
     * @return type
     */
    public function getAdaptatableProductListAsGrid() {
        $originalProducts = $this->dbproducts->getOriginalProducts();


        $controlBand.=
                '<div id="originalproducts_controlband">';

        $controlBand.='<br/>&nbsp;' . $this->objLanguage->languageText('mod_oer_viewas', 'oer') . ':';
        $gridthumbnail = '<img src="skins/oer/images/sort-by-grid.png"/>';
        $gridlink = new link($this->uri(array("action" => "adaptationlist")));
        $gridlink->link = $gridthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_grid', 'oer');
        $controlBand.=$gridlink->show();

        $listthumbnail = '&nbsp;|&nbsp;<img src="skins/oer/images/sort-by-list.png"/>';
        $listlink = new link($this->uri(array("action" => "1a")));
        $listlink->link = $listthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_list', 'oer');
        $controlBand.=$listlink->show();

        $sortbydropdown = new dropdown('sortby');
        $sortbydropdown->addOption('', $this->objLanguage->languageText('mod_oer_none', 'oer'));

        $controlBand.='<br/><br/>' . $this->objLanguage->languageText('mod_oer_sortby', 'oer');
        $controlBand.=$sortbydropdown->show();



        $controlBand.= '</div> ';
        $startNewRow = TRUE;
        $count = 2;
        $table = $this->getObject('htmltable', 'htmlelements');
        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroups->getId("ProductCreators");
        $objGroupOps = $this->getObject("groupops", "groupadmin");
        $userId = $this->objUser->userId();

        foreach ($originalProducts as $originalProduct) {
            if ($startNewRow) {
                $startNewRow = FALSE;
                $table->startRow();
            }
            $thumbnail = '<img src="usrfiles/' . $originalProduct['thumbnail'] . '"  width="79" height="101" align="bottom"/>';
            if ($originalProduct['thumbnail'] == '') {
                $thumbnail = '<img src="skins/oer/images/documentdefault.png"  width="79" height="101" align="bottom"/>';
            }
            $link = new link($this->uri(array("action" => "vieworiginalproduct", "id" => $originalProduct['id'])));
            $link->link = $thumbnail . '<br/>';
            $product = $link->show();

            $link->link = $originalProduct['title'];
            $link->cssClass = 'original_product_listing_title';
            $product.= $link->show();
            if ($objGroupOps->isGroupMember($groupId, $userId)) {
                $editImg = '<img src="skins/oer/images/icons/edit.png">';
                $deleteImg = '<img src="skins/oer/images/icons/delete.png">';

                $editLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $originalProduct['id'])));
                $editLink->link = $editImg;
                $product.=$editLink->show();

                $deleteLink = new link($this->uri(array("action" => "deleteadaptation", "id" => $originalProduct['id'])));
                $deleteLink->link = $deleteImg;
                $deleteLink->cssClass = "deleteadaptation";
                $product.=$deleteLink->show();
            }

            $commentsThumbnail = '<img src="skins/oer/images/comments.png"/>';

            $languageField = new dropdown('language');
            $languageField->cssClass = 'original_product_languageField';
            $languageField->setSelected($product['language']);
            $languageField->addOption('en', $this->objLanguage->languageText('mod_oer_english', 'oer'));
            $product.='<br/><br/>' . $commentsThumbnail . '&nbsp;' . $languageField->show();

            $adaptionsCount = 0;
            $adaptationsLink = new link($this->uri(array("action" => "viewadaptions", "id" => $originalProduct['id'])));
            $adaptationsLink->link = $this->objLanguage->languageText('mod_oer_adaptationscount', 'oer');
            $product.="<br/>" . $adaptionsCount . '&nbsp;' . $adaptationsLink->show();

            //addCell($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
            $table->addCell($product, null, null, null, "view_original_product");
            if ($count > 3) {
                $table->endRow();
                $startNewRow = TRUE;
                $count = 1;
            }
            $count++;
        }
        return $controlBand . $table->show();
    }

    /**
     * Creates side navigation links for moving in between forms when managing
     * an adaptation
     */
    function buildAdaptationStepsNav($id) {

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "build_product_steps_nav";
        $header->str = $this->objLanguage->languageText('mod_oer_jumpto', 'oer');

        $content = $header->show();

        $content.='<ul id="nav-secondary">';

        $link = new link($this->uri(array("action" => "editoriginalproductstep1", "id" => $id)));
        $link->link = $this->objLanguage->languageText('mod_oer_step1', 'oer');
        $content.='<li>' . $link->show() . '</li>';


        $link = new link($this->uri(array("action" => "editoriginalproductstep2", "id" => $id)));
        $link->link = $this->objLanguage->languageText('mod_oer_step2', 'oer');
        $content.='<li>' . $link->show() . '</li>';


        $link = new link($this->uri(array("action" => "editoriginalproductstep3", "id" => $id)));
        $link->link = $this->objLanguage->languageText('mod_oer_step3', 'oer');
        $content.='<li>' . $link->show() . '</li>';

        $link = new link($this->uri(array("action" => "editoriginalproductstep4", "id" => $id)));
        $link->link = $this->objLanguage->languageText('mod_oer_step4', 'oer');
        $content.='<li>' . $link->show() . '</li>';


        $content.="</ul>";


        return $content;
    }

    function userHasPermissions() {
        $objGroupOps = $this->getObject("groupops", "groupadmin");
        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $this->objUser = $this->getObject("user", "security");
        //Set groupId for site managers
        $groupId = $objGroups->getId("ProductCreators");
        //Get userId
        $userId = $this->objUser->userId();
        //Flag to check if user has perms to manage adaptations
        $hasPerms = false;
        if ($this->objUser->isLoggedIn()) {
            if ($objGroupOps->isGroupMember($groupId, $userId)) {
                $hasPerms = true;
            }
        }
        return $hasPerms;
    }

}

?>