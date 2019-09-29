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

class xtcViewEntries extends JViewLegacy {
	function display($tpl=null)	{
		global $app;

		$db = JFactory::getDBO();
		$option = JRequest::getCmd( 'option');
		$filter_order		= $app->getUserStateFromRequest( "$option.filter_order",		'filter_order',		'id',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( "$option.filter_order_Dir",	'filter_order_Dir',	'',		'word' );
		$filter_state		= $app->getUserStateFromRequest( "$option.filter_state",		'filter_state',		'',		'string' );
		$filter_component	= $app->getUserStateFromRequest( "$option.filter_component",		'filter_component',		'',		'string' );
		$filter_user	= $app->getUserStateFromRequest( "$option.filter_user",		'filter_user',		'',		'string' );
		$filter_category	= $app->getUserStateFromRequest( "$option.filter_category",		'filter_category',		'',		'string' );
		$search				= $app->getUserStateFromRequest( "$option.search",			'search',			'',		'string' );
		$search				= JString::strtolower( $search );

		$limit		= $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart	= $app->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		$where = array();

		if ( $filter_state != '' ) {
			$where[] = "r.published = $filter_state";
		}

		if ( $filter_component != '' ) {
			$where[] = "r.component = '$filter_component'";
		}

		if ( $filter_user != '' ) {
			$where[] = "r.user_id = $filter_user";
		}

		if ($search) {
			$where[] = 'LOWER(title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' AND ' . implode( ' AND ', $where ) : '' );

		$orderby = " ORDER BY $filter_order $filter_order_Dir";

		// MAKE QUERIES
		$k2 = file_exists(JPATH_ROOT.'/components/com_k2');

		// Pagination Counts
		$total = 0;
		$query = '
			SELECT count(r.id)
			FROM #__jxtc_readinglist AS r, #__content AS jc, #__categories AS jcc
			WHERE r.component = "com_content"
			AND jc.id = r.item_id
			AND jcc.id = jc.catid
		'.$where;
		if ( $filter_category != '' ) { $query .= " AND jcc.title = '$filter_category'"; }

//echo str_replace('#__','jos_',$query);die;

		$db->setQuery( $query );
		$total += $db->loadResult();
		if ($db->getErrorNum()) {  JError::raiseError(500, $db->getError().str_replace('#__','jos_',$query) );  }

		if ($k2) {
			$query = '
				SELECT count(r.id)
				FROM #__jxtc_readinglist AS r, #__k2_items AS kc, #__k2_categories AS kcc
				WHERE r.component = "com_k2"
				AND kc.id = r.item_id
				AND kcc.id = kc.catid
			'.$where;
			if ( $filter_category != '' ) { $query .= " AND kcc.name = '$filter_category'"; }
		
			$db->setQuery( $query );
			$total += $db->loadResult();
			if ($db->getErrorNum()) {  JError::raiseError(500, $db->getError().str_replace('#__','jos_',$query) );  }
		}

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );

		// View Data
		$query = '
			SELECT r.*,u.username,jc.title,jcc.title AS category
			FROM #__users AS u, #__jxtc_readinglist AS r, #__content AS jc, #__categories AS jcc
			WHERE r.component = "com_content"
			AND u.id = r.user_id
			AND jc.id = r.item_id
			AND jcc.id = jc.catid
		'.$where;
		if ( $filter_category != '' ) { $query .= " AND jcc.title = '$filter_category'"; }

		if ($k2) {
			$query .= ' UNION 
				SELECT r.*,u.username,kc.title,kcc.name AS category
				FROM #__users AS u, #__jxtc_readinglist AS r, #__k2_items AS kc, #__k2_categories AS kcc
				WHERE r.component = "com_k2"
				AND u.id = r.user_id
				AND kc.id = r.item_id
				AND kcc.id = kc.catid
			'.$where;
			if ( $filter_category != '' ) { $query .= " AND kcc.name = '$filter_category'"; }
		}
		
		$query .= $orderby;

//echo str_replace('#__','jos_',$query);

		$db->setQuery( $query, $pagination->limitstart, $pagination->limit );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {  JError::raiseError(500, $db->getError().str_replace('#__','jos_',$query) );  }

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		// state filter
		$options = array(
					  JHTML::_('select.option', '', JText::_('RL_FLT_STATE_DEFAULT') ),
					  JHTML::_('select.option', 0, JText::_('Deleted') ),
					  JHTML::_('select.option', 1, JText::_('Enabled') )
						);
		$lists['state'] = JHTML::_('select.genericlist', 
                             $options, 
                             'filter_state', 
                             'class="inputbox" size="1" onchange="Joomla.submitform();"', 
                             'value', 
                             'text',
                             $filter_state);

		// Component filter
		$query = 'SELECT distinct(component) as value, component as text FROM #__jxtc_readinglist';
		$db->setQuery( $query );
		$options = $db->loadObjectList();
		array_unshift($options, JHtml::_('select.option', '', JText::_('RL_FLT_COMPONENT_DEFAULT')));
		$lists['component'] = JHTML::_('select.genericlist', 
                             $options, 
                             'filter_component', 
                             'class="inputbox" size="1" onchange="Joomla.submitform();"', 
                             'value', 
                             'text',
                             $filter_component);

		// User filter
		$query = 'SELECT distinct(r.user_id) as value, u.username as text FROM #__jxtc_readinglist as r, #__users as u WHERE u.id = r.user_id ORDER BY u.username';
		$db->setQuery( $query );
		$options = $db->loadObjectList();
		array_unshift($options, JHtml::_('select.option', '', JText::_('RL_FLT_USER_DEFAULT')));
		$lists['user'] = JHTML::_('select.genericlist', 
                             $options, 
                             'filter_user', 
                             'class="inputbox" size="1" onchange="Joomla.submitform();"', 
                             'value', 
                             'text',
                             $filter_user);

		// Category filter
		$categories = array();
		$query = '
			SELECT distinct(jcc.title) as category
			FROM #__jxtc_readinglist AS r, #__content jc, #__categories jcc
			WHERE r.component = "com_content"
			AND jc.id = r.item_id
			AND jcc.id = jc.catid';
		
		if ($k2) {
			$query .= ' UNION 
				SELECT distinct(kcc.name) as category
				FROM #__jxtc_readinglist AS r, #__k2_items AS kc, #__k2_categories AS kcc
				WHERE r.component = "com_k2"
				AND kc.id = r.item_id
				AND kcc.id = kc.catid
			';
		}
		$query .= ' ORDER BY category';
		
		$db->setQuery( $query );
		$result = $db->loadObjectList();
		if ($db->getErrorNum()) {  JError::raiseError(500, $db->getError().str_replace('#__','jos_',$query) );  }

		$options = array(JHtml::_('select.option', '', JText::_('RL_FLT_CATEGORY_DEFAULT')));
		foreach ($result as $row) { $options[] = JHtml::_('select.option', $row->category, $row->category); }
	
		$lists['category'] = JHTML::_('select.genericlist', 
                             $options, 
                             'filter_category', 
                             'class="inputbox" size="1" onchange="Joomla.submitform();"', 
                             'value', 
                             'text',
                             $filter_category);

		$this->assign('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$rows);
		$this->assignRef('pagination',	$pagination);

		parent::display($tpl);
	}
}