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
class K2StoreModelShippingMethods extends K2StoreModel
{

	/**
	 * shippingMethod id
	 *
	 * @var int
	 */
	var $_id = null;

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
		$ns = 'com_k2store.shippingmethods';
		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $ns.'.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit	= JRequest::getVar('edit',true);

		if($edit)
			$this->setId((int)$array[0]);
	}

	/**
	 *
	 * @access public
	 * @return array
	 */

	function getItem()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}


		return $this->_data;
	}

	function getItemFront()
	{
		$db = JFactory::getDBO();
		//$query = 'SELECT a.* FROM #__k2store_shippingmethods AS a WHERE a.published=1 ORDER BY a.id';
		$query = $this->_buildQuery();
		$db->setQuery($query);
		return $db->loadObjectList();
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

		$query = ' SELECT a.*'
			. ' FROM #__k2store_shippingmethods AS a '
			. $where
			. $orderby
		;

		return $query;
	}

	function _buildContentOrderBy()
	{
		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = 'com_k2store.shippingmethods';

		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.id',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , a.id';


		return $orderby;
	}

	function _buildContentWhere()
	{
				$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = 'com_k2store.shippingmethods';
		$db					=JFactory::getDBO();
		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'a.id',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $ns.'search',			'search',			'',				'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);
		$filter_published = $this->getState('filter_published');
		$where = array();
		if($filter_published) {
		$where[] = ' a.published=1';
		}

		if ($search) {
			$where[] = 'LOWER(a.shipping_method_name) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false ).
			           'OR LOWER(a.shipping_method_type) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}

	/**
	 * Method to set the Shipping methods identifier
	 *
	 * @access	public
	 * @param	int Shipping methods identifier
	 */
	function setId($id)
	{
		// Set Shipping methods id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the  data
		if ($this->_loadData())
		{
			// Initialize some variables

		}
		else  $this->_initData();

		return $this->_data;
	}


	function store($data)
	{
		$row= $this->getTable();

		// Bind the form fields to the web link table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the web link table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Method to remove a shipping method
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__k2store_shippingmethods'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to (un)publish a Shipping methods
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	=JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__k2store_shippingmethods'
				. ' SET published = '.(int) $publish
				. ' WHERE id IN ( '.$cids.' )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to load Shipping methods data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{

		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT a.* FROM #__k2store_shippingmethods AS a' .
					' WHERE a.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}



		return true;
	}

	/**
	 * Method to initialise the Shipping methods data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$shippingmethods = new stdClass();
			$shippingmethods->id					= 0;
			$shippingmethods->shipping_method_name  = null;
			$shippingmethods->shipping_method_type  = null;
			$shippingmethods->published				= 0;
			$this->_data							= $shippingmethods;
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to get the Shipping method Type..
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function getShippingMethodType($type)
	{
		//$shipping_types=Array(0=>"Flat Rate per Order",1=>"Quantity Based per Order",2=>"Price Based per Order");
		$shipping_types=Array(0=>"K2STORE_SHIPM_FLAT_RATE_PER_ORDER",
					1=>"K2STORE_SHIPM_QUANTITY_BASED_PER_ORDER",
					2=>"K2STORE_SHIPM_PRICE_BASED_PER_ORDER");
		//return $shipping_types[$type];
		return JText::_($shipping_types[$type]);
	}

}
