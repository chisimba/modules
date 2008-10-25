<?php
/**
 * Class to handle blog elements (links and blogroll).
 *
 * This object can be used elsewhere in the system to render certain aspects of the interface.
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
 * @version    $Id$
 * @package    blog
 * @subpackage blogops
 * @author     Paul Scott <pscott@uwc.ac.za>
 * @copyright  2006-2007 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://avoir.uwc.ac.za
 * @see        References to other sections (if any)...
 */
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
 * Class to handle blog elements (links and blogroll)
 *
 * This object can be used elsewhere in the system to render certain aspects of the interface
 *
 * @category  Chisimba
 * @package   blog
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2007 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class blogcategories extends object
{
    /**
     * Description for public
     *
     * @var    mixed
     * @access public
     */
    public $objConfig;
    /**
     * Standard init function called by the constructor call of Object
     *
     * @access public
     * @return NULL
     */
    public function init() 
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objDbBlog = $this->getObject('dbblog');
            $this->loadClass('href', 'htmlelements');
            $this->sysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $this->showfullname = $this->sysConfig->getValue('show_fullname', 'blog');
            $this->objUser = $this->getObject('user', 'security');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die($e);
        }
        if (!extension_loaded("imap")) {
            $this->mail2blog = FALSE;
        }
    }
    /**
     * Method to quickly add a category to the default category (parent = 0)
     * Can take a comma delimited list as an input arg
     *
     * @param  bool   $featurebox
     * @return string
     */
    public function quickCats($featurebox = FALSE) 
    {
        $this->loadClass('textinput', 'htmlelements');
        $qcatform = new form('qcatadd', $this->uri(array(
            'action' => 'catadd',
            'mode' => 'quickadd'
        )));
        $qcatform->addRule('catname', $this->objLanguage->languageText("mod_blog_phrase_titlereq", "blog") , 'required');
        $qcatname = new textinput('catname');
        $qcatname->size = 15;
        $qcatform->addToForm($qcatname->show());
        $this->objqCButton = &new button($this->objLanguage->languageText('word_update', 'system'));
        $this->objqCButton->setValue($this->objLanguage->languageText('word_update', 'system'));
        $this->objqCButton->setToSubmit();
        $qcatform->addToForm($this->objqCButton->show());
        $qcatform = $qcatform->show();
        if ($featurebox == FALSE) {
            return $qcatform;
        } else {
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_qcatdetails", "blog") , $this->objLanguage->languageText("mod_blog_quickaddcat", "blog") . "<br />" . $qcatform);
            return $ret;
        }
    }
    /**
     * Method to insert the quick add categories to the db
     *
     * @param  string  $list
     * @param  integer $userid
     * @return void
     */
    public function quickCatAdd($list = NULL, $userid) 
    {
        $list = explode(",", $list);
        foreach($list as $items) {
            // echo $items;
            $insarr = array(
                'userid' => $userid,
                'cat_name' => $items,
                'cat_nicename' => $items,
                'cat_desc' => '',
                'cat_parent' => 0
            );
            $this->objDbBlog->setCats($userid, $insarr);
        }
    }
    /**
     * Method to build and display the full scale category editor
     *
     * @param  integer $userid
     * @return string
     */
    public function categoryEditor($userid) 
    {
        // get the categories layout sorted
        $this->loadClass('href', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $cats = $this->objDbBlog->getAllCats($userid);
        $headstr = $this->objLanguage->languageText("mod_blog_catedit_instructions", "blog");
        $totcount = $this->objDbBlog->catCount(NULL);
        // create a table to view the categories
        $cattable = $this->newObject('htmltable', 'htmlelements');
        $cattable->cellpadding = 3;
        // set up the header row
        $cattable->startHeaderRow();
        $cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_parent", "blog"));
        // $cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_name", "blog"));
        $cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_nicename", "blog"));
        $cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_descrip", "blog"));
        $cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_count", "blog"));
        $cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_editdeletecat", "blog"));
        $cattable->endHeaderRow();
        if (!empty($cats)) {
            foreach($cats as $rows) {
                // print_r($rows);
                // start the cats rows
                $cattable->startRow();
                if ($rows['cat_parent'] != '0') {
                    $maparr = $this->objDbBlog->mapKid2Parent($rows['cat_parent']);
                    if (!empty($maparr)) {
                        $rows['cat_parent'] = "<em><b>" . $maparr[0]['cat_name'] . "</b></em>";
                    }
                }
                if ($rows['cat_parent'] == '0') {
                    $rows['cat_parent'] = "<em>" . $this->objLanguage->languageText("mod_blog_word_default", "blog") . "</em>";
                }
                $cattable->addCell($rows['cat_parent']);
                // $cattable->addCell($rows['cat_name']);
                $cattable->addCell($rows['cat_nicename']);
                $cattable->addCell($rows['cat_desc']);
                $cattable->addCell($this->objDbBlog->catCount($rows['id']));
                // $rows['cat_count']);
                $this->objIcon = &$this->getObject('geticon', 'htmlelements');
                $edIcon = $this->objIcon->getEditIcon($this->uri(array(
                    'action' => 'catadd',
                    'mode' => 'edit',
                    'id' => $rows['id'],
                    'module' => 'blog'
                )));
                $delIcon = $this->objIcon->getDeleteIconWithConfirm($rows['id'], array(
                    'module' => 'blog',
                    'action' => 'deletecat',
                    'id' => $rows['id']
                ) , 'blog');
                $cattable->addCell($edIcon . $delIcon);
                $cattable->endRow();
            }
            $ctable = $headstr . $cattable->show();
        } else {
            $ctable = $this->objLanguage->languageText("mod_blog_nocats", "blog");
        }
        // add a new category form:
        $catform = new form('catadd', $this->uri(array(
            'action' => 'catadd'
        )));
        $catform->addRule('catname', $this->objLanguage->languageText("mod_blog_phrase_titlereq", "blog") , 'required');
        $cfieldset = $this->getObject('fieldset', 'htmlelements');
        $cfieldset->setLegend($this->objLanguage->languageText('mod_blog_catdetails', 'blog'));
        $catadd = $this->newObject('htmltable', 'htmlelements');
        $catadd->cellpadding = 5;
        // category name field
        $catadd->startRow();
        $clabel = new label($this->objLanguage->languageText('mod_blog_catname', 'blog') . ':', 'input_catname');
        $catname = new textinput('catname');
        $catadd->addCell($clabel->show());
        $catadd->addCell($catname->show());
        $catadd->endRow();
        $catadd->startRow();
        $dlabel = new label($this->objLanguage->languageText('mod_blog_catparent', 'blog') . ':', 'input_catparent');
        // category parent field (dropdown)
        // get a list of the parent cats
        $pcats = $this->objDbBlog->getAllCats($userid);
        $addDrop = new dropdown('catparent');
        $addDrop->addOption(0, $this->objLanguage->languageText("mod_blog_defcat", "blog"));
        // loop through the existing cats and make sure not to add a child to the dd
        foreach($pcats as $adds) {
            $parent = $adds['cat_parent'];
            if ($adds['cat_parent'] == '0') {
                $addDrop->addOption($adds['id'], $adds['cat_name']);
            }
        }
        $catadd->addCell($dlabel->show());
        $catadd->addCell($addDrop->show());
        $catadd->endRow();
        // start a htmlarea for the category description (optional)
        $catadd->startRow();
        $desclabel = new label($this->objLanguage->languageText('mod_blog_catdesc', 'blog') . ':', 'input_catdesc');
        $this->loadClass('textarea', 'htmlelements');
        $cdesc = new textarea;
        // $this->newObject('textarea','htmlelements');
        $cdesc->setName('catdesc');
        // $cdesc->setBasicToolBar();
        $catadd->addCell($desclabel->show());
        $catadd->addCell($cdesc->show());
        // showFCKEditor());
        $catadd->endRow();
        $catform->addRule('catname', $this->objLanguage->languageText("mod_blog_phrase_titlereq", "blog") , 'required');
        $cfieldset->addContent($catadd->show());
        $catform->addToForm($cfieldset->show());
        $this->objCButton = &new button($this->objLanguage->languageText('word_update', 'system'));
        $this->objCButton->setValue($this->objLanguage->languageText('word_update', 'system'));
        $this->objCButton->setToSubmit();
        $catform->addToForm($this->objCButton->show());
        $catform = $catform->show();
        return $ctable . "<br />" . $catform;
    }
    /**
     * Short description for public
     *
     * Long description (if any) ...
     *
     * @param  array   $catarr Parameter description (if any) ...
     * @param  unknown $userid Parameter description (if any) ...
     * @param  unknown $catid  Parameter description (if any) ...
     * @return object  Return description (if any) ...
     * @access public
     */
    public function catedit($catarr, $userid, $catid) 
    {
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        // $this->loadClass('heading', 'htmlelements');
        $this->loadClass('href', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        // add a new category form:
        $catform = new form('catadd', $this->uri(array(
            'action' => 'catadd',
            'mode' => 'editcommit',
            'id' => $catid
        )));
        $cfieldset = $this->getObject('fieldset', 'htmlelements');
        $cfieldset->setLegend($this->objLanguage->languageText('mod_blog_cateditor', 'blog'));
        $catadd = $this->newObject('htmltable', 'htmlelements');
        $catadd->cellpadding = 5;
        // category name field
        $catadd->startRow();
        $clabel = new label($this->objLanguage->languageText('mod_blog_catname', 'blog') . ':', 'input_catname');
        $catname = new textinput('catname');
        $catname->setValue($catarr['cat_name']);
        $catadd->addCell($clabel->show());
        $catadd->addCell($catname->show());
        $catadd->endRow();
        $catadd->startRow();
        $dlabel = new label($this->objLanguage->languageText('mod_blog_catparent', 'blog') . ':', 'input_catparent');
        // category parent field (dropdown)
        // get a list of the parent cats
        $pcats = $this->objDbBlog->getAllCats($userid);
        $addDrop = new dropdown('catparent');
        $addDrop->addOption(0, $this->objLanguage->languageText("mod_blog_defcat", "blog"));
        // loop through the existing cats and make sure not to add a child to the dd
        foreach($pcats as $adds) {
            $parent = $adds['cat_parent'];
            if ($adds['cat_parent'] == '0') {
                $addDrop->addOption($adds['id'], $adds['cat_name']);
            }
        }
        $catadd->addCell($dlabel->show());
        $catadd->addCell($addDrop->show());
        $catadd->endRow();
        // start a htmlarea for the category description (optional)
        $catadd->startRow();
        $desclabel = new label($this->objLanguage->languageText('mod_blog_catdesc', 'blog') . ':', 'input_catdesc');
        $this->loadClass('textarea', 'htmlelements');
        $cdesc = new textarea;
        // $this->newObject('textarea','htmlelements');
        $cdesc->setName('catdesc');
        $cdesc->setContent($catarr['cat_desc']);
        // $cdesc->setBasicToolBar();
        $catadd->addCell($desclabel->show());
        $catadd->addCell($cdesc->show());
        // showFCKEditor());
        $catadd->endRow();
        $cfieldset->addContent($catadd->show());
        $catform->addToForm($cfieldset->show());
        $this->objCButton = &new button($this->objLanguage->languageText('word_update', 'system'));
        $this->objCButton->setValue($this->objLanguage->languageText('word_update', 'system'));
        $this->objCButton->setToSubmit();
        $catform->addToForm($this->objCButton->show());
        $catform = $catform->show();
        return $catform;
    }
    /**
     * Method to build and display the categories menu
     * Setting the optional featurebox parameter to true will display the categories in a featurebox block
     *
     * @param  array  $cats
     * @param  bool   $featurebox
     * @return string
     */
    public function showCatsMenu($cats, $featurebox = FALSE, $userid = NULL) 
    {
        $this->objUser = $this->getObject('user', 'security');
        $objSideBar = $this->newObject('sidebar', 'navigation');
        $nodes = array();
        $childnodes = array();
        if (!empty($cats)) {
            $ret = "<em>" . $this->objLanguage->languageText("mod_blog_categories", "blog") . "</em>";
            $ret.= "<br />";
            foreach($cats as $categories) {
                // build the sub list as well
                if (isset($categories['child'])) {
                    foreach($categories['child'] as $kid) {
                        if ($this->objUser->isLoggedIn()) {
                            $childnodes[] = array(
                                'text' => $kid['cat_nicename'],
                                'uri' => $this->uri(array(
                                    'action' => 'viewblog',
                                    'catid' => $kid['id'],
                                    'userid' => $userid
                                ) , 'blog')
                            );
                        } else {
                            $childnodes[] = array(
                                'text' => $kid['cat_nicename'],
                                'uri' => $this->uri(array(
                                    'action' => 'viewblog',
                                    'catid' => $kid['id'],
                                    'userid' => $userid
                                ) , 'blog')
                            );
                        }
                    }
                }
                if ($this->objUser->isLoggedIn()) {
                    $nodestoadd[] = array(
                        'text' => $categories['cat_nicename'],
                        'uri' => $this->uri(array(
                            'action' => 'viewblog',
                            'catid' => $categories['id'],
                            'userid' => $userid
                        ) , 'blog') ,
                        'haschildren' => $childnodes
                    );
                } else {
                    $nodestoadd[] = array(
                        'text' => $categories['cat_nicename'],
                        'uri' => $this->uri(array(
                            'action' => 'viewblog',
                            'catid' => $categories['id'],
                            'userid' => $userid
                        ) , 'blog') ,
                        'haschildren' => $childnodes
                    );
                }
                $childnodes = NULL;
                $nodes = NULL;
            }
            $ret.= $objSideBar->show($nodestoadd, NULL, array(
                'action' => 'randblog',
                'userid' => $userid
            ) , 'blog', $this->objLanguage->languageText("mod_blog_word_default", "blog"));
        } else {
            // no cats defined
            $ret = NULL;
        }
        if ($featurebox == FALSE) {
            return $ret;
        } else {
            if (is_null($ret)) {
                return NULL;
            }
            // build a show ALL posts link
            $plink = new href($this->uri(array(
                'action' => 'showallposts',
                'userid' => $userid
            )) , $this->objLanguage->LanguageText("mod_blog_word_showallposts", "blog") , NULL);
            $objFeatureBox = $this->getObject('featurebox', 'navigation');
            $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_categories", "blog") , $plink->show() . "<br />" . $ret);
            return $ret;
        }
    }
}
?>