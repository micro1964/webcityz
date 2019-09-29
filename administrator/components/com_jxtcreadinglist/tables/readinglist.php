<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

class TableReadinglist extends JTable {
	var $id = 0;
	var $published = 0;
	var $checked_out = 0;
	var $checked_out_time	= '';
	var $ordering = 0;
	var $item_id = 0;
	var $user_id = 0;
	var $component = '';
	var $entry_date = 0;
	var $read = 0;

	function __construct( &$db ) {
		parent::__construct( '#__jxtc_readinglist', 'id', $db );
	}

	function bind( $array, $ignore='' ) {
		$result = parent::bind( $array );
		// cast properties
		$this->id	= (int) $this->id;

		return $result;
	}

	function getRecordId($userid,$itemid,$content) {
		$query->clear();
		$query->select($this->_db->quoteName('id'));
		$query->from($this->_db->quoteName('#__jxtc_readinglist'));
		$query->where($this->_db->quoteName('user_id') . ' = ' . $this->_db->quote($userid));
		$query->where($this->_db->quoteName('item_id') . ' = ' . $this->_db->quote($itemid));
		$query->where($this->_db->quoteName('component') . ' = ' . $this->_db->quote($component));
		$this->_db->setQuery($query);
		$id = intval($this->_db->loadResult());
		return $id;
	}
}