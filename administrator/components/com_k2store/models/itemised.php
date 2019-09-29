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

/**
 *
 * @package		Joomla
 * @subpackage	K2Store
 * @since 1.5
 */
class K2StoreModelItemised extends K2StoreModel
{
	/**
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = $option.'.itemised';

		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $ns .'.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
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

		$query = JFactory::getDbo()->getQuery(true);
		$query->select('oi.*');
		$query->select('count(oi.product_id) AS count');
		$query->select('SUM(oi.orderitem_quantity) AS sum');
		$query->from('#__k2store_orderitems AS oi');
		$query->leftJoin('#__k2_items AS product ON product.id=oi.product_id');
		$query->select('category.name AS category_name');
		$query->leftJoin('#__k2_categories AS category ON category.id=product.catid');


		$this->_buildContentWhere($query);
		$this->_buildContentOrderBy($query);
		$query->group('oi.product_id,oi.orderitem_attributes');
		return $query;
	}

	function _buildContentOrderBy($query)
	{
				$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = $option.'.itemised';
		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'oi.product_id',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		//$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , oi.modified_date';
		$query->order($filter_order.' '.$filter_order_Dir)->order('oi.modified_date');
		return;
	}

	function _buildContentWhere($query)
	{
		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = $option.'.itemised';
		$db					=JFactory::getDBO();
		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'oi.product_id',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$filter_orderstate	= $mainframe->getUserStateFromRequest( $ns.'filter_orderstate',	'filter_orderstate',	'',			'word' );
		$search				= $mainframe->getUserStateFromRequest( $ns.'search',			'search',			'',				'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		$where = array();

		if ($search) {
			$where[] = 'LOWER(category.name) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false ).
			           'OR LOWER(oi.orderitem_name) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
		}

		if($filter_orderstate) {
			if($filter_orderstate == 'Confirmed') {
				$where[] = 'a.order_state = '.$db->Quote($db->escape( $filter_orderstate, true ),false);
			} else if($filter_orderstate == 'Pending') {
				$where[] = 'a.order_state = '.$db->Quote($db->escape( $filter_orderstate, true ),false);
			} else if($filter_orderstate == 'Failed') {
				$where[] = 'a.order_state = '.$db->Quote($db->escape( $filter_orderstate, true ),false);
			}
		}
		foreach($where as $w) {
			$query->where($w);
		}
		//$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return;
	}

	function _getOrderID($id) {

			$db = JFactory::getDBO();
			$query = "SELECT order_id FROM #__k2store_orders WHERE id={$id}";
			$db->setQuery($query);
			return $db->loadResult();

	}

	function _getOrderItemIDs($id) {

		//first get the order_id
		$order_id = $this->_getOrderID($id);

		//get the order item ids
		$db = JFactory::getDBO();
		$query = "SELECT orderitem_id FROM #__k2store_orderitems WHERE order_id=".$db->Quote($order_id);
		$db->setQuery($query);
		return $db->loadResultArray();
	}

}
