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

class xtcViewReadinglist extends JViewLegacy {
	function display($tpl = null) {

		$params = JComponentHelper::getParams('com_jxtcreadinglist');
		$this->assignRef('params', $params);

		$user = JFactory::getUser();
		$this->assignRef('user', $user);

		if ($user->guest) {
			$app = JFactory::getApplication();
			$app->redirect('index.php', JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'));
		}

		$this->cid = JRequest::getInt('cid',-1);
		$this->Itemid = JRequest::getInt('Itemid');
		$items = jxtcrlhelper::getReadingList(0);

		$this->categories = array_keys($items); sort($this->categories);

		$options = array( JHTML::_('select.option', -1, Jtext::_('RL_ALLCATEGORIES') ) );
		foreach ($this->categories as $id => $category) {
			$options[] = JHTML::_('select.option', $id, $category );
		}
		$this->categorySelector = JHTML::_('select.genericlist', $options, 'cid', 'class="pull-left" onchange="document.readingListForm.submit()"', 'value', 'text',$this->cid);

		if ($this->cid == -1) {	// no filter
			$this->items = $items;
		}
		else {
			$category = $this->categories[$this->cid];
			$filteredItems = array($category => $items[$category]);
			$this->items = $filteredItems;
		}

		parent::display($tpl);
	}
}