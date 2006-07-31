<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

class feed extends object
{
	/**
	 * The feed class will make use of a number of abstract classes that will
	 * be able to parse and consume a number of RSS type formats,
	 * including:
	 * <pre>
	 * <li>RSS</li>
	 * <li>Atom</li>
	 * <li>Derivitives of the above</li>
	 * </pre>
	 *
	 * Feed provides functionality for consuming RSS and Atom feeds.
	 * It provides a natural syntax for accessing elements of feeds, feed attributes, and entry attributes.
	 * Feed also has extensive support for modifying feed and entry structure with the same natural syntax, and turning the result back into XML.
	 * In the future, this modification support could provide support for the Atom Publishing Protocol.
	 * Programmatically, Feed consists of a base Feed class, abstract Feed_Abstract and Feed_EntryAbstract base classes for representing
	 * Feeds and Entries, specific implementations of feeds and entries for RSS and Atom, and a behind-the-scenes helper for making the natural
	 * syntax magic work.
	 *
	 * @access public
	 * @copyright AVOIR
	 * @author Paul Scott
	 */

}