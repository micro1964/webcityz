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

 class K2StoreModelZones extends JModelList {


	 protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		$type = $app->getUserStateFromRequest($this->context.'.filter.country_type', 'filter_country_type', '', 'string');
		$this->setState('filter.country_type', $type);
		$country = $app->getUserStateFromRequest($this->context.'.filter.country_options', 'filter_country_options', '', 'string');
		$this->setState('filter.country_options', $country);


		// Load the parameters.
		$params = JComponentHelper::getParams('com_k2store');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.zone_id', 'asc');
	}


	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');
		return parent::getStoreId($id);
	}

	public function getCountries(){
		// Create a new query object.
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			$query->select('a.country_id,a.country_name');
			$query->from('#__k2store_countries AS a');
			$query->where('state = 1');
			$query->order('a.country_name');
			$db->setQuery($query);
			return $db->loadObjectList();
		}

	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select(
			$this->getState(
				'list.select',
				'a.zone_id, a.zone_name,a.zone_code,a.country_id,a.state,c.country_name'
			)
		);

		$query->from('#__k2store_zones AS a');

		$query->join('LEFT', '#__k2store_countries AS c ON c.country_id =a.country_id ');

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
				$query->where('a.zone_id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.zone_name LIKE '.$search.
				' OR a.zone_code LIKE '.$search.' OR a.country_id LIKE '.$search.')');
			}
		}

		// Filter by Country.
		$country = $this->getState('filter.country_options');
		if ($country) {
			$query->where('a.country_id = '.$db->quote($country));
		}
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		if($orderCol == 'a.zone_id' ) {
			$orderCol = 'a.zone_id '.$orderDirn.', a.zone_id';
		} else {
			$orderCol = 'a.zone_id '.$orderDirn.', a.zone_id';
		}

		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
 }
