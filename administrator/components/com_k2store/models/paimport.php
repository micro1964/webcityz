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


//no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class K2StoreModelPaImport extends K2StoreModel
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
		$ns = 'com_k2store.paimport';
	
		$id = JRequest::getVar('id', 0, 'get', 'int');
		$this->setId((int)$id);
	
		$ns = $option.'.paimport';
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
	
		$query = ' SELECT p.id,p.title'
		. ' FROM #__k2_items AS p '
		. $where
		. $orderby
		;
	
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = $option.'.paimport';
	
		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'p.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
	
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
	
		return $orderby;
	}
	
	function _buildContentWhere()
	{
		$mainframe = JFactory::getApplication();
		$option = 'com_k2store';
		$ns = $option.'.paimport';
	
		$db					=JFactory::getDBO();
		$filter_order		= $mainframe->getUserStateFromRequest( $ns.'filter_order',		'filter_order',		'p.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $ns.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
	
		$where = array();
	
		$where[] ='p.id IN (SELECT product_id FROM #__k2store_productattributes)';
		$where[] = 'p.id !='.$db->Quote($this->_id);
	
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
	
		return $where;
	}
	
	function getAttributes($product_id) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__k2store_productattributes');
		$query->where('product_id='.$db->quote($product_id));
		$query->order('ordering ASC');
		$db->setQuery($query);
		return $db->loadObjectList(); 
	}
	
	function getAttributeOptions($productattribute_id) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__k2store_productattributeoptions');
		$query->where('productattribute_id='.$db->quote($productattribute_id));
		$query->order('ordering ASC');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	 * copy the attributes and options.
	 *
	 * @source_product_id  int  Source product id.
	 * @dest_product_id  int  Destination product id.
	 *
	 * @since   2.7
	 */
	
	function importAttributeFromProduct($source_product_id, $dest_product_id) {
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables');
		
		//first get the attributes of source product
		
		$source_attributes = $this->getAttributes($source_product_id);
		
		if(count($source_attributes) < 1) {
			$this->setError(JText::_('K2STORE_PAI_PRODUCT_DONT_HAVE_ATTRIBUTES'));
			return false;
		}

		//now we have the attributes. Loop to insert them
		foreach ($source_attributes as $s_attribute) {
			
			unset($sa_item);
			
			//load source first
			$sa_item = JTable::getInstance('ProductAttributes', 'Table');
			$sa_item->load($s_attribute->productattribute_id);
			
			//now copy it
			$dest_row = JTable::getInstance('ProductAttributes', 'Table');
			$dest_row  = $sa_item;
			$dest_row->productattribute_id = NULL;
			$dest_row->product_id = $dest_product_id;
			$dest_row->store();
		//	$dest_row->reorder();
			//now copy the atribute options
			$source_attribute_options = $this->getAttributeOptions($s_attribute->productattribute_id);
		
			if(count($source_attribute_options)) {
				foreach ($source_attribute_options as $sa_option) {
					
					unset($sao_item);
					//load source
					$sao_item = JTable::getInstance('ProductAttributeOptions', 'Table');
					$sao_item->load($sa_option->productattributeoption_id);
					
					//now copy it;
					
					$dest_sao_row = JTable::getInstance('ProductAttributeOptions', 'Table');
					$dest_sao_row = $sao_item;
					
					$dest_sao_row->productattributeoption_id = NULL;
					$dest_sao_row->productattribute_id = $dest_row->productattribute_id;
					$dest_sao_row->store();
					//$dest_sao_row->reorder();
				}
			}
		}
		return true;
	}
}