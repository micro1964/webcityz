<?php
/**
 * @version		1.3.5
 * @package		Reading List for Joomla! 3.x
 * @author		JoomlaXTC http://www.joomlaxtc.com
 * @copyright	Copyright (C) 2012-2017 Monev Software LLC. All rights reserved.
 * @license		http://opensource.org/licenses/GPL-2.0 GNU Public License, version 2.0
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.controller' );

class xtcController extends JControllerLegacy {

	function remove()	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);

		$msg = '';
		$row = JTable::getInstance('readinglist','table');
		foreach ($cid as $id) {
			if (!$row->delete($id)) { $msg .= $row->getError(); }
		}

    if ($msg) {
			JError::raiseError(500, $msg );
	  }
	  else {
			$msg = JText::_( 'Removal succesful' );
			$this->setRedirect( 'index.php?option=com_jxtcreadinglist&view=entries', $msg );
		}
	}

	function publish()	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);

		$msg = '';
		$row = JTable::getInstance('readinglist','table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->published = 1;
			if (!$row->store()) { $msg .= $row->getError(); }
		}

    if ($msg) {
			JError::raiseError(500, $msg );
	  }
	  else {
			$this->setRedirect( 'index.php?option=com_jxtcreadinglist&view=entries' );
		}
	}

	function unpublish()	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);

		$msg = '';
		$row = JTable::getInstance('readinglist','table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->published = 0;
			if (!$row->store()) { $msg .= $row->getError(); }
		}

    if ($msg) {
			JError::raiseError(500, $msg );
	  }
	  else {
			$this->setRedirect( 'index.php?option=com_jxtcreadinglist&view=entries' );
		}
	}
}