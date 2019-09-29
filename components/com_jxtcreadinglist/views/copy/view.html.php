<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.view');

class xtcViewCopy extends JViewLegacy {
	function display($tpl = null) {

		$cid = JRequest::getInt('cid');
		$items = jxtcrlhelper::getReadingList(0);
		$this->assignRef('items', $items);

		parent::display($tpl);
	}
}