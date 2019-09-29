<?php
/*------------------------------------------------------------------------
 # com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.modellist');

 class K2StoreModelCountries extends JModelList {


	 protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		// Load the parameters.
		$params = JComponentHelper::getParams('com_k2store');
		$this->setState('params', $params);
		// List state information.
		parent::populateState('a.country_name', 'asc');
	}


	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');
		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.country_id, a.country_name, a.country_isocode_2, a.country_isocode_3, a.state'
			)
		);
		$query->from('#__k2store_countries AS a');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.country_id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.country_name LIKE '.$search.' OR a.country_isocode_2 LIKE '.$search.
				' OR a.country_isocode_3 LIKE '.$search.')');
			}
		}
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		if($orderCol == 'a.country_id' ) {
			$orderCol = 'a.country_id '.$orderDirn.', a.country_id';
		} else {
			$orderCol = 'a.country_id '.$orderDirn.', a.country_id';
		}


		//echo	$orderCol = 'a.id '.$orderDirn.', a.id ';
		$query->order($db->escape($orderCol.' '.$orderDirn));
		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}


	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$table=JTable::getInstance('Country','Table');
		$table->publish($pks,$state);

		return true;
	}


	public function delete($pks = null)
	{

		// Initialise variables.
		$table=JTable::getInstance('Country','Table');
		$table->delete($pks);

		return true;
	}
 }
