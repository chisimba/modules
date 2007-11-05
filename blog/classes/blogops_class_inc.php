<?php
/**
 * Class to handle blog elements.
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
 * @version    CVS: $Id$
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
 * Class to handle blog elements
 * 
 * This object can be used elsewhere in the system to render certain aspects of the interface
 * 
 * @category  Chisimba
 * @package   blog
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2007 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class blogops extends object
{

  /**
   * Description for public
   * 
   * @var    mixed 
   * @access public
   */
   public $objConfig;

  /**
   * Description for public
   * 
   * @var    boolean
   * @access public 
   */
   public $mail2blog = TRUE;

   /**
    * Description for public
    * 
    * @var    string
    * @access public
    */
	public $showfullname;
	
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
			$tt = $this->newObject('domtt', 'htmlelements');
			$this->sysConfig = $this->getObject('dbsysconfig', 'sysconfig');
			$this->showfullname = $this->sysConfig->getValue('show_fullname', 'blog');
			$this->objUser = $this->getObject('user', 'security');
		}
		catch(customException $e) {
			echo customException::cleanUp();
			die();
		}
		if (!extension_loaded("imap")) {
			$this->mail2blog = FALSE;
		}
	}

   /**
    * Short description for public
    * 
    * Long description (if any) ...
    * 
    * @return string Return description (if any) ...
    * @access public
    */
	public function showKML()
	{
		$kml = $this->getObject('kmlgen', 'simplemap');
		$doc = $kml->overlay('my map', 'a test map');
		$doc.= $kml->generateSimplePlacemarker('place1', 'a place', '18.629057', '-33.932922', 0);
		$doc.= $kml->generateSimplePlacemarker('place2', 'another place', '32.56667', '0.33333', 0);
		$doc.= $kml->simplePlaceSuffix();
		return $doc;
	}
   /**
    * Methods to control blog links and blogrolls...
    * 
    * @param integer $userid     The user id
    * @param boolean $featurebox The featurebox switch
    * 
    * @return string $str The rendered output
    */
	public function showBlinks($userid, $featurebox = FALSE)
	{
		$this->loadClass('href', 'htmlelements');
		// grab all of the links for the user
		$links = $this->objDbBlog->getUserLinksonly($userid);
		if (empty($links)) {
			return NULL;
		}
		$str = NULL;
		foreach($links as $link) {
			$hr = new href($link['link_url'], $link['link_name'], 'target="' . $link['link_target'] . '" alt="' . $link['link_description'] . '"');
			$str.= "<ul>" . $hr->show() . "</ul>";
		}
		if ($featurebox == TRUE) {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_links", "blog") , $str, 'bloglinks', 'default');
			return $ret;
		} else {
			return $str;
		}
	}

   /**
    * Short description for public
    * 
    * Long description (if any) ...
    * 
    * @param integer $userid     Parameter description (if any) ...
    * @param boolean $featurebox Parameter description (if any) ...
    * 
    * @return string  Return description (if any) ...
    * @access public 
    */
	public function showBroll($userid, $featurebox = FALSE)
	{
		$this->loadClass('href', 'htmlelements');
		// grab all of the links for the user
		$links = $this->objDbBlog->getUserbroll($userid);
		if (empty($links)) {
			return NULL;
		}
		$str = NULL;
		foreach($links as $link) {
			$hr = new href($link['link_url'], $link['link_name'], 'target="' . $link['link_target'] . '" alt="' . $link['link_description'] . '"');
			$str.= "<ul>" . $hr->show() . "</ul>";
		}
		if ($featurebox == TRUE) {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_blogroll", "blog") , $str, 'blogroll', 'default');
			return $ret;
		} else {
			return $str;
		}
	}

   /**
    * Short description for public
    * 
    * Long description (if any) ...
    * 
    * @param boolean $featurebox Parameter description (if any) ...
    * @param string  $ldata      Parameter description (if any) ...
    * 
    * @return mixed   Return description (if any) ...
    * @access public 
    */
	public function editBlinks($featurebox = FALSE, $ldata = NULL)
	{
		$this->loadClass('href', 'htmlelements');
		$this->loadClass('label', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$this->loadClass('textarea', 'htmlelements');
		$this->loadClass('dropdown', 'htmlelements');
		$this->objUser = $this->getObject('user', 'security');
		if ($ldata == NULL) {
			$lform = new form('addlink', $this->uri(array(
                                            'action' => 'addlink',
			)
			)
                                           );
		} else {
			$ldata = $ldata[0];
			$lform = new form('addlink', $this->uri(array(
			'action' => 'linkedit',
			'mode' => 'edit',
			'id' => $ldata['id']
			)));
		}
		// add rules
		$lform->addRule('lurl', $this->objLanguage->languageText("mod_blog_phrase_lurlreq", "blog") , 'required');
		$lform->addRule('lname', $this->objLanguage->languageText("mod_blog_phrase_lnamereq", "blog") , 'required');
		// start a fieldset
		$lfieldset = $this->getObject('fieldset', 'htmlelements');
		$ladd = $this->newObject('htmltable', 'htmlelements');
		$ladd->cellpadding = 3;
		// url textfield
		$ladd->startRow();
		$lurllabel = new label($this->objLanguage->languageText('mod_blog_lurl', 'blog') . ':', 'input_lurl');
		$lurl = new textinput('lurl');
		$lurl->size = "56%";
		if (isset($ldata['link_url'])) {
			$lurl->setValue(htmlentities($ldata['link_url'], ENT_QUOTES));
		}
		$ladd->addCell($lurllabel->show());
		$ladd->addCell($lurl->show());
		$ladd->endRow();
		// name
		$ladd->startRow();
		$lnamelabel = new label($this->objLanguage->languageText('mod_blog_lname', 'blog') . ':', 'input_lname');
		$lname = new textinput('lname');
		$lname->size = '56%';
		if (isset($ldata['link_name'])) {
			$lname->setValue($ldata['link_name']);
		}
		$ladd->addCell($lnamelabel->show());
		$ladd->addCell($lname->show());
		$ladd->endRow();
		// description
		$ladd->startRow();
		$ldesclabel = new label($this->objLanguage->languageText('mod_blog_ldesc', 'blog') . ':', 'input_ldesc');
		$ldesc = new textarea('ldescription');
		$ldesc->setColumns(48);
		if (isset($ldata['link_description'])) {
			$ldesc->setValue($ldata['link_description']);
		}
		$ladd->addCell($ldesclabel->show());
		$ladd->addCell($ldesc->show());
		$ladd->endRow();
		// link target dropdown
		$ladd->startRow();
		$ltargetlabel = new label($this->objLanguage->languageText('mod_blog_ltarget', 'blog') . ':', 'input_ltarget');
		$ltarget = new dropdown('ltarget');
		$ltarget->extra = ' style="width:64%;" ';
		$ltarget->addOption('_blank', $this->objLanguage->languageText("mod_blog_linktarget_blank", 'blog'));
		$ltarget->addOption('_self', $this->objLanguage->languageText("mod_blog_linktarget_self", 'blog'));
		$ltarget->addOption('_parent', $this->objLanguage->languageText("mod_blog_linktarget_parent", 'blog'));
		$ltarget->addOption('_top', $this->objLanguage->languageText("mod_blog_linktarget_top", 'blog'));
		$ladd->addCell($ltargetlabel->show());
		$ladd->addCell($ltarget->show());
		$ladd->endRow();
		// link type dropdown
		$ladd->startRow();
		$ltypelabel = new label($this->objLanguage->languageText('mod_blog_ltype', 'blog') . ':', 'input_ltype');
		$ltype = new dropdown('ltype');
		$ltype->extra = ' style="width:64%;" ';
		$ltype->addOption('blogroll', $this->objLanguage->languageText("mod_blog_linktype_blogroll", 'blog'));
		$ltype->addOption('bloglink', $this->objLanguage->languageText("mod_blog_linktype_bloglink", 'blog'));
		$ladd->addCell($ltypelabel->show());
		$ladd->addCell($ltype->show());
		$ladd->endRow();
		// notes
		$ladd->startRow();
		$lnoteslabel = new label($this->objLanguage->languageText('mod_blog_lnotes', 'blog') . ':', 'input_lnotes');
		$lnotes = new textarea('lnotes');
		$lnotes->setColumns(48);
		if (isset($ldata['link_notes'])) {
			$lnotes->setValue($ldata['link_notes']);
		}
		$ladd->addCell($lnoteslabel->show());
		$ladd->addCell($lnotes->show());
		$ladd->endRow();
		// end off the form and add the buttons
		$this->objLButton = &new button($this->objLanguage->languageText('word_save', 'system'));
		$this->objLButton->setValue($this->objLanguage->languageText('word_save', 'system'));
		$this->objLButton->setToSubmit();
		$lfieldset->addContent($ladd->show());
		$lform->addToForm($lfieldset->show());
		$lform->addToForm($this->objLButton->show());
		$lform = $lform->show();
		// ok now the table with the edit/delete for each rss feed
		$elinks = $this->objDbBlog->getUserLinks($this->objUser->userId());
		$eltable = $this->newObject('htmltable', 'htmlelements');
		$eltable->cellpadding = 3;
		// $eltable->border = 1;
		// set up the header row
		$eltable->startHeaderRow();
		$eltable->addHeaderCell($this->objLanguage->languageText("mod_blog_lhead_name", "blog"));
		$eltable->addHeaderCell($this->objLanguage->languageText("mod_blog_lhead_description", "blog"));
		$eltable->addHeaderCell($this->objLanguage->languageText("mod_blog_lhead_type", "blog"));
		$eltable->addHeaderCell('');
		$eltable->endHeaderRow();
		// set up the rows and display
		if (!empty($elinks)) {
			foreach($elinks as $rows) {
				$eltable->startRow();
				$linklink = new href($rows['link_url'], $rows['link_name']);
				$eltable->addCell($linklink->show());
				$eltable->addCell(($rows['link_description']));
				$eltable->addCell(($rows['link_type']));
				$this->objIcon = &$this->getObject('geticon', 'htmlelements');
				$edIcon = $this->objIcon->getEditIcon($this->uri(array(
				'action' => 'addlink',
				'mode' => 'edit',
				'id' => $rows['id'],
				'module' => 'blog'
				)));
				$delIcon = $this->objIcon->getDeleteIconWithConfirm($rows['id'], array(
				'module' => 'blog',
				'action' => 'deletelink',
				'id' => $rows['id']
				) , 'blog');
				$eltable->addCell($edIcon . $delIcon);
				$eltable->endRow();
			}
			// $eltable = $eltable->show();

		}
		if ($featurebox == TRUE) {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_linkedit", "blog") , $lform . $eltable->show());
			return $ret;
		} else {
			return $lform . $eltable->show();
		}
	}
	/**
     * Method to look up geonames database for lat lon cords of a certain place as a string
     *
     * @param  array   $params
     * @param  integer $limit 
     * @return string 
     */
	public function findGeoTag($params = array() , $limit = '10')
	{
		// do a sanity check on the array of params...
		if (!isset($params['place']) || !isset($params['countrycode'])) {
			return FALSE;
			break;
		}
		$wsurl = "http:// ws.geonames.org/search?";
		$searchparams = "q=" . $params['place'] . "&country=" . $params['countrycode'] . "&maxRows=" . $limit;
		$url = $wsurl . $searchparams;
		// set a client to get the request
		// get the proxy info if set
		$this->objProxy = $this->getObject('proxyparser', 'utilities');
		$proxyArr = $this->objProxy->getProxy();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if (!empty($proxyArr) && $proxyArr['proxy_protocol'] != '') {
			curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'] . ":" . $proxyArr['proxy_port']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'] . ":" . $proxyArr['proxy_pass']);
		}
		$code = curl_exec($ch);
		curl_close($ch);
		return $code;
	}
	/**
     * Method to return a timeline object of a specific users blog posts
     *
     * @param  array   $posts 
     * @param  integer $userid
     * @return array  
     */
	public function myBlogTimeline($posts, $userid)
	{
		$this->objUser = $this->getObject('user', 'security');
		// parse the hell outta the posts and build up the timeline XML
		$str = '<data date-time-format="iso8601">';
		foreach($posts as $post) {
			// start the event tag
			$str.= "<event ";
			// add in the event details
			$date = date('Y-m-d', $post['post_ts']);
			$title = $post['post_title'];
			$plink = new href($this->uri(array(
			'action' => 'viewsingle',
			'postid' => $post['id'],
			'userid' => $post['userid']
			) , 'blog') , $this->objLanguage->languageText("mod_blog_viewpost", "blog") , 'target=_top');
			$image = $this->objUser->getUserImageNoTags($userid);
			$str.= 'start="' . $date . '" title="' . $title . '" image="' . $image . '">';
			$str.= htmlentities($post['post_excerpt'] . "<br />" . $plink->show());
			$str.= "</event>";
		}
		$startdate = date('Y', $posts[0]['post_ts']);
		$str.= "</data>";
		return array(
		$str,
		$startdate
		);
	}
	/**
     * Method to parse the timeline URI data
     *
     * @param  integer $int     
     * @param  integer $fdate   
     * @param  string  $timeline
     * @return mixed  
     */
	public function parseTimeline($int, $fdate, $timeline)
	{
		$objIframe = $this->getObject('iframe', 'htmlelements');
		$objIframe->width = "100%";
		$objIframe->height = "700";
		$ret = $this->uri(array(
		"mode" => "plain",
		"action" => "viewtimeline",
		"timeLine" => $timeline,
		"intervalUnit" => $int,
		"focusDate" => $fdate,
		"tlHeight" => '700'
		) , "timeline");
		$objIframe->src = $ret;
		return $objIframe->show();
	}
	/**
     * Method not yet implemented due to processor usage and db hit rate
     *
     */
	public function siteBlogTimeline()
	{
		// grab all of the posts

	}
	/**
     * Method to create the form used in the geoTag block
     *
     * @param  void  
     * @return string
     */
	public function geoTagForm()
	{
		$this->loadClass('href', 'htmlelements');
		$this->loadClass('label', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$this->loadClass('textarea', 'htmlelements');
		$this->objUser = $this->getObject('user', 'security');
		$geoform = new form('checkgeo', $this->uri(array(
		'action' => 'checkgeo'
		)));
		// add rules
		$geoform->addRule('geoplace', $this->objLanguage->languageText("mod_blog_phrase_geoplacereq", "blog") , 'required');
		$geoform->addRule('geocountrycode', $this->objLanguage->languageText("mod_blog_phrase_geocountrycodereq", "blog") , 'required');
		// start a fieldset
		$geofieldset = $this->getObject('fieldset', 'htmlelements');
		$geoadd = $this->newObject('htmltable', 'htmlelements');
		$geoadd->cellpadding = 3;
		// place textfield
		$geoadd->startRow();
		$geoplacelabel = new label($this->objLanguage->languageText('mod_blog_geoplace', 'blog') . ':', 'input_geoplace');
		$geoplace = new textinput('geoplace');
		$geoplace->size = '45%';
		$geoadd->addCell($geoplacelabel->show());
		$geoadd->addCell($geoplace->show());
		$geoadd->endRow();
		// Country code
		$geoadd->startRow();
		// get the codes and countries from the languagecode class
		$langcode = $this->getObject('languagecode', 'language');
		$list = $langcode->country();
		$geocountrycodelabel = new label($this->objLanguage->languageText('mod_blog_geocountrycode', 'blog') . ':', 'input_geocountrycode');
		$geocountrycode = $list;
		// new textinput('geocountrycode');
		$geoadd->addCell($geocountrycodelabel->show());
		$geoadd->addCell($geocountrycode);
		// ->show());
		$geoadd->endRow();
		// end off the form and add the buttons
		$this->objGeoButton = &new button($this->objLanguage->languageText('word_lookup', 'blog'));
		$this->objGeoButton->setValue($this->objLanguage->languageText('word_lookup', 'blog'));
		$this->objGeoButton->setToSubmit();
		$geofieldset->addContent($geoadd->show());
		$geoform->addToForm($geofieldset->show());
		$geoform->addToForm($this->objGeoButton->show());
		$geoform = $geoform->show();
		// featurebox it...
		$objGeoFeaturebox = $this->getObject('featurebox', 'navigation');
		return $objGeoFeaturebox->show($this->objLanguage->languageText("mod_blog_geolookup", "blog") , $geoform);
	}
	/**
     * Method to show the latest images posted to the blog as a slideshow
     *
     * @return string
     */
	public function showDiaporama()
	{
		$this->objConfig = $this->getObject('altconfig', 'config');
		// build up the array of images...
		$path = $this->objConfig->getContentBasePath() . 'blog/';
		if (!file_exists($path)) {
			mkdir($path, 0777);
		}
		chdir($path);
		$counter = 0;
		$entry = NULL;
		$filearr = glob("{*.jpg,*.JPG,*.png,*.PNG,*.gif,*.GIF}", GLOB_BRACE);
		if (empty($filearr)) {
			return NULL;
		}
		foreach($filearr as $images) {
			$entry.= 't_img[' . $counter . '] = "' . $this->objConfig->getSiteRoot() . "usrfiles/blog/" . $images . '";';
			$counter++;
		}
		$head = '<script type="text/javascript">
                var id_current = 0;

                function majDiaporama ()
                {
                     var t_img = new Array();';
		$head.= $entry;
		$head.= "var img = $('imageDiaporama');

                       Element.hide('imageDiaporama');
                       img.src = '';
                       if (id_current < (t_img.length-1)) id_current++;
                       else id_current = 0;
                       img.src = t_img[id_current];
                       new Effect.Appear('imageDiaporama');";
		$head.= 'window.setTimeout("majDiaporama()",5000);
                }
            </script>';
		$this->appendArrayVar('headerParams', $head);
		$content = '<body onLoad="majDiaporama ();">
                    <div class="warning" id="photoLog">
                    <img src=" " style="width : 120px; height : 80px;" alt="random selection of pictures" id="imageDiaporama"/>
                    </div>  ';
		$this->objFeaturebox = $this->getObject('featurebox', 'navigation');
		$ret = $this->objFeaturebox->showContent($this->objLanguage->languageText("mod_blog_recentpics", "blog") , $content);
		return $ret;
	}
	/**
     * Method to display the login box for prelogin blog operations
     *
     * @param  bool   $featurebox
     * @return string
     */
	public function loginBox($featurebox = FALSE)
	{
		$objLogin = &$this->getObject('logininterface', 'security');
		$objRegister = $this->getObject('block_register', 'security');
		if ($featurebox == FALSE) {
			return $objLogin->renderLoginBox('blog') . "<br />" . $objRegister->show();
		} else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			return $objFeatureBox->show($this->objLanguage->languageText("word_login", "system") , $objLogin->renderLoginBox('blog') . "<br />" . $objRegister->show());
		}
	}
	/**
     * Method to output a rss feeds box
     *
     * @param  string $url 
     * @param  string $name
     * @return string
     */
	public function rssBox($url, $name)
	{
		$url = urldecode($url);
		$objFeatureBox = $this->getObject('featurebox', 'navigation');
		$objRss = $this->getObject('rssreader', 'feed');
		$objRss->parseRss($url);
		$head = $this->objLanguage->languageText("mod_blog_word_headlinesfrom", "blog");
		$head.= " " . $name;
		$content = "<ul>\n";
		foreach($objRss->getRssItems() as $item) {
			if (!isset($item['link'])) {
				$item['link'] = NULL;
			}
			@$content.= "<li><a href=\"" . htmlentities($item['link']) . "\">" . htmlentities($item['title']) . "</a></li>\n";
		}
		$content.= "</ul>\n";
		return $objFeatureBox->show($head, $content);
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $rssurl Parameter description (if any) ...
     * @param  string  $name   Parameter description (if any) ...
     * @param  unknown $feedid Parameter description (if any) ...
     * @return mixed   Return description (if any) ...
     * @access public 
     */
	public function rssRefresh($rssurl, $name, $feedid)
	{
		// echo $rssurl; die();
		$objFeatureBox = $this->getObject('featurebox', 'navigation');
		$objRss = $this->getObject('rssreader', 'feed');
		$this->objConfig = $this->getObject('altconfig', 'config');
		// get the proxy info if set
		$objProxy = $this->getObject('proxyparser', 'utilities');
		$proxyArr = $objProxy->getProxy();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $rssurl);
		// curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if (!empty($proxyArr) && $proxyArr['proxy_protocol'] != '') {
			curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxy_host'] . ":" . $proxyArr['proxy_port']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxy_user'] . ":" . $proxyArr['proxy_pass']);
		}
		$rsscache = curl_exec($ch);
		curl_close($ch);
		// var_dump($rsscache);
		// put in a timestamp
		$addtime = time();
		$addarr = array(
		'url' => $rssurl,
		'rsstime' => $addtime
		);
		// write the file down for caching
		$path = $this->objConfig->getContentBasePath() . "/blog/rsscache/";
		$rsstime = time();
		if (!file_exists($path)) {
			mkdir($path);
			chmod($path, 0777);
			$filename = $path . $this->objUser->userId() . "_" . $rsstime . ".xml";
			if (!file_exists($filename)) {
				touch($filename);
			}
			$handle = fopen($filename, 'wb');
			fwrite($handle, $rsscache);
		} else {
			$filename = $path . $this->objUser->userId() . "_" . $rsstime . ".xml";
			$handle = fopen($filename, 'wb');
			fwrite($handle, $rsscache);
		}
		// update the db
		$addarr = array(
		'url' => $rssurl,
		'rsscache' => $filename,
		'rsstime' => $addtime
		);
		// print_r($addarr);
		$this->objDbBlog->updateRss($addarr, $feedid);
		// echo $rsscache;
		$objRss->parseRss($rsscache);
		$rssbits = $objRss->getRssItems();
		if (empty($rssbits) || !isset($rssbits)) {
			$objRss2 = $this->newObject('rssreader', 'feed');
			// fallback to the known good url
			$objRss2->parseRss($rssurl);
			$head = $this->objLanguage->languageText("mod_blog_word_headlinesfrom", "blog");
			$head.= " " . $name;
			$content = "<ul>\n";
			foreach($objRss2->getRssItems() as $item) {
				if (!isset($item['link'])) {
					$item['link'] = NULL;
				}
				@$content.= "<li><a href=\"" . htmlentities($item['link']) . "\">" . htmlentities($item['title']) . "</a></li>\n";
			}
			$content.= "</ul>\n";
			return $objFeatureBox->show($head, $content);
		} else {
			foreach($objRss->getRssItems() as $item) {
				if (!isset($item['link'])) {
					$item['link'] = NULL;
				}
				@$content.= "<li><a href=\"" . htmlentities($item['link']) . "\">" . htmlentities($item['title']) . "</a></li>\n";
			}
			$content.= "</ul>\n";
		}
		return $objFeatureBox->show($head, $content);
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  boolean $featurebox Parameter description (if any) ...
     * @param  array   $rdata      Parameter description (if any) ...
     * @return mixed   Return description (if any) ...
     * @access public 
     */
	public function rssEditor($featurebox = FALSE, $rdata = NULL)
	{
		// print_r($rdata);
		$this->loadClass('href', 'htmlelements');
		$this->loadClass('label', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$this->loadClass('textarea', 'htmlelements');
		$this->objUser = $this->getObject('user', 'security');
		if ($rdata == NULL) {
			$rssform = new form('addrss', $this->uri(array(
			'action' => 'addrss'
			)));
		} else {
			$rdata = $rdata[0];
			$rssform = new form('addrss', $this->uri(array(
			'action' => 'rssedit',
			'mode' => 'edit',
			'id' => $rdata['id']
			)));
		}
		// add rules
		$rssform->addRule('rssurl', $this->objLanguage->languageText("mod_blog_phrase_rssurlreq", "blog") , 'required');
		$rssform->addRule('name', $this->objLanguage->languageText("mod_blog_phrase_rssnamereq", "blog") , 'required');
		// start a fieldset
		$rssfieldset = $this->getObject('fieldset', 'htmlelements');
		$rssadd = $this->newObject('htmltable', 'htmlelements');
		$rssadd->cellpadding = 3;
		// url textfield
		$rssadd->startRow();
		$rssurllabel = new label($this->objLanguage->languageText('mod_blog_rssurl', 'blog') . ':', 'input_rssuser');
		$rssurl = new textinput('rssurl');
		$rssurl->extra = ' style="width:100%;" ';
		if (isset($rdata['url'])) {
			$rssurl->setValue(htmlentities($rdata['url'], ENT_QUOTES));
			// $rssurl->setValue('url');

		}
		$rssadd->addCell($rssurllabel->show());
		$rssadd->addCell($rssurl->show());
		$rssadd->endRow();
		// name
		$rssadd->startRow();
		$rssnamelabel = new label($this->objLanguage->languageText('mod_blog_rssname', 'blog') . ':', 'input_rssname');
		$rssname = new textinput('name');
		$rssname->extra = ' style="width:100%;" ';
		if (isset($rdata['name'])) {
			$rssname->setValue($rdata['name']);
		}
		$rssadd->addCell($rssnamelabel->show());
		$rssadd->addCell($rssname->show());
		$rssadd->endRow();
		// description
		$rssadd->startRow();
		$rssdesclabel = new label($this->objLanguage->languageText('mod_blog_rssdesc', 'blog') . ':', 'input_rssname');
		$rssdesc = new textarea('description');
		$rssdesc->extra = ' style="width:100%;" ';
		if (isset($rdata['description'])) {
			// var_dump($rdata['description']);
			$rssdesc->setValue($rdata['description']);
		}
		$rssadd->addCell($rssdesclabel->show());
		$rssadd->addCell($rssdesc->show());
		$rssadd->endRow();
		// end off the form and add the buttons
		$this->objRssButton = &new button($this->objLanguage->languageText('word_save', 'system'));
		$this->objRssButton->setValue($this->objLanguage->languageText('word_save', 'system'));
		$this->objRssButton->setToSubmit();
		$rssfieldset->addContent($rssadd->show());
		$rssform->addToForm($rssfieldset->show());
		$rssform->addToForm($this->objRssButton->show());
		$rssform = $rssform->show();
		// ok now the table with the edit/delete for each rss feed
		$efeeds = $this->objDbBlog->getUserRss($this->objUser->userId());
		$ftable = $this->newObject('htmltable', 'htmlelements');
		$ftable->cellpadding = 3;
		// $ftable->border = 1;
		// set up the header row
		$ftable->startHeaderRow();
		$ftable->addHeaderCell($this->objLanguage->languageText("mod_blog_fhead_name", "blog"));
		$ftable->addHeaderCell($this->objLanguage->languageText("mod_blog_fhead_description", "blog"));
		$ftable->addHeaderCell('');
		$ftable->endHeaderRow();
		// set up the rows and display
		if (!empty($efeeds)) {
			foreach($efeeds as $rows) {
				$ftable->startRow();
				$feedlink = new href($rows['url'], $rows['name']);
				$ftable->addCell($feedlink->show());
				// $ftable->addCell(htmlentities($rows['name']));
				$ftable->addCell(($rows['description']));
				$this->objIcon = &$this->getObject('geticon', 'htmlelements');
				$edIcon = $this->objIcon->getEditIcon($this->uri(array(
				'action' => 'addrss',
				'mode' => 'edit',
				'id' => $rows['id'],
				// 'url' => $rows['url'],
				// 'description' => $rows['description'],
				'module' => 'blog'
				)));
				$delIcon = $this->objIcon->getDeleteIconWithConfirm($rows['id'], array(
				'module' => 'blog',
				'action' => 'deleterss',
				'id' => $rows['id']
				) , 'blog');
				$ftable->addCell($edIcon . $delIcon);
				$ftable->endRow();
			}
			// $ftable = $ftable->show();

		}
		if ($featurebox == TRUE) {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_importblog", "blog") , $imform . $ftable);
			return $ret;
		} else {
			return $rssform . $ftable->show();
		}
	}
	/**
     * Method to display a link to all the blogs on the system
     * Setting $featurebox = TRUE will display the link in a block style featurebox
     *
     * @param  bool   $featurebox
     * @return string
     */
	public function showBlogsLink($featurebox = FALSE)
	{
		// set up a link to the other users blogs...
		$oblogs = new href($this->uri(array(
		'action' => 'allblogs'
		)) , $this->objLanguage->languageText("mod_blog_viewallblogs", "blog") , NULL);
		// Link for siteblogs Added by Irshaad Hoodain
		$ositeblogs = new href($this->uri(array(
		'action' => 'siteblog'
		)) , $this->objLanguage->languageText("mod_blog_viewsiteblogs", "blog") , NULL);
		$defmodLink = new href($this->uri(array() , '_default') , $this->objLanguage->languageText("mod_blog_returntosite", "blog") , NULL);
		if ($featurebox == FALSE) {
			$ret = $oblogs->show() . "<br />" . $defmodLink->show();
		} else {
			$boxContent = $oblogs->show() . "<br />";
			$boxContent.= $defmodLink->show() . "<br />";
			//
			// database abstraction object
			$this->objDbBlog = $this->getObject('dbblog');
			$postresults = $this->objDbBlog->getAllPosts($userid = 1, null);
			if (!$postresults == null) {
				$boxContent.= $ositeblogs->show() . "<br />";
			}
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_otherblogs", "blog") , $boxContent);
			// $ret .= $objFeatureBox->show($this->objLanguage->languageText("mod_blog_viewsiteblogs","blog"), $ositeblogs->show());
			// $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_otherblogs", "blog") , $oblogs->show());
			// $ret.= $objFeatureBox->show($this->objLanguage->languageText("mod_blog_viewsiteblogs", "blog") , $ositeblogs->show());

		}
		return $ret;
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
	/**
     * Method to display a link categories box
     *
     * @param  array  $linkcats  
     * @param  bool   $featurebox
     * @return string
     */
	public function showLinkCats($linkcats, $featurebox = FALSE)
	{
		$this->objUser = &$this->getObject('user', 'security');
		// cycle through the link categories and display them
		foreach($linkcats as $lc) {
			$ret = "<em>" . $lc['catname'] . "</em><br />";
			$linkers = $this->objDbBlog->getLinksCats($this->objUser->userid() , $lc['id']);
			if (!empty($linkers)) {
				$ret.= "<ul>";
				foreach($linkers as $lk) {
					$ret.= "<li>";
					$alt = htmlentities($lk['link_description']);
					$link = new href(htmlentities($lk['link_url']) , htmlentities($lk['link_name']) , "alt='{$alt}'");
					$ret.= $link->show();
					$ret.= "</li>";
				}
				$ret.= "</ul>";
			}
		}
		if ($featurebox == FALSE) {
			return $ret;
		} else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			if (!isset($ret)) {
				$ret = NULL;
			}
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_linkcategories", "blog") , $ret);
			return $ret;
		}
	}
	/**
     * Method to create a form to import the blog data from a remote
     *
     * @param  bool   $featurebox
     * @return string
     */
	public function showImportForm($featurebox = TRUE)
	{
		$this->objUser = $this->getObject('user', 'security');
		$imform = new form('importblog', $this->uri(array(
		'action' => 'importblog'
		)));
		// start a fieldset
		$imfieldset = $this->getObject('fieldset', 'htmlelements');
		// $imfieldset->setLegend($this->objLanguage->languageText('mod_blog_importblog', 'blog'));
		$imadd = $this->newObject('htmltable', 'htmlelements');
		$imadd->cellpadding = 5;
		// server dropdown
		$servdrop = new dropdown('server');
		$servdrop->addOption("fsiu", $this->objLanguage->languageText("mod_blog_fsiu", "blog"));
		$servdrop->addOption("elearn", $this->objLanguage->languageText("mod_blog_elearn", "blog"));
		$servdrop->addOption("santec", $this->objLanguage->languageText("mod_blog_santec", "blog"));
		// $servdrop->addOption("freecourseware", $this->objLanguage->languageText("mod_blog_freecourseware", "blog"));
		// $servdrop->addOption("5ive", $this->objLanguage->languageText("mod_blog_5ive", "blog"));
		// $servdrop->addOption("pear", $this->objLanguage->languageText("mod_blog_peardemo", "blog"));
		// $servdrop->addOption("dfx", $this->objLanguage->languageText("mod_blog_dfx", "blog"));
		$imadd->startRow();
		$servlabel = new label($this->objLanguage->languageText('mod_blog_impserv', 'blog') . ':', 'input_importfrom');
		$imadd->addCell($servlabel->show());
		$imadd->addCell($servdrop->show());
		$imadd->endRow();
		// username textfield
		$imadd->startRow();
		$imulabel = new label($this->objLanguage->languageText('mod_blog_impuser', 'blog') . ':', 'input_impuser');
		$imuser = new textinput('username');
		$usernameval = $this->objUser->username();
		if (isset($usernameval)) {
			$imuser->setValue($this->objUser->username());
		}
		$imadd->addCell($imulabel->show());
		$imadd->addCell($imuser->show());
		$imadd->endRow();
		// add rules
		// $imform->addRule('server', $this->objLanguage->languageText("mod_blog_phrase_imserverreq", "blog") , 'required');
		// $imform->addRule('username', $this->objLanguage->languageText("mod_blog_phrase_imuserreq", "blog") , 'required');
		// end off the form and add the buttons
		$this->objIMButton = &new button($this->objLanguage->languageText('word_import', 'system'));
		$this->objIMButton->setValue($this->objLanguage->languageText('word_import', 'system'));
		$this->objIMButton->setToSubmit();
		$imfieldset->addContent($imadd->show());
		$imform->addToForm($imfieldset->show());
		$imform->addToForm($this->objIMButton->show());
		$imform = $imform->show();
		if ($featurebox == TRUE) {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_importblog", "blog") , $imform);
			return $ret;
		} else {
			return $imform;
		}
	}
	/**
     * Method to show a mail setup form to set the DSN for mail2blog
     *
     * @param  bool   $featurebox
     * @param  array  $dsnarr    
     * @return string
     */
	public function showMailSetup($featurebox = TRUE, $dsnarr = NULL)
	{
		if ($this->mail2blog == FALSE) {
			return NULL;
		}
		// start a form to go back to the setupmail action with the vars
		// make sure that all form vars are required!
		$mform = new form('setupmail', $this->uri(array(
		'action' => 'setupmail'
		)));
		// add all the rules
		$mform->addRule('mprot', $this->objLanguage->languageText("mod_blog_phrase_mprotreq", "blog") , 'required');
		$mform->addRule('mserver', $this->objLanguage->languageText("mod_blog_phrase_mserverreq", "blog") , 'required');
		$mform->addRule('muser', $this->objLanguage->languageText("mod_blog_phrase_muserreq", "blog") , 'required');
		$mform->addRule('mpass', $this->objLanguage->languageText("mod_blog_phrase_mpassreq", "blog") , 'required');
		$mform->addRule('mport', $this->objLanguage->languageText("mod_blog_phrase_mportreq", "blog") , 'required');
		$mform->addRule('mbox', $this->objLanguage->languageText("mod_blog_phrase_mboxreq", "blog") , 'required');
		$mfieldset = $this->getObject('fieldset', 'htmlelements');
		$mfieldset->setLegend($this->objLanguage->languageText('mod_blog_setupmail', 'blog'));
		$madd = $this->newObject('htmltable', 'htmlelements');
		$madd->cellpadding = 5;
		// mail protocol field
		// dropdown for the POP/IMAP Chooser
		$protdrop = new dropdown('mprot');
		$protdrop->addOption("pop3", $this->objLanguage->languageText("mod_blog_pop3", "blog"));
		$protdrop->addOption("imap", $this->objLanguage->languageText("mod_blog_imap", "blog"));
		$madd->startRow();
		$protlabel = new label($this->objLanguage->languageText('mod_blog_mailprot', 'blog') . ':', 'input_mprot');
		$madd->addCell($protlabel->show());
		$madd->addCell($protdrop->show());
		$madd->endRow();
		// Mail server field
		$madd->startRow();
		$mslabel = new label($this->objLanguage->languageText('mod_blog_mailserver', 'blog') . ':', 'input_mserver');
		$mserver = new textinput('mserver');
		if (isset($dsnarr['server'])) {
			$mserver->setValue($dsnarr['server']);
		}
		$madd->addCell($mslabel->show());
		$madd->addCell($mserver->show());
		$madd->endRow();
		// Mail user field
		$madd->startRow();
		$mulabel = new label($this->objLanguage->languageText('mod_blog_mailuser', 'blog') . ':', 'input_muser');
		$muser = new textinput('muser');
		if (isset($dsnarr['user'])) {
			$muser->setValue($dsnarr['user']);
		}
		$madd->addCell($mulabel->show());
		$madd->addCell($muser->show());
		$madd->endRow();
		// Mail password field
		$madd->startRow();
		$mplabel = new label($this->objLanguage->languageText('mod_blog_mailpass', 'blog') . ':', 'input_mpass');
		$mpass = new textinput('mpass');
		if (isset($dsnarr['pass'])) {
			$mpass->setValue($dsnarr['pass']);
		}
		$madd->addCell($mplabel->show());
		$madd->addCell($mpass->show());
		$madd->endRow();
		// mail port field
		// dropdown for the POP/IMAP port
		$portdrop = new dropdown('mport');
		$portdrop->addOption(110, $this->objLanguage->languageText("mod_blog_110", "blog"));
		$portdrop->addOption(143, $this->objLanguage->languageText("mod_blog_143", "blog"));
		$madd->startRow();
		$portlabel = new label($this->objLanguage->languageText('mod_blog_mailport', 'blog') . ':', 'input_mport');
		$madd->addCell($portlabel->show());
		$madd->addCell($portdrop->show());
		$madd->endRow();
		// Mailbox field
		$madd->startRow();
		$mblabel = new label($this->objLanguage->languageText('mod_blog_mailbox', 'blog') . ':', 'input_mbox');
		$mbox = new textinput('mbox');
		if (isset($dsnarr['mailbox'])) {
			$mserver->setValue($dsnarr['mailbox']);
		}
		$mbox->setValue("INBOX");
		$madd->addCell($mblabel->show());
		$madd->addCell($mbox->show());
		$madd->endRow();
		$this->objMButton = &new button($this->objLanguage->languageText('word_save', 'system'));
		$this->objMButton->setValue($this->objLanguage->languageText('word_save', 'system'));
		$this->objMButton->setToSubmit();
		$mfieldset->addContent($madd->show());
		$mform->addToForm($mfieldset->show());
		$mform->addToForm($this->objMButton->show());
		$mform = $mform->show();
		if ($featurebox == TRUE) {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_setupmail", "blog") , $mform);
			return $ret;
		}
	}
	/**
     * Method to display the link to blog admin on post login
     *
     * @param  bool   $featurebox
     * @return string
     */
	public function showAdminSection($featurebox = FALSE, $blogadmin = FALSE, $showOrHide = 'none')
	{
		// admin section
		if ($featurebox == FALSE) {
			$ret = "<em>" . $this->objLanguage->languageText("mod_blog_admin", "blog") . "</em><br />";
		} else {
			$ret = NULL;
		}
		// blog admin page
		$admin = new href($this->uri(array(
		'action' => 'blogadmin'
		)) , $this->objLanguage->languageText("mod_blog_blogadmin", "blog"));
		// profile
		$profile = new href($this->uri(array(
		'action' => 'setprofile'
		)) , $this->objLanguage->languageText("mod_blog_setprofile", "blog"));
		// blog importer
		$import = new href($this->uri(array(
		'action' => 'importblog'
		)) , $this->objLanguage->languageText("mod_blog_blogimport", "blog"));
		// mail setup
		if ($this->mail2blog == FALSE) {
			$mailsetup = NULL;
		} else {
			$mailsetup = new href($this->uri(array(
			'action' => 'setupmail'
			)) , $this->objLanguage->languageText("mod_blog_setupmail", "blog"));
		}
		// write new post link
		$newpost = new href($this->uri(array(
		'action' => 'blogadmin',
		'mode' => 'writepost'
		)) , $this->objLanguage->languageText("mod_blog_writepost", "blog"));
		// edit existing posts
		$editpost = new href($this->uri(array(
		'action' => 'blogadmin',
		'mode' => 'editpost'
		)) , $this->objLanguage->languageText("mod_blog_word_editposts", "blog"));
		// edit/create cats
		$editcats = new href($this->uri(array(
		'action' => 'blogadmin',
		'mode' => 'editcats'
		)) , $this->objLanguage->languageText("mod_blog_word_categories", "blog"));
		// add/delete RSS feeds
		$rssedits = new href($this->uri(array(
		'action' => 'rssedit'
		)) , $this->objLanguage->languageText("mod_blog_rssaddedit", "blog"));
		// add/delete RSS feeds
		$linksedits = new href($this->uri(array(
		'action' => 'linkeditor'
		)) , $this->objLanguage->languageText("mod_blog_linksaddedit", "blog"));
		// view all other blogs
		$addeditpages = new href($this->uri(array(
		'action' => 'setpage'
		)) , $this->objLanguage->languageText("mod_blog_blogpages", "blog"));
		// moderate comments
		$modcomms = new href($this->uri(array(
		'action' => 'moderate'
		), 'blogcomments') , $this->objLanguage->languageText("mod_blog_modcomms", "blog"));
		// view all other blogs
		$viewblogs = new href($this->uri(array(
		'action' => 'allblogs'
		)) , $this->objLanguage->languageText("mod_blog_viewallblogs", "blog"));
		// go back to your blog
		$viewmyblog = new href($this->uri(array(
		'action' => 'viewblog'
		)) , $this->objLanguage->languageText("mod_blog_viewmyblog", "blog"));
		// go to siteblog
		$siteblog = new href($this->uri(array(
		'action' => 'siteblog'
		)) , $this->objLanguage->languageText("mod_blog_viewmyblog", "blog"));
		if ($blogadmin == TRUE) {
			// build up a bunch of featureboxen and send em out
			// this will only happen with the front page (blogadmin template)
			$this->objUser = $this->getObject('user', 'security');
			if ($this->objUser->inAdminGroup($this->objUser->userId())) {
				$linksarr = array(
				$admin,
				$profile,
				$import,
				$mailsetup,
				$newpost,
				$editpost,
				$editcats,
				$rssedits,
				$linksedits,
				$viewblogs,
				$viewmyblog
				);
			} else {
				$linksarr = array(
				$admin,
				$profile,
				$import,
				$newpost,
				$editpost,
				$editcats,
				$rssedits,
				$linksedits,
				$viewblogs,
				$viewmyblog
				);
			}
			foreach($linksarr as $links) {
				$objFeatureBox = $this->newObject('featurebox', 'navigation');
				$ret.= $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_admin", "blog") , $links->show());
			}
			return $ret;
		} else {
			// build the links
			$this->objUser = $this->getObject('user', 'security');
			if ($this->objUser->inAdminGroup($this->objUser->userId())) {
				if ($this->mail2blog == FALSE) {
					$topper = $newpost->show() . "<br />" . $editpost->show() . "<br />" . $viewmyblog->show();
					$ret.= $admin->show() . "<br />" . $profile->show() . "<br />" . $import->show() . "<br />" . $editcats->show() . "<br />" . $rssedits->show() . "<br />" . $linksedits->show() . "<br />" . $addeditpages->show() . "<br />" . $modcomms->show() . "<br />" . $viewblogs->show();
				} else {
					$topper = $newpost->show() . "<br />" . $editpost->show() . "<br />" . $viewmyblog->show();
					$ret.= $admin->show() . "<br />" . $profile->show() . "<br />" . $import->show() . "<br />" . $mailsetup->show() . "<br />" . $editcats->show() . "<br />" . $rssedits->show() . "<br />" . $linksedits->show() . "<br />" . $addeditpages->show() . "<br />" . $modcomms->show() . "<br />" . $viewblogs->show();
				}
			} else {
				$topper = $newpost->show() . "<br />" . $editpost->show() . "<br />" . $viewmyblog->show();
				$ret.= $admin->show() . "<br />" . $profile->show() . "<br />" . $import->show() . "<br />" . $editcats->show() . "<br />" . $rssedits->show() . "<br />" . $linksedits->show() . "<br />" . $addeditpages->show() . "<br />" . $modcomms->show() . "<br />" . $viewblogs->show();
			}
		}
		if ($featurebox == FALSE) {
			return $ret;
		} else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			/* scriptaculous moved to default page template / no need to suppress XML*/
			// $this->setVar('pageSuppressXML',true);
			$objIcon = &$this->getObject('geticon', 'htmlelements');
			$objIcon->setIcon('toggle');
			$str = "<a href=\"javascript:;\" onclick=\"Effect.toggle('adminmenu','slide', adjustLayout());\">" . $this->objLanguage->languageText("mod_blog_moreoptions", "blog") . "</a>";
			// $objIcon->show() . "</a>";
			$str.= '<div id="adminmenu"  style="width:170px;overflow: hidden;display:' . $showOrHide . ';"> ';
			$str.= $ret;
			$str.= '</div>';
			$box = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_admin", "blog") , $topper . "<br />" . $str);
			return $box;
		}
	}
	/**
     * Method to display the posts per user
     *
     * @param  array  $posts
     * @return string
     */
	public function showPosts($posts, $showsticky = FALSE)
	{
		$mm = $this->getObject('parse4mindmap', 'filters');
		$this->objComments = &$this->getObject('commentapi', 'blogcomments');
		$this->objTB = $this->getObject("trackback");
		// set the trackback options
		$tboptions = array(
		// Options for trackback class
		'strictness' => 1,
		'timeout' => 30, // seconds
		'fetchlines' => 30,
		'fetchextra' => true,
		// Options for HTTP_Request class
		'httprequest' => array(
		'allowRedirects' => true,
		'maxRedirects' => 2,
		'method' => 'GET',
		'useragent' => 'Chisimba',
		) ,
		);
		$ret = NULL;
		// Middle column (posts)!
		// break out the ol featurebox...
		if (!empty($posts)) {
			foreach($posts as $post) {
				// get the washout class and parse for all the bits and pieces
				$washer = $this->getObject('washout', 'utilities');
				$post['post_content'] = $washer->parseText($post['post_content']);
				$objFeatureBox = $this->getObject('featurebox', 'navigation');
				// build the top level stuff
				$dt = date('r', $post['post_ts']);
				$this->objUser = $this->getObject('user', 'security');
				$userid = $this->objUser->userId();
				if ($showsticky == FALSE) {
					if ($post['stickypost'] == 1) {
						unset($post);
						continue;
					}
				}
				if ($post['stickypost'] == 1) {
					$objStickyIcon = $this->newObject('geticon', 'htmlelements');
					$objStickyIcon->setIcon('sticky_yes');
					$headLink = new href($this->uri(array(
					'action' => 'viewsingle',
					'postid' => $post['id'],
					'userid' => $post['userid']
					)) , stripslashes($post['post_title']) , NULL);
					$head = $objStickyIcon->show() . $headLink->show() . "<br />" . $dt;
				} else {
					$headLink = new href($this->uri(array(
					'action' => 'viewsingle',
					'postid' => $post['id'],
					'userid' => $post['userid']
					)) , stripslashes($post['post_title']) , NULL);
					$head = $headLink->show() . "<br />" . $dt."<br />";
					// .'<script src="http:// digg.com/tools/diggthis.js" type="text/javascript"></script>';
				}
				// dump in the post content and voila! you have it...
				// build the post content plus comment count and stats???
				// do the BBCode Parsing
				try {
					$this->bbcode = $this->getObject('bbcodeparser', 'utilities');
				}
				catch(customException $e) {
					customException::cleanUp();
				}
				// $post['post_content'] = stripslashes($this->bbcode->parse4bbcode($post['post_content']));
				$this->cleaner = $this->newObject('htmlcleaner', 'utilities');
				// set up the trackback link
				$bloggerprofile = $this->objDbBlog->checkProfile($this->objUser->userId());
				if (isset($bloggerprofile['blog_name'])) {
					$blog_name = $bloggerprofile['blog_name'];
					// $this->getParam('blog_name');

				} else {
					if ($this->showfullname == 'FALSE') {
						$blog_name = $this->objUser->userName($userid);
					} else {
						$blog_name = $this->objUser->fullname($userid);
					}
				}
				// $blog_name = $this->objUser->fullname($userid);
				$url = $this->uri(array(
				'action' => 'randblog',
				'userid' => $userid,
				'module' => 'blog'
				));
				$trackback_url = $this->uri(array(
				'action' => 'tbreceive',
				'userid' => $post['userid'],
				'module' => 'blog',
				'postid' => $post['id']
				));
				$pdfurl = $this->uri(array(
				'action' => 'makepdf',
				'userid' => $post['userid'],
				'module' => 'blog',
				'postid' => $post['id']
				));
				$extra = NULL;
				$tbdata = array(
				'id' => $post['id'],
				'title' => $post['post_title'],
				'excerpt' => $post['post_excerpt'],
				'blog_name' => $blog_name,
				'url' => $url,
				'trackback_url' => $trackback_url,
				'extra' => $extra
				);
				$this->objTB->setup($tbdata, $tboptions);
				$linktxt = $this->objLanguage->languageText("mod_blog_trackbackurl", "blog");
				$tburl = new href($trackback_url, $linktxt, NULL);
				$tburl = $tburl->show();
				// set up the link to SEND a trackback
				if (isset($bloggerprofile['blog_name'])) {
					$blog_name = $bloggerprofile['blog_name'];
					// $this->getParam('blog_name');

				} else {
					if ($this->showfullname == 'FALSE') {
						$blog_name = $this->objUser->userName($userid);
					} else {
						$blog_name = $this->objUser->fullname($userid);
					}
				}
				// $blog_name = $this->objUser->fullname($userid);
				$sender = $this->uri(array(
				'action' => 'tbsend',
				'userid' => $post['userid'],
				'module' => 'blog',
				'postid' => $post['id'],
				'blog_name' => $blog_name,
				'title' => $post['post_title'],
				'excerpt' => $post['post_excerpt'],
				'url' => urlencode($this->uri(array(
				'action' => 'viewsingle',
				'userid' => $post['userid'],
				'module' => 'blog',
				'postid' => $post['id']
				))) ,
				));
				$sendtblink = new href($sender, $this->objLanguage->languageText("mod_blog_sendtrackback", "blog") , NULL);
				$sendtblink = $sendtblink->show();
				$bmurl = $this->uri(array(
				'action' => 'viewsingle',
				'userid' => $post['userid'],
				'module' => 'blog',
				'postid' => $post['id']
				));
				$bmurl = urlencode($bmurl);
				$bmlink = "http:// www.addthis.com/bookmark.php?pub=&amp;url=" . $bmurl . "&amp;title=" . urlencode(addslashes(htmlentities($post['post_title'])));
				$bmtext = '<img src="http:// www.addme.com/images/button1-bm.gif" width="125" height="16" border="0" alt="' . $this->objLanguage->languageText("mod_blog_bookmarkpost", "blog") . '"/>';
				// $this->objLanguage->languageText("mod_blog_bookmarkpost", "blog");
				$bookmark = new href($bmlink, $bmtext, NULL);
				// grab the number of trackbacks per post
				$pid = $post['id'];
				$numtb = $this->objDbBlog->getTrackbacksPerPost($pid);
				if ($numtb != 0) {
					$numtblnk = new href($this->uri(array(
					'module' => 'blog',
					'action' => 'viewsingle',
					'mode' => 'viewtb',
					'postid' => $pid,
					'userid' => $post['userid']
					)) , $this->objLanguage->languageText("mod_blog_vtb", "blog") , NULL);
					// $numtb, NULL);
					$numtb = $numtblnk->show();
				} else {
					$numtb = $this->objLanguage->languageText("mod_blog_trackbacknotrackback", "blog");
				}
				// do the cc licence part
				// do the cc licence part
				$cclic = $post['post_lic'];
				// get the lic that matches from the db
				$this->objCC = $this->getObject('displaylicense', 'creativecommons');
				if ($cclic == '') {
					$cclic = 'copyright';
				}
				$iconList = $this->objCC->show($cclic);
				// $commentLink = $this->objComments->addCommentLink($type = NULL);
				if ($post['comment_status'] == 'Y' || $post['comment_status'] == 'on') {
					$commentCount = $this->objComments->getCount($post['id']);
				}
				// edit icon in a table 1 row x however number of things to do
				if ($post['userid'] == $userid) {
					$tburl = $tburl . "<br />" . $numtb . "<br />" . $sendtblink;
					$this->objIcon = &$this->getObject('geticon', 'htmlelements');
					$edIcon = $this->objIcon->getEditIcon($this->uri(array(
					'action' => 'postedit',
					'id' => $post['id'],
					'module' => 'blog'
					)));
					// Set the table name
					$tbl = $this->newObject('htmltable', 'htmlelements');
					$tbl->cellpadding = 3;
					$tbl->width = "100%";
					$tbl->align = "center";
					// set up the header row
					$tbl->startHeaderRow();
					$tbl->addHeaderCell('');
					// $this->objLanguage->languageText("mod_blog_editpost", "blog"));
					// edit
					$tbl->addHeaderCell('');
					// $this->objLanguage->languageText("mod_blog_bookmarkpost", "blog"));
					// bookmark
					if ($post['comment_status'] == 'Y' || $post['comment_status'] == 'on') {
						$tbl->addHeaderCell('');
						// $this->objLanguage->languageText("mod_blog_leavecomment", "blog"));
						// comments

					}
					$tbl->addHeaderCell('');
					// $this->objLanguage->languageText("mod_blog_trackbackurl", "blog"));
					// trackback
					$tbl->addHeaderCell('');
					// $this->objLanguage->languageText("mod_blog_cclic", "blog"));
					// Licence
					$tbl->addHeaderCell('');
					// save as pdf
					$tbl->endHeaderRow();
					$tbl->startRow();
					$tbl->addCell($edIcon);
					// edit icon
					$tbl->addCell($bookmark->show());
					// bookmark link(s)
					if ($post['comment_status'] == 'Y' || $post['comment_status'] == 'on') {
						$tbl->addCell($this->setComments($post, FALSE) . " " . $commentCount);
						// $commentLink);
						// comment link(s)

					}
					$tbl->addCell($tburl);
					// trackback URL
					$tbl->addCell($iconList);
					// cc licence
					$pdficon = $this->newObject('geticon', 'htmlelements');
					$pdficon->setIcon('filetypes/pdf');
					$lblView = $this->objLanguage->languageText("mod_blog_saveaspdf", "blog");
					$pdficon->alt = $lblView;
					$pdficon->align = false;
					$pdfimg = $pdficon->show();
					$pdflink = new href($pdfurl, $pdfimg, NULL);
					// and the mail to a friend icon
					$mtficon = $this->newObject('geticon', 'htmlelements');
					$mtficon->setIcon('filetypes/eml');
					$lblmtf = $this->objLanguage->languageText("mod_blog_mailtofriend", "blog");
					$mtficon->alt = $lblmtf;
					$mtficon->align = false;
					$mtfimg = $mtficon->show();
					$mtflink = new href($this->uri(array(
					'action' => 'mail2friend',
					'postid' => $post['id'],
					'bloggerid' => $post['userid']
					)) , $mtfimg, NULL);
					if ($post['showpdf'] == '1' || $post['showpdf'] == 'on') {
						$tbl->addCell($pdflink->show() . $mtflink->show());
					}
					$tbl->endRow();
					// echo $this->objTB->autodiscCode();
					// tack the tags onto the end of the post content...
					$thetags = $this->objDbBlog->getPostTags($post['id']);
					$linkstr = NULL;
					foreach($thetags as $tags) {
						$link = new href($this->uri(array(
						'action' => 'viewblogbytag',
						'userid' => $userid,
						'tag' => $tags['meta_value']
						)) , $tags['meta_value']);
						$linkstr.= $link->show();
						$link = NULL;
					}
					if (empty($linkstr)) {
						$linkstr = $this->objLanguage->languageText("mod_blog_word_notags", "blog");
					}
					$fboxcontent = $post['post_content'] . $this->cleaner->cleanHtml("<br /><hr />" . "<center><em><b>" . $this->objLanguage->languageText("mod_blog_word_tags4thispost", "blog") . "</b><br />" . $linkstr . "</em><hr />" . "<center>" . $tbl->show() . "</center>");
					$ret.= $objFeatureBox->showContent($head, $fboxcontent);
				} else {
					// table of non logged in options
					// Set the table name
					$tblnl = $this->newObject('htmltable', 'htmlelements');
					$tblnl->cellpadding = 3;
					$tblnl->width = "100%";
					$tblnl->align = "center";
					// set up the header row
					$tblnl->startHeaderRow();
					$tblnl->addHeaderCell('');
					// $this->objLanguage->languageText("mod_blog_bookmarkpost", "blog"));
					// bookmark
					$tblnl->addHeaderCell('');
					// $this->objLanguage->languageText("mod_blog_trackbackurl", "blog"));
					// trackback
					if ($post['comment_status'] == 'Y' || $post['comment_status'] == 'on') {
						$tblnl->addHeaderCell('');
						// $this->objLanguage->languageText("mod_blog_leavecomment", "blog"));

					}
					$tblnl->addHeaderCell('');
					// $this->objLanguage->languageText("mod_blog_cclic", "blog"));
					// Licence
					$tblnl->addHeaderCell('');
					$tblnl->endHeaderRow();
					$tblnl->startRow();
					$tblnl->addCell($bookmark->show());
					// bookmark link(s)
					$tblnl->addCell($tburl . "&nbsp;" . $numtb);
					// trackback URL
					if ($post['comment_status'] == 'Y' || $post['comment_status'] == 'on') {
						$tblnl->addCell($this->setComments($post, FALSE) . " " . $commentCount);
					}
					$tblnl->addCell($iconList);
					// cc licence
					$pdficon = $this->newObject('geticon', 'htmlelements');
					$pdficon->setIcon('filetypes/pdf');
					$lblView = $this->objLanguage->languageText("mod_blog_saveaspdf", "blog");
					$pdficon->alt = $lblView;
					$pdficon->align = false;
					$pdfimg = $pdficon->show();
					$pdflink = new href($pdfurl, $pdfimg, NULL);
					// and the mail to a friend icon
					$mtficon = $this->newObject('geticon', 'htmlelements');
					$mtficon->setIcon('filetypes/eml');
					$lblmtf = $this->objLanguage->languageText("mod_blog_mailtofriend", "blog");
					$mtficon->alt = $lblmtf;
					$mtficon->align = false;
					$mtfimg = $mtficon->show();
					$mtflink = new href($this->uri(array(
					'action' => 'mail2friend',
					'postid' => $post['id'],
					'bloggerid' => $post['userid']
					)) , $mtfimg, NULL);
					$tblnl->addCell($pdflink->show() . $mtflink->show());
					// pdf icon
					$tblnl->endRow();
					// echo $this->objTB->autodiscCode();
					// tack the tags onto the end of the post content...
					$thetags = $this->objDbBlog->getPostTags($post['id']);
					$linkstr = NULL;
					foreach($thetags as $tags) {
						$link = new href($this->uri(array(
						'action' => 'viewblogbytag',
						'userid' => $userid,
						'tag' => $tags['meta_value']
						)) , $tags['meta_value']);
						$linkstr.= $link->show();
						$link = NULL;
					}
					if (empty($linkstr)) {
						$linkstr = $this->objLanguage->languageText("mod_blog_word_notags", "blog");
					}
					$ret.= $objFeatureBox->showContent($head, /*$this->cleaner->cleanHtml(*/
					$post['post_content']) . "<center>" . $tblnl->show() . "</center>" /*)*/;
				}
			}
		} else {
			$ret = FALSE;
			// "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_noposts", "blog") . "</center></em></h1>";

		}
		return $ret;
	}
	/**
     * Function addCommentForm
     *
     */
	public function addCommentForm($postid, $userid, $captcha = FALSE, $comment = NULL, $useremail = NULL)
	{
		$this->objComApi = $this->getObject('commentapi', 'blogcomments');
		return $this->objComApi->commentAddForm($postid, 'blog', 'tbl_blog_posts', $userid, TRUE, TRUE, FALSE, $captcha, $comment, $useremail);
	}
	/**
     *
     */
	public function setComments($post, $icon = TRUE)
	{
		// COMMENTS
		if ($icon == TRUE) {
			$objLink = new link($this->uri(array(
			'action' => 'viewsingle',
			'postid' => $post['id'],
			'userid' => $post['userid']
			) , 'blog'));
			$comment_icon = $this->newObject('geticon', 'htmlelements');
			$comment_icon->setIcon('comment');
			$lblView = $this->objLanguage->languageText("mod_blog_addcomment", "blog");
			$comment_icon->alt = $lblView;
			$comment_icon->align = false;
			$objLink->link = $comment_icon->show();
			return $objLink->show();
		} else {
			$objLink = new href($this->uri(array(
			'action' => 'viewsingle',
			'postid' => $post['id'],
			'userid' => $post['userid']
			)) , $this->objLanguage->languageText("mod_blog_comments", "blog") , NULL);
			return $objLink->show();
		}
	}
	/**
     * Method to build and create the feeds options box
     *
     * @param      integer $userid    
     * @param      bool    $featurebox
     * @return     string 
     * @deprecated - old method
     */
	public function showFeeds($userid, $featurebox = FALSE, $showOrHide = 'none')
	{
		$this->objUser = $this->getObject('user', 'security');
		$leftCol = NULL;
		if ($featurebox == FALSE) {
			$leftCol.= "<em>" . $this->objLanguage->languageText("mod_blog_feedheader", "blog") . "</em><br />";
		}
		// RSS2.0
		$rss2 = $this->getObject('geticon', 'htmlelements');
		$rss2->align = "top";
		$rss2->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array(
		'action' => 'feed',
		'format' => 'rss2',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_word_rss2", "blog"));
		$rss2feed = $rss2->show() . $link->show() . "<br />";
		// RSS0.91
		$rss091 = $this->getObject('geticon', 'htmlelements');
		$rss091->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array(
		'action' => 'feed',
		'format' => 'rss091',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_word_rss091", "blog"));
		$leftCol.= $rss091->show() . $link->show() . "<br />";
		// RSS1.0
		$rss1 = $this->getObject('geticon', 'htmlelements');
		$rss1->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array(
		'action' => 'feed',
		'format' => 'rss1',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_word_rss1", "blog"));
		$leftCol.= $rss1->show() . $link->show() . "<br />";
		// PIE
		$pie = $this->getObject('geticon', 'htmlelements');
		$pie->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array(
		'action' => 'feed',
		'format' => 'pie',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_word_pie", "blog"));
		$leftCol.= $pie->show() . $link->show() . "<br />";
		// MBOX
		$mbox = $this->getObject('geticon', 'htmlelements');
		$mbox->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array(
		'action' => 'feed',
		'format' => 'mbox',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_word_mbox", "blog"));
		$leftCol.= $mbox->show() . $link->show() . "<br />";
		// OPML
		$opml = $this->getObject('geticon', 'htmlelements');
		$opml->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array(
		'action' => 'feed',
		'format' => 'opml',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_word_opml", "blog"));
		$leftCol.= $opml->show() . $link->show() . "<br />";
		// ATOM
		$atom = $this->getObject('geticon', 'htmlelements');
		$atom->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array(
		'action' => 'feed',
		'format' => 'atom',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_word_atom", "blog"));
		$atomfeed = $atom->show() . $link->show() . "<br />";

		// Plain HTML
		$html = $this->getObject('geticon', 'htmlelements');
		$html->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array(
		'action' => 'feed',
		'format' => 'html',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_word_html", "blog"));

		// Comment RSS2.0
		$rss2comm = $this->getObject('geticon', 'htmlelements');
		$rss2comm->align = "top";
		$rss2comm->setIcon('rss', 'gif', 'icons/filetypes');
		$link = new href($this->uri(array(
		'action' => 'commentfeed',
		'format' => 'rss2',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_word_commentrss2", "blog"));
		$rss2comments = $rss2comm->show() . $link->show() . "<br />";
		$leftCol.= $html->show() . $link->show() . "<br />";
		/* scriptaculous moved to default page template / no need to suppress XML*/
		// $this->setVar('pageSuppressXML',true);
		$objIcon = &$this->getObject('geticon', 'htmlelements');
		$objIcon->setIcon('toggle');
		$str = "<a href=\"javascript:;\" onclick=\"Effect.toggle('feedmenu','slide', adjustLayout());\">" . $this->objLanguage->languageText("mod_blog_moreoptions", "blog")."</a>";
		// $objIcon->show() . "</a>";
		$topper = $rss2feed . $atomfeed;
		$str.= '<div id="feedmenu"  style="width:170px;overflow: hidden;display:' . $showOrHide . ';"> ';
		$str.= $leftCol;
		$str.= '</div>';
		if ($featurebox == FALSE) {
			return $str;
		} else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_feedheader", "blog") , $topper . "<br />" . $str);
			return $ret;
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
     * Method to quick add a post to the posts table
     *
     * @param integer $userid 
     * @param array   $postarr
     * @param string  $mode   
     */
	public function quickPostAdd($userid, $postarr, $mode = NULL)
	{
		// check the sysconfig as to whether we should enable the google ping
		$this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
		$this->googleBlogPing = $this->objSysConfig->getValue('ping_google', 'blog');
		if ($this->googleBlogPing == 'TRUE') {
			$this->pingGoogle($userid);
		}
		if (!empty($postarr)) {
			if ($mode == NULL) {
				$this->objDbBlog->insertPost($userid, $postarr);
			} else {
				$this->objDbBlog->insertPost($userid, $postarr, $mode);
			}
		}
	}
	/**
     * Method to scrub grubby html
     *
     * @param  string $document
     * @return string
     */
	function html2txt($document, $scrub = TRUE)
	{
		if ($scrub == TRUE) {
			$search = array(
			'@<script[^>]*?>.*?</script>@si', // Strip out javascript
			/*'@<[\/\!]*?[^<>]*?>@si',*/
			// Strip out HTML tags
			/*'@<style[^>]*?>.*?</style>@siU',*/
			// Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'
			// Strip multi-line comments including CDATA

			);
		} else {
			$search = array(
			'@<script[^>]*?>.*?</script>@si', // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
			/*'@<style[^>]*?>.*?</style>@siU',*/
			// Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@', // Strip multi-line comments including CDATA
			'!(\n*(.+)\n*!x', // Strip out newlines

			);
		}
		$text = preg_replace($search, '', $document);
		$text = str_replace("<br /><br />", "", $text);
		$text = str_replace("<br />  <br />", "<br />", $text);
		$text = str_replace("<br />", "\n", $text);
		$text = str_replace("<br\">", "\n", $text);
		$text = str_replace("<", " <", $text);
		$text = str_replace(">", "> ", $text);
		$text = rtrim($text, "\n");
		return $text;
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
     * Method to display the posts editor in its entirety
     *
     * @param  integer $userid
     * @param  integer $editid
     * @param  string $defaultText Default Text to be populated in the Editor
     * @return boolean
     */
	public function postEditor($userid, $editid = NULL, $defaultText = NULL)
	{
		$this->loadClass('label', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$this->loadClass('form', 'htmlelements');
		// $this->loadClass('heading', 'htmlelements');
		$this->loadClass('href', 'htmlelements');
		$this->loadClass('htmlarea', 'htmlelements');
		if (isset($editid)) {
			$mode = 'editpost';
			// get the relevant post from the editid
			$editparams = $this->objDbBlog->getPostById($editid);
			if (!empty($editparams)) {
				//               print_r($editparams);
				$editparams = $editparams[0];
				$editparams['tags'] = $this->objDbBlog->getPostTags($editid);
			}
		}
		if (!isset($mode)) {
			$mode = NULL;
		}
		if (!isset($editparams)) {
			$editparams = NULL;
		}
		$postform = new form('postadd', $this->uri(array(
		'action' => 'postadd',
		'mode' => $mode,
		'id' => $editparams['id'],
		'postexcerpt' => $editparams['post_excerpt'],
		'postdate' => $editparams['post_date']
		)));
		$pfieldset = $this->newObject('fieldset', 'htmlelements');
		$pfieldset->setLegend($this->objLanguage->languageText('mod_blog_posthead', 'blog'));
		$ptable = $this->newObject('htmltable', 'htmlelements');
		$ptable->cellpadding = 5;
		// post title field
		$ptable->startRow();
		$plabel = new label($this->objLanguage->languageText('mod_blog_posttitle', 'blog') . ':', 'input_posttitle');
		$title = new textinput('posttitle');
		$title->size = 60;
		$postform->addRule('posttitle', $this->objLanguage->languageText("mod_blog_phrase_ptitlereq", "blog") , 'required');
		if (isset($editparams['post_title'])) {
			$title->setValue(stripslashes($editparams['post_title']));
		}
		$ptable->addCell($plabel->show());
		$ptable->addCell($title->show());
		$ptable->endRow();
		// post category field
		// dropdown of cats
		$ptable->startRow();
		$pdlabel = new label($this->objLanguage->languageText('mod_blog_postcat', 'blog') . ':', 'input_cat');
		$pDrop = new dropdown('cat');
		if (isset($editparams['post_category'])) {
			$pDrop->addOption($editparams['post_category'], $editparams['post_category']);
			$pDrop->setSelected($editparams['post_category']);
			$pDrop->addOption(1, $this->objLanguage->languageText("mod_blog_defcat", "blog"));
		} else {
			$pDrop->addOption(0, $this->objLanguage->languageText("mod_blog_defcat", "blog"));
		}
		$pcats = $this->objDbBlog->getAllCats($userid);
		foreach($pcats as $adds) {
			$pDrop->addOption($adds['id'], stripslashes($adds['cat_name']));
		}
		$ptable->addCell($pdlabel->show());
		$ptable->addCell($pDrop->show());
		$ptable->endRow();
		// post status dropdown
		$ptable->startRow();
		$pslabel = new label($this->objLanguage->languageText('mod_blog_poststatus', 'blog') . ':', 'input_status');
		$psDrop = new dropdown('status');
		$psDrop->addOption(0, $this->objLanguage->languageText("mod_blog_published", "blog"));
		$psDrop->addOption(1, $this->objLanguage->languageText("mod_blog_draft", "blog"));
		// $psDrop->addOption(2, $this->objLanguage->languageText("mod_blog_hidden","blog"));
		$ptable->addCell($pslabel->show());
		$ptable->addCell($psDrop->show());
		$ptable->endRow();
		// allow comments?
		$this->loadClass("checkbox", "htmlelements");
		$commentsallowed = new checkbox('commentsallowed', $this->objLanguage->languageText("mod_blog_word_yes", "blog") , true);
		$ptable->startRow();
		$pcomlabel = new label($this->objLanguage->languageText('mod_blog_commentsallowed', 'blog') . ':', 'input_commentsallowed');
		$ptable->addCell($pcomlabel->show());
		$ptable->addCell($commentsallowed->show());
		$ptable->endRow();
		// Sticky post?
		$this->loadClass("checkbox", "htmlelements");
		if (isset($editparams['stickypost']) && $editparams['stickypost'] == 1) {
			$sticky = new checkbox('stickypost', 1, TRUE);
		} else {
			$sticky = new checkbox('stickypost', 1, FALSE);
		}
		$ptable->startRow();
		$pstickylabel = new label($this->objLanguage->languageText('mod_blog_stickypost', 'blog') . ':', 'input_stickypost');
		$ptable->addCell($pstickylabel->show());
		$ptable->addCell($sticky->show());
		$ptable->endRow();
		// show as a PDF?
		$this->loadClass("checkbox", "htmlelements");
		if (isset($editparams['showpdf']) && $editparams['showpdf'] == 1) {
			$showpdf = new checkbox('showpdf', 1, TRUE);
		} else {
			$showpdf = new checkbox('showpdf', 1, FALSE);
		}
		$ptable->startRow();
		$showpdflabel = new label($this->objLanguage->languageText('mod_blog_showpdf', 'blog') . ':', 'input_showpdf');
		$ptable->addCell($showpdflabel->show());
		$ptable->addCell($showpdf->show());
		$ptable->endRow();
		// post excerpt
		$this->loadClass('textarea', 'htmlelements');
		$pexcerptlabel = new label($this->objLanguage->languageText('mod_blog_postexcerpt', 'blog') . ':', 'input_postexcerpt');
		$pexcerpt = new textarea('postexcerpt');
		$pexcerpt->setName('postexcerpt');
		$ptable->startRow();
		if (isset($editparams['post_excerpt'])) {
			$pexcerpt->setcontent(stripslashes($editparams['post_excerpt']));
			// nl2br - htmmlentittes +

		}
		$ptable->addCell($pexcerptlabel->show());
		$ptable->addCell($pexcerpt->show());
		$ptable->endRow();
		// post content
		$pclabel = new label($this->objLanguage->languageText('mod_blog_pcontent', 'blog') . ':', 'input_postcontent');
		$pcon = $this->newObject('htmlarea', 'htmlelements');
		$pcon->setName('postcontent');
		$pcon->height = 400;
		$pcon->width = 420;
		$pcon->setDefaultToolbarSet();
		if (isset($editparams['post_content'])) {
			$pcon->setcontent((stripslashes(($editparams['post_content']))));
		} else if (!is_null($defaultText)) {
			$pcon->setcontent($defaultText);
		}
		$ptable->startRow();
		$ptable->addCell($pclabel->show());
		$ptable->addCell($pcon->showFCKEditor());
		$ptable->endRow();
		// tags input box
		$ptable->startRow();
		$tlabel = new label($this->objLanguage->languageText('mod_blog_tags', 'blog') . ':', 'input_tags');
		$tags = new textinput('tags');
		$tags->size = 65;
		if (isset($editparams['tags'])) {
			// this thing should be an array, so we need to loop thru and create the comma sep list again
			$tagstr = NULL;
			foreach($editparams['tags'] as $taglets) {
				$tagstr.= $taglets['meta_value'] . ",";
			}
			$tags->setValue(stripslashes($tagstr));
		}
		$ptable->addCell($tlabel->show());
		$ptable->addCell($tags->show());
		$ptable->endRow();
		// CC licence
		$lic = $this->getObject('licensechooser', 'creativecommons');
		$ptable->startRow();
		$pcclabel = new label($this->objLanguage->languageText('mod_blog_cclic', 'blog') . ':', 'input_cclic');
		$ptable->addCell($pcclabel->show());
		if (isset($editparams['post_lic'])) {
			$lic->defaultValue = $editparams['post_lic'];
		}
		$ptable->addCell($lic->show());
		$ptable->endRow();
		$ts = new textinput('post_ts', NULL, 'hidden', NULL);
		// $ts->extra = "hidden";
		if (isset($editparams['post_ts'])) {
			$ts->setValue($editparams['post_ts']);
		}
		$postform->addRule('posttitle', $this->objLanguage->languageText("mod_blog_phrase_ptitlereq", "blog") , 'required');
		// $postform->addRule('postcontent', $this->objLanguage->languageText("mod_blog_phrase_pcontreq", "blog"),'required');
		$pfieldset->addContent($ptable->show());
		$postform->addToForm($pfieldset->show() . $ts->show());
		$this->objPButton = &new button($this->objLanguage->languageText('mod_blog_word_post', 'blog'));
		$this->objPButton->setValue($this->objLanguage->languageText('mod_blog_word_post', 'blog'));
		$this->objPButton->setToSubmit();
		// $postform->addToForm($this->objPButton->show());
		// $postform = $postform->show();
		// return $postform;
		// check box Added By Irshaad Hoosain
		$this->loadClass('checkbox', 'htmlelements');
		$siteblogcheckbox = new checkbox('checkbox');
		// ,'unassign',false);
		$siteblogcheckbox = $siteblogcheckbox->show();
		// IS Admin
		$siteblogcheckbox = new checkbox('checkbox');
		// ,'unassign',false);
		$siteblogcheckbox = $siteblogcheckbox->show();
		// IS Admin
		$this->objUser = $this->getObject('user', 'security');
		if ($this->objUser->inAdminGroup($userid, 'Site Admin')) {
			$postform->addToForm('Site Blog' . ' ' . $siteblogcheckbox);
		} else {
		}
		$postform->addToForm('<br>' . ' ' . '</br>');
		$postbutton_text = $this->objPButton->show();
		$postform->addToForm($postbutton_text);
		$postform = $postform->show();
		return $postform;
	}
	/**
     * Method to get the archiveed posts array for manipulation
     *
     * @param  string  $userid
     * @return array  
     * @access private
     */
	private function _archiveArr($userid)
	{
		// add in a foreach for each year
		$allposts = $this->objDbBlog->getAbsAllPosts($userid);
		// print_r($allposts);
		$revposts = array_reverse($allposts);
		$recs = count($revposts);
		if ($recs > 0) {
			$recs = $recs-1;
		}
		if (!empty($revposts)) {
			// echo count($revposts);
			$lastrec = $revposts[$recs]['post_ts'];
			$firstrec = $revposts[0]['post_ts'];
			$c1 = date("ym", $firstrec);
			$c2 = date("ym", $lastrec);
			$startdate = date("m", $firstrec);
			$enddate = date("m", $lastrec);
			// . " " .date("Y", $lastrec);
			// create a while loop to get all the posts between start and end dates
			$postarr = array();
			// echo $c1, $c2;
			// echo $startdate, $enddate;
			foreach($revposts as $themonths) {
				$months[] = date("ym", $themonths['post_ts']);
				$posts = array();
				// $this->objDbBlog->getPostsMonthly(mktime(0, 0, 0, date("m",$themonths['post_ts']), 1, date("y", $themonths['post_ts'])) , $userid);
				$postarr[date("Ym", $themonths['post_ts']) ] = $posts;
			}
			return $postarr;
		} else {
			return NULL;
		}
	}
	/**
     * Method to produce the archived posts box
     *
     * @param  string $userid    
     * @param  objetc $featurebox
     * @return string
     */
	public function archiveBox($userid, $featurebox = FALSE, $showOrHide = 'none')
	{
		// get the posts for each month
		$posts = $this->_archiveArr($userid);
		// print_r($posts);die();
		if (!empty($posts)) {
			$yearmonth = array_keys($posts);
			$arks = NULL;
			foreach($yearmonth as $months) {
				$month = str_split($months, 4);
				$thedate = mktime(0, 0, 0, intval($month[1]) , 1, intval($month[0]));
				$arks[] = array(
				'formatted' => date("F", $thedate) . " " . date("Y", $thedate) ,
				'raw' => $month[1],
				'rfc' => $thedate
				);
			}
			$thismonth = mktime(0, 0, 0, date("m", time()) , 1, date("y", time()));
			if ($featurebox == FALSE) {
				return $thismonth;
			} else {
				$objFeatureBox = $this->getObject('featurebox', 'navigation');
				$lnks = NULL;
				foreach($arks as $ark) {
					$lnk = new href($this->uri(array(
					'module' => 'blog',
					'action' => 'showarchives',
					'month' => $ark['raw'],
					'year' => $ark['rfc'],
					'userid' => $userid
					)) , $ark['formatted']);
					$lnks.= $lnk->show() . "<br />";
				}
				// $str = "<a href=\"javascript:;\" onclick=\"Effect.toggle('archivemenu','slide', adjustLayout());\">[...]</a>";
				// $str .='<div id="archivemenu"  style="width:170px;overflow: hidden;display:'.$showOrHide.';"> ';
				// $str .= $lnks;
				// $str .= '</div>';
				$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_archives", "blog") , $lnks, 'arkbox', 'none');
				return $ret;
			}
		} else {
			return NULL;
		}
	}
	/**
     * Method to edit and manage posts
     *
     * @param  integer $userid
     * @return string 
     */
	public function managePosts($userid, $month = NULL, $year = NULL)
	{
		// create a table with the months posts, plus a dropdown of all months to edit
		// put the edit icon at the end of each row, with text linked to the postEditor() method
		// create an array with keys: cat, excerpt, title, content, catid for edit
		// start the edit table
		$editform = new form('postedit', $this->uri(array(
		'action' => 'postedit'
		)));
		// $edfieldset = $this->newObject('fieldset', 'htmlelements');
		// $edfieldset->setLegend($this->objLanguage->languageText('mod_blog_posthead', 'blog'));
		$edtable = $this->newObject('htmltable', 'htmlelements');
		$edtable->cellpadding = 5;
		// grab the posts for this month
		// $posts = $this->objDbBlog->getPostsMonthly(mktime(0,0,0,date("m", time()), 1, date("y", time())), $userid);
		// change this to get from the form input rather
		if ($month == NULL && $year == NULL) {
			if ($this->objUser->inAdminGroup($userid)) {
				$posts = $this->objDbBlog->getAbsAllPostsWithSiteBlogs($userid);
			}
			$posts = $this->objDbBlog->getAbsAllPosts($userid);
		}
		$count = count($posts);
		// print_r($posts);
		// add in a table header...
		$edtable->startHeaderRow();
		$edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_posttitle", "blog"));
		$edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_postdate", "blog"));
		$edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_poststatus", "blog"));
		$edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_postcat", "blog"));
		$edtable->addHeaderCell($this->objLanguage->languageText("mod_blog_editdelete", "blog"));
		$edtable->endHeaderRow();
		foreach($posts as $post) {
		(($count%2) == 0) ? $oddOrEven = 'even' : $oddOrEven = 'odd';
		$edtable->row_attributes = " onmouseover=\"this.className='tbl_ruler';\" onmouseout=\"this.className='" . $oddOrEven . "'; \"";
		$edtable->startRow();
		$edtable->addCell($post['post_title']);
		$edtable->addCell(date('r', $post['post_ts']));
		// do some voodoo on the post status, so that it looks better
		switch ($post['post_status']) {
			case '0':
				$post['post_status'] = $this->objLanguage->languageText("mod_blog_published", "blog");
				break;

			case '1':
				$post['post_status'] = $this->objLanguage->languageText("mod_blog_draft", "blog");
				break;

			case '2':
				$post['post_status'] = $this->objLanguage->languageText("mod_blog_hidden", "blog");
				break;
		}
		$edtable->addCell($post['post_status']);
		// category voodoo
		if ($post['post_category'] == '0') {
			$post['post_category'] = $this->objLanguage->languageText("mod_blog_word_default", "blog");
		} else {
			$mapcats = $this->objDbBlog->mapKid2Parent($post['post_category']);
			if (isset($mapcats[0])) {
				$post['post_category'] = $mapcats[0]['cat_name'];
			}
		}
		$edtable->addCell($post['post_category']);
		// do the edit and delete icon
		$this->objIcon = &$this->getObject('geticon', 'htmlelements');
		$edIcon = $this->objIcon->getEditIcon($this->uri(array(
		'action' => 'postedit',
		'id' => $post['id'],
		'module' => 'blog'
		)));
		$delIcon = $this->objIcon->getDeleteIconWithConfirm($post['id'], array(
		'module' => 'blog',
		'action' => 'deletepost',
		'id' => $post['id']
		) , 'blog');
		// do the checkboxen for the multi delete.
		$this->loadClass('checkbox', 'htmlelements');
		$cbox = new checkbox('arrayList[]');
		$cbox->cssId = 'checkbox_' . $post['id'];
		$cbox->setValue($post['id']);
		$edtable->addCell($edIcon . $delIcon . $cbox->show());
		$edtable->endRow();
		}
		// submit button for multidelete
		$this->objdelButton = &new button('deleteposts');
		$this->objdelButton->setValue($this->objLanguage->languageText('mod_blog_word_deleteselected', 'blog'));
		$this->objdelButton->setToSubmit();
		$editform->addToForm($edtable->show());
		$editform->addToForm($this->objdelButton->show());
		$editform = $editform->show();
		return $editform;
	}
	/**
     * Method to add a quick post as a blocklet
     *
     * @param  integer $userid    
     * @param  bool    $featurebox
     * @return mixed  
     */
	public function quickPost($userid, $featurebox = FALSE)
	{
		// form for the quick poster blocklet
		$this->loadClass('textarea', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$qpform = new form('qpadd', $this->uri(array(
		'action' => 'postadd',
		'mode' => 'quickadd'
		)));
		$qpform->addRule('postcontent', $this->objLanguage->languageText("mod_blog_phrase_pcontreq", "blog") , 'required');
		$qptitletxt = $this->objLanguage->languageText("mod_blog_posttitle", "blog") . "<br />";
		$qptitle = new textinput('posttitle');
		// post content textarea
		$qpcontenttxt = $this->objLanguage->languageText("mod_blog_pcontent", "blog") . "<br />";
		$qpcontent = new textarea('postcontent');
		// $qpcontent->setName('postcontent');
		// $qpcontent->setBasicToolBar();
		// dropdown of cats
		$qpcattxt = $this->objLanguage->languageText("mod_blog_postcat", "blog") . "<br />";
		$qpDrop = new dropdown('cat');
		$qpDrop->addOption(0, $this->objLanguage->languageText("mod_blog_defcat", "blog"));
		// loop through the existing cats and make sure not to add a child to the dd
		$pcats = $this->objDbBlog->getAllCats($userid);
		foreach($pcats as $adds) {
			$qpDrop->addOption($adds['id'], $adds['cat_name']);
		}
		// set up the form elements so they fit nicely in a box
		$qptitle->size = 15;
		$qpcontent->cols = 15;
		$qpcontent->rows = 5;
		$qpform->addToForm($qptitletxt . $qptitle->show());
		$qpform->addToForm("<br />");
		$qpform->addToForm($qpcontenttxt . $qpcontent->show());
		$qpform->addToForm("<br />");
		$qpform->addToForm($qpcattxt . $qpDrop->show());
		$this->objqpCButton = &new button('blogit');
		$this->objqpCButton->setValue($this->objLanguage->languageText('mod_blog_word_blogit', 'blog'));
		$this->objqpCButton->setToSubmit();
		$qpform->addToForm($this->objqpCButton->show());
		$qpform = $qpform->show();
		if ($featurebox == FALSE) {
			return $qpform;
		} else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_qpdetails", "blog") , $this->objLanguage->languageText("mod_blog_quickaddpost", "blog") . "<br />" . $qpform);
			return $ret;
		}
	}
	/**
     * Method to display the last ten posts as a block
     *
     * @author Megan Watson
     * @access public 
     * @param  integer $num        The number of posts to display. Default = 10
     * @param  bool    $featurebox Return the posts as a string or formatted in a featurebox. Default = false, return as a string
     * @return string  html
     */
	public function showLastTenPosts($num = 10, $featurebox = FALSE)
	{
		$objUser = $this->getObject('user', 'security');
		$this->loadClass('link', 'htmlelements');
		$data = $this->objDbBlog->getLastPosts($num);
		$str = '';
		// Display the posts
		if (!empty($data)) {
			foreach($data as $item) {
				$linkuri = $this->uri(array(
				'action' => 'viewsingle',
				'postid' => $item['id'],
				'userid' => $item['userid']
				));
				$link = new href($linkuri, stripslashes($item['post_title']));
				$str.= '<p>';
				$str.= '<b>' . $link->show() . '</b><br />';
				if ($this->showfullname == 'FALSE') {
					$nameshow = $this->objUser->userName($item['userid']);
				} else {
					$nameshow = $this->objUser->fullname($item['userid']);
				}
				$str.= '<font class="minute">' . $nameshow . '</font>';
				// $str .= '<br />'.$item['post_excerpt'];
				// TODO: put in a hr class (CSS) that takes up very little space
				$str.= '</p>';
			}
		}
		// Display either as a string for the block or in a featurebox
		if ($featurebox == FALSE) {
			return $str;
		} else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_block_latestblogs", "blog") , $str);
			return $ret;
		}
	}
	/**
     * Methid to build a table of all available bloggers on the system
     *
     * @param  array  $rec
     * @return string
     */
	public function buildBloggertable($rec)
	{
		$lastentry = $this->objDbBlog->getLatestPost($rec['id']);
		$link = new href($this->uri(array(
		'action' => 'randblog',
		'userid' => $rec['id']
		)) , stripslashes($lastentry['post_title']));
		$this->cleaner = $this->newObject('htmlcleaner', 'utilities');
		$txt = stripslashes($lastentry['post_excerpt']);
		$txt = stripslashes($txt);
		$txtlen = 100;
		$str_to_count = $txt;
		// html_entity_decode($txt);
		if (strlen($str_to_count) <= $txtlen) {
			$txt = $this->cleaner->cleanHtml($txt);
		} else {
			$txt = substr($str_to_count, 0, $txtlen-3);
			$txt.= $txt . "...";
			$txt = $this->cleaner->cleanHtml($txt);
		}
		$lastpost = $link->show() . "<br />" . $txt;
		$stable = $this->newObject('htmltable', 'htmlelements');
		$stable->cellpadding = 2;
		// set up the header row
		$stable->startHeaderRow();
		$stable->addHeaderCell('');
		// $this->objLanguage->languageText("mod_blog_blogger", "blog") . ":");
		$stable->addHeaderCell('');
		// "<em>" . $rec['name'] . "</em>");
		$stable->endHeaderRow();
		$stable->startRow();
		$stable->addCell($rec['img']);
		$stable->addCell($this->objLanguage->languageText("mod_blog_lastseen", "blog") . " : " . $rec['laston'] . "<br />" . $lastpost);
		$stable->endRow();
		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_blogger", "blog") . " : " . "<em>" . $rec['name'] . "</em>", $stable->show());
		return $ret;
	}
	/**
     * Date manipulation method for getting posts by month/date
     *
     * @param  mixed selected date $sel_date
     * @return array
     */
	public function retDates($sel_date = NULL)
	{
		if ($sel_date == NULL) {
			$sel_date = mktime(0, 0, 0, date("m", time()) , 1, date("y", time()));
		}
		$t = getdate($sel_date);
		$start_date = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'], 1, $t['year']);
		$start_date-= 86400*date('w', $start_date);
		$prev_year = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'], $t['mday'], $t['year']-1);
		$prev_month = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon']-1, $t['mday'], $t['year']);
		$next_year = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon'], $t['mday'], $t['year']+1);
		$next_month = mktime($t['hours'], $t['minutes'], $t['seconds'], $t['mon']+1, $t['mday'], $t['year']);
		return array(
		'mbegin' => $sel_date,
		'prevyear' => $prev_year,
		'prevmonth' => $prev_month,
		'nextyear' => $next_year,
		'nextmonth' => $next_month
		);
	}
	/**
     * Method to append config settings to the config.xml file
     *
     * @param array $newsettings
     */
	public function setupConfig($newsettings)
	{
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->objConfig->appendToConfig($newsettings);
	}
	/**
     * Method to parse the DSN
     *
     * @access public
     * @param  string $dsn
     * @return void  
     */
	public function parseDSN($dsn)
	{
		$parsed = NULL;
		// $this->imapdsn;
		$arr = NULL;
		if (is_array($dsn)) {
			$dsn = array_merge($parsed, $dsn);
			return $dsn;
		}
		// find the protocol
		if (($pos = strpos($dsn, ':// ')) !== false) {
			$str = substr($dsn, 0, $pos);
			$dsn = substr($dsn, $pos+3);
		} else {
			$str = $dsn;
			$dsn = null;
		}
		if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
			$parsed['protocol'] = $arr[1];
			$parsed['protocol'] = !$arr[2] ? $arr[1] : $arr[2];
		} else {
			$parsed['protocol'] = $str;
			$parsed['protocol'] = $str;
		}
		if (!count($dsn)) {
			return $parsed;
		}
		// Get (if found): username and password
		if (($at = strrpos($dsn, '@')) !== false) {
			$str = substr($dsn, 0, $at);
			$dsn = substr($dsn, $at+1);
			if (($pos = strpos($str, ':')) !== false) {
				$parsed['user'] = rawurldecode(substr($str, 0, $pos));
				$parsed['pass'] = rawurldecode(substr($str, $pos+1));
			} else {
				$parsed['user'] = rawurldecode($str);
			}
		}
		// server
		if (($col = strrpos($dsn, ':')) !== false) {
			$strcol = substr($dsn, 0, $col);
			$dsn = substr($dsn, $col+1);
			if (($pos = strpos($strcol, '/')) !== false) {
				$parsed['server'] = rawurldecode(substr($strcol, 0, $pos));
			} else {
				$parsed['server'] = rawurldecode($strcol);
			}
		}
		// now we are left with the port and mailbox so we can just explode the string and clobber the arrays together
		$pm = explode("/", $dsn);
		$parsed['port'] = $pm[0];
		$parsed['mailbox'] = $pm[1];
		$dsn = NULL;
		return $parsed;
	}
	/**
     * Method to retrieve the mail dsn from the config.xml file
     *
     * @param  void  
     * @return string
     */
	public function getMailDSN()
	{
		// check that the variables are set, if not return the template, otherwise return a thank you and carry on
		$this->objConfig = $this->getObject('altconfig', 'config');
		$vals = $this->objConfig->getItem('BLOG_MAIL_DSN');
		if ($vals != FALSE) {
			$dsnparse = $this->parseDSN($vals);
			return $dsnparse;
		} else {
			return FALSE;
		}
	}
	/**
     * Method to build a tag cloud from blog entry tags
     *
     * @param  string $userid
     * @return array 
     */
	public function blogTagCloud($userid, $showOrHide = 'none')
	{
		$this->objTC = $this->getObject('tagcloud', 'utilities');
		// get all the tags
		$tagarr = $this->objDbBlog->getTagsByUser($userid);
		if (empty($tagarr)) {
			return NULL;
		}
		foreach($tagarr as $uni) {
			$t[] = $uni['meta_value'];
		}
		$utags = array_unique($t);
		foreach($utags as $tag) {
			// create the url
			$url = $this->uri(array(
			'action' => 'viewblogbytag',
			'tag' => $tag,
			'userid' => $userid
			));
			// get the count of the tag (weight)
			$weight = $this->objDbBlog->getTagWeight($tag, $userid);
			$weight = $weight*1000;
			$tag4cloud = array(
			'name' => $tag,
			'url' => $url,
			'weight' => $weight,
			'time' => time()
			);
			$ret[] = $tag4cloud;
		}
		$icon = $this->getObject('geticon', 'htmlelements');
		$icon->setIcon('up');
		$objFeatureBox = $this->getObject('featurebox', 'navigation');
		return $objFeatureBox->show($this->objLanguage->languagetext("mod_blog_tagcloud", "blog") , $this->objTC->buildCloud($ret) , 'tagcloud', 'none');
	}
	/**
     * Method to show the trackbacks in the trackback table to the user on a singleview post display
     *
     * @param  string $pid
     * @return string
     */
	public function showTrackbacks($pid)
	{
		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$tbs = $this->objDbBlog->grabTrackbacks($pid);
		// loop through the trackbacks and build a featurebox to show em
		if (empty($tbs)) {
			// shouldn't happen except on permalinks....?
			return $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_trackback4post", "blog") , "<em>" . $this->objLanguage->languageText("mod_blog_trackbacknotrackback", "blog") . "</em>");
		}
		$tbtext = NULL;
		foreach($tbs as $tracks) {
			// build up the display
			$tbtable = $this->newObject('htmltable', 'htmlelements');
			$tbtable->cellpadding = 2;
			// $tbtable->width = '80%';
			// set up the header row
			$tbtable->startHeaderRow();
			$tbtable->addHeaderCell('');
			$tbtable->addHeaderCell('');
			$tbtable->endHeaderRow();
			// where did it come from?
			$whofromhost = $tracks['tburl'];
			$link = new href(htmlentities($whofromhost) , htmlentities($whofromhost) , NULL);
			$whofromhost = $link->show();
			$blogname = stripslashes($tracks['blog_name']);
			// title and excerpt
			$title = stripslashes($tracks['title']);
			$excerpt = stripslashes($tracks['excerpt']);
			$tbtable->startRow();
			$tbtable->addCell($this->objLanguage->languageText("mod_blog_tbremhost", "blog"));
			$tbtable->addCell($whofromhost);
			$tbtable->endRow();
			$tbtable->startRow();
			$tbtable->addCell($this->objLanguage->languageText("mod_blog_tbblogname", "blog"));
			$tbtable->addCell($blogname);
			$tbtable->endRow();
			$tbtable->startRow();
			$tbtable->addCell($this->objLanguage->languageText("mod_blog_tbblogtitle", "blog"));
			$tbtable->addCell($title);
			$tbtable->endRow();
			$tbtable->startRow();
			$tbtable->addCell($this->objLanguage->languageText("mod_blog_tbblogexcerpt", "blog"));
			$tbtable->addCell($excerpt);
			$tbtable->endRow();

			// add in a delete option...
			$this->objIcon = &$this->getObject('geticon', 'htmlelements');
			$tbdelIcon = $this->objIcon->getDeleteIconWithConfirm($tracks['id'], array(
			'module' => 'blog',
			'action' => 'deletetb',
			'id' => $tracks['id'],
			'pid' => $pid
			) , 'blog');
			$tbtext.= $tbtable->show().$tbdelIcon;
			$tbtable = NULL;
		}
		$tbtext = $this->bbcode->parse4bbcode($tbtext);
		$this->bbcode = $this->getObject('bbcodeparser', 'utilities');
		$ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_trackback4post", "blog") , $tbtext);
		return $ret;
	}
	/**
     * Method to build the form to send a trackback to another blog
     *
     * @param  array  $postinfo
     * @return string
     */
	public function sendTrackbackForm($postinfo)
	{
		// start a form object
		$this->loadClass('textarea', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$this->loadClass('label', 'htmlelements');
		$stbform = new form('tbsend', $this->uri(array(
		'action' => 'tbsend'
		)));
		$tbfieldset = $this->newObject('fieldset', 'htmlelements');
		$tbfieldset->setLegend($this->objLanguage->languageText('mod_blog_sendtb', 'blog'));
		$tbtable = $this->newObject('htmltable', 'htmlelements');
		$tbtable->cellpadding = 3;
		$tbtable->startHeaderRow();
		$tbtable->addHeaderCell('');
		$tbtable->addHeaderCell('');
		$tbtable->endHeaderRow();
		// post url field
		$tbtable->startRow();
		$myurllabel = new label($this->objLanguage->languageText('mod_blog_posturl', 'blog') . ':', 'input_tbmyurl');
		$myurl = new textinput('url');
		$myurl->size = 59;
		$myurl->setValue($postinfo['url']);
		$tbtable->addCell($myurllabel->show());
		$tbtable->addCell($myurl->show());
		$tbtable->endRow();
		// post id field
		$tbtable->startRow();
		$pidlabel = new label($this->objLanguage->languageText('mod_blog_postid', 'blog') . ':', 'input_postid');
		$pid = new textinput('postid');
		$pid->size = 59;
		$pid->setValue($postinfo['postid']);
		$tbtable->addCell($pidlabel->show());
		$tbtable->addCell($pid->show());
		$tbtable->endRow();
		// blog_name field
		$tbtable->startRow();
		$bnlabel = new label($this->objLanguage->languageText('mod_blog_blogname', 'blog') . ':', 'input_tbbname');
		$bn = new textinput('blog_name');
		$bn->size = 59;
		$bn->setValue(stripslashes($postinfo['blog_name']));
		$tbtable->addCell($bnlabel->show());
		$tbtable->addCell($bn->show());
		$tbtable->endRow();
		// title field
		$tbtable->startRow();
		$titlabel = new label($this->objLanguage->languageText('mod_blog_posttitle', 'blog') . ':', 'input_tbtitle');
		$tit = new textinput('title');
		$tit->size = 59;
		$tit->setValue(stripslashes($postinfo['title']));
		$tbtable->addCell($titlabel->show());
		$tbtable->addCell($tit->show());
		$tbtable->endRow();
		// post excerpt field
		$tbtable->startRow();
		$exlabel = new label($this->objLanguage->languageText('mod_blog_postexcerpt', 'blog') . ':', 'input_tbexcerpt');
		$ex = new textarea('excerpt');
		$ex->setColumns(50);
		$ex->setValue(stripslashes($postinfo['excerpt']));
		$tbtable->addCell($exlabel->show());
		$tbtable->addCell($ex->show());
		$tbtable->endRow();
		// trackback url field
		$tbtable->startRow();
		$tburllabel = new label($this->objLanguage->languageText('mod_blog_trackbackurl', 'blog') . ':', 'input_tburl');
		$tburl = new textinput('tburl');
		$tburl->size = 59;
		$tbtable->addCell($tburllabel->show());
		$tbtable->addCell($tburl->show());
		$tbtable->endRow();
		// add some rules
		$stbform->addRule('url', $this->objLanguage->languageText("mod_blog_phrase_tburlreq", "blog") , 'required');
		$stbform->addRule('postid', $this->objLanguage->languageText("mod_blog_phrase_tbidreq", "blog") , 'required');
		$stbform->addRule('blog_name', $this->objLanguage->languageText("mod_blog_phrase_tbbnreq", "blog") , 'required');
		$stbform->addRule('title', $this->objLanguage->languageText("mod_blog_phrase_tbtitreq", "blog") , 'required');
		$stbform->addRule('excerpt', $this->objLanguage->languageText("mod_blog_phrase_tbexreq", "blog") , 'required');
		$stbform->addRule('tburl', $this->objLanguage->languageText("mod_blog_phrase_tbtburlreq", "blog") , 'required');
		// put it all together and set up a submit button
		$tbfieldset->addContent($tbtable->show());
		$stbform->addToForm($tbfieldset->show());
		$this->objTBButton = new button($this->objLanguage->languageText('mod_blog_word_sendtb', 'blog'));
		$this->objTBButton->setValue($this->objLanguage->languageText('mod_blog_word_sendtb', 'blog'));
		$this->objTBButton->setToSubmit();
		$stbform->addToForm($this->objTBButton->show());
		$stbform = $stbform->show();
		// bust out a featurebox for consistency
		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_sendtb", "blog") , $stbform);
		return $ret;
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  array   $m2fdata Parameter description (if any) ...
     * @return unknown Return description (if any) ...
     * @access public 
     */
	public function sendMail2FriendForm($m2fdata)
	{
		$this->objUser = $this->getObject('user', 'security');
		if ($this->objUser->isLoggedIn()) {
			if ($this->showfullname == 'FALSE') {
				$theuser = $this->objUser->userName($this->objUser->userid());
			} else {
				$theuser = $this->objUser->fullname($this->objUser->userid());
			}
			// $theuser = $this->objUser->fullName($this->objUser->userid());

		} else {
			$theuser = $this->objLanguage->languageText("mod_blog_word_anonymous", "blog");
		}
		// start a form object
		$this->loadClass('textarea', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$this->loadClass('label', 'htmlelements');
		$mform = new form('mail2friend', $this->uri(array(
		'action' => 'mail2friend',
		'postid' => $m2fdata['postid']
		)));
		$mfieldset = $this->newObject('fieldset', 'htmlelements');
		// $mfieldset->setLegend($this->objLanguage->languageText('mod_blog_sendmail2friend', 'blog'));
		$mtable = $this->newObject('htmltable', 'htmlelements');
		$mtable->cellpadding = 3;
		$mtable->startHeaderRow();
		$mtable->addHeaderCell('');
		$mtable->addHeaderCell('');
		$mtable->endHeaderRow();
		// your name
		$mtable->startRow();
		$mynamelabel = new label($this->objLanguage->languageText('mod_blog_myname', 'blog') . ':', 'input_myname');
		$myname = new textinput('sendername');
		$myname->size = '80%';
		$myname->setValue($theuser);
		$mtable->addCell($mynamelabel->show());
		$mtable->addCell($myname->show());
		$mtable->endRow();
		// Friend(s) email addresses
		$mtable->startRow();
		$femaillabel = new label($this->objLanguage->languageText('mod_blog_femailaddys', 'blog') . ':', 'input_femail');
		$emailadd = new textinput('emailadd');
		$emailadd->size = '80%';
		if (isset($m2fdata['user'])) {
			$emailadd->setValue($m2fdata['user']);
		}
		$mtable->addCell($femaillabel->show());
		$mtable->addCell($emailadd->show());
		$mtable->endRow();
		// message for friends (optional)
		$mtable->startRow();
		$fmsglabel = new label($this->objLanguage->languageText('mod_blog_femailmsg', 'blog') . ':', 'input_femailmsg');
		$msg = new textarea('msg', '', 4, 68);
		$mtable->addCell($fmsglabel->show());
		$mtable->addCell($msg->show());
		$mtable->endRow();
		// add a rule
		$mform->addRule('emailadd', $this->objLanguage->languageText("mod_blog_phrase_femailreq", "blog") , 'email');
		$mfieldset->addContent($mtable->show());
		$mform->addToForm($mfieldset->show());
		$this->objMButton = new button($this->objLanguage->languageText('mod_blog_word_sendmail', 'blog'));
		$this->objMButton->setValue($this->objLanguage->languageText('mod_blog_word_sendmail', 'blog'));
		$this->objMButton->setToSubmit();
		$mform->addToForm($this->objMButton->show());
		$mform = $mform->show();
		// bust out a featurebox for consistency
		$objFeatureBox = $this->newObject('featurebox', 'navigation');
		$ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_sendmail2friend", "blog") , $mform);
		return $ret;
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $userid  Parameter description (if any) ...
     * @param  array   $profile Parameter description (if any) ...
     * @return object  Return description (if any) ...
     * @access public 
     */
	public function profileEditor($userid, $profile = NULL)
	{
		// print_r($profile);
		// profile editor and creator
		// start a form object
		$this->loadClass('textarea', 'htmlelements');
		$this->loadClass('htmlarea', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$this->loadClass('label', 'htmlelements');
		if ($profile != NULL) {
			$pform = new form('setprofile', $this->uri(array(
			'action' => 'editprofile',
			'mode' => 'editprofile',
			'id' => $profile['id']
			)));
		} else {
			$pform = new form('setprofile', $this->uri(array(
			'action' => 'setprofile',
			'mode' => 'saveprofile',
			)));
		}
		$pfieldset = $this->newObject('fieldset', 'htmlelements');
		// $pfieldset->setLegend($this->objLanguage->languageText('mod_blog_setprofile', 'blog'));
		$ptable = $this->newObject('htmltable', 'htmlelements');
		$ptable->cellpadding = 3;
		$ptable->startHeaderRow();
		$ptable->addHeaderCell('');
		$ptable->addHeaderCell('');
		$ptable->endHeaderRow();
		// blog name field
		$ptable->startRow();
		$bnamelabel = new label($this->objLanguage->languageText('mod_blog_blogname', 'blog') . ':', 'input_blogname');
		$bname = new textinput('blogname');
		if (isset($profile['blog_name'])) {
			$bname->setValue($profile['blog_name']);
		}
		$bname->size = 59;
		// $bname->setValue();
		$ptable->addCell($bnamelabel->show());
		$ptable->addCell($bname->show());
		$ptable->endRow();
		// blog description field
		$ptable->startRow();
		$bdeclabel = new label($this->objLanguage->languageText('mod_blog_blogdesc', 'blog') . ':', 'input_blogdesc');
		$bdec = new textarea('blogdesc');
		if (isset($profile['blog_descrip'])) {
			$bdec->setValue($profile['blog_descrip']);
		}
		$ptable->addCell($bdeclabel->show());
		$ptable->addCell($bdec->show());
		$ptable->endRow();
		// blogger profile field
		$ptable->startRow();
		$bprflabel = new label($this->objLanguage->languageText('mod_blog_bloggerprofile', 'blog') . ':', 'input_blogprofile');
		$bprf = $this->newObject('htmlarea', 'htmlelements');
		$bprf->setName('blogprofile');
		if (isset($profile['blogger_profile'])) {
			$bprf->setcontent($profile['blogger_profile']);
		}
		$ptable->addCell($bprflabel->show());
		$ptable->addCell($bprf->showFCKEditor());
		$ptable->endRow();
		// put it all together and set up a submit button
		$pfieldset->addContent($ptable->show());
		$pform->addToForm($pfieldset->show());
		$this->objPButton = new button($this->objLanguage->languageText('word_save', 'system'));
		$this->objPButton->setValue($this->objLanguage->languageText('word_save', 'system'));
		$this->objPButton->setToSubmit();
		$pform->addToForm($this->objPButton->show());
		$pform = $pform->show();
		// bust out a featurebox for consistency
		// $objFeatureBox = $this->newObject('featurebox', 'navigation');
		// $ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_setprofile", "blog") , $pform);
		return $pform;
		// return $ret;

	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  string $userid Parameter description (if any) ...
     * @return mixed  Return description (if any) ...
     * @access public
     */
	public function showProfile($userid)
	{
		$objFeatureBox = $this->getObject("featurebox", "navigation");
		$this->loadClass('label', 'htmlelements');
		$this->loadClass('href', 'htmlelements');
		$this->objUser = $this->getObject('user', 'security');
		$this->objConfig = $this->getObject('altconfig', 'config');
		$tllink = new href($this->uri(array(
		'module' => 'blog',
		'action' => 'timeline',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_viewtimelineof", "blog"));
		// go back to your blog
		$viewmyblog = new href($this->uri(array(
		'action' => 'viewblog'
		)) , $this->objLanguage->languageText("mod_blog_viewmyblog", "blog"));
		$check = $this->objDbBlog->checkProfile($userid);
		if ($check != FALSE && $check['blog_name'] != NULL || $check['blog_descrip'] != NULL || $check['blogger_profile'] != NULL) {
			$link = new href($this->uri(array(
			'module' => 'blog',
			'action' => 'viewprofile',
			'userid' => $userid
			)) , $this->objLanguage->languageText("mod_blog_viewprofileof", "blog") . " " . $this->objUser->userName($userid));
			$tllink = new href($this->uri(array(
			'module' => 'blog',
			'action' => 'timeline',
			'userid' => $userid
			)) , $this->objLanguage->languageText("mod_blog_viewtimelineof", "blog"));
			$foaffile = $this->objConfig->getsiteRoot() . "usrfiles/users/" . $userid . "/" . $userid . ".rdf";
			@$rdfcont = file($foaffile);
			if (!empty($rdfcont)) {
				$objFIcon = $this->newObject('geticon', 'htmlelements');
				$objFIcon->setIcon('foaftiny', 'gif', 'icons');
				$lficon = new href($this->objConfig->getsiteRoot() . "/usrfiles/users/" . $userid . "/" . $userid . ".rdf", $objFIcon->show() , NULL);
				$ficon = $lficon->show();
				// new href($this->objConfig->getsiteRoot() . "/usrfiles/users/" . $userid . "/". $userid . ".rdf", $this->objLanguage->languageText("mod_blog_foaflink", "blog"));
				return $objFeatureBox->show($this->objLanguage->languageText("mod_blog_viewprofile", "blog") , $link->show() . "<br />" . $ficon . "<br />" . $tllink->show());
			} else {
				$objFeatureBox = $this->getObject("featurebox", "navigation");
				return $objFeatureBox->show($this->objLanguage->languageText("mod_blog_viewprofile", "blog") , $link->show() . "<br />" . $tllink->show() . "<br />" . $viewmyblog->show());
			}
		} else {
			$objFeatureBox = $this->getObject("featurebox", "navigation");
			return $objFeatureBox->show($this->objLanguage->languageText("mod_blog_viewprofile", "blog") , $tllink->show() . "<br />" . $viewmyblog->show());
		}
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  string $userid Parameter description (if any) ...
     * @return mixed  Return description (if any) ...
     * @access public
     */
	public function showFullProfile($userid)
	{
		if ($this->showfullname == 'FALSE') {
			$pname = $this->objUser->userName($userid);
		} else {
			$pname = $this->objUser->fullName($userid);
		}
		$objFeatureBox = $this->getObject("featurebox", "navigation");
		$this->loadClass('label', 'htmlelements');
		$this->loadClass('href', 'htmlelements');
		$this->objUser = $this->getObject('user', 'security');
		$this->objConfig = $this->getObject('altconfig', 'config');
		$userimg = "<center>" . $this->objUser->getUserImage($userid) . "</center>";
		$tllink = new href($this->uri(array(
		'module' => 'blog',
		'action' => 'timeline',
		'userid' => $userid
		)) , $this->objLanguage->languageText("mod_blog_viewtimelineof", "blog"));
		// go back to your blog
		if($this->objUser->isLoggedIn())
		{
			$viewmyblog = new href($this->uri(array(
			'action' => 'viewblog'
			)) , $this->objLanguage->languageText("mod_blog_viewmyblog", "blog"));
		}
		else {
			$viewmyblog = new href($this->uri(array(
			'action' => 'allblogs'
			)) , $this->objLanguage->languageText("mod_blog_viewallblogs", "blog"));
		}
		$check = $this->objDbBlog->checkProfile($userid);
		if ($check != FALSE) {
			$link = new href($this->uri(array(
			'module' => 'blog',
			'action' => 'viewprofile',
			'userid' => $userid
			)) , $this->objLanguage->languageText("mod_blog_viewprofileof", "blog") . " " . $this->objUser->userName($userid));
			$tllink = new href($this->uri(array(
			'module' => 'blog',
			'action' => 'timeline',
			'userid' => $userid
			)) , $this->objLanguage->languageText("mod_blog_viewtimelineof", "blog"));
			$foaffile = $this->objConfig->getsiteRoot() . "usrfiles/users/" . $userid . "/" . $userid . ".rdf";
			@$rdfcont = file($foaffile);
			if (!empty($rdfcont)) {
				$objFIcon = $this->newObject('geticon', 'htmlelements');
				$objFIcon->setIcon('foaftiny', 'gif', 'icons');
				$lficon = new href($this->objConfig->getsiteRoot() . "/usrfiles/users/" . $userid . "/" . $userid . ".rdf", $objFIcon->show() , NULL);
				$ficon = $lficon->show();
				// new href($this->objConfig->getsiteRoot() . "/usrfiles/users/" . $userid . "/". $userid . ".rdf", $this->objLanguage->languageText("mod_blog_foaflink", "blog"));
				return $objFeatureBox->show($this->objLanguage->languageText("mod_blog_viewfullprofile", "blog") . " " . $pname, $link->show() . "<br />" . $ficon . "<br />" . $tllink->show());
			} else {
				$objFeatureBox = $this->getObject("featurebox", "navigation");
				return $objFeatureBox->show($this->objLanguage->languageText("mod_blog_viewfullprofile", "blog") . " " . $pname, $link->show() . "<br />" . $tllink->show() . "<br />" . $viewmyblog->show());
			}
		} else {
			$objFeatureBox = $this->getObject("featurebox", "navigation");
			return $objFeatureBox->show($this->objLanguage->languageText("mod_blog_viewfullprofile", "blog") . " " . $pname, $userimg . "<br />" . $tllink->show() . "<br />" . $viewmyblog->show());
		}
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $userid  Parameter description (if any) ...
     * @param  array   $profile Parameter description (if any) ...
     * @return mixed   Return description (if any) ...
     * @access public 
     */
	public function displayProfile($userid, $profile)
	{
		$objFeatureBox = $this->getObject("featurebox", "navigation");
		$this->objUser = $this->getObject('user', 'security');
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->bbcode = $this->getObject('bbcodeparser', 'utilities');
		$prtable = $this->newObject('htmltable', 'htmlelements');
		$this->loadClass('label', 'htmlelements');
		$prtable->cellpadding = 3;
		$prtable->startHeaderRow();
		$prtable->addHeaderCell('');
		$prtable->addHeaderCell('');
		$prtable->endHeaderRow();
		// blog name field
		$prtable->startRow();
		$bnamelabel = $this->objLanguage->languageText('mod_blog_blogname', 'blog');
		$bname = $profile['blog_name'];
		$prtable->addCell($bnamelabel);
		$prtable->addCell($bname);
		$prtable->endRow();
		$prtable->startRow();
		$bdeclabel = $this->objLanguage->languageText('mod_blog_blogdescription', 'blog');
		$bdec = stripslashes($this->bbcode->parse4bbcode($profile['blog_descrip']));
		$prtable->addCell($bdeclabel);
		$prtable->addCell($bdec);
		$prtable->endRow();
		// blogger profile field
		$prtable->startRow();
		$bprflabel = $this->objLanguage->languageText('mod_blog_bloggerprf', 'blog');
		$bprf = stripslashes($this->bbcode->parse4bbcode($profile['blogger_profile']));
		$prtable->addCell($bprflabel);
		$prtable->addCell($bprf);
		$prtable->endRow();
		$content = $prtable->show();
		if ($this->showfullname == 'FALSE') {
			$namer = $this->objUser->userName($userid);
		} else {
			$namer = $this->objUser->fullname($userid);
		}
		return $objFeatureBox->showContent($this->objLanguage->languageText("mod_blog_profileof", "blog") . " " . $namer, $content);
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $userid     Parameter description (if any) ...
     * @param  array   $check      Parameter description (if any) ...
     * @param  array   $page       Parameter description (if any) ...
     * @param  boolean $featurebox Parameter description (if any) ...
     * @return mixed   Return description (if any) ...
     * @access public 
     */
	public function pageEditor($userid, $check = NULL, $page = NULL, $featurebox = FALSE)
	{
		// start a form object
		$this->loadClass('href', 'htmlelements');
		$this->loadClass('textarea', 'htmlelements');
		$this->loadClass('htmlarea', 'htmlelements');
		$this->loadClass('textinput', 'htmlelements');
		$this->loadClass('label', 'htmlelements');
		// var_dump($page);
		if ($page != NULL) {
			$pform = new form('setpage', $this->uri(array(
			'action' => 'setpage',
			'mode' => 'editpage',
			'id' => $page[0]['id']
			)));
		} else {
			$pform = new form('setpage', $this->uri(array(
			'action' => 'setpage',
			'mode' => 'savepage',
			)));
		}
		$pfieldset = $this->newObject('fieldset', 'htmlelements');
		// $pfieldset->setLegend($this->objLanguage->languageText('mod_blog_setpage', 'blog'));
		$ptable = $this->newObject('htmltable', 'htmlelements');
		$ptable->cellpadding = 3;
		$ptable->startHeaderRow();
		$ptable->addHeaderCell('');
		$ptable->addHeaderCell('');
		$ptable->endHeaderRow();
		// page name field
		$ptable->startRow();
		$bnamelabel = new label($this->objLanguage->languageText('mod_blog_pagename', 'blog') . ':', 'input_pagename');
		$bname = new textinput('page_name');
		if (isset($page[0]['page_name'])) {
			$bname->setValue($page[0]['page_name']);
		}
		$bname->size = 59;
		// $bname->setValue();
		$ptable->addCell($bnamelabel->show());
		$ptable->addCell($bname->show());
		$ptable->endRow();
		// content page field
		$ptable->startRow();
		$bprflabel = new label($this->objLanguage->languageText('mod_blog_pagecontent', 'blog') . ':', 'input_pagecontent');
		$bprf = $this->newObject('htmlarea', 'htmlelements');
		$bprf->setName('page_content');
		if (isset($page[0]['page_content'])) {
			$bprf->setcontent($page[0]['page_content']);
		}
		$ptable->addCell($bprflabel->show());
		$ptable->addCell($bprf->showFCKEditor());
		$ptable->endRow();
		// put it all together and set up a submit button
		$pfieldset->addContent($ptable->show());
		$pform->addToForm($pfieldset->show());
		$this->objPButton = new button($this->objLanguage->languageText('word_save', 'system'));
		$this->objPButton->setValue($this->objLanguage->languageText('word_save', 'system'));
		$this->objPButton->setToSubmit();
		$pform->addToForm($this->objPButton->show());
		$pform = $pform->show();
		// ok now the table with the edit/delete for each rss feed
		$efeeds = $this->objDbBlog->getUserRss($this->objUser->userId());
		$ftable = $this->newObject('htmltable', 'htmlelements');
		$ftable->cellpadding = 3;
		// $ftable->border = 1;
		// set up the header row
		$ftable->startHeaderRow();
		$ftable->addHeaderCell($this->objLanguage->languageText("mod_blog_phead_name", "blog"));
		// $ftable->addHeaderCell($this->objLanguage->languageText("mod_blog_phead_description", "blog"));
		$ftable->addHeaderCell('');
		$ftable->endHeaderRow();
		// set up the rows and display
		if (!empty($check)) {
			foreach($check as $rows) {
				$ftable->startRow();
				$feedlink = new href($this->uri(array(
				'action' => 'showpage',
				'pageid' => $rows['id']
				)) , $rows['page_name'], 'target="_blank" alt="' . $rows['page_name'] . '"');
				$ftable->addCell($feedlink->show());
				// $ftable->addCell(htmlentities($rows['name']));
				$this->objIcon = &$this->getObject('geticon', 'htmlelements');
				$edIcon = $this->objIcon->getEditIcon($this->uri(array(
				'action' => 'setpage',
				'mode' => 'editpage',
				'id' => $rows['id'],
				'module' => 'blog'
				)));
				$delIcon = $this->objIcon->getDeleteIconWithConfirm($rows['id'], array(
				'module' => 'blog',
				'action' => 'deletepage',
				'id' => $rows['id']
				) , 'blog');
				$ftable->addCell($edIcon . $delIcon);
				$ftable->endRow();
			}
			// $ftable = $ftable->show();

		}
		if ($featurebox == TRUE) {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_editpages", "blog") , $pform . $ftable->show());
			return $ret;
		} else {
			return $pform . $ftable->show();
		}
		// return $pform;

	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $userid     Parameter description (if any) ...
     * @param  boolean $featurebox Parameter description (if any) ...
     * @return string  Return description (if any) ...
     * @access public 
     */
	public function showPages($userid, $featurebox = FALSE)
	{
		$this->loadClass('href', 'htmlelements');
		// grab all of the links for the user
		$pages = $this->objDbBlog->getPages($userid);
		if (empty($pages)) {
			return NULL;
		}
		$str = NULL;
		foreach($pages as $page) {
			$link = $this->uri(array(
			'action' => 'showpage',
			'pageid' => $page['id']
			));
			$hr = new href($link, $page['page_name'], ' alt="' . $page['page_name'] . '"');
			$str.= "<ul>" . $hr->show() . "</ul>";
		}
		if ($featurebox == TRUE) {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_pages", "blog") , $str, 'blogpages', 'default');
			return $ret;
		} else {
			return $str;
		}
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @return void  
     * @access public
     */
	public function mail2blog()
	{
		// grab the DSN from the config file
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->objImap = $this->getObject('imap', 'mail');
		$this->dsn = $this->objConfig->getItem('BLOG_MAIL_DSN');
		try {
			// grab a list of all valid users to an array for verification later
			$valid = $this->objDbBlog->checkValidUser();
			$valadds = array();
			// cycle through the valid email addresses and check that the mail is from a real user
			foreach($valid as $addys) {
				$valadds[] = array(
				'address' => $addys['emailaddress'],
				'userid' => $addys['userid']
				);
			}
			// connect to the IMAP/POP3 server
			$this->conn = $this->objImap->factory($this->dsn);
			// grab the mail headers
			$this->objImap->getHeaders();
			// var_dump($this->objImap->getHeaders());
			// check mail
			$this->thebox = $this->objImap->checkMbox();
			// get the mail folders
			$this->folders = $this->objImap->populateFolders($this->thebox);
			// count the messages
			$this->msgCount = $this->objImap->numMails();
			// echo $this->msgCount;
			// get the meassge headers
			$i = 1;
			// parse the messages
			while ($i <= $this->msgCount) {
				// get the header info
				$headerinfo = $this->objImap->getHeaderInfo($i);
				// from
				$address = $headerinfo->fromaddress;
				// subject
				$subject = $headerinfo->subject;
				// date
				$date = $headerinfo->Date;
				// message flag
				$read = $headerinfo->Unseen;
				// message body
				$bod = $this->objImap->getMessage($i);
				// check if there is an attachment
				if (empty($bod[1])) {
					// nope no attachments
					$attachments = NULL;
				} else {
					// set the attachment
					$attachments = $bod[1];
					// loop through the attachments and write them down

				}
				// make sure the body doesn't have any nasty chars
				$message = @htmlentities($bod[0]);
				// check for a valid user
				if (!empty($address)) {
					// check the address against tbl_users to see if its valid.
					// just get the email addy, we dont need the name as it can be faked
					$fadd = $address;
					// get rid of the RFC formatted email bits
					$parts = explode("<", $fadd);
					$parts = explode(">", $parts[1]);
					// raw address string that we can use to check against
					$addy = $parts[0];
					// check if the address we get from the msg is in the array of valid addresses
					foreach($valadds as $user) {
						// check if there is a match to the user list
						if ($user['address'] != $addy) {
							// Nope, no match, not validated!
							$validated = NULL;
						} else {
							// echo "Valid user!";
							// match found, you are a valid user dude!
							$validated = TRUE;
							// set the userid
							$userid = $user['userid'];
							// all is cool, so lets break out of this loop and carry on
							break;
						}
					}
				}
				if ($validated == TRUE) {
					// insert the mail data into an array for manipulation
					$data[] = array(
					'userid' => $userid,
					'address' => $address,
					'subject' => $subject,
					'date' => $date,
					'messageid' => $i,
					'read' => $read,
					'body' => $message,
					'attachments' => $attachments
					);
				}
				// delete the message as we don't need it anymore
				echo "sorting " . $this->msgCount . "messages";
				$this->objImap->delMsg($i);
				$i++;
			}
			// is the data var set?
			if (!isset($data)) {
				$data = array();
			}
			// lets look at the data now
			foreach($data as $datum) {
				$newbod = $datum['body'];
				// add the [img][/img] tags to the body so that the images show up
				// we discard any other mimetypes for now...
				if (!empty($datum['attachments'])) {
					if (is_array($datum['attachments'])) {
						foreach($datum['attachments'] as $files) {
							// do check for multiple attachments
							// set the filename of the attachment
							$fname = $files['filename'];
							$filenamearr = explode(".", $fname);
							$ext = pathinfo($fname);
							$filename = $filenamearr[0] . "_" . time() . "." . $ext['extension'];
							// decode the attachment data
							$filedata = base64_decode($files['filedata']);
							// set the path to write down the file to
							$path = $this->objConfig->getContentBasePath() . 'users/' . $userid . '/';
							// 'blog/';
							$fullpath = $this->objConfig->getsiteRoot() . "/usrfiles/users/" . $userid . '/';
							// check that the data dir is there
							// echo $path, $fullpath; die();
							if (!file_exists($path)) {
								// dir doesn't exist so create it quickly
								mkdir($path, 0777);
							}
							// fix up the filename a little
							$filename = str_replace(" ", "_", $filename);
							$filename = str_replace("%20", "_", $filename);
							// change directory to the data dir
							chdir($path);
							// write the file
							$handle = fopen($filename, 'wb');
							fwrite($handle, $filedata);
							fclose($handle);
							$type = mime_content_type($filename);
							$tparts = explode("/", $type);
							// print_r($tparts);
							if ($tparts[0] == "image") {
								// add the img stuff to the body at the end of the "post"
								$newbod.= "[img]" . $fullpath . $filename . "[/img]" . "<br />";
							} elseif ($tparts[1] == "3gpp") {
								if ($tparts[0] == "video") {
									log_debug("Found a 3gp Video file! Processing...");
									// send to the mediaconverter to convert to flv
									$mediacon = $this->getObject('media', 'utilities');
									$file = $path . $filename;
									// echo $file;
									$flv = $mediacon->convert3gp2flv($file, $fullpath);
									// echo "file saved to: $flv";
									$newbod.= "[FLV]" . $flv . "[/FLV]" . " <br />";
									// echo $newbod;

								} elseif ($tparts[0] == "audio") {
									log_debug("Found a 3gp amr file! Processing...");
									// amr file
									$mediacon = $this->getObject('media', 'utilities');
									$file = $path . $filename;
									// echo $file;
									$mp3 = $mediacon->convertAmr2Mp3($file, $fullpath);
									$newbod.= "[EMBED]" . $mp3 . "[/EMBED]" . " <br />";
								}
							} elseif ($tparts[1] == "mp4") {
								if ($tparts[0] == "video") {
									log_debug("Found an MP4 container file");
									// send to the mediaconverter to convert to flv
									$mediacon = $this->getObject('media', 'utilities');
									$file = $path . $filename;
									// echo $file;
									$flv = $mediacon->convertMp42flv($file, $fullpath);
									// echo "file saved to: $flv";
									$newbod.= "[FLV]" . $flv . "[/FLV]" . " <br />";
								}
							} else {
								// add the img stuff to the body at the end of the "post"
								$newbod.= "[url]" . $this->objConfig->getSiteRoot() . 'usrfiles/users/' . $userid . '/' . urlencode($filename) . "[/url]" . "<br />";
							}
						}
					} else {
						// set the filename of the attachment
						$fname = $datum['attachments'][0]['filename'];
						$filenamearr = explode(".", $fname);
						$ext = pathinfo($fname);
						$filename = $filenamearr[0] . "_" . time() . "." . $ext['extension'];
						// decode the attachment data
						$filedata = base64_decode($datum['attachments'][0]['filedata']);
						// set the path to write down the file to
						$path = $this->objConfig->getContentBasePath() . 'blog/';
						// check that the data dir is there
						// fix up the filename a little
						$filename = str_replace(" ", "_", $filename);
						if (!file_exists($path)) {
							// dir doesn't exist so create it quickly
							mkdir($path, 0777);
						}
						// change directory to the data dir
						chdir($path);
						// write the file
						$handle = fopen($filename, 'wb');
						fwrite($handle, $filedata);
						fclose($handle);
						$type = mime_content_type($filename);
						$tparts = explode("/", $type);
						if ($tparts[0] == "image") {
							// add the img stuff to the body at the end of the "post"
							$newbod.= "[img]" . $this->objConfig->getSiteRoot() . 'usrfiles/blog/' . $filename . "[/img]" . "<br />";
						} else {
							// add the img stuff to the body at the end of the "post"
							$newbod.= "[url]" . $this->objConfig->getSiteRoot() . 'usrfiles/blog/' . urlencode($filename) . "[/url]" . "<br />";
						}
					}
				} else {
					// no attachments to worry about
					$newbod = $datum['body'];
				}
				// Write the new post to the database as a "Quick Post"
				$this->quickPostAdd($datum['userid'], array(
				'posttitle' => $datum['subject'],
				'postcontent' => $newbod,
				'postcat' => 0,
				'postexcerpt' => '',
				'poststatus' => '0',
				'commentstatus' => 'Y',
				'postmodified' => date('r') ,
				'commentcount' => 0,
				'postdate' => $datum['date']
				) , 'mail');
			}
		}
		// any issues?
		catch(customException $e) {
			// clean up and die!
			customException::cleanUp();
		}
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @return void  
     * @access public
     */
	public function listmail2blog()
	{
		// grab the DSN from the config file
		$this->objConfig = $this->getObject('altconfig', 'config');
		$this->objImap = $this->getObject('imap', 'mail');
		$listdsn = $this->sysConfig->getValue('list_dsn', 'blog');
		// $listdsn = $this->objConfig->getItem('BLOG_LISTMAIL_DSN');
		// $userid = $this->sysConfig->getValue('list_userid', 'blog');
		// $listidentifier = $this->sysConfig->getValue('list_identifier', 'blog');
		// grab a list of identified lists
		$validlists = $this->objDbBlog->getLists();
		// create an array of valid identifiers
		foreach($validlists as $valididentifiers) {
			$valid[] = $valididentifiers['list_identifier'];
		}
		try {
			// connect to the IMAP/POP3 server
			$this->conn = $this->objImap->factory($listdsn);
			// grab the mail headers
			$this->objImap->getHeaders();
			// check mail
			$this->thebox = $this->objImap->checkMbox();
			// get the mail folders
			$this->folders = $this->objImap->populateFolders($this->thebox);
			// count the messages
			$this->msgCount = $this->objImap->numMails();
			// echo $this->msgCount;
			// get the meassge headers
			$i = 1;
			// parse the messages
			while ($i <= $this->msgCount) {
				// get the header info
				$headerinfo = $this->objImap->getHeaderInfo($i);
				// from
				$address = @$headerinfo->fromaddress;
				// subject
				$subject = @$headerinfo->subject;
				// date
				$date = @$headerinfo->Date;
				// message flag
				$read = @$headerinfo->Unseen;
				// message body
				$bod = $this->objImap->getMessage($i);
				// put this into a foreach to check all valid lists
				// check to see that the message comes from [Nextgen-online]
				foreach($valid as $listidentifier) {
					if (preg_match('/\[' . $listidentifier . '\]/U', $subject)) {
						$message = @htmlentities($bod[0]);
						$listinfo = $this->objDbBlog->getListInfo($listidentifier);
						// print_r($listinfo);die();
						$userid = $listinfo[0]['listuser'];
						// lets strip out the email addresses first to stop spam bots
						$message = str_replace("<", "", $message);
						$message = str_replace(">", "", $message);
						$message = preg_replace('/[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}/im', " " . $this->objLanguage->languageText("mod_blog_emailreplaced", "blog") , $message);
						// insert the mail data into an array for manipulation
						$data[] = array(
						'userid' => $userid,
						'address' => $address,
						'subject' => $subject,
						'date' => $date,
						'messageid' => $i,
						'read' => $read,
						'body' => $message,
						'attachments' => $attachments
						);
						// echo "valid list mail";
						$validated = TRUE;
						// break;

					} else {
						$validated = FALSE;
					}
				}
				// check if there is an attachment
				if (empty($bod[1])) {
					// nope no attachments
					$attachments = NULL;
				} else {
					// set the attachment
					$attachments = $bod[1];
					// loop through the attachments and write them down

				}
				// make sure the body doesn't have any nasty chars
				$message = @htmlentities($bod[0]);
				/*if($validated == TRUE)
				{
				// echo "grabbing the list info";
				// grab the userid from the table
				$listinfo = $this->objDbBlog->getListInfo($listidentifier);
				// print_r($listinfo);die();
				$userid = $listinfo[0]['listuser'];
				// insert the mail data into an array for manipulation
				$data[] = array('userid' => $userid,'address' => $address, 'subject' => $subject, 'date' => $date, 'messageid' => $i, 'read' => $read,
				'body' => $message, 'attachments' => $attachments);
				}*/
				// delete the message as we don't need it anymore
				// echo "sorting " . $this->msgCount . "messages";
				$this->objImap->delMsg($i);
				$i++;
			}
			// is the data var set?
			if (!isset($data)) {
				$data = array();
			}
			// lets look at the data now
			foreach($data as $datum) {
				$newbod = $datum['body'];
				// add the [img][/img] tags to the body so that the images show up
				// we discard any other mimetypes for now...
				if (!empty($datum['attachments'])) {
					if (is_array($datum['attachments'])) {
						foreach($datum['attachments'] as $files) {
							// do check for multiple attachments
							// set the filename of the attachment
							$fname = $files['filename'];
							$filenamearr = explode(".", $fname);
							$ext = pathinfo($fname);
							$filename = $filenamearr[0] . "_" . time() . "." . $ext['extension'];
							// decode the attachment data
							$filedata = base64_decode($files['filedata']);
							// set the path to write down the file to
							$path = $this->objConfig->getContentBasePath() . 'users/' . $userid . '/';
							// 'blog/';
							$fullpath = $this->objConfig->getsiteRoot() . "/usrfiles/users/" . $userid . '/';
							// check that the data dir is there
							// echo $path, $fullpath; die();
							if (!file_exists($path)) {
								// dir doesn't exist so create it quickly
								mkdir($path, 0777);
							}
							// fix up the filename a little
							$filename = str_replace(" ", "_", $filename);
							$filename = str_replace("%20", "_", $filename);
							// change directory to the data dir
							chdir($path);
							// write the file
							$handle = fopen($filename, 'wb');
							fwrite($handle, $filedata);
							fclose($handle);
							$type = mime_content_type($filename);
							$tparts = explode("/", $type);
							// print_r($tparts);
							if ($tparts[0] == "image") {
								// add the img stuff to the body at the end of the "post"
								$newbod.= "[img]" . $fullpath . $filename . "[/img]" . "<br />";
							} elseif ($tparts[1] == "3gpp") {
								if ($tparts[0] == "video") {
									log_debug("Found a 3gp Video file! Processing...");
									// send to the mediaconverter to convert to flv
									$mediacon = $this->getObject('media', 'utilities');
									$file = $path . $filename;
									// echo $file;
									$flv = $mediacon->convert3gp2flv($file, $fullpath);
									// echo "file saved to: $flv";
									$newbod.= "[FLV]" . $flv . "[/FLV]" . " <br />";
									// echo $newbod;

								} elseif ($tparts[0] == "audio") {
									log_debug("Found a 3gp amr file! Processing...");
									// amr file
									$mediacon = $this->getObject('media', 'utilities');
									$file = $path . $filename;
									// echo $file;
									$mp3 = $mediacon->convertAmr2Mp3($file, $fullpath);
									$newbod.= "[EMBED]" . $mp3 . "[/EMBED]" . " <br />";
								}
							} elseif ($tparts[1] == "mp4") {
								if ($tparts[0] == "video") {
									log_debug("Found an MP4 container file");
									// send to the mediaconverter to convert to flv
									$mediacon = $this->getObject('media', 'utilities');
									$file = $path . $filename;
									// echo $file;
									$flv = $mediacon->convertMp42flv($file, $fullpath);
									// echo "file saved to: $flv";
									$newbod.= "[FLV]" . $flv . "[/FLV]" . " <br />";
								}
							} else {
								// add the img stuff to the body at the end of the "post"
								$newbod.= "[url]" . $this->objConfig->getSiteRoot() . 'usrfiles/users/' . $userid . '/' . urlencode($filename) . "[/url]" . "<br />";
							}
						}
					} else {
						// set the filename of the attachment
						$fname = $datum['attachments'][0]['filename'];
						$filenamearr = explode(".", $fname);
						$ext = pathinfo($fname);
						$filename = $filenamearr[0] . "_" . time() . "." . $ext['extension'];
						// decode the attachment data
						$filedata = base64_decode($datum['attachments'][0]['filedata']);
						// set the path to write down the file to
						$path = $this->objConfig->getContentBasePath() . 'blog/';
						// check that the data dir is there
						// fix up the filename a little
						$filename = str_replace(" ", "_", $filename);
						if (!file_exists($path)) {
							// dir doesn't exist so create it quickly
							mkdir($path, 0777);
						}
						// change directory to the data dir
						chdir($path);
						// write the file
						$handle = fopen($filename, 'wb');
						fwrite($handle, $filedata);
						fclose($handle);
						$type = mime_content_type($filename);
						$tparts = explode("/", $type);
						if ($tparts[0] == "image") {
							// add the img stuff to the body at the end of the "post"
							$newbod.= "[img]" . $this->objConfig->getSiteRoot() . 'usrfiles/blog/' . $filename . "[/img]" . "<br />";
						} else {
							// add the img stuff to the body at the end of the "post"
							$newbod.= "[url]" . $this->objConfig->getSiteRoot() . 'usrfiles/blog/' . urlencode($filename) . "[/url]" . "<br />";
						}
					}
				} else {
					// no attachments to worry about
					$newbod = $datum['body'];
				}
				// Write the new post to the database as a "Quick Post"
				$this->quickPostAdd($datum['userid'], array(
				'posttitle' => $datum['subject'],
				'postcontent' => $newbod,
				'postcat' => 0,
				'postexcerpt' => '',
				'poststatus' => '0',
				'commentstatus' => 'Y',
				'postmodified' => date('r') ,
				'commentcount' => 0,
				'postdate' => $datum['date']
				) , 'mail');
			}
		}
		// any issues?
		catch(customException $e) {
			// clean up and die!
			customException::cleanUp();
		}
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $userid Parameter description (if any) ...
     * @return void   
     * @access public 
     */
	public function pingGoogle($userid)
	{
		$objBk = $this->getObject('background', 'utilities');
		$status = $objBk->isUserConn();
		$callback = $objBk->keepAlive();
		$this->objProxy = $this->getObject('proxy', 'utilities');
		// set up for Google Blog API
		$changesURL = $this->uri(array(
		'module' => 'blog',
		'action' => 'feed',
		'userid' => $userid
		));
		$name = $this->objUser->fullname($userid) . " Chisimba blog";
		$blogURL = $this->uri(array(
		'module' => 'blog',
		'action' => 'randblog',
		'userid' => $userid
		));
		// OK lets put it together...
		$gurl = "http:// blogsearch.google.com/ping";
		// do the http request
		// echo $gurl;
		$gurl = str_replace('%26amp%3B', "&", $gurl);
		$gurl = str_replace('&amp;', "&", $gurl);
		$gurl = $gurl . "?name=" . urlencode($name) . "&url=" . urlencode($blogURL) . "&changesUrl=" . urlencode($changesURL);
		// get the proxy info if set
		$proxyArr = $this->objProxy->getProxy(NULL);
		// print_r($proxyArr); die();
		if (!empty($proxyArr)) {
			$parr = array(
			'proxy_host' => $proxyArr['proxyserver'],
			'proxy_port' => $proxyArr['proxyport'],
			'proxy_user' => $proxyArr['proxyusername'],
			'proxy_pass' => $proxyArr['proxypassword']
			);
		}
		// echo $gurl; die();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $gurl);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if (!empty($proxyArr)) {
			curl_setopt($ch, CURLOPT_PROXY, $proxyArr['proxyserver'] . ":" . $proxyArr['proxyport']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr['proxyusername'] . ":" . $proxyArr['proxypassword']);
		}
		$code = curl_exec($ch);
		curl_close($ch);
		switch ($code) {
			case "Thanks for the ping.":
				log_debug("Google blogs API Success! Google said: " . $code);
				break;

			default:
				log_debug("Google blogs API Failure! Google said: " . $code);
				break;
		}
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $term Parameter description (if any) ...
     * @return unknown Return description (if any) ...
     * @access public 
     */
	public function quickSearch($term)
	{
		$ret = $this->objDbBlog->quickSearch($term);
		return $ret;
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  boolean $featurebox Parameter description (if any) ...
     * @return mixed   Return description (if any) ...
     * @access public 
     */
	public function searchBox($featurebox = TRUE)
	{
		$this->loadClass('textinput', 'htmlelements');
		$qseekform = new form('qseek', $this->uri(array(
		'action' => 'blogsearch',
		)));
		$qseekform->addRule('searchterm', $this->objLanguage->languageText("mod_blog_phrase_searchtermreq", "blog") , 'required');
		$qseekterm = new textinput('searchterm');
		$qseekterm->size = 15;
		$qseekform->addToForm($qseekterm->show());
		$this->objsTButton = &new button($this->objLanguage->languageText('word_search', 'system'));
		$this->objsTButton->setValue($this->objLanguage->languageText('word_search', 'system'));
		$this->objsTButton->setToSubmit();
		$qseekform->addToForm($this->objsTButton->show());
		$qseekform = $qseekform->show();
		if ($featurebox == FALSE) {
			return $qseekform;
		} else {
			$objFeatureBox = $this->getObject('featurebox', 'navigation');
			$ret = $objFeatureBox->show($this->objLanguage->languageText("mod_blog_qseek", "blog") , $this->objLanguage->languageText("mod_blog_qseekinstructions", "blog") . "<br />" . $qseekform);
			return $ret;
		}
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  array  $searchres Parameter description (if any) ...
     * @return string Return description (if any) ...
     * @access public
     */
	public function displaySearchResults($searchres)
	{
		$res = NULL;
		if (empty($searchres)) {
			$res.= "<hr>";
			$res.= "<h1>" . $this->objLanguage->languageText("mod_blog_noresultsfound", "blog") . "</h1>";
			return $res;
		} else {
			$res.= "<h3>" . $this->objLanguage->languageText("mod_blog_searchresults", "blog") . "</h3><br />";
		}
		foreach($searchres as $results) {
			if ($this->showfullname == "FALSE") {
				$blogger = $this->objUser->userName($results['userid']);
			} else {
				$blogger = $this->objUser->fullName($results['userid']);
			}
			$image = $this->objUser->getUserImage($results['userid']);
			$link = new href($this->uri(array(
			'module' => 'blog',
			'action' => 'viewsingle',
			'postid' => $results['id']
			)) , $results['post_title']
			);
			$teaser = $results['post_excerpt'] . "<br />";
			// pull together a table
			$srtable = $this->newObject('htmltable', 'htmlelements');
			$srtable->cellpadding = 2;
			// set up the header row
			$srtable->startHeaderRow();
			$srtable->addHeaderCell('');
			$srtable->addHeaderCell('');
			$srtable->endHeaderRow();
			$srtable->startRow();
			$srtable->addCell("<strong>" . $blogger . "</strong>" . "<br />" . $image);
			$srtable->addCell("<br />" . $link->show() . "<br />" . $teaser);
			$srtable->endRow();
			$res.= $srtable->show() . "<br /><hr><br />";
		}
		return $res;
	}

	/**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param string    $o_file Parameter description (if any) ...
     * @param integer   $t_ht   Parameter description (if any).
     * 
     * @return string   Return description (if any) .
     * @access public 
     */
	public function makeThumbnail($o_file, $t_ht = 150)
	{
		$image_info = getImageSize($o_file);
		// see EXIF for faster way
		switch ($image_info['mime']) {
			case 'image/gif':
				if (imagetypes() &IMG_GIF) {
					// not the same as IMAGETYPE
					$o_im = imageCreateFromGIF($o_file);
				} else {
					$ermsg = 'GIF images are not supported<br />';
				}
				break;

			case 'image/jpeg':
				if (imagetypes() &IMG_JPG) {
					$o_im = imageCreateFromJPEG($o_file);
				} else {
					$ermsg = 'JPEG images are not supported<br />';
				}
				break;

			case 'image/png':
				if (imagetypes() &IMG_PNG) {
					$o_im = imageCreateFromPNG($o_file);
				} else {
					$ermsg = 'PNG images are not supported<br />';
				}
				break;

			case 'image/wbmp':
				if (imagetypes() &IMG_WBMP) {
					$o_im = imageCreateFromWBMP($o_file);
				} else {
					$ermsg = 'WBMP images are not supported<br />';
				}
				break;

			default:
				$ermsg = $image_info['mime'] . ' images are not supported<br />';
				break;
		}
		if (!isset($ermsg)) {
			$o_wd = imagesx($o_im);
			$o_ht = imagesy($o_im);
			// thumbnail width = target * original width / original height
			$t_wd = round($o_wd*$t_ht/$o_ht);
			$t_im = imageCreateTrueColor($t_wd, $t_ht);
			imageCopyResampled($t_im, $o_im, 0, 0, 0, 0, $t_wd, $t_ht, $o_wd, $o_ht);
			$ext = strrchr($o_file, '.');
			if ($ext !== false) {
				$newfile = substr($o_file, 0, -strlen($ext));
			}
			$newfile = basename($newfile);
			$newfile = $newfile . "_" . time() . ".jpg";
			$newfile = $this->objConfig->getSiteRootPath() . $newfile;
			// touch($newfile);
			$ret = imageJPEG($t_im, $newfile);
			imageDestroy($o_im);
			imageDestroy($t_im);
			return $newfile;
		}
		return $ermsg;
	}
}
?>