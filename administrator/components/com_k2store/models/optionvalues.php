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

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE.'/components/com_k2store/models/_base.php');

class K2StoreModelOptionValues extends K2StoreModelBase
{
    protected function _buildQueryWhere(&$query)
    {
        $filter_id	= $this->getState('filter_id');
        $filter_option_id  = $this->getState('filter_option_id');

		if (strlen($filter_id))
        {
            $query->where('tbl.optionvalue_id = '.(int) $filter_id);
       	}
        if (strlen($filter_option_id))
        {
            $query->where('tbl.option_id = '.(int) $filter_option_id);
        }
    }

    protected function _buildQueryFields(&$query)
    {
        $field = array();
        //$field[] = " geozone.geozone_name ";

        $query->select( $this->getState( 'select', 'tbl.*' ) );
        $query->select( $field );
    }

	public function getList($refresh=false)
	{

		$list = parent::getList($refresh=false);

		// If no item in the list, return an array()
        if( empty( $list ) ){
        	return array();
        }

		foreach($list as $item)
		{
			$item->link_remove = 'index.php?option=com_k2store&view=optionvalues&task=delete&cid[]='.$item->optionvalue_id;
		}

		return $list;
	}


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

}
