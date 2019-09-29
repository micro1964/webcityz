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


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_k2store/tables');
class K2StoreModelProductOptionValues extends K2StoreModel
{
	
	/**
	 * TaxProfile id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * TaxProfile data
	 *
	 * @var array
	 */
	var $_data = null;
	
	function __construct()
	{
		parent::__construct();
		
		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$id = JRequest::getVar('product_option_id', 0, 'get', 'int');
		$this->setId((int)$id);
		
		$ns = $option.'.productoptionvalues';
		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $ns.'.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);	
			
			
	}
	
	function setId($id)
	{
		// Set taxprofile id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}
	
	function getId() {
		return $this->_id;			
	}
	
	
	/**
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	
	/**
	 *
	 * @access public
	 * @return array
	 */
	function getAllData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			//$this->_data = $this->_getList($query);
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObjectList();
				
		}
	
		return $this->_data;
	}
	
	
	/**
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT a.* '
			. ' FROM #__k2store_product_optionvalues AS a '
			. $where
			. $orderby
		;

		return $query;
	}

	function _buildContentOrderBy()
	{
				$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = $option.'.productoptionvalues';
		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		
		return $orderby;
	}

	function _buildContentWhere()
	{
				$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$db					=JFactory::getDBO();
		$ns = $option.'.productoptionvalues';
		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		
		$where = array();
		
		$where[] = 'a.product_option_id='.$db->Quote($this->_id);
		
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}
	
	function getProductOptions() {
		
		$query = $this->_db->getQuery(true);
		$query->select('po.*');
		$query->from('#__k2store_product_options AS po');
		$query->where('po.product_option_id='.$this->getId());
		$query->select('o.type, o.option_name');
		$query->join('LEFT', '#__k2store_options AS o ON po.option_id = o.option_id');
		$query->where('o.state=1');
		$this->_db->setQuery($query);
		$row = $this->_db->loadObject();
		return $row;
	}
	
	function getOptionValues($option_id) {
	
		$query = $this->_db->getQuery(true);
		$query->select('ov.*');
		$query->from('#__k2store_optionvalues AS ov');
		$query->where('ov.option_id='.$option_id);
		$query->order('ov.ordering ASC');
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		return $rows;
	}
	
}