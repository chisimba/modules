<?php
/**
 *
 * Provides a means to include Disqus comments in a page or section of a page.
 *
  * Provides a means to include Disqus comments in a page or section of a
 * page, such as a blog or forum post. Disqus is blog comment Web service.
 * Webmasters employ Disqus' service to add Web 2.0-style interactive blog
 * discussion. The service is delivered via a hosted, Software as a Service
 * (SaaS) model. Disqus is installed into a blog via a javascript Web widget.
 * Website visitors can leave comments on a blog and save those comments on
 * the Disqus.com website. See disqus.com for more.
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
 * @package   disqus
 * @author    Derek Keats _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: dbdisqus.php,v 1.2 2008-01-08 13:07:15 dkeats Exp $
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
* Database accesss class for Chisimba for the module disqus
*
* @author Derek Keats
* @package disqus
*
*/
class disquselems extends dbtable
{
    /**
    *
    * Constructor for the disquselems class
    * @access public
    *
    */
    public function init()
    {
        //
    }

    public function addWidget()
    {
        $ret = "<script type='text/javascript'>
//<[CDATA[
(function() {
        var links = document.getElementsByTagName('a');
        var query = '?';
        for(var i = 0; i < links.length; i++) {
            if(links[i].href.indexOf('#disqus_thread') >= 0) {
                query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
            }
        }
        document.write('<script type=\"text/javascript\" src=\"http://disqus.com/forums/dkeats/get_num_replies.js' + query + '\"></' + 'script>');
    })();
//]]>
</script>";
    }

}
?>
