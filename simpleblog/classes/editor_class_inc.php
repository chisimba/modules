<?php
/**
 *
 * Ops class for Simple Blog
 *
 * Ops class for Simple Blog which assists with building the blog posts
 * user interface elements.
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
 * @package   simpleblog
 * @author    Administrative User admin@localhost.local
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
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
* Ops class for Simple Blog
*
* Ops class for Simple Blog which assists with building the blog posts
* user interface elements.
*
* @package   simpleblog
* @author    Administrative User admin@localhost.local
*
*/
class editor extends object
{

    /**
     *
     * @var string Object $objUser String for the user object
     * @access public
     *
     */
    public $objUser;
    /**
     *
     * @var string Object $objLanguage String for the language object
     * @access public
     *
     */
    public $objLanguage;

    /**
    *
    * Intialiser for the simpleblog ops class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // Get an instance of the languate object
        $this->objLanguage = $this->getObject('language', 'language');
        // Instantiate the user object.
        $this->objUser = $this->getObject('user', 'security');
        // Set the jQuery version to the latest functional.
        $this->setVar('JQUERY_VERSION', '1.4.2');
        // Load the functions specific to this module.
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('simpleblog.js', 'simpleblog'));
        // Get the blog posts db.
        $this->objDbPosts = $this->getObject('dbsimpleblog', 'simpleblog');

    }

    /**
     *
     * Load the form elements that needed to build the editor
     * @access private
     * @return VOID
     *
     */
    public function loadEditElements()
    {
        $this->loadClass('form','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('radio','htmlelements');
        $this->loadClass ('hiddeninput', 'htmlelements');
    }

    public function editForm($id=FALSE)
    {
        $objSec = $this->getObject('simpleblogsecurity', 'simpleblog');
        $userId = $this->objUser->userId();
        $objGuesser = $this->getObject('guesser', 'simpleblog');
        $blogId = $objGuesser->guessBlogId();
        if ($objSec->checkRights($blogId, $userId)) {
            // Load the form elements
            $this->loadEditElements();
            return $this->buildEditForm();
        } else {
            return '<div class="error">' 
              . $this->objLanguage->languageText("mod_simpleblog_norights",
                "simpleblog",
                "You do not have rights to edit or create posts in this blog.")
              . '</div>';
        }
        
    }

    public function buildEditForm()
    {
        // Set up empty values so we can use same form for add and edit
        $title = '';
        $content = '';
        $status = '';
        $mode = $this->getParam('mode', 'add');
        if ($mode == 'edit') {
            $id = $this->getParam('postid', FALSE);
            if ($id) {
                $ar = $this->objDbPosts->getForEdit($id);
                $title = trim($ar['post_title']);
                $content = trim($ar['post_content']);
                $status = $ar['post_status'];
                $blogId = $ar['blogid'];
            }
        } else {
            $objGuesser = $this->getObject('guesser', 'simpleblog');
            $blogId =  $objGuesser->guessBlogId();
        }

        //Set up the form action URL
        $paramArray=array(
            'action'=>'savepost',
            'mode'=>$mode);
        $formAction=$this->uri($paramArray, 'simpleblog');

        //Create the form class
        $objForm = new form('simpleblog_editor');
        $objForm->setAction($formAction);
        $objForm->displayType=3;

        // The blog title form element unlabled.
        $objBlogTitle = new textinput('post_title', $title);
        $objBlogTitle->id='post_title';
        $titleLabel = $this->objLanguage->languageText("mod_simpleblog_posttitle",
            "simpleblog", "The title of this post");
        $postTitleFormElement = $titleLabel . ":<br />" . $objBlogTitle->show();


        // Whether it is published or draft
        $objRadioElement = new radio('post_status');
        $objRadioElement->addOption('posted',
          $this->objLanguage->languageText("mod_simpleblog_posted",
          "simpleblog", "posted"));
        $objRadioElement->addOption('draft',
          $this->objLanguage->languageText("mod_simpleblog_draft",
          "simpleblog", "draft"));
        $objRadioElement->setSelected($status);
        $statusLabel =  $this->objLanguage->languageText("mod_simpleblog_statuslabel",
            "simpleblog", "Indicate the publication status of this post");
        $statusFormElement = $statusLabel . ": " . $objRadioElement->show();

        // The blog content editor unlabled.
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'post_content';
        $editor->setContent($content);
        $contentFormElement = $editor->show();

        // Put the blogid in a hidden input
        $objHidden =  new hiddeninput ( 'blogid', $blogId );
        $objHidden->extra = ' id=\'blogid\'';
        $blogidFormElement = $objHidden->show();

        // Put the id in a hidden input
        if (isset($id)) {
            $objHidden =  new hiddeninput ( 'id', $id );
            $objHidden->extra = ' id=\'id\'';
            $postIdFormElement = $objHidden->show();
        } else {
            $postIdFormElement = NULL;
        }

        //Add a save button
        $objButton = $this->newObject('button', 'htmlelements');
        $objButton->setIconClass("save");
        $objButton->button('save',$this->objLanguage->languageText('word_save'));
        $objButton->setToSubmit();
        $saveFormElement = $objButton->show();

        // Build the form
        $objForm->addToForm( $blogidFormElement . $postIdFormElement
          . "<div class='title_form_element'>" . $postTitleFormElement . "</div>"
          . "<div class='status_form_element'>" . $statusFormElement . "</div>"
          . "<div class='content_form_element'>" . $contentFormElement . "</div>"
          . "<div class='savebutton_form_element'>" . $saveFormElement . "</div>");
        return $objForm->show();
    }

}
?>