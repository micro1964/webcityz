<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class TableOrderCoupons extends JTable
{

	/**
	* @param database A database connector object
	*/
	function __construct(&$db)
	{
		parent::__construct('#__k2store_order_coupons', 'order_coupon_id', $db );
	}

	function save($data) {

		if(!parent::save($data)) {
			$this->setError($this->getError());
			return false;
		}
		return true;
	}

}